<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="registrationForm">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
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
                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                    <input type="radio" name="role" value="Youth" class="text-blue-500 focus:ring-blue-500" {{ old('role') == 'Youth' || !old('role') ? 'checked' : '' }} onchange="toggleOrganizationFields()">
                    <span class="ml-3 text-gray-700 font-medium">Youth</span>
                    <span class="ml-auto text-sm text-gray-500">Seek opportunities</span>
                </label>
                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors">
                    <input type="radio" name="role" value="Organization" class="text-blue-500 focus:ring-blue-500" {{ old('role') == 'Organization' ? 'checked' : '' }} onchange="toggleOrganizationFields()">
                    <span class="ml-3 text-gray-700 font-medium">Organization</span>
                    <span class="ml-auto text-sm text-gray-500">Post opportunities</span>
                </label>
                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-600 transition-colors">
                    <input type="radio" name="role" value="Admin" class="text-blue-600 focus:ring-blue-600" {{ old('role') == 'Admin' ? 'checked' : '' }} onchange="toggleOrganizationFields()">
                    <span class="ml-3 text-gray-700 font-medium">Admin</span>
                    <span class="ml-auto text-sm text-gray-500">Manage platform</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Organization Fields (shown for Organization and Admin) -->
        <div id="organizationFields" class="mt-6 space-y-4 hidden">
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Organization Information</h3>
                
                <!-- Organization Name -->
                <div class="mt-4">
                    <x-input-label for="organization_name" :value="__('Organization Name')" />
                    <x-text-input id="organization_name" class="block mt-1 w-full" type="text" name="organization_name" :value="old('organization_name')" />
                    <x-input-error :messages="$errors->get('organization_name')" class="mt-2" />
                </div>

                <!-- Organization Type -->
                <div class="mt-4">
                    <x-input-label for="organization_type" :value="__('Organization Type')" />
                    <select id="organization_type" name="organization_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select type...</option>
                        <option value="NGO" {{ old('organization_type') == 'NGO' ? 'selected' : '' }}>NGO</option>
                        <option value="Company" {{ old('organization_type') == 'Company' ? 'selected' : '' }}>Company</option>
                        <option value="Training Center" {{ old('organization_type') == 'Training Center' ? 'selected' : '' }}>Training Center</option>
                        <option value="Educational Institution" {{ old('organization_type') == 'Educational Institution' ? 'selected' : '' }}>Educational Institution</option>
                        <option value="Government Agency" {{ old('organization_type') == 'Government Agency' ? 'selected' : '' }}>Government Agency</option>
                        <option value="Other" {{ old('organization_type') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('organization_type')" class="mt-2" />
                </div>

                <!-- Contact Email -->
                <div class="mt-4">
                    <x-input-label for="contact_email" :value="__('Contact Email')" />
                    <x-text-input id="contact_email" class="block mt-1 w-full" type="email" name="contact_email" :value="old('contact_email')" />
                    <p class="mt-1 text-xs text-gray-500">Organization contact email (can be different from your account email)</p>
                    <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                </div>

                <!-- Contact Phone -->
                <div class="mt-4">
                    <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                    <x-text-input id="contact_phone" class="block mt-1 w-full" type="tel" name="contact_phone" :value="old('contact_phone')" />
                    <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                </div>

                <!-- Location -->
                <div class="mt-4">
                    <x-input-label for="location" :value="__('Location')" />
                    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="City, Country" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <x-input-label for="organization_description" :value="__('Organization Description')" />
                    <textarea id="organization_description" name="organization_description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Brief description of your organization...">{{ old('organization_description') }}</textarea>
                    <x-input-error :messages="$errors->get('organization_description')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleOrganizationFields() {
            const role = document.querySelector('input[name="role"]:checked').value;
            const orgFields = document.getElementById('organizationFields');
            
            if (role === 'Organization' || role === 'Admin') {
                orgFields.classList.remove('hidden');
                // Make organization fields required
                const orgName = document.getElementById('organization_name');
                const contactEmail = document.getElementById('contact_email');
                if (orgName) orgName.setAttribute('required', 'required');
                if (contactEmail) contactEmail.setAttribute('required', 'required');
            } else {
                orgFields.classList.add('hidden');
                // Remove required attribute
                const orgName = document.getElementById('organization_name');
                const contactEmail = document.getElementById('contact_email');
                if (orgName) orgName.removeAttribute('required');
                if (contactEmail) contactEmail.removeAttribute('required');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOrganizationFields();
        });
    </script>
</x-guest-layout>
