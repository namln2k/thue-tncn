<?php

/**
 * Template Name: Tax Form
 */
?>

<?php get_header(); ?>

<?php the_content(); ?>

<style>
  .tax-form {
    max-width: 1024px;
    margin-left: auto;
    margin-right: auto;
  }

  .tax-form>div+div {
    margin-top: 100px;
  }

  .tax-form .wrapper {
    width: 100%;
  }

  .tax-form .wrapper {
    border-style: solid;
    border-width: 8px 0px 0px 0px;
    border-color: var(--e-global-color-secondary);
    border-radius: 8px;
    box-shadow: 0px 0px 10px 0px rgba(226, 24, 24, 0.5);
    margin-left: auto;
    margin-right: auto;
    padding: 20px 100px;
  }

  .tax-form .section-title {
    color: #0D2556;
  }

  .tax-form .italic {
    font-style: italic;
  }

  .tax-form .section-title {
    font-size: 28px;
    font-weight: 700;
    line-height: 50px;
    text-align: center;
  }

  .tax-form .fieldset {
    border: none;
    display: flex;
    gap: 30px;
  }

  .tax-form .field .input {
    font-size: 14px;
    width: 370px;
    padding: 11px 14px;
    border-radius: 6px;
    background: #F7F7F7;
  }

  .tax-form .field .label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
  }

  .tax-form .field.required .label:after {
    content: " *";
    color: red;
  }

  .tax-form .note {
    font-size: 13px;
    font-style: italic;
    font-weight: 400;
    padding-left: 30px;
  }

  .tax-form .input.select {
    appearance: none;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgiIGhlaWdodD0iMjgiIHZpZXdCb3g9IjAgMCAyOCAyOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI4IiBoZWlnaHQ9IjI4IiByeD0iNSIgZmlsbD0iI0U2RTZFNiIvPgo8cGF0aCBkPSJNMTQuMzg4OSAxOC41MTg5QzE0LjE4ODcgMTguNzY2NSAxMy44MTEzIDE4Ljc2NjUgMTMuNjExMSAxOC41MTg5TDguNTk2MDIgMTIuMzE0M0M4LjMzMTczIDExLjk4NzMgOC41NjQ0NSAxMS41IDguOTg0ODggMTEuNUwxOS4wMTUxIDExLjVDMTkuNDM1NSAxMS41IDE5LjY2ODMgMTEuOTg3MyAxOS40MDQgMTIuMzE0M0wxNC4zODg5IDE4LjUxODlaIiBmaWxsPSIjNUM1QzVDIi8+Cjwvc3ZnPgo=");
    background-repeat: no-repeat;
    background-position: right 6px top 50%;
    background-size: 28px auto;
  }

  .tax-form .field-set-title {
    font-size: 16px;
    font-weight: 700;
    padding-left: 30px;
  }

  .tax-form .input[readonly] {
    background-color: var(--e-global-color-text);
  }

  .tax-form .footer {
    display: flex;
    margin-top: 20px;
  }

  .tax-form .submit,
  .tax-form .submit:hover {
    border-radius: 8px;
    background-color: var(--e-global-color-secondary);
    color: white;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    text-transform: capitalize;
    padding: 20px 83px;
    margin-left: auto;
    margin-right: auto;
  }

  .result td {
    border: 2px solid #EBEBEB;
    border-collapse: collapse;
  }

  .result .result-title {
    text-transform: uppercase;
  }

  .result .row .title {
    background-color: #F2F7FF;
    color: #001134;
    font-weight: 500;
  }

  .result .row .title .main {
    font-size: 16px;
  }

  .result .row .title .subtitle {
    font-size: 14px;
  }

  #table-result {
    display: none;
  }

  .result .contact-form {
    color: #FFFFFF;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 32px 100px;
    background: var(--e-global-color-secondary);
    border-radius: 10px;
  }

  .result .contact-form .form {
    display: flex;
    margin-bottom: 12px;
    flex-direction: column;
    gap: 12px;
  }

  .result .contact-form .form .label {
    color: #FFF;
  }

  .result .contact-form p {
    font-size: 16px;
    font-style: italic;
    font-weight: 600;
    text-align: center;
  }

  .result .contact-form .input,
  .result .contact-form textarea {
    font-size: 16px;
    border-radius: 8px;
    background-color: #FFFFFF;
    color: #5C5C5C;
    padding: 11px 14px;
    border: none;
    box-shadow: none;
    outline: none;
  }

  .result .contact-form textarea {
    width: 770px;
    max-width: 100%;
  }

  .result .contact-form input:focus {
    border: none;
    -webkit-box-shadow: none;
    box-shadow: none;
    outline: none;
  }

  .result .contact-form input[type=submit] {
    background-color: #0D2556;
    border-radius: 8px;
    color: #FFF;
  }

  .result .contact-form .contact-result {
    color: #ff0;
    padding: 0;
    margin: 0;
  }

  .tax-form .fieldset .field {
    display: flex;
    flex-direction: column;
    justify-content: end;
  }

  @media only screen and (max-width: 1024px) {
    .tax-form .wrapper {
      padding: 20px;
    }

    .tax-form .fieldset {
      flex-direction: column;
    }

    .tax-form .field .input {
      max-width: none;
      width: 100%;
    }

    .result .contact-form {
      padding: 28px 20px;
    }

    .result .contact-form .title {
      text-transform: uppercase;
    }

    .result .contact-form textarea {
      width: 100%;
    }
  }
</style>

<div class="tax-form">
  <div class="wrapper">
    <h2 class="section-title form-title">Bảng tính thử thuế thu nhập cá nhân</h2>
    <form action="/?rest_route=/api/v1/calculate-tax" method="POST" class="form" id="tax-form">
      <fieldset class="fieldset row">
        <field class="field required">
          <label for="settlement-year" class="label">Năm quyết toán</label>
          <select name="settlement-year" id="settlement-year" class="input select" value=2024 required>
            <?php foreach (array_reverse(range(2018, 2024)) as $year) {
              if ($year === 2024) {
                echo "<option value='$year' selected>$year</option>";
              } else {
                echo "<option value='$year'>$year</option>";
              }
            } ?>
            <option value='2019'>Trước năm 2019</option>
          </select>
        </field>
        <field class="field required">
          <label for="number-of-income-sources" class="label">Số nguồn thu nhập</label>
          <select name="number-of-income-sources" id="number-of-income-sources" class="input select" required>
            <?php foreach (range(1, 10) as $num) {
              echo "<option value='$num'>$num</option>";
            } ?>
          </select>
        </field>
      </fieldset>
      <p class="note">Ghi chú: năm quyết toán là bắt buộc để làm cơ sở tính số thuế chậm nộp, bị phạt</p>
      <h3 class="title field-set-title">Thống kê thu nhập và nộp thuế thu nhập cá nhân:</h3>
      <div class="income-and-tax-statistics" id="income-and-tax-statistics">
        <fieldset class="fieldset row">
          <field class="field required">
            <label for="income-0" class="label">Tổng thu nhập</label>
            <input type="text" id="income-0" name="income-0" class="input text currency" placeholder="đ" required>
          </field>
          <field class="field required">
            <label for="tax-paid" class="label italic">Số thuế đã khấu trừ</label>
            <input type="text" id="tax-paid-0" name="tax-paid-0" class="input text currency" required>
          </field>
        </fieldset>
      </div>
      <fieldset class="fieldset row">
        <field class="field required">
          <label for="number-of-dependents" class="label">Số người phụ thuộc</label>
          <select name="number-of-dependents" id="number-of-dependents" class="input select" required>
            <?php foreach (range(0, 20) as $num) {
              echo "<option value='$num'>$num</option>";
            } ?>
          </select>
        </field>
        <field class="field">
          <label for="additional-deduction" class="label">Tổng giảm trừ bảo hiểm, từ thiện, nhân đạo, khuyến học hưu trứ tự nguyện</label>
          <input type="text" id="additional-deduction" name="additional-deduction" class="input text currency">
        </field>
      </fieldset>
      <div class="footer">
        <input class="submit" type="submit" value="Thực hiện tra cứu" />
      </div>
    </form>
  </div>

  <div class="result" id="table-result">
    <div class="wrapper">
      <h2 class="section-title result-title">Kết quả tính thử thuế thu nhập cá nhân</h2>
      <table class="table">
        <tbody>
          <tr class="row">
            <td class="title">
              <p class="main">Năm quyết toán</p>
            </td>
            <td class="content">
              <p><b><span id="result-settlement-year"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Số nguồn thu nhập</p>
            </td>
            <td class="content">
              <p><b><span id="result-number-of-income-sources"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Tổng thu nhập chịu thuế</p>
            </td>
            <td class="content">
              <p><b><span id="result-total-income"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Số người phụ thuộc</p>
            </td>
            <td class="content">
              <p><b><span id="result-number-of-dependents"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Tổng các khoản giảm trừ</p>
              <p class="subtitle">Giảm trừ cho bản thân cá nhân</p>
              <p class="subtitle">Giảm cho những người phụ thuộc giảm trừ</p>
              <p class="subtitle">Tổng giảm trừ bảo hiểm, từ thiện, nhân đạo, khuyến học, đóng quỹ hưu trí tự nguyện</p>
            </td>
            <td class="content">
              <p><b><span id="result-total-deduction"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Tổng thu nhập tính thuế</p>
            </td>
            <td class="content">
              <p><b><span id="result-total-taxable-income"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Tổng số thuế thu nhập cá nhân phát sinh trong kỳ</p>
            </td>
            <td class="content">
              <p><b><span id="result-total-tax"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Tổng số thuế phải nộp thêm trong kỳ</p>
            </td>
            <td class="content">
              <p><b><span id="result-tax-need-to-pay"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Số ngày chậm quyết toán</p>
            </td>
            <td class="content">
              <p><b><span id="result-late-payment-days"></span>&nbsp;ngày</b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">"Cảnh báo" phát sinh tổng số tiền chậm nộp</p>
            </td>
            <td class="content">
              <p><b><span id="result-warning-late-payment"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">"Cảnh báo" có thể phát sinh tiền phạt theo quy định</p>
            </td>
            <td class="content">
              <p>Mức thấp nhất: <b><span id="result-warning-late-payment-fine-0"></span></b></p>
              <p>Mức trung bình: <b><span id="result-warning-late-payment-fine-1"></span></b></p>
              <p>Mức cao nhất: <b><span id="result-warning-late-payment-fine-2"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">
              <p class="main">Tổng số tiền thuế được hoàn lại trong kỳ</p>
            </td>
            <td class="content">
              <p><b><span id="result-refundable-tax"></span><b></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="contact-form">
      <h1 class="title">Khuyến cáo</h1>
      <p>Các thông tin nêu trên chỉ là dự tính. Nếu bạn cần làm rõ thêm, hoặc cần tư vấn đề giảm thiểu vi phạm, giảm mức phạt theo luật, tối ưu tăng mức thuế được hoàn theo luật, vui lòng đăng ký tư vấn.</p>
      <form action="/?rest_route=/api/v1/contact-calculate-tax" method="POST" class="form" id="contact-form">
        <fieldset class="row fieldset">
          <field class="field required">
            <label for="customer-name" class="label">Họ tên</label>
            <input type="text" id="customer-name" name="customer-name" class="input" required>
          </field>
          <field class="field required">
            <label for="mobile-number" class="label">Số điện thoại</label>
            <input size="40" maxlength="400" type="tel" name="mobile-number" class="input" required>
          </field>
        </fieldset>
        <fieldset class="row fieldset">
          <field class="field">
            <label for="customer-issue" class="label">Vấn đề</label>
            <textarea id="customer-issue" name="customer-issue" rows=5></textarea>
          </field>
        </fieldset>
        <input class="submit" type="submit" value="Gửi thông tin" />
      </form>
      <div class="contact-result" aria-hidden="true" id="contact-result"></div>
    </div>
  </div>
</div>

<script>
  jQuery(document).ready(function($) {
    const taxForm = $("#tax-form");

    taxForm.submit((event) => {
      event.preventDefault();

      $.ajax({
        url: taxForm.attr('action'),
        type: 'POST',
        data: taxForm.serialize(),
        success: function(response) {
          const result = JSON.parse(response).data;

          $('#additional-deduction').val(result.additional_deduction);

          $('#result-settlement-year').html(result.settlement_year);
          $('#result-number-of-income-sources').html(result.number_of_income_sources);
          $('#result-number-of-dependents').html(result.number_of_dependents);
          $('#result-total-income').html(result.total_income);
          $('#result-total-deduction').html(result.total_deduction);
          $('#result-total-taxable-income').html(result.total_taxable_income);
          $('#result-total-tax').html(result.total_tax);

          $("#result-late-payment-days").html(result.late_payment_days);
          $("#result-tax-need-to-pay").html(result.tax_need_to_pay);
          $("#result-warning-late-payment").html(result.warning_late_payment);
          result.warning_late_payment_fine.forEach((fine, index) => {
            $(`#result-warning-late-payment-fine-${index}`).html(fine);
          })
          $("#result-refundable-tax").html(result.refundable_tax);

          const tableResult = $("#table-result");
          tableResult.css("display", "block");
          scrollToElement(tableResult);
        },
        error: function(response) {}
      });
    })

    const incomeAndTaxStatistics = $('#income-and-tax-statistics');

    const numberOfIncomeSources = $('#number-of-income-sources');

    numberOfIncomeSources.on("change", function onNumberOfIncomeSourcesChange() {
      const numberOfInputs = parseInt(this.value);

      let content = '';
      for (let i = 0; i < numberOfInputs; i++) {
        content +=
          `<fieldset class="fieldset row">
            <field class="field required">
              <label for="income-${i}" class="label">Tổng thu nhập nguồn ${i + 1}</label>
              <input type="text" id="income-${i}" name="income-${i}" class="input text currency" placeholder="đ" required>
            </field>
            <field class="field required">
              <label for="tax-paid-${i}" class="label italic">Số thuế đã khấu trừ nguồn ${i + 1}</label>
              <input type="text" id="tax-paid-${i}" name="tax-paid-${i}" class="input text currency" placeholder="đ" required>
            </field>
          </fieldset>`;
      }

      incomeAndTaxStatistics.html(content);

      initThousandSeparators();
    });

    const contactForm = $("#contact-form");

    contactForm.submit((event) => {
      event.preventDefault();
      const contactResult = $("#contact-result");

      $.ajax({
        url: contactForm.attr('action'),
        type: 'POST',
        data: $.merge(taxForm, contactForm).serialize(),
        success: function(response) {
          const result = JSON.parse(response);

          if (result.message) {
            contactResult.text(result.message);
          } else {
            contactResult.text('Đã có lỗi xảy ra, xin vui lòng thử lại!');
          }
        },
        error: function(response) {
          contactResult.text('Đã có lỗi xảy ra, xin vui lòng thử lại!')
        }
      });
    })

    function initThousandSeparators() {
      const currencyInputs = document.querySelectorAll('#tax-form input.currency');
      for (var i = 0, element; element = currencyInputs[i]; i++) {
        initThousandSeparator(element);
      }
    }

    function initThousandSeparator(element) {
      element.addEventListener('keyup', function() {
        var val = this.value;
        val = val.replace(/[^0-9\.]/g, '');

        if (val != "") {
          valArr = val.split('.');
          valArr[0] = (parseInt(valArr[0], 10)).toLocaleString();
          val = valArr.join('.');
        }

        this.value = val;
      });
    }

    function scrollToElement(element) {
      $([document.documentElement, document.body]).animate({
        scrollTop: element.offset().top - 20
      }, 500);
    }
  })
</script>

<?php get_footer(); ?>