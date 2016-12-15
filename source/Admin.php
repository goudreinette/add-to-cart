<?php namespace AddToCart;

/**
 * The Admin shows a meta_box on product edit pages.
 * This meta_box allows you to activate shared stock for the product
 * and it's variations.
 */
class Admin
{
    public $meta_key = 'add_to_cart';

    function __construct(\Utils\View $view)
    {
        add_action('admin_init', [$this, 'addMetabox']);
        add_action('save_post', [$this, 'handleSave']);
        $this->view = $view;
    }

    function addMetabox()
    {
        add_meta_box(
            $this->meta_key,
            'Variations',
            [$this, 'render'],
            'product',
            'side'
        );
    }

    function render()
    {
        global $post;
        $enabled = get_post_meta($post->ID, $this->meta_key, true);
        $this->view->render('admin', [
            'enabled' => $enabled ? 'checked' : '',
            'key'     => $this->meta_key
        ]);
    }

    function handleSave($post_id)
    {
        update_post_meta(
            $post_id,
            $this->meta_key,
            !!$_POST[$this->meta_key]
        );
    }
}
