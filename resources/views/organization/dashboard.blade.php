<x-app-layout>
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold mb-2">Organization Dashboard</h1>
        <p class="text-sm sm:text-base text-gray-600">Create and manage your opportunities</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-6 rounded-lg border-l-4 border-green-500">
            {{ session('success') }}
        </div>
    @endif

    {{-- Create Opportunity Form --}}
    <div class="card mb-8">
        <h2 class="text-xl font-semibold mb-4">Create New Opportunity</h2>
        <form method="POST" action="{{ route('opportunities.store') }}" class="space-y-4">
            @csrf
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" placeholder="Opportunity Title" required />
            </div>
            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Describe the opportunity..." required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="deadline" :value="__('Deadline')" />
                    <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="seats" :value="__('Available Seats')" />
                    <x-text-input id="seats" name="seats" type="number" class="mt-1 block w-full" placeholder="Number of seats" required min="1" />
                </div>
            </div>
            <div class="pt-4">
                <x-primary-button>Create Opportunity</x-primary-button>
            </div>
        </form>
    </div>

    {{-- List of Organization Opportunities --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Your Opportunities</h2>
        @if($opportunities->isEmpty())
            <div class="card text-center py-8">
                <p class="text-gray-500">You haven't created any opportunities yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($opportunities as $opp)
                    <div class="card">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-base sm:text-lg mb-2">{{ $opp->title }}</h3>
                                <p class="text-sm sm:text-base text-gray-600 mb-2">{{ Str::limit($opp->description, 150) }}</p>
                                <div class="flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500">
                                    <span>Deadline: {{ \Carbon\Carbon::parse($opp->deadline)->format('M d, Y') }}</span>
                                    <span>Seats: {{ $opp->available_slots }}</span>
                                    <span>Applications: {{ $opp->applications_count ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="sm:ml-4 flex-shrink-0">
                                <a href="{{ route('organization.applicants', $opp->id) }}" class="btn-primary text-xs sm:text-sm px-3 sm:px-4 py-2 block text-center sm:inline-block">
                                    View Applicants
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
