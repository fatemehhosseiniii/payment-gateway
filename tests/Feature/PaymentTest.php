<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NasrinRezaei45\Shepacom\ShepaFacade;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_payment_and_verify_successfully()
    {
        $amount = fake()->numberBetween(10000, 999999);
        $tracCode = fake()->uuid();
        // Mock pay request
        ShepaFacade::shouldReceive('send')
            ->once()
            ->andReturn(
                'https://payment.shepa.com/pay/' . $tracCode
            );


        $response = $this->postJson('/api/payment/pay-request', [
            'amount' => $amount,
            'order_code' => fake()->randomNumber(6),
            'gateway_key' => 'shepa',
        ]);

        $response->assertOk();

        // Mock verify request
        ShepaFacade::shouldReceive('verify')
            ->once()
            ->with($tracCode, $amount)
            ->andReturn([
                'refid' => '123456',
                'transaction_id' => '123456',
                'date' => now()->toDateTimeString(),
            ]);

        // Verify Payment
        $responseVerify = $this->getJson(
            '/api/payment/verify/shepa?token=' . $tracCode . '&status=success'
        );

        $responseVerify
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_payment_and_verify_failed()
    {
        $amount = fake()->numberBetween(10000, 999999);
        $tracCode = fake()->uuid();

        ShepaFacade::shouldReceive('send')
            ->twice()
            ->andReturn(
                'https://payment.shepa.com/pay/' . $tracCode,
                'https://payment.shepa.com/pay/' . $tracCode . '_2'
            );

        $this->postJson('/api/payment/pay-request', [
            'amount' => $amount,
            'order_code' => fake()->randomNumber(6),
            'gateway_key' => 'shepa',
        ])->assertOk();

        // مهم: verify نباید صدا زده شود
        ShepaFacade::shouldReceive('verify')->never();

        $responseVerify = $this->getJson(
            '/api/payment/verify/shepa?token=' . $tracCode . '&status=failed'
        );

        $responseVerify->assertOk();

        $responseVerify
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }
}
