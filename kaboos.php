<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background-color: #000;
            color: #00FF00;
            font-family: 'Courier New', Courier, monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #111;
            padding: 20px;
            border: 1px solid #00FF00;
        }
        label, input {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"] {
            background-color: #000;
            color: #00FF00;
            border: 1px solid #00FF00;
            padding: 5px;
        }
        input[type="submit"] {
            background-color: #000;
            color: #00FF00;
            border: 1px solid #00FF00;
            padding: 5px 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #00FF00;
            color: #000;
        }
        .message {
            margin-bottom: 10px;
            color: #FF0000;
        }
    </style>
</head>
<body>
    <form method="post">
        <h1>Login</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" name="login" value="Login">
        
    </form>
</body>
</html>