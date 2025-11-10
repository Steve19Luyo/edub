<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide shadow-lg hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-200 transform']) }}>
    {{ $slot }}
</button>
