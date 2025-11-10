<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">{{ $opportunity->title }}</h1>
        <p class="text-sm sm:text-base text-gray-600">View opportunity details and apply</p>
    </div>

    <div class="card mb-6">
        <div class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold mb-2">Description</h2>
                <p class="text-gray-700">{{ $opportunity->description }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <span class="text-sm font-semibold text-gray-600">Deadline:</span>
                    <p class="text-gray-800">{{ \Carbon\Carbon::parse($opportunity->deadline)->format('F d, Y') }}</p>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-600">Available Seats:</span>
                    <p class="text-gray-800">{{ $opportunity->available_slots }}</p>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-600">Organization:</span>
                    <p class="text-gray-800">{{ $opportunity->organization->name ?? ($opportunity->organization->user->name ?? 'N/A') }}</p>
                </div>
            </div>
        </div>
    </div>

    @auth
        @if(auth()->user()->role === 'Youth')
            <div class="card">
                <form method="POST" action="{{ route('opportunity.apply', $opportunity->id) }}">
                    @csrf
                    <x-primary-button class="w-full">
                        Apply to This Opportunity
                    </x-primary-button>
                </form>
            </div>
        @endif
    @else
        <div class="card text-center py-4">
            <p class="text-gray-600 mb-4">Please <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">login</a> to apply for this opportunity.</p>
        </div>
    @endauth
</x-app-layout>
