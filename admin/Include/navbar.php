<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = strtolower($_SESSION['role']);

// Home URL based on role
switch ($role) {
  case 'admin':
    $home_url = "index.php";
    break;
  case 'doctor':
    $home_url = "doctor_dashboard.php";
    break;
  case 'receptionist':
    $home_url = "staff_dashboard.php";
    break;
  default:
    $home_url = "#"; // Fallback if role is unknown
}
?>

<i class="fa-solid fa-copy"></i>
<nav class="app-header navbar navbar-expand bg-body">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <?php if (in_array($role, ['admin', 'doctor', 'receptionist'])): ?>
        <li class="nav-item d-none d-md-block">
          <a href="<?= $home_url ?>" class="nav-link">Home</a>
        </li>
      <?php endif; ?>

      <li class="nav-item d-none d-md-block"><a href="../../../PRMS/contact.html" class="nav-link">Contact</a>
      </li>
    </ul>
    <!--end::Start Navbar Links-->
    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);">
          <i class="fa-solid fa-copy fs-5"
            title="Terms & Conditions"
            data-bs-toggle="modal"
            data-bs-target="#termsModal"
            style="cursor:pointer;"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen" title="Full Screen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" title="Exit Full Screen" style="display: none"></i>
        </a>
      </li>
      <!--begin::User Menu Dropdown-->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <img src="/PRMS/admin/uploads/<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default.png'; ?>"
            class="user-image rounded-circle shadow" alt="User Image" title="Profile Image" />

          <span class="d-none d-md-inline">
            <?= $_SESSION['role']; ?> Profile
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow">
          <li class="user-header text-bg-primary pt-4">
            <img src="/PRMS/admin/uploads/<?= isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default.png'; ?>"
              class="user-image rounded-circle shadow" alt="User Image" title="Profile Image" />
            <p>
              <?= $_SESSION['user_name']; ?>
              <br>
              <small>Member since <?= date("l, F j, Y", strtotime($_SESSION['user_created'])); ?></small>
            </p>
          </li>
          <li class="user-footer">
            <a href="../../../PRMS/admin/setting.php" class="btn btn-default border btn-flat"><i class="fas fa-cogs"></i> Profile Setting</a>
            <a href="../../../PRMS/admin/logout.php" class="btn btn-default border btn-flat float-end"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
          </li>
        </ul>
      </li>
      <!--end::User Menu Dropdown-->
    </ul>
    <!--end::End Navbar Links-->
  </div>
  <!--end::Container-->
</nav>

<!-- Terms & Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">📜 Terms & Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
        <p>
        <ol type="A">
          <li>You must not misuse the system or data provided.</li>
          <li>All your actions are logged and monitored.</li>
          <li>Do not attempt to breach the security.</li>
          <li>Unauthorized actions may lead to suspension.</li>
        </ol>
        <hr>
        <ol>
          <li><b>Keep records accurate:</b> Make sure all patient details are correct and clear to avoid mistakes.</li>
          <li><b>Update info regularly:</b> Add new findings or treatments as they happen to keep records current.</li>
          <li><b>Use standard formats:</b> Follow consistent templates to make documentation easier to understand and use.</li>
          <li><b>Work together as a team:</b> Share information with colleagues to ensure better patient care.</li>
          <li><b>Focus on patient needs:</b> Address individual concerns to provide personalized and compassionate care.</li>
        </ol>
        </p>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <span>By clicking "I Agree", you confirm acceptance of these conditions.</span>
        <div class="btns">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="agreeBtn">I Agree</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById("agreeBtn").addEventListener("click", function() {
    // Close the modal
    var modal = bootstrap.Modal.getInstance(document.getElementById('termsModal'));
    modal.hide();

    // Action after agreement
    alert("You have accepted the Terms & Conditions.");
    // You can also redirect or perform another action here
    // window.location.href = "dashboard.php";
  });
</script>