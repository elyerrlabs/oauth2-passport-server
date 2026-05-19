<!-- SEO Meta Tags - Forgot Password Page -->
<title>@yield('title', __('Forgot Password') . ' - ' . config('app.name'))</title>
<meta name="description" content="@yield('description', __('Reset your password easily. Enter your email to receive password reset instructions.'))">
<meta name="keywords" content="forgot password, reset password, recover password, password reset">
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<meta name="author" content="{{ config('app.name') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', __('Forgot Password') . ' - ' . config('app.name'))">
<meta property="og:description" content="{{ __('Reset your password in just a few steps') }}">
<meta property="og:image" content="{{ asset('images/og-password.jpg') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="@yield('title', __('Forgot Password') . ' - ' . config('app.name'))">
<meta name="twitter:description" content="{{ __('Reset your password instructions') }}">
<meta name="twitter:image" content="{{ asset('images/twitter-password.jpg') }}">

<!-- Security Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">