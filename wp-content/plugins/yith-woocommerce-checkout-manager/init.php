<?php
/**
 * Plugin Name: YITH WooCommerce Checkout Manager
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-checkout-manager/
 * Description: The <code><strong>YITH WooCommerce Checkout Manager</strong></code> allows you add, edit or remove checkout fields. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 1.36.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-checkout-manager
 * Domain Path: /languages/
 * Requires at least: 6.2
 * Tested up to: 6.4
 * WC requires at least: 8.4
 * WC tested up to: 8.6
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Checkout Manager
 * @version 1.24.0
 */

/**
 * Copyright 2016-2022 Your Inspiration Solutions (email : plugins@yithemes.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Message is WooCommerce is not installed
 *
 * @since 1.0.0
 * @return void
 */
function ywccp_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'YITH WooCommerce Checkout Manager is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-checkout-manager' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! function_exists( 'yith_plugin_onboarding_registration_hook' ) ) {
	include_once 'plugin-upgrade/functions-yith-licence.php';
}
register_activation_hook( __FILE__, 'yith_plugin_onboarding_registration_hook' );


if ( ! defined( 'YWCCP_VERSION' ) ) {
	define( 'YWCCP_VERSION', '1.36.0' );
}
if ( ! defined( 'YWCCP_INIT' ) ) {
	define( 'YWCCP_INIT', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'YWCCP' ) ) {
	define( 'YWCCP', true );
}
if ( ! defined( 'YWCCP_FILE' ) ) {
	define( 'YWCCP_FILE', __FILE__ );
}
if ( ! defined( 'YWCCP_URL' ) ) {
	define( 'YWCCP_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'YWCCP_DIR' ) ) {
	define( 'YWCCP_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'YWCCP_TEMPLATE_PATH' ) ) {
	define( 'YWCCP_TEMPLATE_PATH', YWCCP_DIR . 'templates' );
}
if ( ! defined( 'YWCCP_ASSETS_URL' ) ) {
	define( 'YWCCP_ASSETS_URL', YWCCP_URL . 'assets' );
}
if ( ! defined( 'YWCCP_SLUG' ) ) {
	define( 'YWCCP_SLUG', 'yith-woocommerce-checkout-manager' );
}
if ( ! defined( 'YWCCP_SECRET_KEY' ) ) {
	define( 'YWCCP_SECRET_KEY', 'nwlgveplC1HthBsoJnEO' );
}

// Plugin Framework Version Check.
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWCCP_DIR . 'plugin-fw/init.php' ) ) {
	require_once YWCCP_DIR . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YWCCP_DIR );

/**
 * Init.
 *
 * @since 1.0.0
 * @return void
 */
function ywccp_init() {

	load_plugin_textdomain( 'yith-woocommerce-checkout-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	require_once 'includes/functions.ywccp.php';
	require_once 'includes/hooks.ywccp.php';
	require_once 'includes/class.ywccp.php';

	// Start the game!
	YWCCP();
}

add_action( 'ywccp_init', 'ywccp_init' );

/**
 * Install.
 *
 * @since 1.0.0
 * @return void
 */
function ywccp_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ywccp_install_woocommerce_admin_notice' );
	} else {
		do_action( 'ywccp_init' );
	}
}

add_action( 'plugins_loaded', 'ywccp_install', 11 );

if ( ! function_exists( 'ywccp_activation_plugin_action' ) ) {
	/**
	 * On activation restore order meta fields.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function ywccp_activation_plugin_action() {
		global $wpdb;

		if ( ! function_exists( 'ywccp_get_custom_fields' ) ) {
			require_once 'includes/functions.ywccp.php';
		}

		$billing_fields = ywccp_get_custom_fields( 'billing' );
		$billing_fields = empty( $billing_fields ) ? array() : array_keys( $billing_fields );

		$shipping_fields = ywccp_get_custom_fields( 'shipping' );
		$shipping_fields = empty( $shipping_fields ) ? array() : array_keys( $shipping_fields );

		$fields = array_merge( $shipping_fields, $billing_fields );

		if ( empty( $fields ) ) {
			return;
		}

		$fields = implode( "','", $fields );

		$query = "UPDATE $wpdb->postmeta SET meta_key = CONCAT( '_', meta_key ) WHERE meta_key IN ('$fields')";

		$wpdb->query( $query ); // phpcs:ignore
	}
}
register_activation_hook( __FILE__, 'ywccp_activation_plugin_action' );

if ( ! function_exists( 'ywccp_deactivation_plugin_action' ) ) {
	/**
	 * On deactivation hooks register meta for order
	 *
	 * @since 1.0.0
	 */
	function ywccp_deactivation_plugin_action() {

		global $wpdb;

		if ( ! function_exists( 'ywccp_get_custom_fields' ) ) {
			require_once 'includes/functions.ywccp.php';
		}

		$billing_fields = ywccp_get_custom_fields( 'billing' );
		$billing_fields = empty( $billing_fields ) ? array() : array_keys( $billing_fields );

		$shipping_fields = ywccp_get_custom_fields( 'shipping' );
		$shipping_fields = empty( $shipping_fields ) ? array() : array_keys( $shipping_fields );

		$fields = array_merge( $shipping_fields, $billing_fields );

		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as &$field ) {
			$field = '_' . $field;
		}

		$fields = implode( "','", $fields );

		$query = "UPDATE $wpdb->postmeta SET meta_key = SUBSTRING( meta_key, 2) WHERE meta_key IN ('$fields')";

		$wpdb->query( $query ); // phpcs:ignore
	}
}
register_deactivation_hook( __FILE__, 'ywccp_deactivation_plugin_action' );
