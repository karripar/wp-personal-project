"use strict";

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".like-form").forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);

      const response = await fetch(likeButton.ajax_url, {
        method: "POST",
        headers: {
          "X-WP-Nonce": likeButton.nonce,
        },
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        const countSpan = form.querySelector(".like-count");
        const icon = form.querySelector("ion-icon");

        countSpan.textContent = result.likes;
        icon.setAttribute(
          "name",
          result.liked ? "thumbs-up" : "thumbs-up-outline"
        );
      } else {
        console.error("Failed to update like status");
      }
    });
  });
});
