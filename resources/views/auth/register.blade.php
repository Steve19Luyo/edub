<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('I want to register as')" />
            <div class="mt-2 space-y-2">
                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-pink-500 transition-colors">
                    <input type="radio" name="role" value="Youth" class="text-pink-500 focus:ring-pink-500" checked>
                    <span class="ml-3 text-gray-700 font-medium">Youth</span>
                    <span class="ml-auto text-sm text-gray-500">Seek opportunities</span>
                </label>
                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                    <input type="radio" name="role" value="Organization" class="text-blue-500 focus:ring-blue-500">
                    <span class="ml-3 text-gray-700 font-medium">Organization</span>
                    <span class="ml-auto text-sm text-gray-500">Post opportunities</span>
                </label>
                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-500 transition-colors">
                    <input type="radio" name="role" value="Admin" class="text-purple-500 focus:ring-purple-500">
                    <span class="ml-3 text-gray-700 font-medium">Admin</span>
                    <span class="ml-auto text-sm text-gray-500">Manage platform</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-edubridge-blue hover:text-edubridge-pink font-medium transition-colors" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
