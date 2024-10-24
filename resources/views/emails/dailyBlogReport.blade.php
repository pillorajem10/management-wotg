<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->blog_title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 40px auto;
            overflow: hidden;
        }
        .blog-title {
            color: #c0392b;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
        }
        .blog-intro {
            color: #555;
            line-height: 1.6;
            margin: 15px 0;
            text-align: justify;
        }
        .release-date {
            color: #888;
            font-style: normal;
            display: block;
            text-align: right;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
            text-align: center;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }
        .like-button {
            background-color: #ffffff;
            border: 1px solid #c0392b;
            color: #c0392b;
            font-size: 0.8rem;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
        }

        .like-button:hover {
            transform: scale(1.05); /* Slightly increase size on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="blog-title">{{ $blog->blog_title }}</h1>
        <p class="blog-intro">{{ html_entity_decode(strip_tags($blogIntro)) }}</p>
        <p class="blog-intro">Be encouraged! <br>Mike Pineda</p>
        <a href="https://blogs.wotgonline.com/blogs/{{ $blog->id }}" class="like-button" style="color: #c0392b;">Read Full Article Here</a>
    </div>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} WOTG. All rights reserved.</p>
    </footer>
</body>
</html>
