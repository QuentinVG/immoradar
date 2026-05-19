@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-slate-300 bg-white shadow-sm focus:border-teal-600 focus:ring-teal-600']) }}>
