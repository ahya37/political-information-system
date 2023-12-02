<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class LoggingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    public function testLogging()
    {
        Log::info("Hello Info");
        Log::warning("Hello Warning");
        Log::error("Hello Error");
        Log::critical("Hello Critical");

        $response = $this->get('/');

        $response->assertStatus(200);

    }

    public function testContext()
    {
        Log::info('Hello Info', ["user" => "Ahya"]);


        $this->assertTrue(true);

    }

    public function testChanel()
    {
       $slackLogger =  Log::channel("file"); // send to slack chanel
       $slackLogger->error("Hello slack");

       Log::info("Hello info");


        $this->assertTrue(true);

    }

   
}
