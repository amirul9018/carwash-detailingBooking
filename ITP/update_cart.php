<script>
    // JavaScript to handle quantity changes
    document.querySelectorAll('.increase-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantityElement = document.getElementById(quantity-${productId});
            let quantity = parseInt(quantityElement.innerText);
            quantity++;
            quantityElement.innerText = quantity;

            // Update the cart in the backend via AJAX
            updateCart(productId, quantity);
        });
    });

    document.querySelectorAll('.decrease-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantityElement = document.getElementById(quantity-${productId});
            let quantity = parseInt(quantityElement.innerText);

            if (quantity > 1) {
                quantity--;
                quantityElement.innerText = quantity;
                updateCart(productId, quantity);
            } else {
                // Remove the product from the cart if quantity is 1
                removeFromCart(productId);
            }
        });
    });

    function updateCart(productId, quantity) {
        // Make an AJAX request to update the quantity in the cart
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                // Optionally handle success response, like updating the total price
                console.log(this.responseText);
            } else {
                // Handle error if needed
                console.error("Failed to update cart");
            }
        };
        xhr.send(product_id=${productId}&quantity=${quantity});
    }

    function removeFromCart(productId) {
        // Make an AJAX request to remove the product from the cart
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'remove_from_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                console.log(this.responseText);
                // Reload the page or remove the product row from the table
                location.reload();
            } else {
                console.error("Failed to remove product");
            }
        };
        xhr.send(product_id=${productId});
    }
</script>