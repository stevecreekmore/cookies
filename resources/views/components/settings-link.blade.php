@props(['tag' => 'a'])

@if($tag === 'button')
    <button
        {{ $attributes->merge(['type' => 'button']) }}
        onclick="if(window.CookieConsent){window.CookieConsent.showBanner()}else{alert('Cookie consent not loaded. Please add the middleware.')}"
    >
        {{ $slot }}
    </button>
@else
    <a
        {{ $attributes->merge(['href' => '#']) }}
        onclick="event.preventDefault();if(window.CookieConsent){window.CookieConsent.showBanner()}else{alert('Cookie consent not loaded. Please add the middleware.')}"
    >
        {{ $slot }}
    </a>
@endif
