<?php 
/**
 * Add your custom php code below. 
 * 
 * We recommend you to use "code-snippets" plugin instead: https://wordpress.org/plugins/code-snippets/
 **/
function custom_add_to_cart_redirect() {
    if ( isset($_GET['add-to-cart']) && isset($_GET['redirect_to_checkout']) && $_GET['redirect_to_checkout'] == 'true' ) {
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }
}
add_action('template_redirect', 'custom_add_to_cart_redirect');
function remove_page_title_section() {
    remove_action('auxin_the_main_title_section', 'auxin_the_main_title_section');
}
add_action('wp_head', 'remove_page_title_section');

if (!function_exists('auxin_the_main_title_section')) {
    function auxin_the_main_title_section($args = array()) {
        // Xử lý hàm này theo cách bạn muốn hoặc chỉ đơn giản là return nếu không muốn hiển thị gì.
        return;
    }
}

function my_category_search_filter($query) {
    if ($query->is_search && !is_admin()) {
        if (isset($_GET['cat']) && !empty($_GET['cat'])) {
            $query->set('cat', $_GET['cat']);
        }
    }
    return $query;
}
add_filter('pre_get_posts','my_category_search_filter');

function custom_category_search_form() {
    ob_start(); // Bắt đầu lưu trữ đầu ra của HTML để trả về như một chuỗi

    ?>
    <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
        <label>
            <input type="search" class="search-field" placeholder="Search &hellip;" value="<?php echo get_search_query(); ?>" name="s" />
        </label>
        <input type="hidden" name="cat" value="<?php echo get_queried_object_id(); ?>" />
        <button type="submit" class="search-submit">Search</button>
    </form>
    <?php

    return ob_get_clean(); // Kết thúc lưu trữ và trả về nội dung đã lưu
}

add_shortcode('category_search', 'custom_category_search_form');

function custom_search_title($title) {
    if (is_search()) {
        $title = 'Kết quả tìm kiếm "' . get_search_query() . '"';
    }
    return $title;
}
add_filter('get_the_archive_title', 'custom_search_title');