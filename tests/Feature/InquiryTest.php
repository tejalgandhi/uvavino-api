<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;


class InquiryTest extends TestCase
{

    public function test_store_contact_us()
    {
        $country = Country::factory()->create();
        $data = [
          'name'=>'Tejal Gandhi',
          'email'=>'tejal@yopmail.com',
          'subject'=>'Test subject',
          'message'=>'contact us message',
          'country_id'=>$country->id,
        ];
        Mail::fake();
        $response = $this->post('/api/inquiry', $data);
        $response->assertStatus(201);
    }


}
