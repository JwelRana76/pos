<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 600px;
        }
        h1 {
            font-size: 4rem;
            color: #e74c3c;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>Oops! The page you're looking for can't be found.</p>
        <a href="{{ url('/dashboard') }}">Go Back Home</a>
    </div>
</body>
</html>
