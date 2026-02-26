// Search Function
document.getElementById("searchPatient").addEventListener("keyup", function () {
  let value = this.value.toLowerCase();
  let rows = document.querySelectorAll("#patientTable tbody tr");

  rows.forEach((row) => {
    let name = row.cells[0].textContent.toLowerCase();
    row.style.display = name.includes(value) ? "" : "none";
  });
});
// Active Or Inactive Badge Color
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".status-badge").forEach(function (badge) {
    if (badge.textContent.trim() === "Active") {
      badge.classList.add("bg-success");
    } else {
      badge.classList.add("bg-danger");
    }
  });
});

// Open Edit Modal & Fill Data
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".editBtn").forEach((button) => {
    button.addEventListener("click", function () {
      document.getElementById("edit_id").value = this.dataset.id;
      document.getElementById("edit_name").value = this.dataset.name;
      document.getElementById("edit_age").value = this.dataset.age;
      document.getElementById("edit_medical_history").value =
        this.dataset.medical_history;

      // ✅ Gender Auto-Select Fix
      let gender = this.dataset.gender;
      let genderDropdown = document.getElementById("edit_gender");
      for (let option of genderDropdown.options) {
        if (option.value === gender) {
          option.selected = true;
          break;
        }
      }

      // ✅ Status Auto-Select Fix
      let status = this.dataset.status;
      let statusDropdown = document.getElementById("edit_status");
      for (let option of statusDropdown.options) {
        if (option.value === status) {
          option.selected = true;
          break;
        }
      }

      // ✅ Assigned Doctor Auto-Select Fix
      let assignedDoctor = this.dataset.assigned_doctor;
      let doctorDropdown = document.getElementById("edit_assigned_doctor");
      for (let option of doctorDropdown.options) {
        if (option.value === assignedDoctor) {
          option.selected = true;
          break;
        }
      }

      // 🎯 Show Bootstrap Modal
      let editModal = new bootstrap.Modal(document.getElementById("editModal"));
      editModal.show();
    });
  });
});
