/**
 * Selects an element in the DOM 
 * @param {string} selectors The CSS selector
 * @param {boolean} all Whether to select all or just one
 * @param {Document} node The parent node of the element to select
 * 
 * @return {Element|Array|null} Returns a DOM element with the given selector
 */
function select(selectors, all=false, node=document) {
  let elements = [...node.querySelectorAll(selectors) || []];

  if(elements.length <= 0) throw new DOMException(`No element with ${selectors} found`);

  return all ? elements : elements[0];
}

/**
 * Add an event listener to the element
 * 
 * @param {Element} element The element to add the event listener
 * @param {string} event_type The type of event to listen to
 * @param {Function} handler The event handler
 * @param {object} options Other options
 */
function on(element, event_type, handler, options=undefined) {
  element.addEventListener(event_type, handler, options);
}

/**
 * Sanitizes a text 
 * @param {string} input The input text to sanitize
 * 
 * @return {string} Returns the sanitized text
 */
function sanitizeText(input) {
  let sanitizedInput = input.trim();

  // Remove HTML Tags
  sanitizedInput = sanitizedInput.replace(/<[^>]*/gm, '');

  // Remove potentially harmful characters
  sanitizedInput = sanitizedInput.replace(/[&<>"'`=\/]/g, '');

  return sanitizedInput;
}

/**
 * Converts the first character of a text to uppercase
 * 
 * @param {string} text Specifies the string to convert
 * 
 * @returns Returns the converted string
 */
function uppercaseFirst(text) {
  return text[0].toUpperCase() + text.substring(1).toLowerCase();
}

/**
 * Validates an email address given as input
 * @param {string} email The email address to validate
 * 
 * @return {{isValid, errorMsg}} Returns an object {isValid, errorMsg} 
 * specifying whether the email is valid and the error message
 */
function validateEmail(email) {
  email = sanitizeText(email);

  let isEmpty = email.length <= 0;
  if(isEmpty) {
    return ({
      isValid: false,
      errorMsg: "Email cannot be empty"
    });
  }

  let isValid = /[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/.test(email);
  if(!isValid) {
    return ({
      isValid: false,
      errorMsg: "Email address is not valid"
    });
  }

  return ({
    isValid: true,
    errorMsg: ""
  });
}

/**
 * @param {string} username The username input to validate
 * 
 * @return {{isValid, errorMsg}} Returns an object {isValid, errorMsg} 
 * specifying whether the username is valid and the error message
 */
function validateUsername(username) {
  username = sanitizeText(username);

  let isEmpty = username.length <= 0;
  if(isEmpty) {
    return ({
      isValid: false,
      errorMsg: "Username cannot be empty"
    });
  }

  if(username.length <= 3) {
    return ({
      isValid: false,
      errorMsg: "Username is too short"
    })
  }

  if(username.length >= 16) {
    return ({
      isValid: false,
      errorMsg: "Username is too long"
    })
  }

  let hasWhitespace = /[\s\p{Z}]/u.test(username);
  if(hasWhitespace) {
    return ({
      isValid: false,
      errorMsg: "Whitespaces are not allowed"
    })
  }

  let isValid = /^[a-zA-Z0-9_-]{3,16}$/.test(username);
  if(!isValid) {
    return ({
      isValid: false,
      errorMsg: "Username is invalid"
    })
  }

  return ({
    isValid: true,
    errorMsg: ""
  });
}

/**
 * @param {string} password The password input to validate
 * 
 * @return {{isValid, errorMsg}} 
 */
function validatePassword(password) {
  password = sanitizeText(password);

  // Check is empty
  let isEmpty = password.length <= 0;
  if(isEmpty) {
    return ({
      isValid: false,
      errorMsg: "Password cannot be empty"
    });
  }

  // Check for minimum length (at least 8 characters)
  if(password.length < 8) {
    return ({
      isValid: false,
      errorMsg: "Password is too short"
    })
  }

  // Check for at least one uppercase letter
  if(!/[A-Z]/.test(password)) {
    return ({
      isValid: false,
      errorMsg: "Password must contain at least one uppercase letter"
    })
  }

  // Check for at least one lowercase letter
  if(!/[a-z]/.test(password)) {
    return ({
      isValid: false,
      errorMsg: "Password must contain at least one lowercase letter"
    })
  }

  // Check for at least one number
  if(!/\d/.test(password)) {
    return ({
      isValid: false,
      errorMsg: "Password must contain at least one number"
    })
  }

  // Check for at least one special character
  if(!/[^a-zA-Z0-9]/.test(password)) {
    return ({
      isValid: false,
      errorMsg: "Password must have at least one special character"
    })
  }

  return ({
    isValid: true,
    errorMsg: ""
  });
}

/**
 * Displays a notification message
 * 
 * @param {string} content The content of the notification message
 * @param {string} type The notification message type. One of BS alert types - warning, success, danger
 * 
 * @return {Element} Returns the toast element that display the notification message
 */
function displayNotificationMessage(content, type) {
  let notification_types = {
    "success": {
      "bs-class": "bg-success",
      "title": "Success"
    },
    "danger": {
      "bs-class": "bg-danger",
      "title": "Error",
    },
    "warning": {
      "bs-class": "bg-warning",
      "title": "Warning"
    }
  }

  let alert_toast_container = document.createElement("div");
  alert_toast_container.classList.add("position-fixed", "top-0", "start-50", "translate-middle-x", "m-3");

  let alert_toast_content = `
  <div class="toast border-0 rounded-3 align-items-center ${notification_types[type]["bs-class"]}">
    <div class="toast-header">
      <i class="fa fa-list me-2"></i>
      <strong class="me-auto">ToDoApp</strong>
      <small class="text-muted">${notification_types[type].title}</small>
      <button class="btn btn-close fa fa-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>

    <!-- Toast body -->
    <div class="toast-body text-light">${content}</div>
  </div>
  `;

  alert_toast_container.innerHTML = alert_toast_content;

  document.body.append(alert_toast_container);

  let toast_el = select(".toast", false, alert_toast_container);
  let toast = new bootstrap.Toast(toast_el);

  on(toast_el, "hidden.bs.toast", () => alert_toast_container.remove());

  toast.show();

  return toast_el;
}

/**
 * Copies a text to the clipboard
 * 
 * @param {string} text The text to be copied
 * 
 */
function copyText(text) {
  if(typeof copy !== "undefined") {
    copy(text);
  } else if (typeof navigator !== "undefined" && typeof navigator.clipboard !== "undefined") {
    navigator.clipboard.writeText(text);
  } 
  else {
    let copy_input_el = document.createElement("input");
    copy_input_el.id = copy_input_el.name = "copy-input-el";
    copy_input_el.value = text;
  
    document.body.append(copy_input_el);
  
    copy_input_el = select("input#copy-input-el"); // select the input
  
    copy_input_el.select();
    copy_input_el.setSelectionRange(0, 99999); /** For mobile devices */
  
    // Copy the text inside the text input element
    document.execCommand("copy");
  
    // Remove the text input element
    copy_input_el.remove();  
  }

  // Display the notification
  displayNotificationMessage("Text copied successfully", "danger");
}

/**
 * Initiates the loader component
 * 
 * @param {string} loader_text The text to display while loading. Default is Loading
 * 
 * @returns {Object} Returns the Loader object. This object has callable methods: show() and hide
 * to show and hide the loader respectively.
 */
function initLoader(loader_text="Loading") {
  let loader = new Object();

  let timestamp = Date.now().toString();
  let modal = document.createElement("div");
  let modal_id = modal.id = "loader-backdrop-" + timestamp;

  modal.classList.add("modal", "fade");
  modal.setAttribute("data-bs-backdrop", "static");
  modal.setAttribute("data-bs-keyboard", "false");
  modal.innerHTML = `
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content bg-transparent text-light text-center border-0">
      <div class="modal-body">
        <div class="d-flex align-items-center justify-content-center">
          <i class="fa-1 spinner-grow spinner-grow"></i>
          <span class="ms-2 fs-4">${loader_text}</span>
        </div>
      </div>
    </div>
  </div>
  `;

  let toggle_modal_btn = document.createElement("button");
  toggle_modal_btn.id = "loader-toggle-btn-" + timestamp;
  toggle_modal_btn.setAttribute("data-bs-toggle", "modal");
  toggle_modal_btn.setAttribute("data-bs-target", "#" + modal_id);
  toggle_modal_btn.style.display = "none"; // hide the toggle modal button from view

  // Assign the variables to the loader
  loader.loader_modal = modal;
  loader.toggle_modal_btn = toggle_modal_btn;
  loader.loader_text = loader_text;
  loader.displayed = false;

  /**
   * Shows the loader
   * 
   * Fails if the loader is already shown
   */
  loader.show = () => {
    if(loader.displayed) {
      throw new Error("Loader is already shown!");
    }

    select("main").append(
      loader.toggle_modal_btn,
      loader.loader_modal
    );


    loader.toggle_modal_btn.click();
    loader.displayed = true; // change state
  }

  /**
   * Hides the loader
   * 
   * Fails if the loader is not shown
   */
  loader.hide = () => {
    if(loader.displayed === false) throw new Error("Loader not shown");

    loader.toggle_modal_btn.click(); // click the toggle modal button again to close

    [loader.loader_modal, loader.toggle_modal_btn,].forEach(el => el.remove());
    loader.displayed = false; // change state
  }

  return loader;
}