<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
        ]);

        $enquiry = Enquiry::create(array_merge($validated, [
            'source' => request()->header('referer') ?? 'Contact Page',
            'status' => 'new'
        ]));

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
