<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
        User::create([
            'username' => "adiechahk",
            'email' => "harikrushna@enstead.com",
            'password' => Hash::make('123123')
        ]);
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => "harikrushna@enstead.com",
            "password" => "123123"
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
        $response->assertStatus(400);
    }

    public function test_login_invalid_pswd ()
    {
        User::create([
            'username' => "adiechahk",
            'email' => "harikrushna@enstead.com",
            'password' => Hash::make('123123')
        ]);
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => "harikrushna@enstead.com",
            "password" => "000000"
        ]);
        $response->assertJson(["message" => "Invalid Password !"]);
        $response->assertStatus(400);
    }

    public function test_login_logout ()
    {
        User::create([
            'username' => "adiechahk",
            'email' => "harikrushna@enstead.com",
            'password' => Hash::make('123123')
        ]);
        $this->assertEquals(1, User::count(), "User count doens not match with expected count 1.");
        $response = $this->post('/api/auth/login', [
            "email" => "harikrushna@enstead.com",
            "password" => "123123"
        ]);
        $response->assertJsonStructure(['token']);
        $response->assertStatus(200);
        $token = $response->json()['token'];
        // dd($token);
        $user = User::whereEmail('harikrushna@enstead.com')->first();
        // $response = $this->withHeaders(compact('token'))->post('/api/auth/logout');
        $response = $this->actingAs($user)->post('/api/auth/logout');
        $response->assertJson(["message" => "Logged out."]);
        $response->assertStatus(200);
    }

    public function test_get_user ()
    {
        $userdata = [ 'username' => "adiechahk", 'email' => "harikrushna@enstead.com" ];
        $user = User::create([ ...$userdata, 'password' => Hash::make('123123') ]);
        $response = $this->actingAs($user)->get('/api/user');
        $response->assertJson($userdata);
    }
}
