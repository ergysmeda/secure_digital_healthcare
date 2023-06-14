<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class VideoChatController extends Controller
{
    public function index()
    {
        // Get Twilio configuration from .env
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $apiKeySid = env('TWILIO_API_KEY_SID');  // Get this from Twilio Console
        $apiKeySecret = env('TWILIO_API_KEY_SECRET');  // Get this from Twilio Console

        $appointment = Appointment::getUpcomingAppointments(auth()->user()->id,auth()->user()->role_id);



        if(!is_null($appointment)){
            $appointment = $appointment->toArray();
        }else{
            return view('content.video-chat', ['error' => 'No Appointment ahead']);
        }
        $roomName = md5($appointment['id'].$appointment['appointment_time']);

        // Generate a new access token for the user
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,  // Valid for 1 hour
            auth()->user()->id  // The "identity" (username or user ID)
        );

        // Generate a video grant for the token
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);
        $token->addGrant($videoGrant);

        // Pass the token and roomName to the view
        return View::make('content.video-chat', [
            'accessToken' => $token->toJWT(),
            'roomName' => $roomName
        ]);
    }
}
