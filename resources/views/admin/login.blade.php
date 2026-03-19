<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Auxinor Chemicals</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Lora:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    <style>body { background-color: var(--ink); }</style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans antialiased relative overflow-hidden">
    
    <!-- Background element -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] bg-repeat opacity-50"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-teal/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md p-8">
        <div class="text-center mb-10">
            <h1 class="font-display font-bold text-3xl tracking-wide text-white mb-2">AUXINOR <span class="text-teal text-lg align-top">SYS</span></h1>
            <p class="font-mono text-[11px] uppercase tracking-widest text-white/40">Secure System Access</p>
        </div>

        <div class="bg-ink2 border border-white/10 p-8 shadow-[0_20px_40px_rgba(0,0,0,0.5)] relative">
            <!-- Decorative corner elements -->
            <div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-teal z-20"></div>
            <div class="absolute top-0 right-0 w-2 h-2 border-t border-r border-teal z-20"></div>
            <div class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-teal z-20"></div>
            <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-teal z-20"></div>

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                @if($errors->any())
                    <div class="mb-6 p-4 border border-red-500/50 bg-red-900/20 text-red-200 font-mono text-[11px]">
                        @foreach($errors->all() as $error)
                            <div class="uppercase tracking-wider">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-widest text-white/50 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-ink border border-white/20 p-3 text-white font-mono text-[14px] focus:outline-none focus:border-teal transition-colors">
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-widest text-white/50 mb-2">Passphrase</label>
                    <input type="password" name="password" required class="w-full bg-ink border border-white/20 p-3 text-white font-mono text-[14px] focus:outline-none focus:border-teal transition-colors">
                </div>
                
                <div class="mb-8 flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-teal bg-ink border-white/20 focus:ring-teal">
                    <label for="remember" class="ml-2 font-mono text-[10px] uppercase tracking-widest text-white/50">Remember session</label>
                </div>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white py-4 font-display font-bold text-[11px] uppercase tracking-widest text-center shadow-lg relative overflow-hidden group">
                    <span class="relative z-10">Authenticate</span>
                    <div class="absolute inset-0 bg-white/20 transform -translate-x-full skew-x-12 group-hover:translate-x-full transition-transform duration-700"></div>
                </button>
            </form>
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ url('/') }}" class="font-mono text-[10px] uppercase tracking-wider text-white/30 hover:text-teal transition-colors flex items-center justify-center">
                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Return to public site
            </a>
        </div>
    </div>
</body>
</html>
