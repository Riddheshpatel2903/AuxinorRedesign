@extends('admin.layouts.app')

@section('title', 'Global Settings')
@section('header', 'System Settings')
@section('subheader', 'Manage company information, contact details, and site configuration.')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div x-data="{ activeTab: 'general' }">
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-8 overflow-x-auto overflow-y-hidden">
            <button type="button" @click="activeTab = 'general'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'general' ? 'border-teal text-teal-700' : 'border-transparent text-gray-500 hover:text-ink'">General Info</button>
            <button type="button" @click="activeTab = 'contact'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'contact' ? 'border-teal text-teal-700' : 'border-transparent text-gray-500 hover:text-ink'">Contact & Social</button>
            <button type="button" @click="activeTab = 'hero'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'hero' ? 'border-teal text-teal-700' : 'border-transparent text-gray-500 hover:text-ink'">Hero Section</button>
            <button type="button" @click="activeTab = 'about'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'about' ? 'border-teal text-teal-700' : 'border-transparent text-gray-500 hover:text-ink'">About Section</button>
            <button type="button" @click="activeTab = 'stats'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'stats' ? 'border-teal text-teal-700' : 'border-transparent text-gray-500 hover:text-ink'">Statistics</button>
        </div>
        
        <div class="space-y-6 max-w-4xl min-h-[400px]">
            
            <!-- General Info Tab -->
            <div x-show="activeTab === 'general'" x-transition.opacity style="display: none;" class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">General Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Company Name</label>
                        <input type="text" name="settings[company_name]" value="{{ $settingsData['company_name'] ?? '' }}" class="admin-input w-full p-3 font-serif text-[14px]">
                    </div>
                </div>
            </div>

            <!-- Contact & Social Tab -->
            <div x-show="activeTab === 'contact'" x-transition.opacity style="display: none;" class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">Contact Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Primary Phone</label>
                        <input type="text" name="settings[phone_primary]" value="{{ $settingsData['phone_primary'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Secondary Phone</label>
                        <input type="text" name="settings[phone_secondary]" value="{{ $settingsData['phone_secondary'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Sales Email</label>
                        <input type="email" name="settings[email_sales]" value="{{ $settingsData['email_sales'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Info Email</label>
                        <input type="email" name="settings[email_info]" value="{{ $settingsData['email_info'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">WhatsApp Number (inc country code)</label>
                        <input type="text" name="settings[whatsapp_number]" value="{{ $settingsData['whatsapp_number'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]" placeholder="e.g. 919909907231">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Office Address</label>
                    <textarea name="settings[office_address]" rows="3" class="admin-input w-full p-3 font-serif text-[14px]">{{ $settingsData['office_address'] ?? '' }}</textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Warehouse Address</label>
                    <textarea name="settings[warehouse_address]" rows="3" class="admin-input w-full p-3 font-serif text-[14px]">{{ $settingsData['warehouse_address'] ?? '' }}</textarea>
                </div>
                
                <div class="mb-8">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Google Maps Embed HTML (iframe)</label>
                    <textarea name="settings[google_maps_embed]" rows="4" class="admin-input w-full p-3 font-mono text-[11px] text-gray-400">{{ $settingsData['google_maps_embed'] ?? '' }}</textarea>
                </div>
                
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">Social Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">LinkedIn URL</label>
                        <input type="url" name="settings[social_linkedin]" value="{{ $settingsData['social_linkedin'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Twitter/X URL</label>
                        <input type="url" name="settings[social_twitter]" value="{{ $settingsData['social_twitter'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Facebook URL</label>
                        <input type="url" name="settings[social_facebook]" value="{{ $settingsData['social_facebook'] ?? '' }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                </div>
            </div>

            <!-- Hero Tab -->
            <div x-show="activeTab === 'hero'" x-transition.opacity style="display: none;" class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">Home Hero Section</h3>
                
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Headline Line 1</label>
                        <input type="text" name="settings[hero_headline_line1]" value="{{ $settingsData['hero_headline_line1'] ?? '' }}" class="admin-input w-full p-3 font-display text-[20px] font-bold">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Headline Line 2</label>
                        <input type="text" name="settings[hero_headline_line2]" value="{{ $settingsData['hero_headline_line2'] ?? '' }}" class="admin-input w-full p-3 font-display text-[20px] font-bold">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Headline Line 3 (Italicized/Colored)</label>
                        <input type="text" name="settings[hero_headline_line3]" value="{{ $settingsData['hero_headline_line3'] ?? '' }}" class="admin-input w-full p-3 font-serif text-[20px] italic text-teal-700">
                    </div>
                    
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Hero Subtext</label>
                        <textarea name="settings[hero_subtext]" rows="3" class="admin-input w-full p-3 font-serif text-[14px]">{{ $settingsData['hero_subtext'] ?? '' }}</textarea>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500">Hero Background Image</label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="hidden" name="settings[show_hero_image]" value="0">
                                <input type="checkbox" name="settings[show_hero_image]" value="1" {{ ($settingsData['show_hero_image'] ?? '1') == '1' ? 'checked' : '' }} class="w-3 h-3 text-teal bg-white border-gray-300 rounded focus:ring-teal focus:ring-2">
                                <span class="font-mono text-[10px] uppercase tracking-wider text-gray-500">Show Section</span>
                            </label>
                        </div>
                        <input type="file" name="settings_files[hero_image_url]" accept="image/*" class="admin-input w-full p-3 font-mono text-[13px]">
                        @if(!empty($settingsData['hero_image_url']))
                            <div class="mt-2 h-32 w-48 bg-cover bg-center border border-gray-200 shadow-sm" style="background-image: url('{{ $settingsData['hero_image_url'] }}')"></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- About Tab -->
            <div x-show="activeTab === 'about'" x-transition.opacity style="display: none;" class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">About & Infrastructure Sections</h3>
                
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Mission Statement</label>
                        <textarea name="settings[mission]" rows="2" class="admin-input w-full p-3 font-serif text-[14px]">{{ $settingsData['mission'] ?? '' }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">About Short Description (Home page)</label>
                        <textarea name="settings[about_short]" rows="4" class="admin-input w-full p-3 font-serif text-[14px]">{{ $settingsData['about_short'] ?? '' }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">About Long Description (About page)</label>
                        <textarea name="settings[about_long]" rows="6" class="admin-input w-full p-3 font-serif text-[14px]">{{ $settingsData['about_long'] ?? '' }}</textarea>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500">About Section Image</label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="hidden" name="settings[show_about_image]" value="0">
                                <input type="checkbox" name="settings[show_about_image]" value="1" {{ ($settingsData['show_about_image'] ?? '1') == '1' ? 'checked' : '' }} class="w-3 h-3 text-teal bg-white border-gray-300 rounded focus:ring-teal focus:ring-2">
                                <span class="font-mono text-[10px] uppercase tracking-wider text-gray-500">Show Section</span>
                            </label>
                        </div>
                        <input type="file" name="settings_files[about_image_url]" accept="image/*" class="admin-input w-full p-3 font-mono text-[13px]">
                        @if(!empty($settingsData['about_image_url']))
                            <div class="mt-2 h-24 w-auto inline-block border border-gray-200">
                                <img src="{{ $settingsData['about_image_url'] }}" class="h-full w-auto">
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500">Infrastructure Section Image</label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="hidden" name="settings[show_infra_image]" value="0">
                                <input type="checkbox" name="settings[show_infra_image]" value="1" {{ ($settingsData['show_infra_image'] ?? '1') == '1' ? 'checked' : '' }} class="w-3 h-3 text-teal bg-white border-gray-300 rounded focus:ring-teal focus:ring-2">
                                <span class="font-mono text-[10px] uppercase tracking-wider text-gray-500">Show Section</span>
                            </label>
                        </div>
                        <input type="file" name="settings_files[infra_image_url]" accept="image/*" class="admin-input w-full p-3 font-mono text-[13px]">
                        @if(!empty($settingsData['infra_image_url']))
                            <div class="mt-2 h-24 w-auto inline-block border border-gray-200">
                                <img src="{{ $settingsData['infra_image_url'] }}" class="h-full w-auto">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Tab -->
            <div x-show="activeTab === 'stats'" x-transition.opacity style="display: none;" class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">Company Statistics</h3>
                <p class="font-serif text-[13px] text-gray-500 mb-6 italic">These numbers appear animated across various pages.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Years Active</label>
                        <input type="number" name="settings[stat_years]" value="{{ $settingsData['stat_years'] ?? '' }}" class="admin-input w-full p-3 font-display text-[24px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Products</label>
                        <input type="number" name="settings[stat_products]" value="{{ $settingsData['stat_products'] ?? '' }}" class="admin-input w-full p-3 font-display text-[24px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Industries Served</label>
                        <input type="number" name="settings[stat_industries]" value="{{ $settingsData['stat_industries'] ?? '' }}" class="admin-input w-full p-3 font-display text-[24px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Reach/Logistics</label>
                        <input type="text" name="settings[stat_reach]" value="{{ $settingsData['stat_reach'] ?? '' }}" class="admin-input w-full p-3 font-display text-[24px]">
                    </div>
                </div>
            </div>

            <!-- Sticky Save Button -->
            <div class="admin-card p-6 sticky bottom-0 border-t-2 border-t-gray-100 flex justify-between items-center z-10 bg-white/90 backdrop-blur shadow-[0_-10px_30px_rgba(0,0,0,0.02)]">
                <p class="font-serif italic text-gray-500 text-[13px]">Remember to save your changes before leaving this page.</p>
                <button type="submit" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest text-center shadow-lg">
                    Save Global Settings
                </button>
            </div>
            
        </div>
    </div>
</form>
@endsection
