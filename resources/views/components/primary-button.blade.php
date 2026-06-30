<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 rounded-2xl font-semibold text-sm text-white shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 bg-gradient-to-r from-purple-500 to-teal-400']) }}>
    {{ $slot }}
</button>
