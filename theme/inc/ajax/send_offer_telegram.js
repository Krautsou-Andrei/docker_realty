jQuery(document).ready(function ($) {
  const formCallback = document.querySelector("[data-form-callback]");

  const submitButton = formCallback.querySelector('[type="submit"]');

  if (submitButton) {
    submitButton.addEventListener("click", () => {
      const inputName = formCallback.querySelector("input[name=your-name]");
      const inputPhone = formCallback.querySelector("input[name=your-phone]");
      const inputEmail = formCallback.querySelector("input[name=your-email]");
      const inputLink = formCallback.querySelector("input[name=your-link]");

      if (inputName.value && inputPhone.value && inputLink.value) {
        const data = {
          action: "send_telegram_form",
          name: inputName.value,
          email: inputEmail.value,
          phone: inputPhone.value,
          link: inputLink.value,
        };

        send_telegram_form(data);
      }
    });
  }
});
