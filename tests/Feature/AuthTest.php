<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            "name" => "Harikrushna",
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
            "name" => "Harikrushna",
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
            "name" => "Harikrushna",
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
            "name" => "Harikrushna",
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
        $response->assertJsonValidationErrors(['name']);
        $response->assertStatus(422);

    }
}
