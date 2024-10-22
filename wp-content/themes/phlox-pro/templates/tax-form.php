<?php

/**
 * Template Name: Tax Form
 */
?>

<?php get_header(); ?>

<?php the_content(); ?>

<style>
  .tax-form {
    width: 1024px;
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

  .tax-form .input[disabled] {
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
  }

  .result .contact-form p {
    font-size: 16px;
    font-style: italic;
    font-weight: 600;
    text-align: center;
  }

  .result .contact-form input[type=tel] {
    font-size: 16px;
    border-radius: 8px;
    background-color: #FFFFFF;
    color: #5C5C5C;
    padding: 13px 0px 13px 22px;
    border: none;
    -webkit-box-shadow: none;
    box-shadow: none;
    outline: none;
  }

  .result .contact-form input[type=tel]:focus {
    border: none;
    -webkit-box-shadow: none;
    box-shadow: none;
    outline: none;
  }

  .result .contact-form input[type=tel]::placeholder {
    font-style: italic;
  }

  .result .contact-form button[type=submit] {
    background-color: #0D2556;
    position: relative;
    margin: 0;
    width: 54px;
    border-radius: 8px;
    height: 46px;
    padding: 0;
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
  }
</style>

<div class="tax-form">
  <div class="wrapper">
    <h2 class="section-title form-title">Bảng tính thử thuế thu nhập cá nhân</h2>
    <form action="/?rest_route=/api/v1/calculate-tax" method="POST" class="form" id="tax-form">
      <fieldset class="fieldset row">
        <field class="field required">
          <label for="settlement-year" class="label">Năm quyết toán</label>
          <select name="settlement-year" id="settlement-year" class="input select" required>
            <?php foreach (range(2010, 2024) as $year) {
              echo "<option value='$year'>$year</option>";
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
          <input type="text" id="total-income" name="total-income" class="input text" disabled>
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
          <input type="text" id="personal-deduction" name="personal-deduction" class="input text" disabled>
        </field>
        <field class="field">
          <label for="dependants-deduction" class="label">Giảm cho những người phụ thuộc được giảm trừ</label>
          <input type="text" id="dependants-deduction" name="dependants-deduction" class="input text" disabled>
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
          <input type="text" id="total-deduction" name="total-deduction" class="input text" disabled>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-taxable-income" class="label">Tổng thu nhập tính thuế</label>
          <input type="text" id="total-taxable-income" name=" name=" pension-deduction"" class="input text" disabled>
        </field>
        <field class="field">
          <label for="total-tax" class="label">Tổng số thuế TNCN phát sinh trong kỳ</label>
          <input type="text" id="total-tax" name="total-tax" class="input text" disabled>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-tax-paid" class="label">Tổng số thuế đã nộp trong kỳ</label>
          <input type="text" id="total-tax-paid" name="pension-deduction" class="input text" disabled>
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
              <p><b><span id="tax-need-to-pay"></span>Đ</b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">"Cảnh báo" phát sinh tổng số tiền chậm nộp</td>
            <td class="content">
              <p><b><span id="warning-late-payment"></span>Đ</b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">"Cảnh báo" có thể phát sinh tiền phạt theo quy định</td>
            <td class="content">
              <p>Mức thấp nhất: <b><span id="warning-late-payment-fine-0"></span>Đ</b></p>
              <p>Mức trung bình: <b><span id="warning-late-payment-fine-1"></span>Đ</b></p>
              <p>Mức cao nhất: <b><span id="warning-late-payment-fine-2"></span>Đ</b></p>
            </td>
          </tr>
          <tr class="row">
            <td class="title">Tổng số tiền thuế được hoàn lại trong kỳ</td>
            <td class="content">
              <p><b><span id="refundable-tax"></span>Đ<b></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="contact-form">
      <p>Các thông tin nêu trên chỉ là dự tính. Nếu bạn cần làm rõ thêm, hoặc cần tư vấn đề giảm thiểu vi phạm, giảm mức phạt theo luật, tối ưu tăng mức thuế được hoàn theo luật, vui lòng đăng ký tư vấn.</p>
      <form action="/?rest_route=/api/v1/contact-calculate-tax" method="POST" class="form" id="contact-form">
        <input size="40" maxlength="400" class="" placeholder="Số điện thoại" value="" type="tel" name="mobile-number">
        <button class="submit" type="submit">
          <svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.018 6.2792C14.9102 6.32145 14.841 6.38095 14.7702 6.43268C13.8153 7.13021 12.8629 7.83292 11.9063 8.52872C10.6575 9.43664 9.40629 10.3394 8.15669 11.2464C8.10153 11.2869 8.05461 11.2852 7.99452 11.2645C6.45515 10.7274 4.91413 10.1919 3.37476 9.65391C2.55321 9.3668 1.73331 9.07364 0.911767 8.78653C0.435962 8.62098 0.120679 8.30024 0.0227192 7.77687C-0.0826495 7.21471 0.185711 6.6603 0.677981 6.39992C0.932347 6.26541 1.20894 6.19557 1.47977 6.11021C2.40422 5.81878 3.31467 5.48252 4.23417 5.17557C5.50765 4.7505 6.78113 4.32456 8.05132 3.88915C8.52795 3.72619 9.01116 3.58478 9.4845 3.41148C10.6114 2.99848 11.7598 2.6579 12.8917 2.26042C13.6581 1.99141 14.4335 1.75085 15.1983 1.47925C16.0256 1.1861 16.8669 0.941233 17.6917 0.64032C18.2523 0.435975 18.8244 0.27043 19.3867 0.0703957C20.1465 -0.20034 20.8396 0.347167 20.9796 1.07488C21.0158 1.26198 21.0026 1.44304 20.9499 1.62497C20.6758 2.57599 20.3613 3.51408 20.0806 4.46252C19.7382 5.61961 19.376 6.76981 19.0212 7.92259C18.5849 9.34093 18.1511 10.7601 17.7098 12.1759C17.3534 13.3209 17.0101 14.4694 16.6578 15.6161C16.2174 17.0483 15.7737 18.4787 15.3325 19.91C15.2016 20.335 15.0954 20.7687 14.9414 21.1852C14.7538 21.693 14.301 22.0034 13.7733 22C13.2868 21.9974 12.825 21.6697 12.6554 21.1722C12.4291 20.5101 12.2224 19.8401 12.0109 19.1719C11.4231 17.3216 10.8378 15.4704 10.2476 13.6201C10.2155 13.5184 10.2328 13.4511 10.2888 13.3666C11.1885 12.0259 12.0841 10.6817 12.9814 9.33921C13.6474 8.34249 14.3133 7.34577 14.9793 6.34904C14.9892 6.33439 14.9966 6.31887 15.0196 6.27662L15.018 6.2792Z" fill="white" />
          </svg>
        </button>
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