<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $enquiries = $query->paginate(20)->withQueryString();
        
        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function show(Enquiry $enquiry)
    {
        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,closed',
            'admin_notes' => 'nullable|string'
        ]);
        
        $enquiry->update($validated);
        
        return back()->with('success', 'Enquiry updated successfully.');
    }

    public function status(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:new,contacted,read,replied,closed']);
        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted.');
    }
}
