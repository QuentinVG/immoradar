<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-10 items-center justify-center rounded-md border border-teal-700 bg-teal-700 px-4 py-2 text-sm font-black text-white shadow-sm shadow-teal-900/15 transition hover:-translate-y-0.5 hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
