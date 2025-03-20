<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Word On The Go</title>
    <link rel="icon" href="{{ asset('images/wotg-icon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/auth.css?v=2.6') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <meta property="og:title" content="Word On The Go" />
    <meta property="og:image" content="{{ asset('images/wotg-logo-with-bg.jpeg') }}">
    <meta property="og:image:alt" content="Open Graph Image Description">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:type" content="website">
    <meta property="og:description" content="Welcome to Word On The Go, your online destination for uplifting church blogs. Discover inspiring articles, spiritual insights, and community stories that enrich your faith and connect you with our church family. Join us as we explore faith, hope, and love together!">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Word On The Go">
    <meta name="twitter:description" content="Welcome to Word On The Go, your online destination for uplifting church blogs.">
    <meta name="twitter:image" content="{{ asset('images/wotg-logo-with-bg.jpeg') }}">
</head>
<body class="{{ Request::is('profile/edit') ? 'full-height' : 'full-viewport' }}">
    <div class="auth-container">
        @yield('content')
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
