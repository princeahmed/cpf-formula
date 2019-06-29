<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit();

class CPF_Formula_Settings {

	private $settings_api;

	function __construct() {

		$this->settings_api = new Prince_Settings_API();
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 99 );
	}

	function admin_init() {

		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );

		//initialize settings
		$this->settings_api->admin_init();
	}

	function get_settings_sections() {

		$sections = array(
			array(
				'id'    => 'wcf_formula_general_settings',
				'title' => __( 'General Settings', 'wcf-formula' )
			),

			array(
				'id'    => 'wcf_formula_cdac_settings',
				'title' => __( 'CDAC Donation', 'wcf-formula' )
			),

			array(
				'id'    => 'wcf_formula_sinda_settings',
				'title' => __( 'SINDA Donation', 'wcf-formula' )
			),

			array(
				'id'    => 'wcf_formula_ecf_settings',
				'title' => __( 'ECF Donation', 'wcf-formula' )
			),

			array(
				'id'    => 'wcf_formula_mbmf_settings',
				'title' => __( 'MBMF Donation', 'wcf-formula' )
			),
		);

		return apply_filters( 'wcf_formula_settings_sections', $sections );
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */

	function get_settings_fields() {

		$settings_fields = array(
			'wcf_formula_general_settings' => array(
				array(
					'name'    => 'sdl',
					'label'   => __( 'SDL', 'wcf-formula' ),
					'desc'    => __( 'sdl percentage', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => '.25',
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//CDAC
			'wcf_formula_cdac_settings'    => array(
				array(
					'name'    => 'tw_less_than_2',
					'label'   => __( 'Total Wages < 2000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 2000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => .50,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2_to_3.5',
					'label'   => __( 'Total Wages 2000 - 3500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2000 - 3500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 1.00,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_3.5_to_5',
					'label'   => __( 'Total Wages 3500 - 5000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 3500 - 5000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 1.50,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_5_to_7.5',
					'label'   => __( 'Total Wages 5000 - 7500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 5000 - 7500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 2.00,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_7.5',
					'label'   => __( 'Total Wages > 7500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 7500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 3.00,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//SINDA
			'wcf_formula_sinda_settings'   => array(
				array(
					'name'    => 'tw_less_than_1',
					'label'   => __( 'Total Wages < 1000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 1000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 1.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1_to_1.5',
					'label'   => __( 'Total Wages 1000 - 1500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1000 - 1500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 3.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1.5_to_2.5',
					'label'   => __( 'Total Wages 1500 - 2500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1500 - 2500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 5.00,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2.5_to_4.5',
					'label'   => __( 'Total Wages 2500 - 4500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2500 - 4500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 7.00,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_4.5_to_7.5',
					'label'   => __( 'Total Wages 4500 - 7500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 4500 - 7500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 9.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_7.5_to_10',
					'label'   => __( 'Total Wages 7500 - 10000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 7500 - 10000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 12.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_10_to_15',
					'label'   => __( 'Total Wages 10000 - 15000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 10000 - 15000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 18.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_15',
					'label'   => __( 'Total Wages > 15000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 15000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 30.0,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//ECF
			'wcf_formula_ecf_settings'     => array(
				array(
					'name'    => 'tw_less_than_1',
					'label'   => __( 'Total Wages < 1000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 1000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 2.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1_to_1.5',
					'label'   => __( 'Total Wages 2000 - 1500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1000 - 1500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 4.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1.5_to_2.5',
					'label'   => __( 'Total Wages 1500 - 2500', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1500 - 2500', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 6.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2.5_to_4',
					'label'   => __( 'Total Wages 2500 - 4000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2500 - 4000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 9.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_4_to_7',
					'label'   => __( 'Total Wages 4000 - 7000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 4000 - 7000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 12.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_7_to_10',
					'label'   => __( 'Total Wages 7000 - 10000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 7000 - 10000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 16.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_10',
					'label'   => __( 'Total Wages > 10000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 10000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 20.0,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//MBMF
			'wcf_formula_mbmf_settings'     => array(
				array(
					'name'    => 'tw_less_than_1',
					'label'   => __( 'Total Wages < 1000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 1000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 3.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1_to_2',
					'label'   => __( 'Total Wages 1000 - 2000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1000 - 2000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 4.5,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2_to_3',
					'label'   => __( 'Total Wages 2000 - 3000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2000 - 3000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 6.5,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_3_to_4',
					'label'   => __( 'Total Wages 3000 - 4000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 3000 - 4000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 15.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_4_to_6',
					'label'   => __( 'Total Wages 4000 - 6000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 4000 - 6000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 19.5,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_6_to_8',
					'label'   => __( 'Total Wages 6000 - 8000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 6000 - 8000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 22.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_8_to_10',
					'label'   => __( 'Total Wages 8000 - 10000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 8000 - 10000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 24.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_10',
					'label'   => __( 'Total Wages > 10000', 'wcf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 10000', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 26.0,
					'type'    => 'number',
					'min'     => 0,
				),
			),
		);

		return apply_filters( 'wcf_formula_settings_fields', $settings_fields );
	}

	function admin_menu() {
		add_submenu_page(
			'options-general.php',
			'CPF Formula Settings',
			'CPF Formula',
			'manage_options',
			'cpf-formula-settings',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page() {

		echo '<div class="wrap">';
		echo sprintf( "<h2>%s</h2>", __( 'CPF Formula Settings', 'wcf-formula' ) );
		$this->settings_api->show_settings();
		echo '</div>';

	}

}

new CPF_Formula_Settings();
