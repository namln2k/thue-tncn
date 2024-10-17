<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * CASSO payment method integration
 *
 * @since 3.12.1
 */
final class WC_Casso_Blocks_Support extends AbstractPaymentMethodType {
	/**
	 * Name of the payment method.
	 *
	 * @var string
	 */
	protected $list_payment = [];
	protected $name = '';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'casso', [] );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active()
	{
		$payment_gateways_class   = WC()->payment_gateways();
		$payment_gateways         = $payment_gateways_class->payment_gateways();
		$payment_name = null;
		
		foreach ($payment_gateways as $key => $value) {
			if (strpos($key, 'casso_up_') === 0) {
				if($payment_name === null || $payment_gateways[$key]->enabled === 'yes') {
					$payment_name = $key;
				}

				if (property_exists($payment_gateways[$key], 'checked')) {
					if ($payment_gateways[$key]->checked) {
						continue;
					}
				}

				$this->name = $value->id;

				$this->list_payment[] =	$this->create_payment_method_data($this->name, $value->bank_name, $value->bank_id);

				$payment_gateways[$key]->checked = true;
				$payment_gateways[$key]->is_available();
			}
		}
		if ($payment_name) {
			return $payment_gateways[$payment_name]->is_available();
		} else {
			return false;
		}
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$asset_path   = CASSO_DIR . '/assets/js/index.asset.php';
		$version      = CASSO_VERSION;
		$dependencies = [];
		if ( file_exists( $asset_path ) ) {
			$asset        = require $asset_path;
			$version      = is_array( $asset ) && isset( $asset['version'] )
				? $asset['version']
				: $version;
			$dependencies = is_array( $asset ) && isset( $asset['dependencies'] )
				? $asset['dependencies']
				: $dependencies;
		}
		wp_register_script(
			'wc-casso-blocks-integration',
			CASSO_URL . '/assets/js/block-support.js',
			$dependencies,
			$version,
			true
		);
		wp_set_script_translations(
			'wc-casso-blocks-integration',
			'woocommerce-gateway-casso'
		);
		return [ 'wc-casso-blocks-integration' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function create_payment_method_data($name, $bank_name, $bank_id) {
		$payment_method_description = __("Transfer money to our account<b> %s</b>. The order will be confirmed immediately after the transfer", 'casso-tu-dong-xac-nhan-thanh-toan-chuyen-khoan-ngan-hang');
		$open_b_tag_pos = strpos($payment_method_description, '<b>');
		$close_b_tag_pos = strpos($payment_method_description, '</b>');
		$description_before_bank_name = substr($payment_method_description, 0, $open_b_tag_pos);
		if (substr($description_before_bank_name, -1) != ' ') {
			$description_before_bank_name .= ' ';
		}
		$description_after_bank_name = substr($payment_method_description, $close_b_tag_pos + 4, strlen($payment_method_description) - ($close_b_tag_pos + 4)); // Cong 4 do tag </b> co 4 ky tu
		
		$power_by = __(" <div class='power_by'>Automatic transaction checking by Casso Robot</div>", 'casso-tu-dong-xac-nhan-thanh-toan-chuyen-khoan-ngan-hang');
		$open_div_tag_pos = strpos($power_by, "<div class='power_by'>");
		$power_by_content = substr($power_by, $open_div_tag_pos + 22, strlen($power_by) - ($open_div_tag_pos + 22) - 6); // Tru 6 do tag </div> co 6 ky tu
		return [
            'name'        => $name,
			'bank_name'	  => $bank_name,
			'title'       => __('Transfer', 'casso-tu-dong-xac-nhan-thanh-toan-chuyen-khoan-ngan-hang'),
			'description_before_bank_name' => $description_before_bank_name,
			'description_after_bank_name'  => $description_after_bank_name,
			'power_by'	  => $power_by_content,
			'supports'    => $this->get_supported_features(),
			'logo_url'    => CASSO_URL . '/assets/' . $bank_id . '.png'
		];
		
	}
	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return $this->list_payment;		
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		return $payment_gateways[$this->name]->supports;
	}

    public function console_log($output, $with_script_tags = true)
	{
		// $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
		// 	');';
		// if ($with_script_tags) {
		// 	$js_code = '<script>' . $js_code . '</script>';
		// }
		// echo $js_code;
	}
}
