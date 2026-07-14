{{--
    Reusable form field (v2): floating-label input/textarea/select, with
    old()-value restoration and automatic $errors-bag validation display.
    Select2 fields use a regular top label instead of floating (Select2's
    rendered widget doesn't support the floating-placeholder animation).

    Usage:
        <x-v2.form.field name="category_name" label="Category Name" required />

        <x-v2.form.field name="country" label="Country" type="select"
            :options="$country" select2 />

        <x-v2.form.field name="valid_until" label="Valid Until" datepicker />

        <x-v2.form.field name="remark" label="Remarks" type="textarea" />
--}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'options' => null,
    'required' => false,
    'select2' => false,
    'datepicker' => false,
    'datepickerFormat' => null,
    'placeholder' => null,
])
@php
    $val = old($name, $value);
    $hasError = $errors->has($name);
    $fieldId = 'field-' . str_replace(['[', ']', '.'], '-', $name);
@endphp

@if($type === 'select' && $select2)
    <div class="mb-3">
        <label for="{{ $fieldId }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
        <select name="{{ $name }}" id="{{ $fieldId }}"
                class="form-select {{ $hasError ? 'is-invalid' : '' }}"
                data-v2-select2
                data-placeholder="{{ $placeholder ?? ('Select ' . $label) }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes }}>
            @foreach($options ?? [] as $optValue => $optLabel)
                <option value="{{ $optValue }}" {{ (string) $val === (string) $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
            @endforeach
        </select>
        @if($hasError)
            <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
        @endif
    </div>
@else
    <div class="form-floating mb-3">
        @if($type === 'select')
            <select name="{{ $name }}" id="{{ $fieldId }}"
                    class="form-select {{ $hasError ? 'is-invalid' : '' }}"
                    {{ $required ? 'required' : '' }}
                    {{ $attributes }}>
                @foreach($options ?? [] as $optValue => $optLabel)
                    <option value="{{ $optValue }}" {{ (string) $val === (string) $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
                @endforeach
            </select>
        @elseif($type === 'textarea')
            <textarea name="{{ $name }}" id="{{ $fieldId }}"
                      class="form-control {{ $hasError ? 'is-invalid' : '' }}"
                      placeholder="{{ $placeholder ?? $label }}"
                      style="height: 110px"
                      {{ $required ? 'required' : '' }}
                      {{ $attributes }}>{{ $val }}</textarea>
        @else
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $fieldId }}"
                   value="{{ $val }}"
                   class="form-control {{ $hasError ? 'is-invalid' : '' }}"
                   placeholder="{{ $placeholder ?? $label }}"
                   {{ $datepicker ? 'data-v2-datepicker' : '' }}
                   {{ $datepickerFormat ? 'data-v2-datepicker-format=' . $datepickerFormat : '' }}
                   {{ $required ? 'required' : '' }}
                   {{ $attributes }}>
        @endif
        <label for="{{ $fieldId }}" class="{{ $required ? 'required' : '' }}">{{ $label }}</label>
        @if($hasError)
            <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
        @endif
    </div>
@endif
