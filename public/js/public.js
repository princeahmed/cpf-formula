(function ($) {

    $(document).ready(function () {
        $('#input-group').bootstrapBirthday({
            widget: {
                wrapper: {
                    tag: 'div',
                    class: 'input-group'
                },
                wrapperYear: {
                    use: true,
                    tag: 'span',
                    class: 'input-group-addon'
                },
                wrapperMonth: {
                    use: true,
                    tag: 'span',
                    class: 'input-group-addon'
                },
                wrapperDay: {
                    use: true,
                    tag: 'span',
                    class: 'input-group-addon'
                },
                selectYear: {
                    name: 'birthday[year]',
                    class: 'form-control input-sm'
                },
                selectMonth: {
                    name: 'birthday[month]',
                    class: 'form-control input-sm'
                },
                selectDay: {
                    name: 'birthday[day]',
                    class: 'form-control input-sm'
                }
            },
            dateFormat: 'littleEndian'
        });
        $('#pr_effective').bootstrapBirthday({
            widget: {
                wrapper: {
                    tag: 'div',
                    class: 'input-group'
                },
                wrapperYear: {
                    use: true,
                    tag: 'span',
                    class: 'input-group-addon'
                },
                wrapperMonth: {
                    use: true,
                    tag: 'span',
                    class: 'input-group-addon'
                },
                wrapperDay: {
                    use: true,
                    tag: 'span',
                    class: 'input-group-addon'
                },
                selectYear: {
                    name: 'pr_effective_date[year]',
                    class: 'form-control input-sm'
                },
                selectMonth: {
                    name: 'pr_effective_date[month]',
                    class: 'form-control input-sm'
                },
                selectDay: {
                    name: 'pr_effective_date[day]',
                    class: 'form-control input-sm'
                }
            },
            dateFormat: 'littleEndian'
        });

        //hide/show pre_effective date form on citizenship change
        $('#citizenship').change(function () {
            if ($(this).val() == 'SPR') {
                $('#pr_effective_date').show();
            } else {
                $('#pr_effective_date').hide();
            }
        });

        //calculate contribution
        $('#calculate-contribution').click(function () {

            var data =

                $.ajax({
                    url: $('#process_url').val(),
                    type: 'POST',
                    dataType: 'json',
                    data: $('#cpf_cal_form').serialize(),
                    success: function (response) {
                        console.log(response);
                        if (response.status == 'success') {
                            $('#employee_cpf_contribution').html(response.EmployeesShare);
                            $('#employer_cpf_contribution').html(response.EmployerShare);
                            $('#sdl').html(response.sdl);
                            $('#net_salary').html(response.netSal);
                            $('#cpf_donation_type_amount').html(response.cpf_donation);
                        }
                    }
                });
        });

    });

})(jQuery);

