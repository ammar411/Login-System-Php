<?php
session_start(); // Start the session to store messages
include 'connect.php'; // Ensure this file correctly connects to your database

class LoginValidator {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function validate($username, $password) {
        // Check if fields are empty
        if (empty($username) || empty($password)) {
            return "Both fields are required.";
        }

        // Prevent SQL injection
        $username = mysqli_real_escape_string($this->con, $username);

        // Fetch user data from the database
        $sql = "SELECT * FROM registration WHERE username='$username'";
        $result = mysqli_query($this->con, $sql);

        if ($result) {
            $num = mysqli_num_rows($result);
            if ($num > 0) {
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['password'];

                // Verify the password
                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['logged_in'] = true; // Set a session variable for logged-in state
                    return "Login Successful!";
                } else {
                    return "Invalid username or password.";
                }
            } else {
                return "Invalid username or password.";
            }
        } else {
            return "Database Error: " . mysqli_error($this->con);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Correcting the input field name
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate login
    $validator = new LoginValidator($con);
    $message = $validator->validate($username, $password);

    // Store the message in session
    $_SESSION['message'] = $message;
    header("Location: login.php"); // Redirect back to the login form
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .input-group {
            margin: 10px 0;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background: #4facfe;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background: #00c6ff;
        }

        .message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form id="loginForm" action="login.php" method="post">
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit">Login</button>
        </form>
        <?php
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $class = (strpos($message, 'Successful') !== false) ? 'success' : 'message';
            echo '<div class="' . $class . '">' . htmlspecialchars($message) . '</div>';
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>
    </div>
</body>
</html>