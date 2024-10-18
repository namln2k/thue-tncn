<?php
/**
 * Admin class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Checkout Manager
 * @version 1.0.0
 */

defined( 'YWCCP' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YWCCP_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YWCCP_Admin {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YWCCP_Admin
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @since  1.0.0
		 * @var array
		 * @access public
		 */
		public $options = array();

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YWCCP_VERSION;

		/**
		 * Panel Object
		 *
		 * @var $panel
		 */
		protected $panel;

		/**
		 * Checkout Manager panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'ywccp_panel';

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YWCCP_Admin
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_filter( 'admin_body_class', array( $this, 'remove_body_class' ), 20 );

			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YWCCP_DIR . '/' . basename( YWCCP_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			// Handle custom tab.
			add_action( 'ywccp_fields_general_section', array( $this, 'fields_general_section' ), 10, 2 );

			// Edit and add new fields form.
			add_action( 'admin_footer', array( $this, 'print_add_edit_fields_form' ) );

			// Save options.
			add_action( 'admin_init', array( $this, 'save_options' ) );
			// Reset options.
			add_action( 'admin_init', array( $this, 'reset_options' ) );

			add_action( 'ywccp_print_admin_fields_section_table', array( $this, 'load_fields_table' ), 10, 1 );

			// Filter customer details on edit order section.
			add_filter( 'woocommerce_ajax_get_customer_details', array( $this, 'filter_ajax_customer_details' ), 10, 3 );
			// Register strings for polylang.
			add_action( 'admin_init', array( $this, 'register_strings_polylang' ), 99 );

			// Add customer billing and shipping address to admin profile edit.
			add_action( 'woocommerce_customer_meta_fields', array( $this, 'customer_meta_fields' ), 10, 1 );

			// Custom options for date-format field.
			add_filter( 'yith_plugin_fw_date_formats', array( $this, 'date_formats' ), 10, 2 );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since  1.0.0
		 */
		public function enqueue_scripts() {

			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_register_style( 'ywccp-admin-style', YWCCP_ASSETS_URL . '/css/ywccp-admin.css', array(), YWCCP_VERSION, 'all' );
			wp_register_script( 'ywccp-admin-script', YWCCP_ASSETS_URL . '/js/ywccp-admin' . $min . '.js', array( 'jquery', 'jquery-ui-dialog' ), YWCCP_VERSION, true );

			if ( $this->needs_scripts() ) {
				wp_enqueue_style( 'ywccp-admin-style' );
				wp_enqueue_script( 'ywccp-admin-script' );

				wp_localize_script(
					'ywccp-admin-script',
					'ywccp_admin',
					array(
						'popup_add_title'  => esc_html__( 'Add new field', 'yith-woocommerce-checkout-manager' ),
						'popup_edit_title' => esc_html__( 'Edit field', 'yith-woocommerce-checkout-manager' ),
						'enabled'          => '<span class="status-enabled tips" data-tip="' . esc_html__( 'Yes', 'yith-woocommerce-checkout-manager' ) . '"></span>',
						'deleteConfirm'    => esc_html__( 'Do you really want to delete this field?', 'yith-woocommerce-checkout-manager' ),
					)
				);
			}
		}

		/**
		 * Check if currently admin section needs plugin scripts
		 *
		 * @since  1.0.5
		 * @return boolean
		 */
		protected function needs_scripts() {
			global $post;

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$return = ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page ) ||
				( isset( $post->post_type ) && 'shop_order' === $post->post_type );

			return apply_filters( 'ywccp_admin_needs_scripts', $return );
		}

		/**
		 * Remove a body class to fix an issue with plugin FW
		 *
		 * @since 1.2.12
		 * @param string $classes Body classes.
		 * @return string
		 */
		public function remove_body_class( $classes ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page && 'fields' === sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) {
				return str_replace( 'yith-plugin-fw-panel', '', $classes );
			}
			return $classes;
		}

		/**
		 * Add the action links to plugin admin page
		 *
		 * @since    1.0
		 * @param array $links An array of plugin links.
		 * @return array
		 * @use      plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, true, YWCCP_SLUG );
			return $links;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @since    1.0
		 * @use      /Yit_Plugin_Panel class
		 * @return   void
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general' => __( 'Settings', 'yith-woocommerce-checkout-manager' ),
				'fields'  => __( 'Checkout fields', 'yith-woocommerce-checkout-manager' ),
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => 'YITH WooCommerce Checkout Manager',
				'menu_title'       => 'Checkout Manager',
				'capability'       => apply_filters( 'ywccp_panel_capability', 'manage_options' ),
				'parent'           => '',
				'parent_page'      => 'yith_plugin_panel',
				'page'             => $this->panel_page,
				'admin-tabs'       => apply_filters( 'ywccp_admin_tabs', $admin_tabs ),
				'options-path'     => YWCCP_DIR . '/plugin-options',
				'class'            => yith_set_wrapper_class(),
			);

			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YWCCP_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Plugin Row Meta
		 *
		 * @since    1.0
		 * @use      plugin_row_meta
		 * @param array    $new_row_meta_args An array of plugin row meta.
		 * @param string[] $plugin_meta       An array of the plugin's metadata,
		 *                                    including the version, author,
		 *                                    author URI, and plugin URI.
		 * @param string   $plugin_file       Path to the plugin file relative to the plugins directory.
		 * @param array    $plugin_data       An array of plugin data.
		 * @param string   $status            Status of the plugin. Defaults are 'All', 'Active',
		 *                                    'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                                    'Drop-ins', 'Search', 'Paused'.
		 *
		 * @return array
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( defined( 'YWCCP_INIT' ) && YWCCP_INIT === $plugin_file ) {
				$new_row_meta_args['slug']       = YWCCP_SLUG;
				$new_row_meta_args['is_premium'] = true;
			}

			return $new_row_meta_args;
		}

		/**
		 * Print fields table
		 *
		 * @access public
		 * @since  1.0.0
		 * @param array $options The field options.
		 * @return void
		 */
		public function fields_general_section( $options ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page && 'fields' === sanitize_text_field( wp_unslash( $_GET['tab'] ) )
				&& file_exists( YWCCP_TEMPLATE_PATH . '/admin/fields-general.php' ) ) {

				$sections      = array( 'billing', 'shipping', 'additional' );
				$current       = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : 'billing'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$base_page_url = admin_url( "admin.php?page={$this->panel_page}&tab=fields" );

				include_once YWCCP_TEMPLATE_PATH . '/admin/fields-general.php';
			}
		}

		/**
		 * Print edit form fields
		 *
		 * @since  1.0.0
		 */
		public function print_add_edit_fields_form() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page && file_exists( YWCCP_TEMPLATE_PATH . '/admin/fields-edit.php' ) ) {

				$positions  = ywccp_get_array_positions_field();
				$validation = ywccp_get_array_validation_field();

				include_once YWCCP_TEMPLATE_PATH . '/admin/fields-edit.php';
			}
		}

		/**
		 * Load fields table based on current visible section
		 *
		 * @since  1.0.0
		 * @param string $current Current table section.
		 */
		public function load_fields_table( $current = 'billing' ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page && file_exists( YWCCP_TEMPLATE_PATH . '/admin/fields-table.php' ) ) {

				$fields             = ywccp_get_checkout_fields( $current, true );
				$default_fields_key = ywccp_get_default_fields_key( $current );

				include_once YWCCP_TEMPLATE_PATH . '/admin/fields-table.php';
			}
		}

		/**
		 * Save options fields
		 *
		 * @since  1.0.0
		 */
		public function save_options() {
			// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
			if ( ! isset( $_GET['page'] ) || sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== $this->panel_page || ! isset( $_POST['ywccp-admin-action'] ) || 'fields-save' !== sanitize_text_field( wp_unslash( $_POST['ywccp-admin-action'] ) ) ) {
				return;
			}

			$section = isset( $_POST['ywccp-admin-section'] ) ? sanitize_text_field( wp_unslash( $_POST['ywccp-admin-section'] ) ) : '';
			$names   = isset( $_POST['field_name'] ) ? wp_unslash( $_POST['field_name'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( empty( $names ) ) {
				return;
			}

			$max        = max( array_map( 'absint', array_keys( $names ) ) );
			$new_fields = array();

			for ( $i = 0; $i <= $max; $i++ ) {

				$name = wc_clean( $names[ $i ] );
				$name = str_replace( ' ', '_', $name );

				if ( ! empty( $_POST['field_deleted'][ $i ] ) ) {
					$this->save_ordermeta( $name );
					continue;
				}

				$new_fields[ $name ]                = array();
				$new_fields[ $name ]['type']        = ! empty( $_POST['field_type'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_type'][ $i ] ) ) : 'text';
				$new_fields[ $name ]['label']       = ! empty( $_POST['field_label'][ $i ] ) ? wp_kses_post( wp_unslash( $_POST['field_label'][ $i ] ) ) : '';
				$new_fields[ $name ]['placeholder'] = ! empty( $_POST['field_placeholder'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_placeholder'][ $i ] ) ) : '';
				$new_fields[ $name ]['options']     = ! empty( $_POST['field_options'][ $i ] ) ? $this->crete_options_array( sanitize_text_field( wp_unslash( $_POST['field_options'][ $i ] ) ), $new_fields[ $name ]['type'] ) : array();
				$new_fields[ $name ]['class']       = ! empty( $_POST['field_class'][ $i ] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['field_class'][ $i ] ) ) ) : array();
				$new_fields[ $name ]['label_class'] = ! empty( $_POST['field_label_class'][ $i ] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['field_label_class'][ $i ] ) ) ) : '';
				$new_fields[ $name ]['validate']    = ! empty( $_POST['field_validate'][ $i ] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['field_validate'][ $i ] ) ) ) : '';
				$new_fields[ $name ]['required']    = ( ! empty( $_POST['field_required'][ $i ] ) && 'heading' !== $new_fields[ $name ]['type'] );
				$new_fields[ $name ]['enabled']     = ! empty( $_POST['field_enabled'][ $i ] );
				// Check also in bulk action.
				$bulk_action        = ! empty( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
				$bulk_action_bottom = ! empty( $_POST['bulk_action_bottom'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action_bottom'] ) ) : '';
				if ( ( $bulk_action || $bulk_action_bottom ) && isset( $_POST['select_field'][ $i ] ) ) {
					$new_fields[ $name ]['enabled'] = ( 'enable' === $bulk_action || 'enable' === $bulk_action_bottom );
				}
				$new_fields[ $name ]['show_in_email']     = ! empty( $_POST['field_show_in_email'][ $i ] );
				$new_fields[ $name ]['show_in_order']     = ! empty( $_POST['field_show_in_order'][ $i ] );
				$new_fields[ $name ]['custom_attributes'] = array(
					'data-tooltip' => ! empty( $_POST['field_tooltip'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_tooltip'][ $i ] ) ) : '',
				);
				if ( ! empty( $_POST['field_position'][ $i ] ) ) {
					array_push( $new_fields[ $name ]['class'], sanitize_text_field( wp_unslash( $_POST['field_position'][ $i ] ) ) );
				}

				$new_fields[ $name ]['condition_input_name'] = ! empty( $_POST['field_condition_input_name'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_condition_input_name'][ $i ] ) ) : '';
				$new_fields[ $name ]['condition_type']       = ! empty( $_POST['field_condition_type'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_condition_type'][ $i ] ) ) : '';
				$new_fields[ $name ]['condition_value']      = ! empty( $_POST['field_condition_value'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_condition_value'][ $i ] ) ) : '';
				$new_fields[ $name ]['condition_action']     = ! empty( $_POST['field_condition_action'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_condition_action'][ $i ] ) ) : '';
				$new_fields[ $name ]['condition_required']   = ! empty( $_POST['field_condition_required'][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST['field_condition_required'][ $i ] ) ) : '';
			}

			if ( ! empty( $new_fields ) ) {
				update_option( 'ywccp_fields_' . $section . '_options', $new_fields );
			}

			// phpcs:enable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Create options array for field
		 *
		 * @access protected
		 * @since  1.0.0
		 * @param string $options The option values.
		 * @param string $type The option type.
		 * @return array
		 */
		protected function crete_options_array( $options, $type = '' ) {

			$options_array = array();

			$options = array_map( 'wc_clean', explode( '|', $options ) ); // Create array from string.
			$options = array_unique( $options ); // Remove double entries.

			// First of all add empty options for placeholder if type is option.
			if ( 'select' === $type ) {
				$options_array[''] = '';
			}

			foreach ( $options as $option ) {
				$has_key = strpos( $option, '::' );
				if ( $has_key ) {
					list( $key, $option ) = explode( '::', $option );
				} else {
					$key = $option;
				}

				// Create key.
				$key                   = urldecode( sanitize_title_with_dashes( $key ) ); // Url decode the string to prevent issue with no Latin charset.
				$options_array[ $key ] = stripslashes( $option );
			}

			return array_filter( $options_array );
		}

		/**
		 * Create order meta for prevent losing information if a fields was deleted
		 *
		 * @access protected
		 * @since  1.0.0
		 * @param string $field The field name to convert.
		 */
		protected function save_ordermeta( $field ) {
			global $wpdb;

			$query = $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key LIKE %s", $field, '_' . $field );
			$wpdb->query( $query ); // phpcs:ignore
		}

		/**
		 * Reset default options
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function reset_options() {
			// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
			if ( ! isset( $_GET['page'] ) || sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== $this->panel_page || ! isset( $_POST['ywccp-admin-action'] ) || 'fields-reset' !== sanitize_text_field( wp_unslash( $_POST['ywccp-admin-action'] ) ) ) {
				return;
			}

			$section = isset( $_POST['ywccp-admin-section'] ) ? sanitize_text_field( wp_unslash( $_POST['ywccp-admin-section'] ) ) : 'billing';
			delete_option( 'ywccp_fields_' . $section . '_options' );
			// phpcs:enable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Filter WooCommerce Get customer details via ajax
		 *
		 * @since  1.0.11
		 * @access public
		 * @param array          $data     Customer details.
		 * @param WC_Customer    $customer The customer object.
		 * @param string|integer $user_id  The customer id.
		 * @return array
		 */
		public function filter_ajax_customer_details( $data, $customer, $user_id ) {

			$custom_fields = array(
				'billing'  => ywccp_get_fields_key_filtered( 'billing', true ),
				'shipping' => ywccp_get_fields_key_filtered( 'shipping', true ),
			);

			// Loop custom fields.
			foreach ( $custom_fields as $section => $fields ) {
				// Double check id data section exists.
				if ( ! isset( $data[ $section ] ) ) {
					continue;
				}
				// Loop section fields.
				foreach ( $fields as $field ) {
					$data[ $section ][ $field ] = $customer->get_meta( $section . '_' . $field );
				}
			}

			return $data;
		}

		/**
		 * Register strings for PolyLang
		 *
		 * @since  1.0.0
		 */
		public function register_strings_polylang() {
			if ( ! function_exists( 'pll_register_string' ) ) {
				return;
			}
			$fields = ywccp_get_all_checkout_fields();
			foreach ( $fields as $id => $field ) {
				if ( isset( $field['label'] ) && $field['label'] ) {
					pll_register_string( $id, $field['label'], 'yith-woocommerce-checkout-manager' );
				}
				// Register placeholder.
				if ( isset( $field['placeholder'] ) && $field['placeholder'] ) {
					pll_register_string( $id, $field['placeholder'], 'yith-woocommerce-checkout-manager' );
				}
				// Register tooltip.
				if ( isset( $field['custom_attributes']['data-tooltip'] ) && $field['custom_attributes']['data-tooltip'] ) {
					pll_register_string( $id, $field['custom_attributes']['data-tooltip'], 'yith-woocommerce-checkout-manager' );
				}

				if ( ! empty( $field['options'] ) ) {
					foreach ( $field['options'] as $option_key => $option ) {
						if ( ! $option ) {
							continue;
						}
						// Register single option.
						pll_register_string( $id . '-' . $option_key, $option, 'yith-woocommerce-checkout-manager' );
					}
				}
			}
		}

		/**
		 * Add customer meta fields to edit profile admin page
		 *
		 * @since  1.2.7
		 * @param array $fields An array of customer fields.
		 * @return array
		 */
		public function customer_meta_fields( $fields ) {
			foreach ( array( 'billing', 'shipping' ) as $section ) {

				$custom_fields = ywccp_get_custom_fields( $section );
				if ( empty( $custom_fields ) ) {
					continue;
				}

				foreach ( $custom_fields as $field_key => $field ) {
					// Allowed types are text|checkbox|select. By default is text, if radio use select.
					$type = 'radio' === $field['type'] ? 'select' : $field['type'];

					$fields[ $section ]['fields'][ $field_key ] = array(
						'label'       => $field['label'],
						'description' => '',
						'class'       => '',
						'type'        => $type,
						'options'     => isset( $field['options'] ) ? $field['options'] : false,
					);
				}
			}

			return $fields;
		}

		/**
		 * Filter date formats for date-format field type
		 *
		 * @since 1.4.4
		 * @auhtor Francesco Licandro
		 * @param array   $formats Plugin date formats.
		 * @param boolean $js .
		 * @return array
		 */
		public function date_formats( $formats, $js ) {
			if ( isset( $_GET['page'] ) && $_GET['page'] === $this->panel_page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$formats = array(
					'mm/dd/yy'     => 'm/d/y',
					'yy-mm-dd'     => 'y-m-d',
					'd M, y'       => 'd M, y',
					'd MM, y'      => 'd F, y',
					'DD, d MM, yy' => 'D, d M, y',
				);
			}

			return $formats;
		}
	}
}
/**
 * Unique access to instance of YWCCP_Admin class
 *
 * @since 1.0.0
 * @return YWCCP_Admin
 */
function YWCCP_Admin() { // phpcs:ignore
	return YWCCP_Admin::get_instance();
}
