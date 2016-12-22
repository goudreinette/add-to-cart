<?php namespace AddToCart;

use Utils\View;

class SingleProduct
{
    function __construct(View $view)
    {
        add_action('woocommerce_before_single_product_summary', [$this, 'enqueueScript']);
        add_action('wp_ajax_nopriv_addToCart', [$this, 'addToCart']);
        add_action('wp_ajax_addToCart', [$this, 'addToCart']);
        $this->view = $view;
    }

    function enqueueScript()
    {
        global $product;
        $active = get_post_meta($product->id, Admin::$meta_key, true);
        $type   = $product->product_type;


        if ($active && $type == 'variable') {
            $variations = $this->formatAttributes($product->get_available_variations());
            $this->view->enqueueStyle('product');
            $this->view->render('product', [
                'total_text'       => __('Total: €', 'add-to-cart'),
                'add_to_cart_text' => __('Add to Cart', 'add-to-cart'),
                'variations'       => $variations,
            ]);
            $this->view->enqueueScript('product', [
                'cart_url'   => wc_get_cart_url(),
                'ajax_url'   => admin_url('admin-ajax.php'),
                'product_id' => $product->id,
                'variations' => $variations,
            ]);
        }
    }

    function formatAttributes($variations)
    {
        return array_map(function ($variation) {
            $variation['attribute'] = array_values($variation['attributes'])[0];
            return $variation;
        }, $variations);
    }

    function addToCart()
    {
        $variations = $_POST['variations'];
        $product_id = $_POST['product_id'];

        foreach ($variations as $variation) {
            if ($variation['qty'] != 0) {
                WC()->cart->add_to_cart(
                    $product_id,
                    $variation['qty'],
                    $variation['variation_id'],
                    $variation['attributes']
                );
            }
        }
    }
}
