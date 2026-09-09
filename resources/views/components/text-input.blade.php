@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100']) }}>
