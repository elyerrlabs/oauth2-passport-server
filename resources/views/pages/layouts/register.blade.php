<!-- SEO Meta Tags - Register Page -->
<title>@yield('title', __('Register') . ' - ' . config('app.name'))</title>
<meta name="description" content="@yield('description', __('Create a new account to get started with our services'))">
<meta name="keywords" content="register, sign up, create account, join, registration">
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<meta name="author" content="{{ config('app.name') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', __('Register') . ' - ' . config('app.name'))">
<meta property="og:description" content="{{ __('Join us today and create your account') }}">
<meta property="og:image" content="{{ asset('images/og-register.jpg') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="@yield('title', __('Register') . ' - ' . config('app.name'))">
<meta name="twitter:description" content="{{ __('Create your account now') }}">
<meta name="twitter:image" content="{{ asset('images/twitter-register.jpg') }}">

<!-- Security -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">