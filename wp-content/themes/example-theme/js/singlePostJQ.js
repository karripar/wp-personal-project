"use strict";

const modal = jQuery("#single-post");
const modalButtons = jQuery(".open-modal");
const closeButton = jQuery("#close");
const modalContent = jQuery("#modal-content");

const myAJAXFunction = (id) => {
  const url = singlePost.ajax_url;
  const data = {
    action: "single_post",
    post_id: id,
  };

  jQuery.post(url, data).done((data) => {
    console.log("jQuery AJAX response:", data);
    const result = data.data;
    modalContent.empty();
    modalContent.html(`<h2>${result.post_title}</h2>`);
    modalContent.append(`<div>${result.post_content}</div>`);
    modal[0].showModal();
  });
};

modalButtons.on("click", (e) => {
  e.preventDefault();
  // dataset id
  const postId = jQuery(e.currentTarget).data("id");
  myAJAXFunction(postId);
});

closeButton.on("click", () => {
  modal[0].close();
});
