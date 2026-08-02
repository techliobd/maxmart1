@props(['label', 'name', 'type' => 'text', 'required' => false, 'placeholder' => null, 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @if($type === 'textarea')
        <textarea id="{{ $name }}" 
                  name="{{ $name }}" 
                  rows="4"
                  @if($required) required @endif
                  placeholder="{{ $placeholder }}"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error($name) border-red-500 @enderror">{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select id="{{ $name }}" 
                name="{{ $name }}" 
                @if($required) required @endif
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error($name) border-red-500 @enderror">
            {{ $slot }}
        </select>
    @else
        <input type="{{ $type }}" 
               id="{{ $name }}" 
               name="{{ $name }}" 
               value="{{ old($name, $value) }}"
               @if($required) required @endif
               placeholder="{{ $placeholder }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error($name) border-red-500 @enderror">
    @endif
    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
