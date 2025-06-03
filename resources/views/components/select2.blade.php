<div class="mt-2 mb-5">
    @if($label)
        <label for="{{ $name }}" class="uppercase block text-sm font-medium text-gray-900">{{ $label }}</label>
    @endif

    {{-- <select
        class="select2-custom block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm {{ $class }}"
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        data-max-length="{{ $maxLength }}">

        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $value)
            <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }} data-nombre-completo="{{ $value }}">
                {{ strlen($value) > $maxLength ? substr($value, 0, $maxLength).'...' : $value }}
            </option>
        @endforeach
    </select> --}}
    <select
        id="{{ $id }}"
        class="select2-custom block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm {{ $class }}"
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        data-max-length="{{ $maxLength }}">

        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $value)
            {{-- <option value="{{ (string)$key }}" {{ (string)$key === (string)$selected ? 'selected' : '' }} data-nombre-completo="{{ $value }}">
                {{ strlen($value) > $maxLength ? substr($value, 0, $maxLength).'...' : $value }}
            </option> --}}
            <option value="{{ $key }}" {{ (string)$key === (string)$selected ? 'selected' : '' }}
                data-nombre-completo="{{ $value }}"
                @if($withStock) data-stock="{{ $options[$key]['stock'] ?? 0 }}" @endif>
                {{ strlen($value) > $maxLength ? substr($value, 0, $maxLength).'...' : $value }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div role="alert" class="alert alert-error mt-4 p-2">
            <span class="text-white font-bold">{{ $message }}</span>
        </div>
    @enderror
</div>