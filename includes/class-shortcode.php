<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

/**
 * Class ShortCode
 *
 * add short codes
 *
 * @since 1.0.0
 */
class CPF_Formula_ShortCode {

	/* constructor */
	public function __construct() {
		add_shortcode( 'formula_1', array( $this, 'formula_1' ) );
	}

	/**
	 * Formula 1
	 *
	 * @param $attrs
	 *
	 * @return string
	 */
	function formula_1( $attrs ) {
		ob_start();
		include CPF_FORMULA_PUBLIC . '/html-formula1.php';

		return ob_get_clean();
	}

}

new CPF_Formula_ShortCode();