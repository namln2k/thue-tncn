<?php

/**
 *  Functions and definitions for auxin framework
 *
 * 
 * @package    Auxin
 * @author     averta (c) 2014-2024
 * @link       http://averta.net
 */

/*-----------------------------------------------------------------------------------*/
/*  Add your custom functions here -  We recommend you to use "code-snippets" plugin instead
/*  https://wordpress.org/plugins/code-snippets/
/*-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/*  Init theme framework
/*-----------------------------------------------------------------------------------*/
update_site_option('phlox-pro_license', ['token' => 'activated']);
set_transient('auxin_check_token_validation_status', 1);
add_action('tgmpa_register', function () {
    $tgmpa_instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
    foreach ($tgmpa_instance->plugins as $slug => $plugin) {
        if ($plugin['slug'] === 'auxin-elements') {
            $tgmpa_instance->plugins[$plugin['slug']]['source'] = get_template_directory() . '/plugins/auxin-elements.zip';
            $tgmpa_instance->plugins[$plugin['slug']]['source_type'] = 'external';
        }
        if ($plugin['slug'] === 'dzs-zoomsounds') {
            unset($tgmpa_instance->plugins[$plugin['slug']]);
        }
    }
}, 30);

add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/calculate-tax', array(
        'methods' => 'POST',
        'callback' => 'calculate_tax',
    ));
});

function calculate_tax($req)
{
    $numberOfIncomeSources = $req->get_param('number-of-income-sources');

    $totalIncome = 0;
    $totalTaxPaid = 0;
    for ($i = 0; $i < $numberOfIncomeSources; $i++) {
        $totalIncome += (int)$req->get_param("income-{$i}");
        $totalTaxPaid += (int)$req->get_param("tax-paid-{$i}");
    }

    $personalDeduction = 132000000;

    $numberOfDependents = (int)$req->get_param('number-of-dependents');
    $dependantsDeduction = 52800000 * $numberOfDependents;

    $charityDeduction = $req->get_param('charity-deduction');

    $insuranceDeduction = $req->get_param('insurance-deduction');

    $pensionDeduction = $req->get_param('pension-deduction');

    $totalDeduction = $personalDeduction + $dependantsDeduction + $charityDeduction + $insuranceDeduction + $pensionDeduction;

    $totalTaxableIncome = $totalIncome - $totalDeduction;

    $totalTax = 0;
    $level1FullTax = 60000000 * 5 / 100;
    $level2FullTax = $level1FullTax + (120000000 - 60000000) * 10 / 100;
    $level3FullTax = $level2FullTax + (216000000 - 120000000) * 15 / 100;
    $level4FullTax = $level3FullTax + (384000000 - 216000000) * 20 / 100;
    $level5FullTax = $level4FullTax + (624000000 - 384000000) * 25 / 100;
    $level6FullTax = $level5FullTax + (960000000 - 624000000) * 30 / 100;
    if ($totalTaxableIncome <= 60000000) {
        $totalTax += $totalTaxableIncome * 5 / 100;
    } elseif ($totalTaxableIncome <= 120000000) {
        $totalTax += $level1FullTax;
        $totalTax += ($totalTaxableIncome - 60000000) * 10 / 100;
    } else if ($totalTaxableIncome <= 216000000) {
        $totalTax += $level2FullTax;
        $totalTax += ($totalTaxableIncome - 120000000) * 15 / 100;
    } else if ($totalTaxableIncome <= 384000000) {
        $totalTax += $level3FullTax;
        $totalTax += ($totalTaxableIncome - 216000000) * 20 / 100;
    } else if ($totalTaxableIncome <= 624000000) {
        $totalTax += $level4FullTax;
        $totalTax += ($totalTaxableIncome - 384000000) * 25 / 100;
    } else if ($totalTaxableIncome <= 960000000) {
        $totalTax += $level5FullTax;
        $totalTax += ($totalTaxableIncome - 624000000) * 30 / 100;
    } else {
        $totalTax += $level6FullTax;
        $totalTax += ($totalTaxableIncome - 960000000) * 35 / 100;
    }

    if ($totalTax - $totalTaxPaid > 0) {
        $currentDatetime = current_datetime()->format('Y-m-d');
        $settlementYear = $req->get_param('settlement-year');

        switch ($settlementYear) {
            case '2020':
                $settlementYear = '2021-05-04';
                break;
            case '2021':
                $settlementYear = '2022-05-04';
                break;
            case '2022':
                $settlementYear = '2022-04-30';
                break;
            case '2023':
                $settlementYear = '2023-05-02';
                break;
            default:
                $settlementYear = "{$settlementYear}-04-30";
        }

        $latePaymentDays = subtract_dates($currentDatetime, $settlementYear);
        $taxNeedToPay = $totalTax - $totalTaxPaid;

        $warningLatePayment = $latePaymentDays * $taxNeedToPay * 0.03 / 100;

        if ($latePaymentDays <= 5) {
            $warningLatePaymentFine = [0, 0, 0];
        } elseif ($latePaymentDays <= 30) {
            $warningLatePaymentFine = ['1.000.000', '1.750.000', '2.500.000'];
        } elseif ($latePaymentDays <= 60) {
            $warningLatePaymentFine = ['2.500.000', '3.250.000', '4.000.000'];
        } elseif ($latePaymentDays <= 90) {
            $warningLatePaymentFine = ['4.000.000', '5.750.000', '7.500.000'];
        } else {
            $warningLatePaymentFine = [
                thousand_seperate($taxNeedToPay), 
                thousand_seperate($taxNeedToPay * 1.5), 
                thousand_seperate($taxNeedToPay * 3)
            ];
        }

        $refundableTax = 0;
    } else {
        $latePaymentDays = 0;
        $taxNeedToPay = 0;
        $refundableTax = $totalTaxPaid - $totalTax;
    }

    return json_encode([
        'success' => true,
        'data' => [
            'late_payment_days' => $latePaymentDays,
            'tax_need_to_pay' => thousand_seperate($taxNeedToPay),
            'warning_late_payment' => thousand_seperate($warningLatePayment),
            'warning_late_payment_fine' => $warningLatePaymentFine,
            'refundable_tax' => $refundableTax,
            'total_income' => thousand_seperate($totalIncome) . 'Đ',
            'personal_deduction' => thousand_seperate($personalDeduction) . 'Đ',
            'dependants_deduction' => thousand_seperate($dependantsDeduction) . 'Đ',
            'total_deduction' => thousand_seperate($totalDeduction) . 'Đ',
            'total_taxable_income' => thousand_seperate($totalTaxableIncome) . 'Đ',
            'total_tax' => thousand_seperate($totalTax) . 'Đ',
            'total_tax_paid' => thousand_seperate($totalTaxPaid) . 'Đ'
        ]
    ]);
}

function thousand_seperate($number)
{
    return number_format($number, 0, '.', ',');
}

function subtract_dates($date1, $date2)
{
    $datetime1 = strtotime($date1);
    $datetime2 = strtotime($date2);

    $secs = $datetime1 - $datetime2;
    $days = $secs / 86400;

    return $days;
}

add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/contact-calculate-tax', array(
        'methods' => 'POST',
        'callback' => 'contact_calculate_tax',
    ));
});

function contact_calculate_tax($req)
{
    $mobile = $req->get_param('mobile-number');

    $to = trim(get_bloginfo('admin_email'));
    $subject = "Có khách hàng liên hệ điền phiếu tính thuế";
    $body = "";
    $body .= "
    <div>
        <ul>
            <li><p>Số điện thoại: {$mobile}</p></li>";

    $settlementYear = $req->get_param('settlement-year');
    $body .= "
    <li><p>Năm quyết toán: {$settlementYear}</p></li>";

    $numberOfIncomeSources = $req->get_param('number-of-income-sources');
    $body .= "
    <li>
        <p>Các nguồn thu nhập</p>
        <ul>";

    for ($i = 0; $i < $numberOfIncomeSources; $i++) {
        $index = $i + 1;
        $totalIncome = $req->get_param("income-{$i}");
        $totalIncome = thousand_seperate($totalIncome);
        $totalTaxPaid = $req->get_param("tax-paid-{$i}");
        $totalTaxPaid = thousand_seperate($totalTaxPaid);

        $body .= "
            <li><p>Nguồn {$index}:</p>
                <ul>
                    <li>Tổng thu nhập: {$totalIncome}</li>
                    <li>Số thuế đã khấu trừ: {$totalTaxPaid}</li>
                </ul>
            </li>";
    }

    $body .= "
        </ul>
    </li>";


    $charityDeduction = $req->get_param('charity-deduction');
    $charityDeduction = thousand_seperate($charityDeduction);
    $body .= "<li><p>Tổng từ thiện nhân đạo khuyến học được trừ: {$charityDeduction}</p></li>";


    $insuranceDeduction = $req->get_param('insurance-deduction');
    $insuranceDeduction = thousand_seperate($insuranceDeduction);
    $body .= "<li><p>Tổng các khoản đóng bảo hiểm được trừ: {$insuranceDeduction}</p></li>";


    $pensionDeduction = $req->get_param('pension-deduction');
    $pensionDeduction = thousand_seperate($pensionDeduction);
    $body .= "<li><p>Tổng các khoản đóng quỹ HTTN được trừ: {$pensionDeduction}</p></li>";

    $body .= "
    </ul>
        </div>";

    $headers = array("Content-Type: text/html; charset=UTF-8", "From: My Site Name <support@example.com>");

    wp_mail($to, $subject, $body, $headers);

    if (validate_mobile_number($mobile)) {
        return json_encode([
            'success' => true,
            'message' => 'Cám ơn bạn đã liên hệ, chúng tôi sẽ liên lạc lại bạn sớm nhất.'
        ]);
    }

    return json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập số điện thoại.'
    ]);
}

function validate_mobile_number($mobile)
{
    return preg_match('/^\d+$/', $mobile);
}

require('auxin/auxin-include/auxin.php');
/*-----------------------------------------------------------------------------------*/
