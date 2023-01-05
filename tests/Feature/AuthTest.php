<?php

namespace Tests\Feature;

use App\Events\ForgotPassword;
use App\Listeners\SendForgotPasswordMail;
use App\Mail\ForgetPasswordEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_simple_registration()
    {
        $this->assertEquals(0, User::count(), "User already exists before starting the test.");
        $response = $this->post('/api/auth/register', [
            "username" => "Harikrushna",
            "email" => "harikrushna@enstead.com",
            "password" => "123123"
        ]);
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response->assertJsonStructure(['token']);
        $response->assertStatus(201);
    }

    public function test_registration_with_invalid_email_address()
    {
        $this->assertEquals(0, User::count(), "User already exists before starting the test.");

        $response = $this->post('/api/auth/register', [
            "username" => "Harikrushna",
            "email" => "harikrushna",
            "password" => "123123"
        ]);
        
        $this->assertEquals(0, User::count(), "User count doens not match with expected count 0.");
        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(422);
    }

    public function test_registration_without_email_should_failed()
    {
        $this->assertEquals(0, User::count(), "User already exists before starting the test.");

        $response = $this->post('/api/auth/register', [
            "username" => "Harikrushna",
            "password" => "123123"
        ]);
        
        $this->assertEquals(0, User::count(), "User count doens not match with expected count 0.");
        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(422);

    }

    public function test_registration_without_password_should_failed()
    {
        $this->assertEquals(0, User::count(), "User already exists before starting the test.");

        $response = $this->post('/api/auth/register', [
            "username" => "Harikrushna",
            "email" => "harikrushna@enstead.com"
        ]);
        
        $this->assertEquals(0, User::count(), "User count doens not match with expected count 0.");
        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(422);

    }

    public function test_registration_without_name_should_failed()
    {
        $this->assertEquals(0, User::count(), "User already exists before starting the test.");

        $response = $this->post('/api/auth/register', [
            "email" => "harikrushna@enstead.com",
            "password" => "123123"
        ]);
        
        $this->assertEquals(0, User::count(), "User count doens not match with expected count 0.");
        $response->assertJsonValidationErrors(['username']);
        $response->assertStatus(422);
    }

    public function test_login_simple ()
    {
        $email = "harikrushna@enstead.com";
        User::factory()->create(compact('email'));
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => $email,
            "password" => "password"
        ]);
        $response->assertJsonStructure(['token']);
        $response->assertStatus(200);
    }

    public function test_login_user_not_found ()
    {
        $this->assertEquals(0, User::count(), "User count doens not match with expected count 0.");
        $response = $this->post('/api/auth/login', [
            "email" => "harikrushna@enstead.com",
            "password" => "123123"
        ]);
        $response->assertJson(["message" => "User not found"]);
        $response->assertStatus(404);
    }

    public function test_login_invalid_pswd ()
    {
        $email = "harikrushna@enstead.com";
        User::factory()->create(compact('email'));
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => $email,
            "password" => "000000"
        ]);
        $response->assertJson(["message" => "Invalid Password !"]);
        $response->assertStatus(400);
    }

    public function test_login_logout ()
    {
        $user = User::factory()->create();
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => $user->email,
            "password" => "password"
        ]);
        $response->assertJsonStructure(['token']);
        $response->assertStatus(200);
        $authUser = User::find($user->id);
        $response = $this->actingAs($authUser)->post('/api/auth/logout');
        $response->assertJson(["message" => "Logged out."]);
        $response->assertStatus(200);
    }

    public function test_get_user ()
    {
        $user = User::factory()->create();
        $auth = User::find($user->id);
        $response = $this->actingAs($auth)->get('/api/user');
        $response->assertJson($user->toArray());
    }

    public function test_forget_password_no_user ()
    {
        $email = 'harikrushna@enstead.com';
        $response = $this->post('/api/auth/forget-password', compact('email'));
        $response->assertJson(["message" => "User not found"]);
        $response->assertStatus(404);
    }

    public function test_forget_password_success ()
    {
        Event::fake();
        $user = User::factory()->create();
        $response = $this->post('/api/auth/forget-password', ['email' => $user->email]);
        Event::assertDispatched(ForgotPassword::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
        $response->assertStatus(200);
    }

    public function test_reset_password_with_correct_token()
    {
        $user = User::factory()->create();
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => $user->email,
            "password" => "password"
        ]);
        $response->assertJsonStructure(['token']);
        $response->assertStatus(200);
        $auth = User::find($user->id);
        $response = $this->actingAs($auth)->post('/api/auth/logout');
        $response->assertJson(["message" => "Logged out."]);
        $response->assertStatus(200);
        $token = Password::createToken($user);
        $response = $this->post('/api/auth/reset-password', [
            "token" => $token,
            "email" => $user->email,
            "password" => "112233",
            "password_confirmation" => "112233"
        ]);
        $response->assertJson(["message" => "passwords.reset"]);
        $response->assertStatus(200);
        $response = $this->post('/api/auth/login', [
            "email" => $user->email,
            "password" => "password"
        ]);
        $response->assertJson(["message" => "Invalid Password !"]);
        $response->assertStatus(400);
        $response = $this->post('/api/auth/login', [
            "email" => $user->email,
            "password" => "112233"
        ]);
        $response->assertJsonStructure(['token']);
        $response->assertStatus(200);
        $response = $this->actingAs($auth)->post('/api/auth/logout');
        $response->assertJson(["message" => "Logged out."]);
        $response->assertStatus(200);
    }

    public function test_forgot_password_handler()
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->assertTrue(true);
        $token = Password::createToken($user);
        (new SendForgotPasswordMail())->handle(new ForgotPassword($user, $token));
        Mail::assertSent(ForgetPasswordEmail::class);
    }

    public function test_forgot_password_mail()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $mailable = new ForgetPasswordEmail($user, $token);
        $mailable->assertHasSubject('Forget Password Email');
        $mailable->assertSeeInHtml($token);  
    }
}
