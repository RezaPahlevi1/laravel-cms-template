<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = SiteSetting::get('contact_form_recipient', '');

        if (empty($recipient)) {
            return back()
                ->withInput()
                ->with('error', 'Contact form is not configured yet. Please try again later.');
        }

        Mail::to($recipient)->send(new ContactMail(
            senderName:     $validated['name'],
            senderEmail:    $validated['email'],
            messageSubject: $validated['subject'],
            messageBody:    $validated['message'],
        ));

        return back()->with('success', 'Your message has been sent. We will get back to you shortly.');
    }
}