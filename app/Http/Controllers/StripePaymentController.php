<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        // Set your Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Amount in cents (e.g., $10.00 becomes 1000)
        $amount = $request->input('amount');

        try {
            // Create a PaymentIntent with the order amount and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            // Send client_secret to the frontend
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
