<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;
use App\Models\PageSection;

class ContactController extends Controller
{
    public function index()
    {
        $pageSections = PageSection::forSlug('contact')->with('elements')->get(['*'])->keyBy('section_key');
        return view('contact', compact('pageSections'));
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

        Enquiry::create(array_merge($validated, [
            'source' => $request->header('referer') ?? 'Contact Page',
            'status' => 'new',
        ]));

        return back()->with('contact_success', true);
    }
}
