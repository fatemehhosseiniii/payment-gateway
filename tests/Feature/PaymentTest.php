<?php

namespace Tests\Feature;

use App\Infrastructure\Adapters\PaymentInterface;
use App\Infrastructure\Adapters\ShepaAdapter;
use App\Models\Gateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private string $transactionToken;

    public function test_minimum_amount_payment()
    {

        $response = $this->postJson('/api/payment/pay-request', [
            'amount' => 10000,
            'order_code' => fake()->randomNumber(6),
            'gateway_key' => 'shepa',
        ]);

        $tracCode = explode('/', $response->json('data.redirect_route'));
        $tracCode = $tracCode[count($tracCode) - 1];
        $this->transactionToken = $tracCode;

        $response->assertStatus(200);


        $payment = Mockery::mock(ShepaAdapter::class);

        $payment->shouldReceive('verify')
            ->once()
            ->andReturn(['token'=>$tracCode,'status'=>'success']);
//

        $responseVerify = $this->get('/api/payment/verify/shepa', [
            'token' => Str::uuid(),
            'status' => 'success',
        ]);

        $responseVerify->ddBody();
    }

//    public function test_successfully_verified()
//    {
//
////        $response->assertOk()
////            ->assertJson([
////                'status' => 'success',
////            ]);
//    }
}

