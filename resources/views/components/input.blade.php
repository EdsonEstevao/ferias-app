{{-- @props(['name', 'label', 'type' => 'text', 'class' => ''])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name) }}"
        class="mt-1 block w-full border rounded px-3 py-2 {{ $class }} @error($name) border-red-500 @enderror">
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div> --}}


@props(['name', 'label', 'value' => '', 'icon' => null, 'iconColor' => 'text-gray-400', 'placeholder' => ''])

<div class="group">
    <label for="{{ $name }}"
        class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
        @if ($icon)
            <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <!-- Ícone correspondente -->
            </svg>
        @endif
        {{ $label }}
    </label>
    <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-400 placeholder-gray-400']) }}>
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
