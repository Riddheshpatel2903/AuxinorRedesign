<div class="bg-ink text-[#4a6080] font-mono text-[10px] tracking-[1.5px] py-2 px-4 md:px-8 flex justify-between items-center whitespace-nowrap overflow-hidden">
    <div class="flex items-center space-x-6">
        <a href="mailto:{{ $globalSettings['email_info'] ?? 'info@auxinorchem.com' }}" class="hover:text-teal-2 transition-colors">{{ $globalSettings['email_info'] ?? 'info@auxinorchem.com' }}</a>
        <span class="hidden md:inline">|</span>
        <a href="tel:{{ $globalSettings['phone_primary'] ?? '+91 990-990-7230' }}" class="hover:text-teal-2 transition-colors hidden md:inline">{{ $globalSettings['phone_primary'] ?? '+91 990-990-7230' }}</a>
        <span class="hidden lg:inline">|</span>
        <span class="hidden lg:inline">{{ $globalSettings['office_address'] ?? 'Ahmedabad, Gujarat' }}</span>
    </div>
    <div class="flex items-center space-x-4">
        <a href="{{ $globalSettings['facebook_url'] ?? '#' }}" target="_blank" class="hover:text-teal-2 transition-colors uppercase">FB</a>
        <a href="{{ $globalSettings['linkedin_url'] ?? '#' }}" target="_blank" class="hover:text-teal-2 transition-colors uppercase">IN</a>
        <a href="https://wa.me/{{ $globalSettings['whatsapp_number'] ?? '9909907231' }}" target="_blank" class="hover:text-teal-2 transition-colors uppercase">WA</a>
    </div>
</div>
