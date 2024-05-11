const form = select("form");
const inputs = select(".form-field input", true, form);

inputs.forEach(input => {
  on(input, "input", function() {
    (select(".info", false, input.parentElement)).innerHTML = "";
  })
});


/**
 * Display the error message
 * 
 * @param {Element} error_msg_el The error message element
 * @param {string} error_msg The error message to display
 */
function showErrorMsg(error_msg_el, error_msg) {
  error_msg_el.classList.remove("hidden");
  select(".msg", false, error_msg_el).innerHTML = error_msg;
}

/**
 * Hides the error message
 * 
 * @param {Element} error_msg_el The error message element
 */
function hideErrorMsg(error_msg_el) {
  error_msg_el.classList.add("hidden");
}