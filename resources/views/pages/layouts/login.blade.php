<!-- SEO Meta Tags - Login Page -->
<title>@yield('title', __('Login') . ' - ' . config('app.name'))</title>
<meta name="description" content="@yield('description', __('Sign in to your account to access your dashboard'))">
<meta name="keywords" content="login, sign in, access, account, authentication">
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<meta name="author" content="{{ config('app.name') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', __('Login') . ' - ' . config('app.name'))">
<meta property="og:description" content="{{ __('Sign in to your account') }}">
<meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<!-- Twitter Cards -->
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="@yield('title', __('Login') . ' - ' . config('app.name'))">
<meta name="twitter:description" content="{{ __('Sign in to your account') }}">
<meta name="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">
<!-- Security & Additional Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="strict-origin-when-cross-origin">