<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $toEmail = 'alibehloljafri@gmail.com';
        $message = 'hello i am mail.';
        $subject = 'send mail is subject';

        Mail::to($toEmail)->send(new TestMail($message, $subject));

        return "send email";
    }

    public function sendEmail(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:5|max:50',
            'message' => 'required|min:10|max:255',
            'attachment' => 'required',
        ]);

        $fileName = time() . '.' . $request->file('attachment')->extension();
        $request->file('attachment')->move('uploads',$fileName);

        $adminEmail = 'alibehloljafri@gmail.com';

        Mail::to($adminEmail)->send(new TestMail($request->all(),$fileName));

        return 'send email';
    }
}
