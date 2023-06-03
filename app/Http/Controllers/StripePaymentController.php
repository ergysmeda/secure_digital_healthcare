<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use mikehaertl\pdftk\Pdf;
class StripePaymentController extends Controller
{

    public function stripePost(Request $request)
    {
        $exploded = explode('/', $request->server()['HTTP_REFERER']);
        $appointmentId = $exploded[count($exploded) - 1];

        $appointment = Appointment::with('cost')->where('id', $appointmentId)->first();



        Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $stripe->paymentIntents->create([
            'amount' => $appointment->cost->amount * 100,
            'currency' => 'gbp',
            'payment_method' => 'pm_card_visa',
        ]);

        $appointment->status_id = '1';
        $appointment->notes =  $appointment->notes ."\n paid ".$appointment->cost->amount .' at '. date('Y-m-d H:i:s');

        $payment = new Payment([
            'doctor_id' => $appointment->healthcare_professional_id,
            'patient_id' =>  $appointment->patient_id,
            'amount' =>  $appointment->cost->amount,
            'payment_time' =>  date('Y-m-d H:i:s'),
            'cost_id' =>  $appointment->cost->id,
        ]);

        $payment->save();


        if ($appointment->save()) {
            return redirect()->route('appointments')->with('success', 'Payment created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to complete payment.')->withInput();
        }
    }
}
