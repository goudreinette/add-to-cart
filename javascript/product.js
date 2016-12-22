jQuery(function ($) {
    /**
     * Replace Add To Cart Form
     */
    var addToCartContainer = $('.add-to-cart-container')
    $('form.cart').replaceWith(addToCartContainer)
    $('.price-container').remove()

    /**
     * Update total
     */
    $('.quantity input, .quantity a, .quantity .plus, .quantity .minus').on('click, change, keyup', update)
    update()

    /**
     * Attach event handlers to custom quantity buttons
     */
    $(document).on('click, mouseup', '.quantity .plus, .quantity .minus', function () {
        var input = $(this).parent().find('input[name=quantity]')
        // Plus or minus?
        var value = Number(input.val()) + ($(this).attr('class') == 'plus' ? 1 : -1)
        input.val(value)
        // Manually trigger update
        update()
    })

    /**
     * Add to cart AJAX request
     */
    $('.addtocartbutton').click(function (e) {
        e.preventDefault()
        e.stopPropagation()
        jQuery.post(assigns.ajax_url, {
            action: 'addToCart',
            variations: assigns.variations,
            product_id: assigns.product_id
        }).done(function () {
            location.assign(assigns.cart_url)
        })
    })

    function updateGlobalQuantities() {
        var variations = assigns.variations
        $('.variation').toArray().forEach(function (variation) {
            var quantity = $(variation).find('input[name=quantity]').val()
            var variationId = $(variation).data('variation-id')
            variations.find(function (v) {
                return v.variation_id == variationId
            }).qty = quantity
        })
    }

    function updateTotal() {

        // Get the total price
        var total = 0
        var variations = $('.variation').toArray().forEach(function (variation) {
            var quantity = $(variation).find('input[name="quantity"]').val()
            var price = $(variation).data('display-price')
            var variationTotal = quantity * price
            total += variationTotal
        })

        // Display it, with a transition
        $('.total .amount').text(total)

        $('.cart-total').addClass('transition')
        setTimeout(function () {
            $('.cart-total').removeClass('transition')
        }, 500)
    }

    function update() {
        updateGlobalQuantities()
        updateTotal()
    }
})
