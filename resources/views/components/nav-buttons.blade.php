@props(['back', 'wrapperClass' => 'flex gap-4 mt-6'])

@php
    $previous = url()->previous();
    $isSafePrevious = $previous !== url()->current()
        && parse_url($previous, PHP_URL_HOST) === request()->getHost();
    $backUrl = $isSafePrevious ? $previous : $back;
@endphp

<div class="{{ $wrapperClass }}">
    <a href="{{ $backUrl }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
        Terug
    </a>
    <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
        Home
    </a>
</div>