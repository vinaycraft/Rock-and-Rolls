$(document).ready(function() {
    // Handle quantity buttons
    $('.quantity-btn').click(function() {
        const input = $(this).closest('.input-group').find('.quantity-input');
        const currentValue = parseInt(input.val());
        const action = $(this).data('action');
        
        if (action === 'increase' && currentValue < 10) {
            input.val(currentValue + 1);
        } else if (action === 'decrease' && currentValue > 1) {
            input.val(currentValue - 1);
        }
    });

    // Handle quantity input
    $('.quantity-input').on('change', function() {
        let value = parseInt($(this).val());
        
        if (isNaN(value) || value < 1) {
            value = 1;
        } else if (value > 10) {
            value = 10;
        }
        
        $(this).val(value);
    });

    // Handle add to cart
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const dishId = $(this).data('dish-id');
        const quantity = $(this).closest('.card-body').find('.quantity-input').val();
        const hasCheese = $(this).closest('.card-body')
            .find(`input[name="variant_${dishId}"][id="cheese_${dishId}"]`)
            .is(':checked');

        $.ajax({
            url: `/cart/add/${dishId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                quantity: quantity,
                has_cheese: hasCheese
            },
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
                alert('Failed to add item to cart. Please try again.');
            }
        });
    });

    // Handle cart quantity updates
    $('.cart-quantity-btn').click(function() {
        const row = $(this).closest('tr');
        const input = row.find('.quantity-input');
        const id = input.data('id');
        const currentValue = parseInt(input.val());
        const action = $(this).data('action');
        let newValue = currentValue;
        
        if (action === 'increase' && currentValue < 10) {
            newValue = currentValue + 1;
        } else if (action === 'decrease' && currentValue > 1) {
            newValue = currentValue - 1;
        }

        if (newValue !== currentValue) {
            updateCartItem(id, newValue, row);
        }
    });

    // Handle cart quantity input
    $('.cart-quantity-input').on('change', function() {
        const row = $(this).closest('tr');
        const id = $(this).data('id');
        let value = parseInt($(this).val());
        
        if (isNaN(value) || value < 1) {
            value = 1;
        } else if (value > 10) {
            value = 10;
        }
        
        $(this).val(value);
        updateCartItem(id, value, row);
    });

    // Handle remove from cart
    $('.remove-item').click(function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        $.ajax({
            url: `/cart/remove/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                row.fadeOut(300, function() {
                    $(this).remove();
                    updateCartTotal(response.total);
                    if ($('.cart-table tbody tr').length === 0) {
                        location.reload();
                    }
                });
            },
            error: function() {
                alert('Failed to remove item. Please try again.');
            }
        });
    });

    function updateCartItem(id, quantity, row) {
        $.ajax({
            url: `/cart/update/${id}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                quantity: quantity
            },
            success: function(response) {
                const price = parseFloat(row.find('.price').text().replace('₹', ''));
                const subtotal = price * response.quantity;
                row.find('.subtotal').text('₹' + subtotal.toFixed(2));
                updateCartTotal(response.total);
            },
            error: function() {
                alert('Failed to update quantity. Please try again.');
            }
        });
    }

    function updateCartTotal(total) {
        $('.cart-total').text('₹' + parseFloat(total).toFixed(2));
    }

    // Handle variant selection UI
    $('.variant-select').on('change', function() {
        const name = $(this).attr('name');
        $(`input[name="${name}"]`).closest('.form-check').removeClass('bg-light');
        $(this).closest('.form-check').addClass('bg-light');
    });
});
