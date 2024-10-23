<?php
function calculate($req)
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
  $dependentsDeduction = 52800000 * $numberOfDependents;

  $charityDeduction = $req->get_param('charity-deduction');

  $insuranceDeduction = $req->get_param('insurance-deduction');

  $pensionDeduction = $req->get_param('pension-deduction');

  $totalDeduction = $personalDeduction + $dependentsDeduction + $charityDeduction + $insuranceDeduction + $pensionDeduction;

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

  return [
    'late_payment_days' => $latePaymentDays,
    'tax_need_to_pay' => thousand_seperate($taxNeedToPay),
    'warning_late_payment' => thousand_seperate($warningLatePayment),
    'warning_late_payment_fine' => $warningLatePaymentFine,
    'refundable_tax' => thousand_seperate($refundableTax),
    'total_income' => thousand_seperate($totalIncome),
    'personal_deduction' => thousand_seperate($personalDeduction),
    'dependents_deduction' => thousand_seperate($dependentsDeduction),
    'total_deduction' => thousand_seperate($totalDeduction),
    'total_taxable_income' => thousand_seperate($totalTaxableIncome),
    'total_tax' => thousand_seperate($totalTax),
    'total_tax_paid' => thousand_seperate($totalTaxPaid)
  ];
}

function calculate_tax($req)
{
  return json_encode([
    'success' => true,
    'data' => calculate($req)
  ]);
}

function thousand_seperate($number)
{
  return number_format((int)$number, 0, '.', ',') . 'Đ';
}

function subtract_dates($date1, $date2)
{
  $datetime1 = strtotime($date1);
  $datetime2 = strtotime($date2);

  $secs = $datetime1 - $datetime2;
  $days = $secs / 86400;

  return $days;
}

function contact_calculate_tax($req)
{
  $mobile = $req->get_param('mobile-number');

  // $to = trim(get_bloginfo('admin_email'));
  $to = "namln2aug2k@gmail.com";
  $subject = "Có khách hàng liên hệ điền phiếu tính thuế";
  $body = "";
  $body .= "
    <div>
        <ul>
            <li><p>Số điện thoại: {$mobile}</p></li>";

  $customerName = $req->get_param('customer-name');
  $body .= "<li><p>Họ tên: {$customerName}</p></li>";

  $customerIssue = $req->get_param('customer-issue');
  $body .= "<li><p>Vấn đề: {$customerIssue}</p></li>";

  $settlementYear = $req->get_param('settlement-year');
  $body .= "<li><p>Năm quyết toán: {$settlementYear}</p></li>";

  $numberOfIncomeSources = $req->get_param('number-of-income-sources');
  $body .= "
    <li>
        <p>Các nguồn thu nhập</p>
        <ul>";

  for ($i = 0; $i < $numberOfIncomeSources; $i++) {
    $index = $i + 1;
    $income = $req->get_param("income-{$i}");
    $income = thousand_seperate($income);
    $taxPaid = $req->get_param("tax-paid-{$i}");
    $taxPaid = thousand_seperate($taxPaid);

    $body .= "
            <li><p>Nguồn {$index}:</p>
                <ul>
                    <li>Tổng thu nhập: {$income}</li>
                    <li>Số thuế đã khấu trừ: {$taxPaid}</li>
                </ul>
            </li>";
  }

  $body .= "
        </ul>
    </li>";

  $calculatedResult = calculate($req);

  $totalIncome = $calculatedResult['total_income'];
  $body .= "<li><p>Tổng thu nhập chịu thuế: {$totalIncome}</p></li>";

  $numberOfDependents = $req->get_param('number-of-dependents');
  $body .= "<li><p>Số người phụ thuộc: {$numberOfDependents}</p></li>";

  $personalDeduction = $calculatedResult['personal_deduction'];
  $body .= "<li><p>Giảm trừ cho bản thân cá nhân: {$personalDeduction}</p></li>";

  $dependentsDeduction = $calculatedResult['dependents_deduction'];
  $body .= "<li><p>Giảm cho những người phụ thuộc được giảm trừ: {$dependentsDeduction}</p></li>";

  $charityDeduction = $req->get_param('charity-deduction');
  $charityDeduction = thousand_seperate($charityDeduction);
  $body .= "<li><p>Tổng từ thiện nhân đạo khuyến học được trừ: {$charityDeduction}</p></li>";

  $insuranceDeduction = $req->get_param('insurance-deduction');
  $insuranceDeduction = thousand_seperate($insuranceDeduction);
  $body .= "<li><p>Tổng các khoản đóng bảo hiểm được trừ: {$insuranceDeduction}</p></li>";

  $pensionDeduction = $req->get_param('pension-deduction');
  $pensionDeduction = thousand_seperate($pensionDeduction);
  $body .= "<li><p>Tổng các khoản đóng quỹ HTTN được trừ: {$pensionDeduction}</p></li>";

  $totalDeduction = $calculatedResult['total_deduction'];
  $body .= "<li><p>Tổng các khoản giảm trừ: {$totalDeduction}</p></li>";

  $totalTaxableIncome = $calculatedResult['total_taxable_income'];
  $body .= "<li><p>Tổng thu nhập tính thuế: {$totalTaxableIncome}</p></li>";

  $totalTax = $calculatedResult['total_tax'];
  $body .= "<li><p>Tổng số thuế TNCN phát sinh trong kỳ: {$totalTax}</p></li>";

  $totalTaxPaid = $calculatedResult['total_tax_paid'];
  $body .= "<li><p>Tổng số thuế đã nộp trong kỳ: {$totalTaxPaid}</p></li>";

  $latePaymentDays = $calculatedResult['late_payment_days'];
  $body .= "<li><p>Số ngày chậm quyết toán: {$latePaymentDays}</p></li>";

  $taxNeedToPay = $calculatedResult['tax_need_to_pay'];
  $body .= "<li><p>Tổng số tiền thuế phải nộp thêm trong kỳ: {$taxNeedToPay}</p></li>";

  $warningLatePayment = $calculatedResult['warning_late_payment'];
  $body .= "<li><p>\"Cảnh báo\" phát sinh tổng số tiền chậm nộp: {$warningLatePayment}</p></li>";

  $warningLatePaymentFine = implode(" / ", $calculatedResult['warning_late_payment_fine']);
  $body .= "<li><p>\"Cảnh báo\" có thể phát sinh tiền phạt theo quy định: {$warningLatePaymentFine}</p></li>";

  $refundableTax = $calculatedResult['refundable_tax'];
  $body .= "<li><p>Tổng số tiền thuế được hoàn lại trong kỳ: {$refundableTax}</p></li>";

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
