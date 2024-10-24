<?php
/**
 * Copyright (C) 2014-2020 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wmle_Main_Controller {

	/**
	 * Main Application Controller
	 *
	 * @return Ai1wmle_Main_Controller
	 */
	public function __construct() {
		register_activation_hook( AI1WMLE_PLUGIN_BASENAME, array( $this, 'activation_hook' ) );

		// Activate hooks
		$this->activate_actions();
		$this->activate_filters();
	}

	/**
	 * Activation hook callback
	 *
	 * @return void
	 */
	public function activation_hook() {
		if ( constant( 'AI1WMLE_PURCHASE_ID' ) ) {
			@$this->create_activation_request( AI1WMLE_PURCHASE_ID );
		}
	}

	/**
	 * Create activation request
	 *
	 * @param  string $purchase_id Purchase ID
	 * @return void
	 */
	public function create_activation_request( $purchase_id ) {
		global $wpdb;

		if ( defined( 'AI1WMLE_ACTIVATION_URL' ) ) {
			wp_remote_post(
				AI1WMLE_ACTIVATION_URL,
				array(
					'timeout' => 15,
					'body'    => array(
						'url'           => get_site_url(),
						'email'         => get_option( 'admin_email' ),
						'wp_version'    => get_bloginfo( 'version' ),
						'php_version'   => PHP_VERSION,
						'mysql_version' => $wpdb->db_version(),
						'uuid'          => $purchase_id,
					),
				)
			);
		}
	}

	/**
	 * Initializes language domain for the plugin
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( AI1WMLE_PLUGIN_NAME, false, false );
	}

	/**
	 * Register plugin menus
	 *
	 * @return void
	 */
	public function admin_menu() {
		// Sub-level Settings menu
		add_submenu_page(
			'ai1wm_export',
			__( 'URL Settings', AI1WMLE_PLUGIN_NAME ),
			__( 'URL Settings', AI1WMLE_PLUGIN_NAME ),
			'import',
			'ai1wmle_settings',
			'Ai1wmle_Settings_Controller::index'
		);
	}

	/**
	 * Enqueue scripts and styles for Import Controller
	 *
	 * @param  string $hook Hook suffix
	 * @return void
	 */
	public function enqueue_import_scripts_and_styles( $hook ) {
		if ( stripos( 'all-in-one-wp-migration_page_ai1wm_import', $hook ) === false ) {
			return;
		}

		if ( is_rtl() ) {
			wp_enqueue_style(
				'ai1wmle_import',
				Ai1wm_Template::asset_link( 'css/import.min.rtl.css', 'AI1WMLE' ),
				array( 'ai1wm_import' )
			);
		} else {
			wp_enqueue_style(
				'ai1wmle_import',
				Ai1wm_Template::asset_link( 'css/import.min.css', 'AI1WMLE' ),
				array( 'ai1wm_import' )
			);
		}

		wp_enqueue_script(
			'ai1wmle_import',
			Ai1wm_Template::asset_link( 'javascript/import.min.js', 'AI1WMLE' ),
			array( 'ai1wm_import' )
		);

		wp_localize_script(
			'ai1wmle_import',
			'ai1wmle_dependencies',
			array( 'messages' => $this->get_missing_dependencies() )
		);

		if ( ! defined( 'AI1WMUE_PLUGIN_NAME' ) ) {
			wp_enqueue_script(
				'ai1wmue_uploader',
				Ai1wm_Template::asset_link( 'javascript/uploader.min.js', 'AI1WMLE' ),
				array( 'jquery' )
			);

			wp_localize_script(
				'ai1wmue_uploader',
				'ai1wmue_uploader',
				array(
					'chunk_size'  => apply_filters( 'ai1wm_max_chunk_size', AI1WM_MAX_CHUNK_SIZE ),
					'max_retries' => apply_filters( 'ai1wm_max_chunk_retries', AI1WM_MAX_CHUNK_RETRIES ),
					'url'         => wp_make_link_relative( admin_url( 'admin-ajax.php?action=ai1wm_import' ) ),
					'params'      => array(
						'priority'   => 5,
						'secret_key' => get_option( AI1WM_SECRET_KEY ),
					),
					'filters'     => array(
						'ai1wm_archive_extension' => array( 'wpress' ),
					),
				)
			);
		}
	}

	/**
	 * Enqueue scripts and styles for Settings Controller
	 *
	 * @param  string $hook Hook suffix
	 * @return void
	 */
	public function enqueue_settings_scripts_and_styles( $hook ) {
		if ( stripos( 'all-in-one-wp-migration_page_ai1wmle_settings', $hook ) === false ) {
			return;
		}

		if ( is_rtl() ) {
			wp_enqueue_style(
				'ai1wmle_settings',
				Ai1wm_Template::asset_link( 'css/settings.min.rtl.css', 'AI1WMLE' ),
				array( 'ai1wm_servmask' )
			);
		} else {
			wp_enqueue_style(
				'ai1wmle_settings',
				Ai1wm_Template::asset_link( 'css/settings.min.css', 'AI1WMLE' ),
				array( 'ai1wm_servmask' )
			);
		}

		wp_enqueue_script(
			'ai1wmle_settings',
			Ai1wm_Template::asset_link( 'javascript/settings.min.js', 'AI1WMLE' ),
			array( 'ai1wm_settings' )
		);

		wp_localize_script(
			'ai1wmle_settings',
			'ai1wm_feedback',
			array(
				'ajax'       => array(
					'url' => wp_make_link_relative( admin_url( 'admin-ajax.php?action=ai1wm_feedback' ) ),
				),
				'secret_key' => get_option( AI1WM_SECRET_KEY ),
			)
		);
	}

	/**
	 * Outputs menu icon between head tags
	 *
	 * @return void
	 */
	public function admin_head() {
		?>
		<style type="text/css" media="all">
			.ai1wm-label {
				border: 1px solid #5cb85c;
				background-color: transparent;
				color: #5cb85c;
				cursor: pointer;
				text-transform: uppercase;
				font-weight: 600;
				outline: none;
				transition: background-color 0.2s ease-out;
				padding: .2em .6em;
				font-size: 0.8em;
				border-radius: 5px;
				text-decoration: none !important;
			}

			.ai1wm-label:hover {
				background-color: #5cb85c;
				color: #fff;
			}
		</style>
		<?php
	}

	/**
	 * Register listeners for actions
	 *
	 * @return void
	 */
	private function activate_actions() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'load_textdomain' ) );
		add_action( 'admin_init', array( $this, 'router' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		add_action( 'plugins_loaded', array( $this, 'ai1wm_loaded' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'ai1wm_buttons' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'ai1wm_commands' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'ai1wm_export_options' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'wp_cli' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'wp_cli_extension' ), 30 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_export_scripts_and_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_import_scripts_and_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backups_scripts_and_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_settings_scripts_and_styles' ), 20 );
	}

	/**
	 * Register listeners for filters
	 *
	 * @return void
	 */
	private function activate_filters() {
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 5, 2 );
	}

	/**
	 * Export and import buttons
	 */
	public function ai1wm_buttons() {
		add_filter( 'ai1wm_pro', 'Ai1wmle_Import_Controller::pro', 20 );
	}

	/**
	 * Export and import commands
	 *
	 * @return void
	 */
	public function ai1wm_commands() {
		if ( ai1wmle_is_running() ) {
			add_filter( 'ai1wm_import', 'Ai1wmle_Import_Url::execute', 20 );
			add_filter( 'ai1wm_import', 'Ai1wmle_Import_Download::execute', 30 );
			add_filter( 'ai1wm_import', 'Ai1wmle_Import_Settings::execute', 290 );
			add_filter( 'ai1wm_import', 'Ai1wmle_Import_Database::execute', 310 );

			remove_filter( 'ai1wm_import', 'Ai1wm_Import_Upload::execute', 5 );
		}
	}

	public function get_missing_dependencies() {
		$messages = array();
		if ( ! extension_loaded( 'curl' ) ) {
			$messages[] = __( 'Your PHP is missing cURL extension. <a href="https://help.servmask.com/knowledgebase/curl-missing-in-php-installation/" target="_blank">Technical details</a>', AI1WMLE_PLUGIN_NAME );
		}

		return $messages;
	}

	/**
	 * Advanced export options
	 */
	public function ai1wm_export_options() {
		if ( ! defined( 'AI1WMUE_PLUGIN_NAME' ) ) {
			// Add export inactive themes
			if ( ! has_action( 'ai1wm_export_inactive_themes' ) ) {
				add_action( 'ai1wm_export_inactive_themes', 'Ai1wmle_Export_Controller::inactive_themes' );
			}

			// Add export inactive plugins
			if ( ! has_action( 'ai1wm_export_inactive_plugins' ) ) {
				add_action( 'ai1wm_export_inactive_plugins', 'Ai1wmle_Export_Controller::inactive_plugins' );
			}

			// Add export cache files
			if ( ! has_action( 'ai1wm_export_cache_files' ) ) {
				add_action( 'ai1wm_export_cache_files', 'Ai1wmle_Export_Controller::cache_files' );
			}

			// Add export exclude files
			if ( ! has_action( 'ai1wm_export_advanced_settings' ) ) {
				add_action( 'ai1wm_export_advanced_settings', 'Ai1wmle_Export_Controller::exclude_files' );
			}
		}
	}

	/**
	 * Check whether All-in-One WP Migration has been loaded
	 *
	 * @return void
	 */
	public function ai1wm_loaded() {
		if ( ! defined( 'AI1WM_PLUGIN_NAME' ) ) {
			if ( is_multisite() ) {
				add_action( 'network_admin_notices', array( $this, 'ai1wm_notice' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'ai1wm_notice' ) );
			}
		} else {
			if ( is_multisite() ) {
				add_action( 'network_admin_menu', array( $this, 'admin_menu' ), 20 );
			} else {
				add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
			}

			// Settings
			add_action( 'admin_post_ai1wmle_url_settings', 'Ai1wmle_Settings_Controller::settings' );

			// Picker
			add_action( 'ai1wm_import_left_end', 'Ai1wmle_Import_Controller::picker' );

			// TrustPilot widget
			if ( ! has_action( 'ai1wm_sidebar_right_end' ) ) {
				add_action( 'ai1wm_sidebar_right_end', array( $this, 'trust_pilot' ) );
			}

			// Add import button
			add_filter( 'ai1wm_import_url', 'Ai1wmle_Import_Controller::button' );

			// Add import unlimited
			add_filter( 'ai1wm_max_file_size', array( $this, 'max_file_size' ) );
		}
	}

	/**
	 * WP CLI commands
	 *
	 * @return void
	 */
	public function wp_cli() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'ai1wm', 'Ai1wm_Backup_WP_CLI_Command', array( 'shortdesc' => __( 'All-in-One WP Migration Command', AI1WMLE_PLUGIN_NAME ) ) );
		}
	}

	/**
	 * WP CLI commands: extension
	 *
	 * @return void
	 */
	public function wp_cli_extension() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command(
				'ai1wm url',
				'Ai1wmle_URL_WP_CLI_Command',
				array(
					'shortdesc'     => __( 'All-in-One WP Migration Command for URL Extension', AI1WMLE_PLUGIN_NAME ),
					'before_invoke' => array( $this, 'activate_extension_commands' ),
				)
			);
		}
	}

	/**
	 * Activates extension specific commands
	 *
	 * @return void
	 */
	public function activate_extension_commands() {
		$_GET['url'] = 1;
		$this->ai1wm_commands();
	}

	/**
	 * Display All-in-One WP Migration notice
	 *
	 * @return void
	 */
	public function ai1wm_notice() {
		?>
		<div class="error">
			<p>
				<?php
				_e(
					'URL Extension requires <a href="https://wordpress.org/plugins/all-in-one-wp-migration/" target="_blank">All-in-One WP Migration plugin</a> to be activated. ' .
					'<a href="https://help.servmask.com/knowledgebase/install-instructions-for-url-extension/" target="_blank">URL Extension install instructions</a>',
					AI1WMLE_PLUGIN_NAME
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles for Backup Controller
	 *
	 * @param  string $hook Hook suffix
	 * @return void
	 */
	public function enqueue_backups_scripts_and_styles( $hook ) {
		if ( stripos( 'all-in-one-wp-migration_page_ai1wm_backups', $hook ) === false ) {
			return;
		}

		if ( ! defined( 'AI1WMUE_PLUGIN_BASENAME' ) ) {
			wp_enqueue_script(
				'ai1wmue_restore',
				Ai1wm_Template::asset_link( 'javascript/restore.min.js', 'AI1WMLE' ),
				array( 'jquery' )
			);
		}
	}

	/**
	 * Enqueue scripts and styles for Export Controller
	 *
	 * @param  string $hook Hook suffix
	 * @return void
	 */
	public function enqueue_export_scripts_and_styles( $hook ) {
		if ( stripos( 'toplevel_page_ai1wm_export', $hook ) === false ) {
			return;
		}

		if ( is_rtl() ) {
			wp_enqueue_style(
				'ai1wmle_file_selector',
				Ai1wm_Template::asset_link( 'css/export.min.rtl.css', 'AI1WMLE' )
			);
		} else {
			wp_enqueue_style(
				'ai1wmle_file_selector',
				Ai1wm_Template::asset_link( 'css/export.min.css', 'AI1WMLE' )
			);
		}

		if ( ! defined( 'AI1WMUE_PLUGIN_NAME' ) ) {
			wp_enqueue_script(
				'ai1wmue_file_exclude',
				Ai1wm_Template::asset_link( 'javascript/file-excluder.min.js', 'AI1WMLE' ),
				array( 'jquery' )
			);

			wp_localize_script(
				'ai1wmue_file_exclude',
				'ai1wmue_locale',
				array(
					'button_done'                        => __( 'Done', AI1WMLE_PLUGIN_NAME ),
					'loading_placeholder'                => __( 'Listing files ...', AI1WMLE_PLUGIN_NAME ),
					'selected_no_files'                  => __( 'No files selected', AI1WMLE_PLUGIN_NAME ),
					'selected_multiple'                  => __( '{x} files and {y} folders selected', AI1WMLE_PLUGIN_NAME ),
					'selected_multiple_folders'          => __( '{y} folders selected', AI1WMLE_PLUGIN_NAME ),
					'selected_multiple_files'            => __( '{x} files selected', AI1WMLE_PLUGIN_NAME ),
					'selected_one_file'                  => __( '{x} file selected', AI1WMLE_PLUGIN_NAME ),
					'selected_one_file_multiple_folders' => __( '{x} file and {y} folders selected', AI1WMLE_PLUGIN_NAME ),
					'selected_one_file_one_folder'       => __( '{x} file and {y} folder selected', AI1WMLE_PLUGIN_NAME ),
					'selected_one_folder'                => __( '{y} folder selected', AI1WMLE_PLUGIN_NAME ),
					'selected_multiple_files_one_folder' => __( '{x} files and {y} folder selected', AI1WMLE_PLUGIN_NAME ),
					'column_name'                        => __( 'Name', AI1WMLE_PLUGIN_NAME ),
					'column_date'                        => __( 'Date', AI1WMLE_PLUGIN_NAME ),
					'legend_select'                      => __( 'Click checkbox to toggle selection', AI1WMLE_PLUGIN_NAME ),
					'legend_expand'                      => __( 'Click folder name to expand', AI1WMLE_PLUGIN_NAME ),
					'error_message'                      => __( 'Something went wrong, please refresh and try again', AI1WMLE_PLUGIN_NAME ),
					'button_clear'                       => __( 'Clear selection', AI1WMLE_PLUGIN_NAME ),
					'empty_list_message'                 => __( 'Folder empty. Click on folder icon to close it.', AI1WMLE_PLUGIN_NAME ),
				)
			);

			wp_localize_script(
				'ai1wmue_file_exclude',
				'ai1wmue_file_exclude',
				array(
					'ajax' => array(
						'url'        => wp_make_link_relative( admin_url( 'admin-ajax.php?action=ai1wmle_file_list' ) ),
						'list_nonce' => wp_create_nonce( 'ai1wmle_list' ),
					),
				)
			);
		}
	}

	/**
	 * Register initial router
	 *
	 * @return void
	 */
	public function router() {
		if ( current_user_can( 'export' ) ) {
			add_action( 'wp_ajax_ai1wmle_file_list', 'Ai1wmle_Export_Controller::list_files' );
		}
	}

	/**
	 * Add links to plugin list page
	 *
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( $file === AI1WMLE_PLUGIN_BASENAME ) {
			$links[] = __( '<a href="https://help.servmask.com/knowledgebase/url-extension-user-guide/" target="_blank">User Guide</a>', AI1WMLE_PLUGIN_NAME );
			$links[] = __( '<a href="https://servmask.com/contact-support" target="_blank">Contact Support</a>', AI1WMLE_PLUGIN_NAME );
		}

		return $links;
	}

	/**
	 * Max file size callback
	 *
	 * @return integer
	 */
	public function max_file_size() {
		return AI1WMLE_MAX_FILE_SIZE;
	}

	/**
	 * Register initial parameters
	 *
	 * @return void
	 */
	public function init() {
		if ( AI1WMLE_PURCHASE_ID ) {
			update_option( 'ai1wmle_plugin_key', AI1WMLE_PURCHASE_ID );
		}
	}

	public function trust_pilot() {
		Ai1wm_Template::render(
			'common/trust-pilot',
			array(),
			AI1WMLE_TEMPLATES_PATH
		);
	}
}
