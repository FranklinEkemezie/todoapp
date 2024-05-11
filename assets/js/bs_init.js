// Initiate Bootstrap Popovers
select("[data-bs-toggle='popover']", true).forEach(el => {
  let trigger = el.dataset.bsTrigger || "focus";
  new bootstrap.Popover(el, {trigger});
});

// Initiate Bootsrap toast
// select("[data-bs-toggle='toast']", true).forEach(el => {
//   let toast = new bootstrap.Toast(el);
//   toast.show();
// })

// Initiate Elements to be copied on click


