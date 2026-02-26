<?php
$page_title = "Add New User";
include('config.php');
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// ==========================
// ADD USER LOGIC
// ==========================
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Normal hash for password
    $role = $_POST['role'];

    // Default profile image
    $profile_picture = "default.jpg";

    // Agar user ne profile image upload ki hai
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $profile_picture = time() . "_" . basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $profile_picture;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("jpg", "jpeg", "png");

        // Valid image extension check
        if (in_array($imageFileType, $valid_extensions)) {
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        } else {
            echo "<script>alert('Error: Only JPG, JPEG, PNG files allowed!');</script>";
        }
    }

    // Insert query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, profile_picture) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $role, $profile_picture);

    if ($stmt->execute()) {
        echo "<script>alert('User added successfully!'); window.location='add_users.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// ==========================
// DELETE USER LOGIC
// ==========================
if (isset($_POST['delete_user'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully!'); window.location='add_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
}

// ==========================
// UPDATE USER LOGIC
// ==========================
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Agar profile pic update ho rahi hai
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = "uploads/" . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);

        // Query with profile pic
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=?, profile_picture=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $role, $profile_picture, $id);
    } else {
        // Query without profile pic
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location='add_users.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// ==========================
// FETCH ALL USERS
// ==========================
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add New User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add User</h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label class="mb-1">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Assign Role</label>
                            <select name="role" class="form-control" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="doctor">Doctor</option>
                                <option value="receptionist">Receptionist</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" required>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-danger">Add User</button>
                    </form>
                </div>
            </div>

            <!-- Users List -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Register Users</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $users->fetch_assoc()) : ?>
                                <tr>
                                    <!-- Profile Picture -->
                                    <td>
                                        <img src="uploads/<?= $row['profile_picture']; ?>" width="50" height="50" class="rounded-circle">
                                    </td>

                                    <!-- User Name -->
                                    <td><?= $row['name']; ?></td>

                                    <!-- User Email -->
                                    <td><?= $row['email']; ?></td>

                                    <!-- User Role -->
                                    <td><?= ucfirst($row['role']); ?></td>

                                    <!-- Action Buttons -->
                                    <td>
                                        <!-- Edit Button: fills modal with user data -->
                                        <button class="btn btn-warning btn-sm editUserBtn"
                                            data-id="<?= $row['id']; ?>"
                                            data-name="<?= $row['name']; ?>"
                                            data-email="<?= $row['email']; ?>"
                                            data-role="<?= $row['role']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_user" value="1">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".editUserBtn").forEach(function(button) {
            button.addEventListener("click", function() {
                let id = this.getAttribute("data-id");
                let name = this.getAttribute("data-name");
                let email = this.getAttribute("data-email");
                let profile = this.getAttribute("data-role");

                document.getElementById("editUserId").value = id;
                document.getElementById("editUserName").value = name;
                document.getElementById("editUserEmail").value = email;

                let editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
                editUserModal.show();
            });
        });
    });
</script>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="form-group mb-2">
                        <label>Name</label>
                        <input type="text" name="name" id="editUserName" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Email</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Role</label>
                        <select name="role" id="editUserRole" class="form-control" required>
                            <option value="doctor">Doctor</option>
                            <option value="receptionist">Receptionist</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label>Profile Picture</label>
                        <input type="file" name="profile_picture" id="editprofile_picture" class="form-control">
                    </div>
                    <button type="submit" name="update_user" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('Include/footer.php'); ?>