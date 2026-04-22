@props(['size' => 'md', 'type' => 'spinner'])

@php
    $sizeClasses = [
        'sm' => 'h-4 w-4',
        'md' => 'h-8 w-8',
        'lg' => 'h-12 w-12',
        'xl' => 'h-16 w-16',
    ];
@endphp

@if ($type === 'spinner')
    <div class="flex justify-center items-center">
        <div class="{{ $sizeClasses[$size] }} loading-spinner"></div>
    </div>
@elseif($type === 'dots')
    <div class="flex justify-center items-center">
        <div class="loading-dots">
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
        </div>
    </div>
@elseif($type === 'pulse')
    <div class="flex justify-center items-center">
        <div class="animate-pulse">
            <div class="h-4 bg-slate-200 rounded w-24"></div>
        </div>
    </div>
@elseif($type === 'skeleton')
    <div class="animate-pulse">
        <div class="space-y-3">
            <div class="h-4 bg-slate-200 rounded w-3/4"></div>
            <div class="h-4 bg-slate-200 rounded w-1/2"></div>
            <div class="h-4 bg-slate-200 rounded w-5/6"></div>
        </div>
    </div>
@endif
