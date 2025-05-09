"use strict";

const modal = document.querySelector("#single-post");
const modalButtons = document.querySelectorAll(".open-modal");
const closeButton = document.querySelector("#close");
const modalContent = document.querySelector("#modal-content");

const myAJAXFunction = async (id) => {
  const url = singlePost.ajax_url;
  const data = new URLSearchParams({
    action: "single_post",
    post_id: id,
  });
  const response = await fetch(url, {
    method: "POST",
    body: data,
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  });
  const post = await response.json();
  console.log(post);
  return post;
};

modalButtons.forEach((button) => {
  button.addEventListener("click", async (e) => {
    e.preventDefault();
    // dataset id
    const postId = button.dataset.id;
    const result = await myAJAXFunction(postId);
      modalContent.innerHTML = "";
      modalContent.insertAdjacentHTML("afterbegin",
        `<h2>${result.data.post_title}</h2>`
      )
      modalContent.insertAdjacentHTML("beforeend",
        `<div>${result.data.post_content}</div>`
      );
      modal.showModal();

    });
});

closeButton.addEventListener("click", () => {
  modal.close();
});
