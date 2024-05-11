const settings_tab_content_container = select("main #main-content #settings-tab-content-container");
const setting_options_link = select(
  ".settings-options a.list-group-item[href*='tab']",
  true
);

// Display the right tab and setting tab content
on(window, "load", function() {
  let tab = window.location.search.split("=")[1] || "profile";

  // Display loader
  settings_tab_content_container.innerHTML = `
  <div class="position-absolute top-50 start-50">
    <div class="d-flex align-items-center justify-content-center">
      <i class="spinner-grow me-2"></i> Loading...
    </div>
  </div>`;
  // Change active link
  setting_options_link.forEach(el => {
    el.classList.remove("active");
  });
  (setting_options_link.find(el => el.href.split("=")[1] === tab) ||
  setting_options_link.find(el => el.href.split("=")[1] === "profile"))
    .classList.add("active");
  
  
  // Create a promise to handle asnyc
  (new Promise(
    async function(resolve, reject) {
      let tab_content = await loadSettingTabContent(tab);

      if(tab_content !== null) {
        resolve(tab_content);
      } else {
        reject("Something went wrong!");
      }
    }
  ))
  .then(tab_content_html => {
    setTimeout(() => {
      settings_tab_content_container.innerHTML = tab_content_html
    }, .4 * 1000);
  });
});

// Prevent the page from reloading automatically
// when a new setting option is clicked
setting_options_link.forEach(option_link => {
  on(option_link, "click", async function(event) {
    event.preventDefault();

    let option_link_el = event.target;
    let tab = option_link_el.href.split("=")[1];

    window.history.replaceState(null, null, option_link_el.href); // change the browser URL
    // Display loader
    settings_tab_content_container.innerHTML = `
    <div class="position-absolute top-50 start-50">
      <div class="d-flex align-items-center justify-content-center">
        <i class="spinner-grow me-2"></i> Loading...
      </div>
    </div>`;
    // Change active link
    setting_options_link.forEach(el => {
      el.classList.remove("active");
    });
    setting_options_link.find(el => el.href.split("=")[1] === tab)
      .classList.add("active");
    
    // Create a promise to handle asnyc
    (new Promise(
      async function(resolve, reject) {
        let tab_content = loadSettingTabContent(tab);

        if(tab_content !== null) {
          resolve(tab_content);
        } else {
          reject("Something went wrong!");
        }
      }
    ))
    .then(tab_content_html => {
      setTimeout(() => {
        settings_tab_content_container.innerHTML = tab_content_html
      }, .4 * 1000);
    })
  });
});

/**
 * Fetches the content of a setting tab
 * 
 * @param {string} tab The name of the tab to fetch
 * 
 * @return {Promise<string>} The content of the settings tab as HTML
 */
async function loadSettingTabContent(tab) {
  // Fetch
  return fetch("./settings/" + tab)
    .then(response => {
      if(!response.ok) {
        console.log("Something went wrong!");
      }
      if(response.status === 404) {
        console.log("Setting tab not found");
        window.history.replaceState(null, null, "?tab=profile");
        return loadSettingTabContent("profile");
      }
      
      return response.text();
    })
    .catch(error => {
      console.error("An error occurred: " + error);
    });
}

/**
 * 
 * @param {HTMLButtonElement} el The button element that calls the function
 */
function showEditProfile(el) {
  let edit_profile_btn = el;
  let profile_detail_display_el = select("input", false, edit_profile_btn.parentElement);
  let profile_detail_display_label = select("label", false, edit_profile_btn.parentElement);

  let title = profile_detail_display_label.innerHTML.toLowerCase();
  let Title = uppercaseFirst(title);

  const edit_profile_modal = document.createElement("div");
  edit_profile_modal.id = "edit-profile-modal";
  edit_profile_modal.classList.add("modal");
  edit_profile_modal.innerHTML = `
    <div class="modal-dialog modal-dialog-sm modal-dialog-sm modal-dialog-centered" style="width: 400px;">
      <div class="modal-content">
        <!-- Form section -->
        <form method="POST" action="javacript:void(null)" onsubmit="updateProfile(this, '${profile_detail_display_el.name}', '${Title}')" id="edit-profile-form" name="edit-profile-form">
          <div class="modal-header">
            <h6 class="modal-title fs-5 fw-bold">📝 Edit Your Profile</h6>
          </div>

          <div class="modal-body">
            <div class="edit-${title}-input-container">
              <label for="${title}-input">${Title}</label></br>
              <input type="${profile_detail_display_el.type}" name="${profile_detail_display_el.name}" id="${title}-input" value="${profile_detail_display_el.value}" class="px-1 form-control" />
            </div>
          </div>

          <div class="modal-footer align-items-center justify-content-between">
            <button type="submit" class="btn btn-sm btn-primary">Edit Profile</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  `;

  const toggle_edit_profile_modal_btn = document.createElement("button");
  toggle_edit_profile_modal_btn.setAttribute("data-bs-toggle", "modal");
  toggle_edit_profile_modal_btn.setAttribute("data-bs-target", "#edit-profile-modal");
  toggle_edit_profile_modal_btn.style.display = "none";

  el.parentElement.append(edit_profile_modal, toggle_edit_profile_modal_btn);

  toggle_edit_profile_modal_btn.click();

  // Remove from DOM when closed
  on(edit_profile_modal, "hidden.bs.modal", () => {
    edit_profile_modal.remove();
    toggle_edit_profile_modal_btn.remove();
  });

  // Close when submitted
  on(
    select("form", false, edit_profile_modal),
    "submit",
    () => toggle_edit_profile_modal_btn.click()
  );
}

/**
 * Submits the profile form data to update user's info
 * 
 * @param {HTMLFormElement} form_el The HTML form element with the information
 * @param {string} input_name The name of the HTML input to submit data
 */
function updateProfile(form_el, input_name, title) {
  // Start loader
  let loader = new initLoader('Editing...');
  loader.show();

  // Start form processing
  (new Promise(
    async function(resolve, reject) {
      let form_data = new FormData(form_el);

      // Send
      let res = await fetch("./profile/edit", {
        method: 'POST',
        body: form_data,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
      })
      .then(response => {
        if(!response.ok) {
          console.warn("Something went wrong!");
        }
        if(response.status === 404) {
          console.error("Resource not found!");
        }
        
        let data = response.json();
        console.log(data);
        resolve(data);
        return data;
      }, reason => {
        reject(reason);
      })
      .catch(error => {
        console.log("An error occurred: " + error);
      });
    }
  ))
  .then(response => {
    return response;
  })
  .then(data => {
    console.log(data);
    // Remove the loader; display notification
    setTimeout(() => {
      loader.hide();

      // Display the notification
      let content, notif_type;
      if(data[0] === true) {
        content = uppercaseFirst(title) + " updated successfully!";
        notif_type = "success"
      } else {
        content = "An error occurred!";
        notif_type = "danger";
      }
      let notif_msg = displayNotificationMessage(content, notif_type);
      // on(notif_msg, "hidden.bs.toast", () => window.location.reload()); // reload page for updates

      // Update the page
      select("[data-todoapp-update]", true).filter(el => el.dataset.todoappUpdate === input_name)
        .forEach(el => {
          let new_value = JSON.parse(data[2])[input_name];
          if(el instanceof HTMLInputElement) {
            el.value = new_value;
          } else {
            el.innerHTML = new_value;
          }
        });
    }, .8 * 1000);

    console.table(data);
  });
}

/**
 * Fetches information about the details of a user
 * 
 * @param {array} details An array containing the details to fetch.
 * Accepted elements of the array are 'firstname', 'lastname', 'username', 'email',
 * 'dob', 'reg-date'.
 * 
 */
async function fetchUserDetails(details) {
  return fetch(`/user/profile?details=${JSON.stringify(details)}`)
  .then(response => {
    if(!response.ok) {
      console.warn("Something went wrong");
    }

    return response.json();
  });
}

function showEditPassword(el) {
  const edit_password_modal = document.createElement("div");
  edit_password_modal.id = "edit-password-modal";
  edit_password_modal.classList.add("modal"); // BS custom modal class
  edit_password_modal.innerHTML = `
    <div class="modal-dialog modal-dialog-sm modal-dialog-centered" style="width: 400px;">
      <div class="modal-content">
        <!-- Form section -->
        <form action="javascript:void(null)" name="edit-password-form" onsubmit="updatePassword(this)">
          <div class="modal-header">
            <h6 class="modal-title fs-5 fw-bold">📝 Edit Your Password</h6>
          </div>

          <div class="modal-body">
            <div class="my-2">
              <label for="current-password-input">Current Password</label> <br/>
              <input type="password" name="current-password" id="current-password-input" placeholder="Enter your current password" class="px-1 form-control" autocomplete="false" />
            </div>
            <div class="my-2">
              <label for="new-password-input">New Password</label> <br/>
              <input type="password" name="new-password" id="new-password-input" placeholder="Enter your new password"  class="px-1 form-control" autocomplete="false" />
            </div>
          </div>

          <div class="modal-footer align-items-center justify-content-between">
            <button type="submit" class="btn btn-sm btn-primary">Update Password</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  `;

  const toggle_password_modal_btn = document.createElement("button");
  toggle_password_modal_btn.setAttribute("data-bs-toggle", "modal");
  toggle_password_modal_btn.setAttribute("data-bs-target", "#edit-password-modal");
  toggle_password_modal_btn.style.display = "none";
  toggle_password_modal_btn.hidden = true;

  el.parentElement.append(edit_password_modal, toggle_password_modal_btn);

  toggle_password_modal_btn.click();

  // Remove from DOM when closed
  on(edit_password_modal, "hidden.bs.modal", () => {
    edit_password_modal.remove();
    toggle_password_modal_btn.remove();
  });

  // Close when submitted
  on(
    select("form", false, edit_password_modal),
    "submit",
    () => toggle_password_modal_btn.click()
  );
}

function updatePassword(form_el) {
  // Start loader
  let loader = new initLoader("Updating...");
  loader.show();

  // Start form processing and data submission
  (new Promise(
    async function(resolve, reject) {
      let form_data = new FormData(form_el);

      // Sned form data
      let res = await fetch("./profile/edit", {
        method: 'POST',
        body: form_data,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
      })
      .then(response => {
        if(!response.ok) {
          console.warn("Something went wrong!");
        }
        if(response.status === 404) {
          console.error("Resource not found!");
        }

        let data = response.json();
        console.log(data);
        resolve(data);

        return data;
      }, reason => {
        reject(reason);
      })
      .catch(error => console.log("An error occurred: " + error));
    }
  )).then(response => {
    return response;
  })
  .then(data => {
    // Remove the loader; display notification
    setTimeout(() => {
      loader.hide();

      // Display the notification
      let notif_msg = displayNotificationMessage(
        data[1],
        data[0] ? "success" : "danger"
      );

    }, .8 * 1000);
  });
}

function displayChangeProfilePicture(form_name) {
  let change_pfp_form = select("#" + form_name);
  let change_pfp_input = select("input[type='file']", false, change_pfp_form);

  change_pfp_input.click();

  on(change_pfp_input, "change", function(event) {
    // User has selected an image: submit the form

    let submit_btn = select("[type='submit']", false, change_pfp_form);
    submit_btn.click();
  });

}

function changeProfilePicture(form_el, avatar_id=null) {
  // Start loader
  let loader = new initLoader("Please wait...");
  loader.show();

  // Start form processing
  (new Promise(
    async function(resolve, reject) {
      let request_details = {
        method: 'POST',
        body: null,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        }
      };

      if(form_el !== null) {
        request_details.body = new FormData(form_el);
      } else if(avatar_id !== null) {
        request_details.body = JSON.stringify({"use-avatar": avatar_id});
        request_details.headers["Content-Type"] = "application/json";
      }

      // Send
      let res = await fetch("./profile/edit", request_details)
      .then(response => {
        if(!response.ok) {
          console.warn("Something went wrong!");
        }

        let data = response.json();
        console.log(data);
        resolve(data);
      }, reason => reject(reason))
      .catch(error => console.log("An error occurred: " + error));
    }
  ))
  .then(response => {
    return response;
  })
  .then(data => {
    // Update image: Set the image's 'src' attribute to re-fetch the image
    select("[data-todoapp-update='profile-img']", true)
      .forEach(img => img.src = img.src);

    // Remove the loader; display notification
    setTimeout(() => {
      loader.hide();

      // Display the notification
      let content, notif_type;
      if(data[0] === true) {
        content = "Profile picture updated successfully";
        notif_type = "success";
      } else {
        content = "An error occurred!: " + data[1];
        notif_type = "danger";
      }

      let notif_msg = displayNotificationMessage(content, notif_type);
    }, .4 * 1000);
  })
  // .catch(error => console.error("An error occurred: " + error));
}
