<?php
// Include database configuration file
include("config.php");

// Initialize message variable
$message = "";

// Check if the request method is POST (form submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form inputs
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = md5($_POST['password']); // Hash password using MD5
    $role     = $_POST['role'];

    // Check if the email already exists in the database
    $check_query  = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Email already registered
        $message = "Email already exists!";
    } else {
        // Insert new user into database
        $insert_query = "INSERT INTO users (name, email, password, role) 
                         VALUES ('$name', '$email', '$password', '$role')";

        if ($conn->query($insert_query) === TRUE) {
            // Redirect to login after successful registration
            header("Location: login.php");
            exit();
        } else {
            $message = "Registration failed! Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | PRMS</title>
    <link rel="stylesheet" href="../admin/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="hold-transition login-page bg-light">
    <div class="login-box">
        <div class="card shadow-lg">
            <div class="card-body login-card-body">
                <div class="text-center mb-4">
                    <img src="../admin/assets/img/logo.png" alt="PRMS Logo" width="80">
                    <h3><b>PRMS</b> Registration</h3>
                </div>

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-danger text-center">
                        <?= $message; ?>
                    </div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control rounded" placeholder="Full Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control rounded" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control rounded" placeholder="Password" required>
                    </div>
                    <label for="role" class="mb-2">Select Role:</label>
                    <div class="mb-3">
                        <select name="role" id="role" class="form-control rounded" required>
                            <option value="" disabled selected>Select Account</option>
                            <option value="doctor">Doctor</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Register</button>
                </form>
                <p class="mt-3 text-center">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>