<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'EduBridge') }} - Connect Youth with Opportunities</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-pink-50 via-blue-50 to-white min-h-screen">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation Header -->
            <header class="w-full">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <x-edubridge-logo class="h-10 w-auto" />
                        <span class="text-2xl font-bold gradient-text hidden sm:block">EduBridge</span>
                    </div>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-primary">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-edubridge-blue hover:text-edubridge-pink font-semibold transition-colors">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-primary">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-7xl mx-auto text-center">
                    <!-- Logo and Title -->
                    <div class="mb-8">
                        <x-edubridge-logo class="h-24 w-auto mx-auto mb-6 animate-pulse" />
                        <h1 class="text-5xl md:text-6xl font-bold mb-4 gradient-text">
                            Welcome to EduBridge
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-600 mb-2">
                            Connecting Youth with Educational Opportunities
                        </p>
                        <p class="text-lg text-gray-500 max-w-2xl mx-auto">
                            Discover internships, training programs, and volunteering opportunities from verified organizations.
                        </p>
                    </div>

                    <!-- Call to Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary text-lg px-8 py-4">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-4">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn-secondary text-lg px-8 py-4">
                                Sign In
                            </a>
                        @endauth
                    </div>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 max-w-5xl mx-auto">
                        <!-- Feature 1: For Youth -->
                        <div class="card text-left">
                            <div class="mb-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-edubridge-pink to-edubridge-pink-light flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">For Youth</h3>
                                <p class="text-gray-600">
                                    Browse verified opportunities, apply easily, and track your applications in one place.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2: For Organizations -->
                        <div class="card text-left">
                            <div class="mb-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-edubridge-blue to-edubridge-blue-light flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">For Organizations</h3>
                                <p class="text-gray-600">
                                    Post opportunities, manage applicants, and grow your impact with verified status.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3: Safe & Verified -->
                        <div class="card text-left">
                            <div class="mb-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-edubridge-pink via-edubridge-blue to-edubridge-pink flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Safe & Verified</h3>
                                <p class="text-gray-600">
                                    All organizations are verified by admins to ensure quality and safety for all users.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/80 backdrop-blur-sm border-t border-pink-200/50 mt-auto py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-600">
                    <p>&copy; {{ date('Y') }} EduBridge. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
