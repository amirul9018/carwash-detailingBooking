<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed; /* Set your wallpaper image path */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        .toggle-icon {
            position: absolute;
            left: 20px;
            top: 20px;
            cursor: pointer;
            z-index: 1000;
        }

        .toggle-icon div {
            width: 30px;
            height: 3px;
            background-color: #34495e;
            margin: 5px 0;
            transition: 0.3s;
        }

        .sidebar {
            width: 250px;
            background-color: #2d3e50;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }

        nav ul li a:hover, nav ul li a.active {
            background-color: #1abc9c;
            border-radius: 5px;
        }

        header {
            margin-left: 250px;
            padding: 20px;
            background-color: #34495e;
            color: #ffffff;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }

        main {
            margin-left: 250px;
            padding: 20px;
            max-width: calc(100% - 250px);
            background-color: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            transition: margin-left 0.3s ease;
            flex-grow: 1;
        }

        footer {
            margin-left: 250px;
            padding: 20px;
            background-color: #34495e;
            color: #ffffff;
            position: relative;
            bottom: 0;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            main, header, footer {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="toggle-icon" id="toggleBtn">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <nav class="sidebar">
        <ul>
            <li><a href="../ITP/dashboard.php" class="active">Home</a></li>
            <li><a href="../ITP/manage_user.php">Manage Users</a></li>
            <li><a href="../ITP/manage_bookings.php">Manage Bookings</a></li>
            <li><a href="../ITP/manage_product.php">Manage Products</a></li>
            <li><a href="../ITP/manage_order.php">Manage Orders</a></li>
            <li><a href="../ITP/manage_cust.php">Manage Customers</a></li>
            <li><a href="../ITP/settings.php">Settings</a></li>
            <li><a href="../ITP/logout.php">Logout</a></li>
        </ul>
    </nav>

    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <main>
        <section>
            <h2>Welcome, Admin!</h2>
            <p>Here are some quick stats:</p>
            <ul>
                <li>Total Users: <?php echo $totalUsers; ?></li>
                <li>Total Bookings: <?php echo $totalBookings; ?></li>
                <li>Pending Approvals: 5</li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.querySelector('.sidebar');
        const main = document.querySelector('main');
        const header = document.querySelector('header');
        const footer = document.querySelector('footer');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
            main.classList.toggle('collapsed');
            header.classList.toggle('collapsed');
            footer.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
