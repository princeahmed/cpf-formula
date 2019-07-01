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
				'id'    => 'cpf_formula_general_settings',
				'title' => __( 'General Settings', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_cdac_settings',
				'title' => __( 'CDAC Donation', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_sinda_settings',
				'title' => __( 'SINDA Donation', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_ecf_settings',
				'title' => __( 'ECF Donation', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_mbmf_settings',
				'title' => __( 'MBMF Donation', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_1st_spr',
				'title' => __( '1st year of SPR status', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_2nd_spr',
				'title' => __( '2nd year of SPR status', 'cpf-formula' )
			),

			array(
				'id'    => 'cpf_formula_3rd_spr',
				'title' => __( '3rd year of SPR status', 'cpf-formula' )
			),

		);

		return apply_filters( 'cpf_formula_settings_sections', $sections );
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */

	function get_settings_fields() {

		$settings_fields = array(
			'cpf_formula_general_settings' => array(
				array(
					'name'    => 'sdl',
					'label'   => __( 'SDL', 'cpf-formula' ),
					'desc'    => __( 'sdl percentage', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => '.25',
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//CDAC
			'cpf_formula_cdac_settings'    => array(
				array(
					'name'    => 'tw_less_than_2',
					'label'   => __( 'Total Wages < 2000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 2000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => .50,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2_to_3.5',
					'label'   => __( 'Total Wages 2000 - 3500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2000 - 3500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 1.00,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_3.5_to_5',
					'label'   => __( 'Total Wages 3500 - 5000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 3500 - 5000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 1.50,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_5_to_7.5',
					'label'   => __( 'Total Wages 5000 - 7500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 5000 - 7500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 2.00,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_7.5',
					'label'   => __( 'Total Wages > 7500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 7500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 3.00,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//SINDA
			'cpf_formula_sinda_settings'   => array(
				array(
					'name'    => 'tw_less_than_1',
					'label'   => __( 'Total Wages < 1000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 1000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 1.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1_to_1.5',
					'label'   => __( 'Total Wages 1000 - 1500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1000 - 1500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 3.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1.5_to_2.5',
					'label'   => __( 'Total Wages 1500 - 2500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1500 - 2500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 5.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2.5_to_4.5',
					'label'   => __( 'Total Wages 2500 - 4500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2500 - 4500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 7.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_4.5_to_7.5',
					'label'   => __( 'Total Wages 4500 - 7500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 4500 - 7500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 9.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_7.5_to_10',
					'label'   => __( 'Total Wages 7500 - 10000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 7500 - 10000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 12.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_10_to_15',
					'label'   => __( 'Total Wages 10000 - 15000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 10000 - 15000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 18.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_15',
					'label'   => __( 'Total Wages > 15000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 15000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 30.0,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//ECF
			'cpf_formula_ecf_settings'     => array(
				array(
					'name'    => 'tw_less_than_1',
					'label'   => __( 'Total Wages < 1000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 1000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 2.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1_to_1.5',
					'label'   => __( 'Total Wages 2000 - 1500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1000 - 1500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 4.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1.5_to_2.5',
					'label'   => __( 'Total Wages 1500 - 2500', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1500 - 2500', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 6.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2.5_to_4',
					'label'   => __( 'Total Wages 2500 - 4000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2500 - 4000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 9.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_4_to_7',
					'label'   => __( 'Total Wages 4000 - 7000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 4000 - 7000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 12.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_7_to_10',
					'label'   => __( 'Total Wages 7000 - 10000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 7000 - 10000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 16.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_10',
					'label'   => __( 'Total Wages > 10000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 10000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 20.0,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//MBMF
			'cpf_formula_mbmf_settings'    => array(
				array(
					'name'    => 'tw_less_than_1',
					'label'   => __( 'Total Wages < 1000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is less than 1000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 3.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_1_to_2',
					'label'   => __( 'Total Wages 1000 - 2000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 1000 - 2000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 4.5,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_2_to_3',
					'label'   => __( 'Total Wages 2000 - 3000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 2000 - 3000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 6.5,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_3_to_4',
					'label'   => __( 'Total Wages 3000 - 4000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 3000 - 4000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 15.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_4_to_6',
					'label'   => __( 'Total Wages 4000 - 6000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 4000 - 6000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 19.5,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_6_to_8',
					'label'   => __( 'Total Wages 6000 - 8000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 6000 - 8000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 22.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_8_to_10',
					'label'   => __( 'Total Wages 8000 - 10000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is Between 8000 - 10000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 24.0,
					'type'    => 'number',
					'min'     => 0,
				),
				array(
					'name'    => 'tw_greater_than_10',
					'label'   => __( 'Total Wages > 10000', 'cpf-formula' ),
					'desc'    => __( 'Enter the donation value, when total wages is greater than 10000', 'cpf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 26.0,
					'type'    => 'number',
					'min'     => 0,
				),
			),

			//1st year spr status
			'cpf_formula_1st_spr'          => array(
				//
				array(
					'name'    => '<=55',
					'default' => 'Employer Age 55 & below',
					'type'    => 'heading',
					'class'   => 'prince-settings-heading-tr',
				),

				array(
					'name'    => '<=55tw<=50_e_e_shares',
					'label'   => 'Total Wages <=50',
					'default' => '0',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages <= 50',
					'type'    => 'text',
				),

				array(
					'name'    => '<=55tw<=50_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages <= 50',
					'type'    => 'text',
				),
				//---------> $50 to $500

				array(
					'name'    => '<=55tw50_500_e_e_shares',
					'label'   => 'Total Wages > $50 to $500',
					'default' => '17',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages > $50 to $500 <b>( a% (TW) )</b>',
					'type'    => 'text',
				),

				array(
					'name'    => '<=55tw50_500_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages > $50 to $500',
					'type'    => 'text',
				),
				//---------> $500 to < $750

				array(
					'name'     => '<=55tw500_750_e_e_shares',
					'name2'    => '<=55tw500_750_e_e_shares2',
					'label'    => 'Total Wages > $500 to < $750',
					'default'  => '17',
					'default2' => '0.6',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'    => '<=55tw500_750_e_shares',
					'default' => '0.6',
					'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
					'type'    => 'text',
				),
				//---------≥ $750

				array(
					'name'     => '<=55tw>=750_e_e_shares',
					'name2'    => '<=55tw>=750_e_e_shares2',
					'label'    => 'Total Wages ≥ $750',
					'default'  => '37',
					'default2' => '37',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'     => '<=55tw>=750_e_shares',
					'default'  => '20',
					'name2'    => '<=55tw>=750_e_shares2',
					'default2' => '20',
					'desc'     => 'Enter employee\'s share, when total wages ≥ $750 <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'text',
				),

				//
				array(
					'name'    => '50-60',
					'default' => 'Employer Age Above 55 - 60',
					'type'    => 'heading',
					'class'   => 'prince-settings-heading-tr',
				),

				array(
					'name'    => '50-60-tw<=50_e_e_shares',
					'label'   => 'Total Wages <=50',
					'default' => '0',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages <= 50',
					'type'    => 'text',
				),

				array(
					'name'    => '50-60-tw<=50_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages <= 50',
					'type'    => 'text',
				),
				//---------> $50 to $500

				array(
					'name'    => '50-60-tw50_500_e_e_shares',
					'label'   => 'Total Wages > $50 to $500',
					'default' => '13',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages > $50 to $500 <b>( a% (TW) )</b>',
					'type'    => 'text',
				),

				array(
					'name'    => '50-60-tw50_500_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages > $50 to $500',
					'type'    => 'text',
				),
				//---------> $500 to < $750

				array(
					'name'     => '50-60-tw500_750_e_e_shares',
					'name2'    => '50-60-tw500_750_e_e_shares2',
					'label'    => 'Total Wages > $500 to < $750',
					'default'  => '13',
					'default2' => '0.39',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'    => '50-60-tw500_750_e_shares',
					'default' => '0.39',
					'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
					'type'    => 'text',
				),
				//---------≥ $750

				array(
					'name'     => '50-60-tw>=750_e_e_shares',
					'name2'    => '50-60-tw>=750_e_e_shares2',
					'label'    => 'Total Wages ≥ $750',
					'default'  => '26',
					'default2' => '26',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'     => '50-60-tw>=750_e_shares',
					'default'  => '13',
					'name2'    => '50-60-tw>=750_e_shares2',
					'default2' => '13',
					'desc'     => 'Enter employee\'s share, when total wages ≥ $750 <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'text',
				),

				//====60-65
				array(
					'name'    => '60-65',
					'default' => 'Employer Age Above 60 - 65',
					'type'    => 'heading',
					'class'   => 'prince-settings-heading-tr',
				),

				array(
					'name'    => '60-65-tw<=50_e_e_shares',
					'label'   => 'Total Wages <=50',
					'default' => '0',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages <= 50',
					'type'    => 'text',
				),

				array(
					'name'    => '60-65-tw<=50_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages <= 50',
					'type'    => 'text',
				),
				//---------> $50 to $500

				array(
					'name'    => '60-65-tw50_500_e_e_shares',
					'label'   => 'Total Wages > $50 to $500',
					'default' => '9',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages > $50 to $500 <b>( a% (TW) )</b>',
					'type'    => 'text',
				),

				array(
					'name'    => '60-65-tw50_500_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages > $50 to $500',
					'type'    => 'text',
				),
				//---------> $500 to < $750

				array(
					'name'     => '60-65-tw500_750_e_e_shares',
					'name2'    => '60-65-tw500_750_e_e_shares2',
					'label'    => 'Total Wages > $500 to < $750',
					'default'  => '9',
					'default2' => '0.225',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'    => '60-65-tw500_750_e_shares',
					'default' => '0.225',
					'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
					'type'    => 'text',
				),
				//---------≥ $750

				array(
					'name'     => '60-65-tw>=750_e_e_shares',
					'name2'    => '60-65-tw>=750_e_e_shares2',
					'label'    => 'Total Wages ≥ $750',
					'default'  => '16.5',
					'default2' => '16.5',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'     => '60-65-tw>=750_e_shares',
					'default'  => '7.5',
					'name2'    => '60-65-tw>=750_e_shares2',
					'default2' => '7.5',
					'desc'     => 'Enter employee\'s share, when total wages ≥ $750 <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'text',
				),

				//====above 65==========================
				array(
					'name'    => '>65',
					'default' => 'Employer Age Above 65',
					'type'    => 'heading',
					'class'   => 'prince-settings-heading-tr',
				),

				array(
					'name'    => '>65-tw<=50_e_e_shares',
					'label'   => 'Total Wages <=50',
					'default' => '0',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages <= 50',
					'type'    => 'text',
				),

				array(
					'name'    => '>65-tw<=50_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages <= 50',
					'type'    => 'text',
				),
				//---------> $50 to $500

				array(
					'name'    => '>65-tw50_500_e_e_shares',
					'label'   => 'Total Wages > $50 to $500',
					'default' => '7.5',
					'desc'    => 'Enter employee\'s & employer\'s share, when total wages > $50 to $500 <b>( a% (TW) )</b>',
					'type'    => 'text',
				),

				array(
					'name'    => '>65-tw50_500_e_shares',
					'default' => '0',
					'desc'    => 'Enter employee\'s share, when total wages > $50 to $500',
					'type'    => 'text',
				),
				//---------> $500 to < $750

				array(
					'name'     => '>65-tw500_750_e_e_shares',
					'name2'    => '>65-tw500_750_e_e_shares2',
					'label'    => 'Total Wages > $500 to < $750',
					'default'  => '7.5',
					'default2' => '0.15',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'    => '>65-tw500_750_e_shares',
					'default' => '0.225',
					'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
					'type'    => 'text',
				),
				//---------≥ $750

				array(
					'name'     => '>65-tw>=750_e_e_shares',
					'name2'    => '>65-tw>=750_e_e_shares2',
					'label'    => 'Total Wages ≥ $750',
					'default'  => '12.5',
					'default2' => '12.5',
					'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'double',
				),

				array(
					'name'     => '>65-tw>=750_e_shares',
					'default'  => '5',
					'name2'    => '>65-tw>=750_e_shares2',
					'default2' => '5',
					'desc'     => 'Enter employee\'s share, when total wages ≥ $750 <b>[a% (OW)]* + b% (AW)</b>',
					'type'     => 'text',
					'double'   => 'text',
				),
			),
		);

		return apply_filters( 'cpf_formula_settings_fields', $settings_fields );
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
		echo sprintf( "<h2>%s</h2>", __( 'CPF Formula Settings', 'cpf-formula' ) );
		$this->settings_api->show_settings();
		echo '</div>';

	}

}

new CPF_Formula_Settings();
