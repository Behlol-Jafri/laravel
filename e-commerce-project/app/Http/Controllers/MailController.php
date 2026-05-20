<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SendGrid;
use SendGrid\Mail\Mail;

class MailController extends Controller
{
    public function sendMail()
    {
        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        $email = new Mail();
        $email->setFrom("alibehloljafri@gmail.com", "Your App");
        $email->setSubject("Test Email");
        $email->addTo("alibehloljafri@gmail.com");
        $email->addContent("text/plain", "Hello from SendGrid API");

        $response = $sendgrid->send($email);

        return response()->json([
            'message' => 'Email Send Successfully'
        ]);
    }
}
