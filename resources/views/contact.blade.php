@extends('layouts.app')

@section('title', 'Contact Us | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Hero -->
<section data-section-id="103" data-section-key="contact_hero" data-section-label="Contact Hero" class="relative text-white py-16 px-4 md:px-8 border-b border-line/20 @auth cms-editable @endauth" data-cms-label="Edit Contact Hero">
    <!-- Background Overlay Layer -->
    <div class="ed-bg-overlay absolute inset-0 bg-gradient-to-r from-ink to-ink2 -z-10"></div>
    
    <div class="max-w-[1400px] mx-auto text-center relative z-10">
 <h1 data-element-id="contact_hero_title" class="font-display font-extrabold text-[40px] md:text-[72px] leading-[1.1] mb-8">Contact <em class="italic text-teal-2 font-normal">Us</em></h1>
        <div class="font-sans font-medium text-[11px] uppercase tracking-wider text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">Contact</span>
        </div>
    </div>
</section>

<section data-section-id="132" data-section-key="contact_info" data-section-label="Contact Info & Form" class="bg-bg py-[80px] px-4 md:px-8 border-b border-line relative overflow-hidden @auth cms-editable @endauth" data-cms-label="Edit Contact Info">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 relative z-10">
        <!-- Contact Details -->
        <div class="sr-l">
 <h2 data-element-id="contact_title" class="font-display font-extrabold text-[36px] md:text-[48px] leading-tight text-ink mb-8">Let's Discuss Your <em class="italic text-teal font-normal">Requirements</em></h2>
            
            <p data-element-id="contact_subtitle" class="font-sans italic text-[15px] text-muted mb-12 max-w-md">
                Reach out to our sales and technical team for material specifications, bulk pricing, or logistics queries. 
            </p>
            
            <div class="space-y-8 bg-white p-8 border border-line shadow-sm">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 border border-teal/20 bg-teal-light flex items-center justify-center text-teal flex-shrink-0">📍</div>
 <div>
                        <span class="block font-sans font-bold text-xs uppercase tracking-widest mb-2 text-ink">Head Office</span>
                        <p data-element-id="office_address" class="font-sans text-[13px] text-muted leading-relaxed max-w-sm">{{ $globalSettings['office_address'] ?? 'Ahmedabad, Gujarat' }}</p>
                    </div>
                </div>
                
                <hr class="border-line border-dashed">
                
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 border border-teal/20 bg-teal-light flex items-center justify-center text-teal flex-shrink-0">🏭</div>
 <div>
                        <span class="block font-sans font-bold text-xs uppercase tracking-widest mb-2 text-ink">Warehouse</span>
                        <p data-element-id="warehouse_address" class="font-sans text-[13px] text-muted leading-relaxed max-w-sm">{{ $globalSettings['warehouse_address'] ?? 'Aslali, Ahmedabad, Gujarat' }}</p>
                    </div>
                </div>
                
                <hr class="border-line border-dashed">
                
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 border border-teal/20 bg-teal-light flex items-center justify-center text-teal flex-shrink-0">📞</div>
                        <div>
                            <span class="block font-sans font-bold text-[10px] uppercase tracking-widest mb-2 text-ink">Call Us</span>
                            <a data-element-id="phone_1" href="tel:{{ $globalSettings['phone_primary'] ?? '' }}" class="font-sans font-medium text-[13px] text-muted hover:text-teal block mb-1">{{ $globalSettings['phone_primary'] ?? '+91' }}</a>
                            <a data-element-id="phone_2" href="tel:{{ $globalSettings['phone_secondary'] ?? '' }}" class="font-sans font-medium text-[13px] text-muted hover:text-teal block">{{ $globalSettings['phone_secondary'] ?? '' }}</a>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 border border-teal/20 bg-teal-light flex items-center justify-center text-teal flex-shrink-0">✉️</div>
                        <div>
                            <span class="block font-sans font-bold text-[10px] uppercase tracking-widest mb-2 text-ink">Email</span>
                            <a data-element-id="email_1" href="mailto:{{ $globalSettings['email_sales'] ?? '' }}" class="font-sans font-medium text-[13px] text-muted hover:text-teal block mb-1">{{ $globalSettings['email_sales'] ?? 'sales@auxinorchem.com' }}</a>
                            <a data-element-id="email_2" href="mailto:{{ $globalSettings['email_info'] ?? '' }}" class="font-sans font-medium text-[13px] text-muted hover:text-teal block">{{ $globalSettings['email_info'] ?? 'info@auxinorchem.com' }}</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex space-x-4">
                <a href="https://wa.me/{{ $globalSettings['whatsapp_number'] ?? '9909907231' }}" target="_blank" class="flex items-center space-x-2 bg-[#25D366] text-white px-6 py-3 font-display font-bold text-xs shadow hover:bg-[#1da851] transition-colors rounded-sm">
                    <span>Chat on WhatsApp</span>
                </a>
            </div>
        </div>
        
        <!-- Form -->
        <div id="enquiry-form" class="bg-white border-t-4 border-t-teal border border-line p-8 md:p-11 shadow-lg sr-up">
            <h3 class="font-display font-bold text-2xl mb-8 border-b border-line pb-4 text-ink">Direct Enquiry</h3>
            
            @if(session('success'))
                <div class="p-4 mb-6 bg-teal-light text-teal border border-teal font-mono text-[11px] uppercase tracking-wider">
                    {{ session('success') }}
                </div>
            @endif
            
 <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block font-sans font-bold text-[10px] text-muted uppercase tracking-widest mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-sans text-[14px]">
                </div>
                
                <div class="mb-6">
                    <label class="block font-sans font-bold text-[10px] text-muted uppercase tracking-widest mb-2">Contact Person *</label>
                    <input type="text" name="contact_person" required value="{{ old('contact_person') }}" class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-sans text-[14px]">
                    @error('contact_person') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-sans font-bold text-[10px] text-muted uppercase tracking-widest mb-2">Email *</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-sans text-[14px]">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-sans font-bold text-[10px] text-muted uppercase tracking-widest mb-2">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-sans text-[14px]">
                    </div>
                </div>
                
                <div class="mb-10">
                    <label class="block font-sans font-bold text-[10px] text-muted uppercase tracking-widest mb-2">Message *</label>
                    <textarea name="message" required rows="4" class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-sans text-[14px] resize-none">{{ old('message') }}</textarea>
                    @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="btn-primary w-full py-4 font-display font-bold text-[11px] uppercase tracking-widest hover:shadow-lg">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Google Maps -->
<section class="h-[400px] w-full bg-line filter grayscale-[30%]">
    <div class="w-full h-full">
        {!! $globalSettings['google_maps_embed'] ?? '' !!}
    </div>
</section>
@endsection
