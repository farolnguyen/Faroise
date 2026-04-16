@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-700 border-slate-600 text-slate-100 placeholder-slate-400 focus:border-cyan-500 focus:ring-cyan-500 rounded-lg shadow-sm']) }}>
