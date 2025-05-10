'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // Attach listeners to initial Add to Cart buttons
    attachAddListeners();

    // Attach listeners to initial Remove buttons
    attachRemoveListeners();
});

const attachAddListeners = () => {
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;

            fetch(customCart.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=add_to_cart&product_id=${productId}&quantity=1`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Item added to cart!');
                    refreshCart(); // Update cart visually
                } else {
                    alert(data.data || 'Something went wrong');
                }
            });
        });
    });
}

const attachRemoveListeners = () => {
    document.querySelectorAll('.remove-from-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const cartItemId = btn.dataset.cartItemId;

            fetch(customCart.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=remove_from_cart&cart_item_id=${cartItemId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    refreshCart(); // Update cart visually
                } else {
                    alert(data.data || 'Something went wrong');
                }
            });
        });
    });
}

function refreshCart() {
    fetch(`${customCart.ajax_url}?action=get_updated_cart`)
        .then(res => res.text())
        .then(html => {
            const cartContent = document.getElementById('cartContent');
            if (cartContent) {
                cartContent.innerHTML = html;
                attachRemoveListeners(); // Reattach listeners to new buttons
            }
        });
}
