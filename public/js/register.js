/**
 * register.js — Registration page interactions
 * Handles: user type toggle, loading indicator, alert dismiss
 */

// Dismiss the alert banner
function dismissAlert() {
  const alert = document.getElementById("form-alert");
  if (!alert) return;
  alert.style.transition = "opacity 0.3s, transform 0.3s";
  alert.style.opacity = "0";
  alert.style.transform = "translateY(-8px)";
  setTimeout(() => alert.remove(), 320);
}

// Switch between private / company form fields
function switchType(type) {
  const privateFields = document.getElementById("private-fields");
  const companyFields = document.getElementById("company-fields");
  const btnPrivate = document.getElementById("btn-private");
  const btnCompany = document.getElementById("btn-company");
  const userTypeInput = document.getElementById("user_type");

  if (type === "private") {
    privateFields.style.display = "block";
    companyFields.style.display = "none";
    btnPrivate.classList.add("active");
    btnCompany.classList.remove("active");
    btnPrivate.setAttribute("aria-pressed", "true");
    btnCompany.setAttribute("aria-pressed", "false");
    userTypeInput.value = "private";
  } else {
    privateFields.style.display = "none";
    companyFields.style.display = "block";
    btnCompany.classList.add("active");
    btnPrivate.classList.remove("active");
    btnCompany.setAttribute("aria-pressed", "true");
    btnPrivate.setAttribute("aria-pressed", "false");
    userTypeInput.value = "company";
  }
}

// Loading indicator on form submit
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("register-form");
  const submitBtn = document.getElementById("submit-btn");
  const btnText = document.getElementById("btn-text");
  const spinner = document.getElementById("btn-spinner");

  if (form) {
    form.addEventListener("submit", function () {
      submitBtn.disabled = true;
      btnText.textContent = "Lähetetään...";
      spinner.style.display = "inline-block";
    });
  }

  // Auto-dismiss alert after 6 seconds (matches the CSS progress bar)
  const alert = document.getElementById("form-alert");
  if (alert) {
    alert.scrollIntoView({ behavior: "smooth", block: "nearest" });
    setTimeout(dismissAlert, 6000);
  }
});
