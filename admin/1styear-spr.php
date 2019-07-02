<?php

function cpf_formula_1st_year_spr() {
	return array(
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
			'default' => '4',
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
			'default'  => '4',
			'default2' => '0.15',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'    => '<=55tw500_750_e_shares',
			'default' => '0.15',
			'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
			'type'    => 'text',
		),
		//---------≥ $750

		array(
			'name'     => '<=55tw>=750_e_e_shares',
			'name2'    => '<=55tw>=750_e_e_shares2',
			'label'    => 'Total Wages ≥ $750',
			'default'  => '9',
			'default2' => '9',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'     => '<=55tw>=750_e_shares',
			'name2'    => '<=55tw>=750_e_shares2',
			'default'  => '5',
			'default2' => '5',
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
			'default' => '4',
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
			'default'  => '4',
			'default2' => '0.15',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'    => '50-60-tw500_750_e_shares',
			'default' => '0.15',
			'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
			'type'    => 'text',
		),
		//---------≥ $750

		array(
			'name'     => '50-60-tw>=750_e_e_shares',
			'name2'    => '50-60-tw>=750_e_e_shares2',
			'label'    => 'Total Wages ≥ $750',
			'default'  => '9',
			'default2' => '9',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'     => '50-60-tw>=750_e_shares',
			'name2'    => '50-60-tw>=750_e_shares2',
			'default'  => '5',
			'default2' => '5',
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
			'default' => '3.5',
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
			'default'  => '3.5',
			'default2' => '0.15',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'    => '60-65-tw500_750_e_shares',
			'default' => '0.15',
			'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
			'type'    => 'text',
		),
		//---------≥ $750

		array(
			'name'     => '60-65-tw>=750_e_e_shares',
			'name2'    => '60-65-tw>=750_e_e_shares2',
			'label'    => 'Total Wages ≥ $750',
			'default'  => '8.5',
			'default2' => '8.5',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'     => '60-65-tw>=750_e_shares',
			'default'  => '5',
			'name2'    => '60-65-tw>=750_e_shares2',
			'default2' => '5',
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
			'default' => '3.5',
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
			'default'  => '3.5',
			'default2' => '0.15',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages > $500 to < $750 <b>(a% (TW) + b (TW - $500))</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'    => '>65-tw500_750_e_shares',
			'default' => '0.15',
			'desc'    => 'Enter employee\'s share, when total wages > $500 to < $750 <b>a (TW - $500)</b>',
			'type'    => 'text',
		),
		//---------≥ $750

		array(
			'name'     => '>65-tw>=750_e_e_shares',
			'name2'    => '>65-tw>=750_e_e_shares2',
			'label'    => 'Total Wages ≥ $750',
			'default'  => '8.5',
			'default2' => '8.5',
			'desc'     => 'Enter employee\'s & employer\'s share, when total wages ≥ $750 
								  <b>[a% (OW)]* + b% (AW)</b>',
			'type'     => 'text',
			'double'   => 'double',
		),

		array(
			'name'     => '>65-tw>=750_e_shares',
			'name2'    => '>65-tw>=750_e_shares2',
			'default'  => '5',
			'default2' => '5',
			'desc'     => 'Enter employee\'s share, when total wages ≥ $750 <b>[a% (OW)]* + b% (AW)</b>',
			'type'     => 'text',
			'double'   => 'text',
		),
	);
}