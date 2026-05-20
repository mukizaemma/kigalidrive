<button {{ $attributes->merge(['type' => 'submit', 'class' => 'kdr-btn kdr-btn-primary inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
