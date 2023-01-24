<?php

namespace Tests\Feature\Models;

use App\Models\WineUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class WineUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_connect_id_for_wine_user()
    {
        $user = WineUser::factory()->create();
        $this->assertNull(Arr::get($user->toArray(), 'connection_id'), "Connection id should be null initially.");
        $user->updateConnectId('abc');
        $this->assertEquals("abc", Arr::get($user->toArray(), 'connection_id'), "Connection id is not updating as expected.");
    }

    public function test_connection_id_will_be_retrived()
    {
        $user = WineUser::factory()->create(['connection_id' => 'test']);
        $this->assertEquals("test", $user->getConnectId());
    }

    public function test_email_will_be_retrived()
    {
        $user = WineUser::factory()->create(['email' => 'test@enstead.com']);
        $this->assertEquals("test@enstead.com", $user->getEmail());
    }
}
