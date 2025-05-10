document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contact-form");
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
  
      const formData = new FormData(form);
      formData.append("action", "submit_contact_form");
  
      fetch(ajaxurl.ajaxurl, {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then((data) => {
          document.getElementById("response-message").innerText = data;
          form.reset();
        })
        .catch((err) => {
          document.getElementById("response-message").innerText = "Lähetys epäonnistui.";
        });
    });
  });
  