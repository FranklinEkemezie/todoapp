const signup_form = select("form#signup-form");
const username = select("input#username", false, signup_form);
const email_address =select("input#email", false, signup_form);

const error_msg = select("main section#form-section .error-msg");

// Add event listener for the login form
signup_form.onsubmit = function(event) {
  let form_is_valid = validateSignupForm();

  hideErrorMsg(error_msg);

  if(!form_is_valid) {
    // Display error message
    showErrorMsg(error_msg, "Invalid arguments! Please try again");
  }

  return form_is_valid;
}

function validateSignupForm() {
  let username_is_valid = validateUsername(username.value);
  let email_is_valid = validateEmail(email_address.value);

  let username_info = select(".info", false, username.parentElement);
  let email_address_info = select(".info", false, email_address.parentElement);

  // Display info if available
  username_info.innerHTML = username_is_valid.errorMsg;
  email_address_info.innerHTML = email_is_valid.errorMsg;

  return username_is_valid.isValid && email_is_valid.isValid;
}

// Close error message on clicking the close button
error_msg.addEventListener("click", function() {
  error_msg.classList.add("hidden");
})