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
    font-size: 16;
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
            <option value='2019'>Trước năm 2019</option>
            <?php foreach (range(2018, 2024) as $year) {
              if ($year === 2024) {
                echo "<option value='$year' selected>$year</option>";
              } else {
                echo "<option value='$year'>$year</option>";
              }
            } ?>
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
            <input type="text" id="income-0" name="income-0" class="input text" placeholder="đ" required>
          </field>
          <field class="field required">
            <label for="tax-paid" class="label italic">Số thuế đã khấu trừ</label>
            <input type="text" id="tax-paid-0" name="tax-paid-0" class="input text" required>
          </field>
        </fieldset>
      </div>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-income" class="label">Tổng thu nhập chịu thuế</label>
          <input type="text" id="total-income" name="total-income" class="input text" readonly>
        </field>
        <field class="field required">
          <label for="number-of-dependents" class="label">Số người phụ thuộc</label>
          <select name="number-of-dependents" id="number-of-dependents" class="input select" required>
            <?php foreach (range(0, 20) as $num) {
              echo "<option value='$num'>$num</option>";
            } ?>
          </select>
        </field>
      </fieldset>
      <h3 class="title field-set-title">Các khoản giảm trừ:</h3>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="personal-deduction" class="label">Giảm trừ cho bản thân cá nhân</label>
          <input type="text" id="personal-deduction" name="personal-deduction" class="input text" readonly>
        </field>
        <field class="field">
          <label for="dependants-deduction" class="label">Giảm cho những người phụ thuộc được giảm trừ</label>
          <input type="text" id="dependants-deduction" name="dependants-deduction" class="input text" readonly>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="charity-deduction" class="label">Tổng từ thiện nhân đạo khuyến học được trừ</label>
          <input type="text" id="charity-deduction" name="charity-deduction" class="input text">
        </field>
        <field class="field">
          <label for="insurance-deduction" class="label">Tổng các khoản đóng bảo hiểm được trừ</label>
          <input type="text" id="insurance-deduction" name="insurance-deduction" class="input text">
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="pension-deduction" class="label">Tổng các khoản đóng quỹ HTTN được trừ</label>
          <input type="text" id="pension-deduction" name="pension-deduction" class="input text">
        </field>
        <field class="field">
          <label for="total-deduction" class="label">Tổng các khoản giảm trừ</label>
          <input type="text" id="total-deduction" name="total-deduction" class="input text" readonly>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-taxable-income" class="label">Tổng thu nhập tính thuế</label>
          <input type="text" id="total-taxable-income" name="total-taxable-income" class="input text" readonly>
        </field>
        <field class="field">
          <label for="total-tax" class="label">Tổng số thuế TNCN phát sinh trong kỳ</label>
          <input type="text" id="total-tax" name="total-tax" class="input text" readonly>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-tax-paid" class="label">Tổng số thuế đã nộp trong kỳ</label>
          <input type="text" id="total-tax-paid" name="total-tax-paid" class="input text" readonly>
        </field>
      </fieldset>
      <div class="footer">
        <input class="submit" type="submit" value="Thực hiện tra cứu" />
      </div>
    </form>
  </div>

  <div class="result" id="table-result">
    <div class="wrapper">
      <h2 class="section-title result-title">Kết quả</h2>
      <table class="table">
        <tbody>
          <tr class="row">
            <td class="title">Số ngày chậm quyết toán</td>
            <td class="content">
              <p><b><span id="late-payment-days"></span>&nbsp;ngày</b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">Tổng số tiền thuế phải nộp thêm trong kỳ</td>
            <td class="content">
              <p><b><span id="tax-need-to-pay"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">"Cảnh báo" phát sinh tổng số tiền chậm nộp</td>
            <td class="content">
              <p><b><span id="warning-late-payment"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">"Cảnh báo" có thể phát sinh tiền phạt theo quy định</td>
            <td class="content">
              <p>Mức thấp nhất: <b><span id="warning-late-payment-fine-0"></span></b></p>
              <p>Mức trung bình: <b><span id="warning-late-payment-fine-1"></span></b></p>
              <p>Mức cao nhất: <b><span id="warning-late-payment-fine-2"></span></b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">Tổng số tiền thuế được hoàn lại trong kỳ</td>
            <td class="content">
              <p><b><span id="refundable-tax"></span><b></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="contact-form">
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

          $('#total-income').val(result.total_income);
          $('#personal-deduction').val(result.personal_deduction);
          $('#dependants-deduction').val(result.dependants_deduction);
          $('#total-deduction').val(result.total_deduction);
          $('#total-taxable-income').val(result.total_taxable_income);
          $('#total-tax').val(result.total_tax);
          $('#total-tax-paid').val(result.total_tax_paid);

          $("#late-payment-days").html(result.late_payment_days);
          $("#tax-need-to-pay").html(result.tax_need_to_pay);
          $("#warning-late-payment").html(result.warning_late_payment);
          result.warning_late_payment_fine.forEach((fine, index) => {
            $(`#warning-late-payment-fine-${index}`).html(fine);
          })
          $("#refundable-tax").html(result.refundable_tax);

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
              <input type="text" id="income-${i}" name="income-${i}" class="input text" placeholder="đ" required>
            </field>
            <field class="field required">
              <label for="tax-paid-${i}" class="label italic">Số thuế đã khấu trừ nguồn ${i + 1}</label>
              <input type="text" id="tax-paid-${i}" name="tax-paid-${i}" class="input text" required>
            </field>
          </fieldset>`;
      }

      incomeAndTaxStatistics.html(content);
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

    function scrollToElement(element) {
      $([document.documentElement, document.body]).animate({
        scrollTop: element.offset().top - 20
      }, 500);
    }
  })
</script>

<?php get_footer(); ?>