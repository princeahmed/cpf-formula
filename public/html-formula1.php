<?php

// exit if file is called directly
defined( 'ABSPATH' ) || exit();

$processUrl = CPF_FORMULA_URL . '/includes/cpf-contribution-process.php';

?>
<form class="form-horizontal" role="form" id="cpf_cal_form">
    <input type="hidden" name="process_url" id="process_url" value="<?php echo $processUrl; ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-8">
                    <label for="usr">Your Payroll Month and Year:</label>
                    <select class="form-control" id="payroll_my" name="payroll_my">
                        <option value="">Select</option>
						<?php
						for ( $y = date( 'Y' ); $y >= 2016; $y -- ) {

							for ( $m = 1; $m <= 12; $m ++ ) {
								$month = date( 'M Y', mktime( 0, 0, 0, $m, 1, $y ) );

								printf( '<option value="%s">%1$s</option>', $month );
							}
						} ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-10">
                    <label for="pwd">Birthday:</label>
                    <input type="text" class="form-control" name="birthday" id="input-group"/>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-8">
                    <label for="usr">Citizenship:</label>
                    <select class="form-control" id="citizenship" name="citizenship">
                        <option value="SC" selected>Singapore Citizen</option>
                        <option value="SPR">Singapore PR</option>
                    </select>
                </div>
            </div>

            <div class="form-group row" id="pr_effective_date">
                <div class="col-md-10">
                    <label for="pwd">PR Effective Date:</label>
                    <input type="text" class="form-control" name="pr_effective_date" id="pr_effective"/>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-8">
                    <label for="usr">CPF Donation Type:</label> <select class="form-control" name="CPFDT" id="CPFDT">
                        <option value="" selected>N/A</option>
                        <option value="CDAC">CDAC</option>
                        <option value="SINDA">SINDA</option>
                        <option value="ECF">ECF</option>
                        <option value="MBMF">MBMF</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="usr">Salary (Ordinary Wage):</label>
                        <input type="text" class="form-control" id="ow" name="ow">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="usr">Bonus/Comission (Add. Wage):</label>
                        <input type="text" class="form-control" id="aw" name="aw">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" id="calculate-contribution" class="btn btn-primary">Calculate CPF Contribution</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="ttable-info align-right">
                        <thead>
                        <tr>
                            <th class="bold">Description</th>
                            <th class="bold">Amount ($)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="highlight">
                            <td>CPF (Employee)</td>
                            <td id="employee_cpf_contribution"></td>
                        </tr>
                        <tr class="highlight">
                            <td>CPF (Employer)</td>
                            <td id="employer_cpf_contribution"></td>
                        </tr>
                        <tr>
                            <td>CPF (Employee) Donation</td>
                            <td id="cpf_donation_type_amount"></td>
                        </tr>
                        <tr>
                            <td>SDL</td>
                            <td id="sdl"></td>
                        </tr>
                        <tr class="total">
                            <td class="bold">Net Salary</td>
                            <td class="bold" id="net_salary"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
