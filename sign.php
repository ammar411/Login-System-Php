<?php
include 'connect.php'; // Ensure this file correctly connects to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure correct input field name from the form
    $username = isset($_POST['name']) ? $_POST['name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    // Check if fields are empty
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        die("All fields are required.");
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Prevent SQL injection
    $username = mysqli_real_escape_string($con, $username);

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists
    $sql = "SELECT * FROM registration WHERE username='$username'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            echo "User Already Exists";
        } else {
            // Insert new user with hashed password
            $sql = "INSERT INTO registration (username, password) VALUES ('$username', '$hashedPassword')";
            $result = mysqli_query($con, $sql);
            if ($result) {
                echo "Sign Up Successful";
            } else {
                die("Error: " . mysqli_error($con));
            }
        }
    } else {
        die("Error checking existing user: " . mysqli_error($con));
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #4facfe, #00f2fe);
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        .logo {
            width: 70px;
            margin-bottom: 15px;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .input-group {
            position: relative;
            margin: 15px 0;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 40px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .error.show {
            opacity: 1;
        }

        button {
            background: #4facfe;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background 0.3s ease-in-out;
        }

        button:hover {
            background: #00c6ff;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordError = document.getElementById('passwordError');
            const confirmPasswordError = document.getElementById('confirmPasswordError');

            if (password !== confirmPassword) {
                confirmPasswordError.textContent = "Passwords do not match.";
                confirmPasswordError.classList.add('show');
                return false;
            } else {
                confirmPasswordError.textContent = "";
                confirmPasswordError.classList.remove('show');
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <img src="https://via.placeholder.com/70" alt="Logo" class="logo">
        <h2>Create Account</h2>
        <form id="myForm" action="sign.php" method="post" onsubmit="return validateForm()">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" id="name" name="name" placeholder="Full Name" required>
                <div id="nameError" class="error"></div>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <div id="passwordError" class="error"></div>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <div id="confirmPasswordError" class="error"></div>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <?php
        if (isset($_GET['message'])) {
            echo '<div class="success">' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>
    </div>
</body>
</html>