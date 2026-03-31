<?php

namespace App\Http\Controllers;

use App\Mail\Welcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request){
        $toEmail = 'alin25194@gmail.com';
        $message = 'Hello, Welcome to our website';
        $subject = 'Welcome to Nadeem ali';

        Mail::to($toEmail)->send(new Welcome($message,$subject));

         return "Email Sent Successfully";
    }
}
