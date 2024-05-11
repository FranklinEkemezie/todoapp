const signup_form = select("form#signup-form");
const password = select("input#password", false, signup_form);
const confirm_password = select("input#confirm-password", false, signup_form);

const error_msg = select("main section#form-section .error-msg");

// Add event listener for the login form
signup_form.onsubmit = function () {
  let form_is_valid = validateSignupForm();

  hideErrorMsg(error_msg);
  if(!form_is_valid) {
    // Display error message
    showErrorMsg(error_msg, "Invalid arguments! Please try again")
  }

  console.log(form_is_valid)

  return form_is_valid;
}

function validateSignupForm() {
  let password_is_valid = validatePassword(password.value);
  let confirm_password_is_valid = sanitizeText(confirm_password.value).length > 0 &&
    password.value === confirm_password.value;

  let password_info = select(".info", false, password.parentElement);
  let confirm_password_info = select(".info", false, confirm_password.parentElement);

  // Display info if available
  password_info.innerHTML = password_is_valid.errorMsg;
  confirm_password_info.innerHTML = !confirm_password_is_valid ? "Passwords do not match" : "";

  console.log(password_is_valid, confirm_password_is_valid);

  return password_is_valid.isValid && confirm_password_is_valid;
}

// Close error message on clicking the close button
error_msg.addEventListener("click", function () {
  error_msg.classList.add("hidden");
});

// Toggle password
const toggle_password = select("#toggle-password");
let password_shown = false;
on(toggle_password, "click", function(event) {
  event.preventDefault();

  let [show, hide] = [
    {icon: "fa-eye", label: "Hide Password", type: "password"},
    {icon: "fa-eye-slash", label: "Show Password", type: "text"}
  ]
  let toggle_password_icon = select(".toggle-password-icon", false, toggle_password);
  let toggle_password_label = select("label[for='toggle-password']", false, toggle_password.parentElement);

  if(password_shown) {
    // Hide password
    toggle_password_icon.classList.replace(show.icon, hide.icon);
    toggle_password_label.innerHTML = hide.label;
    password.type = confirm_password.type = show.type;

    password_shown = false;
  } else {
    // Show password
    toggle_password_icon.classList.replace(hide.icon, show.icon);
    toggle_password_label.innerHTML = show.label;
    password.type = confirm_password.type = hide.type;

    password.type = "text";
    password_shown = true;
  }

});
