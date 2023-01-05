<?php

namespace Tests\Unit\Http\Resource;

use App\Http\Resources\Success;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class SuccessTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_array_object()
    {
        $arr = (new Success(["status" => true]))->toArray(new Request());
        $this->assertArrayHasKey("status", $arr, "'status' key not found in success resource");
    }

    public function test_string_object()
    {
        $arr = (new Success("operation success"))->toArray(new Request());
        $this->assertArrayHasKey("message", $arr, "'message' key not found in success resource");
    }
}
