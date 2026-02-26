<?php
include '../admin/config.php';

// Check if session user_id is set
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
$user = $query->fetch_assoc();

// Handle cases where the user is not found
if (!$user) {
    die("User not found in the database.");
}

// Initialize an empty message variable
$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Profile Update
    if (isset($_POST['update_profile'])) {
        $new_name = htmlspecialchars(trim($_POST['name']));
        $new_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

        if (!empty($new_name) && !empty($new_email)) {
            $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $new_name, $new_email, $user_id);
            $stmt->execute();

            $_SESSION['user_name'] = $new_name;
            $_SESSION['user_email'] = $new_email;

            // Image upload
            if (!empty($_FILES['profile_picture']['name'])) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024;  // Max 5MB
                $image_name = time() . "_" . $_FILES['profile_picture']['name'];

                if (in_array($_FILES['profile_picture']['type'], $allowed_types) && $_FILES['profile_picture']['size'] <= $max_size) {
                    move_uploaded_file($_FILES['profile_picture']['tmp_name'], "../../PRMS/admin/uploads/" . $image_name);

                    $stmt = $conn->prepare("UPDATE users SET profile_picture=? WHERE id=?");
                    $stmt->bind_param("si", $image_name, $user_id);
                    $stmt->execute();

                    $_SESSION['profile_picture'] = $image_name;
                    $user['profile_picture'] = $image_name;
                } else {
                    $message = "<div class='alert alert-danger'>Invalid image file or file size exceeded!</div>";
                }
            }

            header("Location: setting.php?success=profile_updated");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Name and Email cannot be empty!</div>";
        }
    }

    // Password Update
    if (isset($_POST['update_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Fetch the current hashed password from the database
        $query = $conn->query("SELECT password FROM users WHERE id='$user_id'");
        $user = $query->fetch_assoc();
        $hashed_password = $user['password'];

        // Verify if the old password matches
        if (md5($old_password) === $hashed_password) {
            // Check if the new password and confirm password match
            if ($new_password === $confirm_password) {
                // Hash the new password using md5
                $new_hashed_password = md5($new_password);

                // Update the password in the database
                $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param("si", $new_hashed_password, $user_id);
                $stmt->execute();

                // Redirect with success message
                header("Location: setting.php?success=password_updated");
                exit();
            } else {
                $message = "<div class='alert alert-danger'>New passwords do not match!</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Old password is incorrect!</div>";
        }
    }
}

// After updating the profile or password, display a success message
if (isset($_GET['success'])) {
    if ($_GET['success'] == "profile_updated") {
        echo "<script>alert('Profile updated successfully!'); window.location.href='setting.php';</script>";
    } elseif ($_GET['success'] == "password_updated") {
        echo "<script>alert('Password updated successfully!'); window.location.href='setting.php';</script>";
    }
}

include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');
?>


<main class="app-main">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profile Settings</h3>
            </div>
            <div class="card-body">
                <?= $message; ?>

                <!-- Profile Update Form -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3 form-group">
                        <label for="name">Profile Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? ''); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="email">Personal Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" id="profileInput" onchange="previewImage(event)">
                        <img id="profilePreview"
                            src="/PRMS/admin/uploads/<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'default.png'; ?>"
                            alt="Profile Image" title="Current Profile Image" width="100" class="m-2 ms-0 img-fluid rounded-3">
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>

                <!-- Password Update Form -->
                <hr>
                <h4>Change Password</h4>
                <form method="POST">
                    <div class="mb-3 form-group">
                        <label for="old_password">Old Password</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" name="update_password" class="btn btn-warning">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('include/footer.php'); ?>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profilePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>