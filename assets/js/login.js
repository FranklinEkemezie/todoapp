const login_form = select("form#login-form");
const email_address = select("input#email", false, login_form);
const password = select("input#password", false, login_form);

const error_msg = select("main section#form-section .error-msg");

// Add event listener for the login form
login_form.onsubmit = function(event) {
  let form_is_valid = validateLoginForm();

  hideErrorMsg(error_msg);
  if(!form_is_valid) {
    // Display error message
    showErrorMsg(error_msg, "Invalid arguments! Please try again");
  }

  return form_is_valid;
}


function validateLoginForm() {
  let email_is_valid = sanitizeText(email_address.value).length > 0;
  let password_is_valid = sanitizeText(password.value) .length > 0;

  let email_address_info = select(".info", false, email_address.parentElement);
  let password_info = select(".info", false, password.parentElement);

  // Display info if available
  email_address_info.innerHTML = !email_is_valid ? "Email cannot be empty" : "";
  password_info.innerHTML = !password_is_valid ? "Password cannot be empty" : "";

  return email_is_valid && password_is_valid;
}

// Close error message on clicking the close button
error_msg.addEventListener("click", function () {
  error_msg.classList.add("hidden");
});
