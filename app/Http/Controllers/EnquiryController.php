<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

// Mailable classes to be created
// use App\Mail\EnquiryReceived;
// use App\Mail\EnquiryConfirmation;

class EnquiryController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'product_category' => 'nullable|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $enquiry = Enquiry::create(array_merge($validated, [
            'source' => request()->header('referer') ?? 'Direct',
            'status' => 'new'
        ]));

        try {
            // Uncomment when Mailables are available
            // Mail::to(Setting::get('email_info', 'info@auxinorchem.com'))->send(new EnquiryReceived($enquiry));
            // Mail::to($enquiry->email)->send(new EnquiryConfirmation($enquiry));
        } catch (\Exception $e) {
            // Log mail error but continue
            \Log::error("Mail sending failed: " . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Enquiry submitted successfully.']);
    }
}
