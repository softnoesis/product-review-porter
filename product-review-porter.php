<?php
/**
 * Plugin Name: Product Review Porter
 * Description: Product Review Porter.
 * Version: 1.0.0
 * Author: Softnoesis Pvt. Ltd.
 */

// Add a new tab for reviews on the product page

add_action('admin_menu', 'register_my_custom_submenu_page');

function register_my_custom_submenu_page() {
    add_submenu_page( 'woocommerce', 'SN Product Review Import Export', 'SN Product Review Import Export', 'manage_options', 'sn-product-review-import-export', 'sn_product_reviev_import_export' ); 
}

function sn_product_reviev_import_export() {
    $get_path = plugin_dir_path(__FILE__);
    include($get_path . 'includes/import-export.php');
}