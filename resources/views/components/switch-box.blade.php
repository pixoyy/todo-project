<div class="form-group col-md-12 mb-3 ">
    <div class="form-check form-switch {{ $class ?? '' }}">
        <label class="form-check-label form-label  mx-3" for="{{ $id ?? 'flexSwitchCheckDefault' }}">
            {{ $label ?? '' }}
        </label>

        <!-- Hidden input ensures a value of 0 when checkbox is unchecked -->
        <input type="hidden" name="{{ $name ?? '' }}" value="0" >

        <input
            class="form-check-input"
            type="checkbox"
            id="{{ $id ?? 'flexSwitchCheckDefault' }}"
            name="{{ $name ?? '' }}"
            value="1"
            {{ isset($value) && $value == 1 ? 'checked' : '' }}
            {{ $attributes->merge(['disabled' => $disabled ?? false]) }}
        >
    </div>

    @error("$name")
        <div class="d-block invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<style>
    .form-switch .form-check-input {
        width: 45px;
        height: 25px;
        margin-top: 0;
    }


    .form-check-input:checked {
        background-color: #2F318B;
        border-color: #2F318B;
    }
</style>
