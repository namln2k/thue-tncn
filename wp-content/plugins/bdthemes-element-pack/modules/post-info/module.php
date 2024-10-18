<?php

namespace ElementPack\Modules\PostInfo;

use ElementPack\Base\Element_Pack_Module_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	public function get_name() {
		return 'post-info';
	}

	public function get_widgets() {

		$widgets = [
			'Post_Info',
		];

		return $widgets;
	}
}
