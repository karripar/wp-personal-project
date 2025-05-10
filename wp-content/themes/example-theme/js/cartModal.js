'use strict';

const cartModal = document.querySelector("#cartModal");
const cartModalContent = document.querySelector(".cart-modal-content");
const closeModalBtn = document.querySelector(".close-cart-btn");
const openCartBtn = document.getElementById("open-cart");

if (openCartBtn && cartModal && closeModalBtn) {
  openCartBtn.addEventListener("click", () => {
    cartModal.style.display = "block";
  });

  closeModalBtn.addEventListener("click", () => {
    cartModal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      cartModal.style.display = "none";
    }
  });
}
