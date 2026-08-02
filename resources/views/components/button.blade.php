@props(['href' => null, 'type' => 'button', 'variant' => 'primary', 'size' => 'md', 'disabled' => false, 'submit' => false])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClasses = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'outline' => 'border-2 border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
];

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];

$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);

if ($disabled) {
    $classes .= ' opacity-50 cursor-not-allowed';
}
@endphp

@if($href && !$disabled)
    <a href="{{ $href }}" class="{{ $classes }}">
        {{ $slot }}
    </a>
@elseif($submit)
    <button type="submit" @if($disabled) disabled @endif class="{{ $classes }}">
        {{ $slot }}
    </button>
@else
    <button type="{{ $type }}" @if($disabled) disabled @endif class="{{ $classes }}">
        {{ $slot }}
    </button>
@endif
