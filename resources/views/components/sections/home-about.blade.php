@props(['data'])
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-20">
            <div class="flex-1 relative">
                <div class="absolute -top-10 -left-10 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl"></div>
                
                <div class="relative">
                    @if($data['image'] ?? false)
                        <img src="{{ asset($data['image']) }}" alt="About" class="rounded-3xl shadow-2xl w-full relative z-10" data-element-key="image">
                    @endif
                    <div class="absolute -bottom-8 -left-8 bg-teal-600 p-8 rounded-3xl text-white shadow-2xl z-20 hidden md:block">
                        <div class="text-3xl font-bold mb-1">15+</div>
                        <div class="text-xs uppercase tracking-widest opacity-80">Years of Excellence</div>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <span class="text-teal-600 font-bold uppercase tracking-widest text-sm mb-4 block">About Our Journey</span>
                <h2 class="text-3xl md:text-5xl font-bold mb-8 text-gray-900 leading-tight" data-element-key="title">
                    {{ $data['title'] ?? '' }}
                </h2>
                <div class="text-gray-600 text-lg leading-relaxed mb-10 space-y-4" data-element-key="subtitle">
                    {!! $data['subtitle'] ?? '' !!}
                </div>
                
                @if($data['button_text'] ?? false)
                    <a href="{{ route('about') }}" class="group inline-flex items-center gap-3 font-bold text-gray-900 hover:text-teal-600 transition-colors" data-element-key="button_text">
                        {{ $data['button_text'] }}
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
