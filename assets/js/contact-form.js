// contact-form.js

document
  .getElementById("contactForm")
  ?.addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = this;
    const btn = document.getElementById("submitButton");
    const alertBox = document.getElementById("form-alert");

    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = "Sending…";

    const formData = new FormData(form);

    try {
      const res = await fetch(form.action, { method: "POST", body: formData });
      const json = await res.json();

      alertBox.classList.remove("d-none", "alert-success", "alert-danger");
      alertBox.classList.add(
        json.success ? "alert-success" : "alert-danger",
        "show"
      );
      alertBox.innerHTML = `
        ${json.message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;

      if (json.success) {
        form.reset();
        grecaptcha.reset();
      }
    } catch (err) {
      alertBox.classList.remove("d-none", "alert-success");
      alertBox.classList.add("alert-danger", "show");
      alertBox.innerHTML = `
        Something went wrong. Please try again later.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      console.error(err);
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
      setTimeout(() => alertBox.classList.add("d-none"), 5000);
    }
  });

// reCAPTCHA hooks
function recaptchaSuccess() {
  document.getElementById("submitButton").disabled = false;
}
function recaptchaExpired() {
  document.getElementById("submitButton").disabled = true;
}
