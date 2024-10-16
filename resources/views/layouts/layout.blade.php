<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #c0392b;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            width: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .hamburger {
            cursor: pointer;
            margin-left: 20px;
        }

        .sidebar {
            background-color: #e74c3c;
            color: white;
            padding: 20px;
            width: 250px;
            height: 100vh;
            position: fixed;
            left: -250px; /* Initially hidden */
            transition: left 0.3s ease;
        }

        .sidebar.active {
            left: 0; /* Show when active */
        }

        .sidebar ul {
            list-style-type: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        .main-content {
            padding: 20px;
            margin-left: 0; /* Adjust for sidebar */
            transition: margin-left 0.3s ease;
        }

        header {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .cards {
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .card p {
            font-size: 24px;
            color: #c0392b;
        }

        .logout-button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            text-align: left; /* Optional for alignment */
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margin */
        }

        .logout-button:hover {
            text-decoration: none; /* Optional hover effect */
        }
    </style>
    @yield('styles')
</head>
<body>
    <div>
        <nav class="navbar">
            <div class="navbar-brand">
                <a href="/">
                    <img src="{{ asset('images/wotg-logo.png') }}" alt="WOTG Logo" style="width: 3.8rem;">
                </a>
            </div>            
            <div class="hamburger" onclick="toggleDrawer()">
                <i class="fas fa-bars"></i>
            </div>
        </nav>               

        <aside class="sidebar" id="sidebar">
            <ul>
                <li><a href="/seekers">Seekers</a></li>
                <li>
                    <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>               

        <div class="main-content">
            <header>
                <h1>@yield('title', 'Dashboard')</h1>
            </header>
            <div class="cards">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function toggleDrawer() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
