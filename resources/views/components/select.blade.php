@props(['name', 'label', 'options' => []])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
        class="mt-1 block w-full border rounded px-3 py-2 @error($name) border-red-500 @enderror">
        <option value="">Selecione</option>
        @foreach ($options as $option)
            <option value="{{ $option }}" {{ old($name) == $option ? 'selected' : '' }}>{{ $option }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
