<?php
function unformatCurrency($number)
{
  return preg_replace('/[^a-z0-9 ]/i', '', $number);
}

function sanitizeInput($req)
{
  $additionalDeduction = $req->get_param('additional-deduction');
  $req->set_param('additional-deduction', unformatCurrency($additionalDeduction));

  $numberOfIncomeSources = $req->get_param('number-of-income-sources');
  for ($i = 0; $i < $numberOfIncomeSources; $i++) {
    $incomeField = "income-{$i}";
    $incomeValue = (int)unformatCurrency($req->get_param($incomeField));
    $req->set_param($incomeField, $incomeValue);

    $taxPaidField = "tax-paid-{$i}";
    $taxPaidValue = (int)unformatCurrency($req->get_param($taxPaidField));
    $req->set_param($taxPaidField, $taxPaidValue);
  }

  return $req;
}

function calculate($req)
{
  $req = sanitizeInput($req);

  $result = [];

  $settlementYear = $req->get_param('settlement-year');
  $result['settlement_year'] = $settlementYear;

  $numberOfIncomeSources = $req->get_param('number-of-income-sources');
  $result['number_of_income_sources'] = $numberOfIncomeSources;

  $totalIncome = 0;
  $totalTaxPaid = 0;
  for ($i = 0; $i < $numberOfIncomeSources; $i++) {
    $totalIncome += (int)$req->get_param("income-{$i}");
    $totalTaxPaid += (int)$req->get_param("tax-paid-{$i}");
  }

  $result['total_income'] = format_currency($totalIncome);
  $result['total_tax_paid'] = format_currency($totalTaxPaid);

  $personalDeduction = 132000000;
  $result['personal_deduction'] = format_currency($personalDeduction);

  $numberOfDependents = (int)$req->get_param('number-of-dependents');
  $result['number_of_dependents'] = $numberOfDependents;
  $dependentsDeduction = 52800000 * $numberOfDependents;
  $result['dependents_deduction'] = format_currency($dependentsDeduction);

  $additionalDeduction = $req->get_param('additional-deduction');
  $result['additional_deduction'] = format_currency($additionalDeduction);

  $totalDeduction = $personalDeduction + $dependentsDeduction + $additionalDeduction;
  $result['total_deduction'] = format_currency($totalDeduction);

  $totalTaxableIncome = $totalIncome - $totalDeduction;

  if ($totalTaxableIncome <= 0) {
    $result['total_taxable_income'] = format_currency(0);
    $result['total_tax'] = format_currency(0);
    $result['tax_need_to_pay'] = format_currency(0);
    $result['late_payment_days'] = 0;
    $result['warning_late_payment'] = format_currency(0);
    $result['warning_late_payment_fine'] = ['0Đ', '0Đ', '0Đ'];
    $result['refundable_tax'] = format_currency($totalTaxPaid);

    return $result;
  }

  $result['total_taxable_income'] = format_currency($totalTaxableIncome);

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

  $result['total_tax'] = format_currency($totalTax);

  $taxNeedToPay = $totalTax - $totalTaxPaid;

  if ($taxNeedToPay > 0) {
    $currentDatetime = current_datetime()->format('Y-m-d');
    switch ($settlementYear) {
      case '2020':
        $settlementDay = '2021-05-04';
        break;
      case '2021':
        $settlementDay = '2022-05-04';
        break;
      case '2022':
        $settlementDay = '2022-04-30';
        break;
      case '2023':
        $settlementDay = '2023-05-02';
        break;
      default:
        $settlementDay = "{$settlementYear}-04-30";
    }

    $latePaymentDays = subtract_dates($currentDatetime, $settlementDay);

    $warningLatePayment = $latePaymentDays * $taxNeedToPay * 0.03 / 100;

    if ($latePaymentDays <= 5) {
      $warningLatePaymentFine = ['0Đ', '0Đ', '0Đ'];
    } elseif ($latePaymentDays <= 30) {
      $warningLatePaymentFine = ['1.000.000Đ', '1.750.000Đ', '2.500.000Đ'];
    } elseif ($latePaymentDays <= 60) {
      $warningLatePaymentFine = ['2.500.000Đ', '3.250.000Đ', '4.000.000Đ'];
    } elseif ($latePaymentDays <= 90) {
      $warningLatePaymentFine = ['4.000.000Đ', '5.750.000Đ', '7.500.000Đ'];
    } else {
      $warningLatePaymentFine = [
        format_currency($taxNeedToPay),
        format_currency($taxNeedToPay * 1.5),
        format_currency($taxNeedToPay * 3)
      ];
    }

    $refundableTax = 0;
  } else {
    $taxNeedToPay = 0;
    $latePaymentDays = 0;
    $warningLatePayment = 0;
    $warningLatePaymentFine = ['0Đ', '0Đ', '0Đ'];
    $refundableTax = $totalTaxPaid - $totalTax;
  }

  $result['tax_need_to_pay'] = format_currency($taxNeedToPay);
  $result['late_payment_days'] = $latePaymentDays;
  $result['warning_late_payment'] = format_currency($warningLatePayment);
  $result['warning_late_payment_fine'] = $warningLatePaymentFine;
  $result['refundable_tax'] = format_currency($refundableTax);

  return $result;
}

function calculate_tax($req)
{
  return json_encode([
    'success' => true,
    'data' => calculate($req)
  ]);
}

function format_currency($number)
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
    $income = format_currency($income);
    $taxPaid = $req->get_param("tax-paid-{$i}");
    $taxPaid = format_currency($taxPaid);

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

  $numberOfDependents = $calculatedResult['number_of_dependents'];
  $body .= "<li><p>Số người phụ thuộc: {$numberOfDependents}</p></li>";

  $personalDeduction = $calculatedResult['personal_deduction'];
  $body .= "<li><p>Giảm trừ cho bản thân cá nhân: {$personalDeduction}</p></li>";

  $dependentsDeduction = $calculatedResult['dependents_deduction'];
  $body .= "<li><p>Giảm cho những người phụ thuộc được giảm trừ: {$dependentsDeduction}</p></li>";

  $additionalDeduction = $calculatedResult['additional_deduction'];
  $body .= "<li><p>Tổng giảm trừ bảo hiểm, từ thiện, nhân đạo, khuyến học hưu trứ tự nguyện: {$additionalDeduction}</p></li>";

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

  $warningLatePaymentFine = $calculatedResult['warning_late_payment_fine'];
  $warningLatePaymentFineLow = $warningLatePaymentFine[0];
  $warningLatePaymentFineMedium = $warningLatePaymentFine[1];
  $warningLatePaymentFineHigh = $warningLatePaymentFine[2];

  $body .= "
  <li>
    <p>\"Cảnh báo\" có thể phát sinh tiền phạt theo quy định:</p>
    <ul>
      <li>Mức thấp nhất: $warningLatePaymentFineLow</li>
      <li>Mức trung bình: $warningLatePaymentFineMedium</li>
      <li>Mức cao nhất: $warningLatePaymentFineHigh</li>
    </ul>
  </li>";

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
