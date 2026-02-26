<?php
// Include database configuration file
include("config.php");

// Initialize error variable
$error = "";

// Check if the request method is POST (form submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form inputs
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encrypt password using md5 (basic security)

    // Prepare SQL query to check user credentials
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    // If user found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Store user info in session
        $_SESSION['user_id']        = $user['id'];
        $_SESSION['role']           = $user['role'];
        $_SESSION['user_name']      = $user['name'];
        $_SESSION['user_created']   = $user['created_at'];
        $_SESSION['profile_picture'] = $user['profile_picture'];

        // Redirect based on user role
        switch ($user['role']) {
            case 'Admin':
                header("Location: index.php");
                break;
            case 'Doctor':
                header("Location: doctor_dashboard.php");
                break;
            case 'Receptionist':
                header("Location: staff_dashboard.php");
                break;
            default:
                header("Location: ../index.html");
                break;
        }

        exit(); // Stop script after redirect

    } else {
        // If credentials don't match
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Medinova</title>
    <link rel="stylesheet" href="../admin/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="hold-transition login-page bg-light">
    <div class="login-box">
        <div class="card shadow-lg">
            <div class="card-body login-card-body rounded-4">
                <div class="text-center mb-4">
                    <img src="../admin/assets/img/logo.png" alt="Medinova Logo" width="80">
                    <h3><b>PRMS</b> Login</h3>
                </div>

                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger text-center">
                        <?= $error; ?>
                    </div>
                <?php } ?>

                <form method="POST" action="">
                    <div class="input-group mb-3 align-items-center">
                        <div class="input-group-append">
                            <div class="input-group-text border border-0">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <input type="email" name="email" class="form-control rounded" placeholder="Email" required>
                    </div>
                    <div class="input-group mb-3 align-items-center">
                        <div class="input-group-append">
                            <div class="input-group-text border border-0">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <!-- <input type="password" name="password" class="form-control rounded" placeholder="Password" required> -->
                        <input type="password" id="pwd" name="password" class="form-control rounded" placeholder="Password" required>
                        <div class="input-group-append">
                            <span id="togglePwd" class="fa fa-fw fa-eye field-icon" style="cursor: pointer;position: absolute;right: 10px;top: 10px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                        <p class="mt-3 text-center">
                            Don't have an account? <a href="register.php">Register here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.getElementById("togglePwd").addEventListener("click", function() {
            let passwordField = document.getElementById("pwd");
            let icon = this;

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    </script>
</body>

</html>