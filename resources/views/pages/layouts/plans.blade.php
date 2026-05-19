<!-- SEO Meta Tags - Plans & Pricing Page -->
<title>@yield('title', __('Plans & Pricing') . ' - ' . config('app.name'))</title>
<meta name="description" content="@yield('description', __('Choose the perfect plan for your needs. Compare features and pricing to find the best option.'))">
<meta name="keywords" content="plans, pricing, subscription, packages, pricing plans, membership">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="author" content="{{ config('app.name') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', __('Plans & Pricing') . ' - ' . config('app.name'))">
<meta property="og:description" content="{{ __('Compare our plans and choose the best option for you') }}">
<meta property="og:image" content="{{ asset('images/og-pricing.jpg') }}">
<meta property="og:image:alt" content="{{ __('Pricing plans comparison') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:price:amount" content="@yield('price', '0')">
<meta property="og:price:currency" content="{{ config('app.currency', 'USD') }}">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="@yield('title', __('Plans & Pricing') . ' - ' . config('app.name'))">
<meta name="twitter:description" content="{{ __('Find the perfect plan for your needs') }}">
<meta name="twitter:image" content="{{ asset('images/twitter-pricing.jpg') }}">
<meta name="twitter:image:alt" content="{{ __('Pricing plans') }}">

<!-- Structured Data for Pricing (Schema.org) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ config('app.name') }} Plans",
    "description": "{{ __('Subscription plans for our services') }}",
    "offers": {
        "@type": "AggregateOffer",
        "priceCurrency": "{{ config('app.currency', 'USD') }}",
        "lowPrice": "@yield('min_price', '0')",
        "highPrice": "@yield('max_price', '0')",
        "offerCount": "@yield('total_plans', '3')"
    }
}
</script>

<!-- Additional SEO Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">