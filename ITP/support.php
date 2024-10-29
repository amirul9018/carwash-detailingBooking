<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a.active,
        nav ul li a:hover {
            background-color: #0056b3;
        }

        main {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        section {
            margin-bottom: 40px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #007bff;
        }

        .faq-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .faq-list li {
            margin-bottom: 20px;
        }

        .faq-list h3 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .faq-list p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Support</h1>
        <nav>
            <ul>
                <li><a href="../ITP/mainpage.php">Dashboard</a></li>
                <li><a href="../ITP/bookawash.php">Book a Wash</a></li>
                <li><a href="../ITP/shop.php">Online Shop</a></li>
                <li><a href="../ITP/support.php" class="active">Support</a></li>
                <li><a href="../ITP/customer_settings.php">Account Settings</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Frequently Asked Questions</h2>
            <ul class="faq-list">
                <li>
                    <h3>How do I book a car wash?</h3>
                    <p>To book a car wash, simply go to the "Book a Wash" page, select your desired service, choose a date and time, and confirm your booking.</p>
                </li>
                <li>
                    <h3>Can I cancel or reschedule my booking?</h3>
                    <p>Yes, you can cancel or reschedule your booking from the "My Bookings" page, provided it meets the cancellation policy criteria.</p>
                </li>
                <li>
                    <h3>What payment methods do you accept?</h3>
                    <p>We accept all major credit cards and online payment systems like PayPal.</p>
                </li>
            </ul>
        </section>

        <section>
            <h2>Submit a Support Request</h2>
            <form action="..//ITP/submit_support_request.php" method="post">
                <div class="input-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required aria-label="Subject of the support request">
                </div>
                <div class="input-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required aria-label="Message for the support team"></textarea>
                </div>
                <button type="submit" name="submit_request">Submit Request</button>
            </form>
        </section>

        <section>
            <h2>Contact Us</h2>
            <p>If you need immediate assistance, you can reach our support team at:</p>
            <p><strong>Email:</strong> support@carwash.com</p>
            <p><strong>Phone:</strong> +6 017 973 1634&nbsp;</p>
            <p><strong>Live Chat:</strong> Available 24/7</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
