<?php
session_start(); // Start the session

// Database connection
$host = 'localhost';
$dbname = 'my_database';
$dbusername = 'root';
$dbpassword = '';
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$username_error = $password_error = "";
$login_error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email)) {
        $username_error = "Username is required!";
    }

    if (empty($password)) {
        $password_error = "Password is required!";
    }

    // If validation passes, check credentials in the database
    if (empty($username_error) && empty($password_error)) {
        // Prepare and execute the query to fetch the user by email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            // Fetch the user data
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Successful login: set session variable and redirect to home page
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: successfulReg.html"); // Redirect to home page
                exit();
            } else {
                // Invalid password
                $password_error = "Incorrect password.";
            }
        } else {
            // User does not exist
            $username_error = "No account found with that username.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="page1_general_style.css" />
    <style>
        .btn-container a {
            color: #8099E2;
            font-size: 1rem;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
            color: #1F3B8B;
            font-size: 1rem;
        }

        .register-link a {
            color: #8099E2;
            text-decoration: underline;
            font-size: 1.2rem;
        }

        img {
            height: 150px;
            width: 150px;
            margin-top: 0.5rem;
            margin-bottom: 2rem;
        }

        h2 {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            text-align: center;
            color: #1F3B8B;
        }

        /* Mobile Styling for 0px to 480px */
        @media (max-width: 480px) {
            .form-container {
                border-radius: 0;
                background: rgb(255, 255, 255);
            background: linear-gradient(180deg, rgba(255,255,255,1) 28%, rgba(30,58,138,1) 95%);
            }

            h1 {
                font-size: 1.2rem; /* Adjust heading size for mobile */
            }

            .subtitle {
                font-size: 1rem;
            }

            img {
                width: 100px; /* Adjust image size */
                height: 100px;
                margin-top: 1rem;
                margin-bottom: 1.5rem;
            }

            .register-link, .register-link a {
                font-size: 0.8rem;
            }

            .btn-container a {
                font-size: 0.7rem;
            }

            .btn-container {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <section class="form-container">
        <div>
            <h1>Welcome to <i>E-BRGY</i></h1>
            <p class="subtitle">Barangay 834 Zone 91</p>
            <img src="./assets/brgy_logo.png" alt="barangay_logo" width="100px">
        </div>   
        <div>
            <h2>Login</h2>

            <form action="login.php" method="POST">
                 <!-- Username -->
        <div class="input-item">
            <label for="username">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              class="input-field"
              placeholder="Enter your username"
              required
            />
          </div>
  
          <!-- Password -->
          <div class="input-item">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              class="input-field"
              placeholder="Enter your password"
              required
            />
          </div>

                <!-- Error message before button -->
                <?php if ($username_error || $password_error || $login_error): ?>
                    <div class="error-message"><?php echo $login_error ?: ($username_error ?: $password_error); ?></div>
                <?php endif; ?>

                <div class="btn-container">
                    <button type="submit" class="btn">Login</button>
                    <a href="reset_password_email.html">Forgot password</a>
                </div>
            </form>
            <div class="register-link">
                <p>Don't have an account?</p>
                <a href="create_account.php">Register</a>
            </div>
        </div>
    </section>                                                                 
</body>
</html>
