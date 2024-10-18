<?php

/**
 * Template Name: Tax Form
 */
?>

<?php get_header(); ?>

<?php the_content(); ?>

<style>
  .tax-form {
    border-style: solid;
    border-width: 8px 0px 0px 0px;
    border-color: var(--e-global-color-secondary);
    border-radius: 8px;
    box-shadow: 0px 0px 10px 0px rgba(226, 24, 24, 0.5);
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 100px;
    width: fit-content;
  }

  .tax-form .wrapper {
    padding: 20px 100px;
  }

  .tax-form .title {
    color: #0D2556;
  }

  .tax-form .italic {
    font-style: italic;
  }

  .tax-form .form-title {
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
</style>

<div class="tax-form">
  <div class="wrapper">
    <h2 class="title form-title">Bảng tính thử thuế thu nhập cá nhân</h2>
    <form action="" class="form">
      <fieldset class="fieldset row">
        <field class="field">
          <label for="name" class="label">Họ và tên</label>
          <input type="text" id="name" class="input text">
        </field>
        <field class="field">
          <label for="mobile-number" class="label">Số điện thoại</label>
          <input type="text" id="mobile-number" class="input text">
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field required">
          <label for="settlement-year" class="label">Năm quyết toán</label>
          <select name="settlement-year" id="settlement-year" class="input select">
            <?php foreach (range(2010, 2024) as $year) {
              echo "<option value='$year'>$year</option>";
            } ?>
          </select>
        </field>
        <field class="field required">
          <label for="number-of-income-sources" class="label">Số nguồn thu nhập</label>
          <select name="number-of-income-sources" id="number-of-income-sources" class="input select">
            <?php foreach (range(1, 10) as $num) {
              echo "<option value='$num'>$num</option>";
            } ?>
          </select>
        </field>
      </fieldset>
      <p class="note">Ghi chú: năm quyết toán là bắt buộc để làm cơ sở tính số thuế chậm nộp, bị phạt</p>
      <h3 class="title field-set-title">Thống kê thu nhập và nộp thuế thu nhập cá nhân:</h3>
      <fieldset class="fieldset row">
        <field class="field required">
          <label for="total-income" class="label">Tổng thu nhập</label>
          <input type="text" id="total-income" class="input text" placeholder="đ">
        </field>
        <field class="field required">
          <label for="tax-deducted" class="label italic">Số thuế đã khấu trừ</label>
          <input type="text" id="tax-deducted" class="input text">
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-taxable-income" class="label">Tổng thu nhập chịu thuế</label>
          <input type="text" id="total-taxable-income" class="input text" disabled>
        </field>
        <field class="field required">
          <label for="number-of-dependents" class="label">Số người phụ thuộc</label>
          <select name="number-of-dependents" id="number-of-dependents" class="input select">
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
          <input type="text" id="personal-deduction" class="input text" disabled>
        </field>
        <field class="field">
          <label for="deduction-for-dependents" class="label">Giảm cho những người phụ thuộc được giảm trừ</label>
          <input type="text" id="deduction-for-dependents" class="input text" disabled>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="charity-deduction" class="label">Tổng từ thiện nhân đạo khuyến học được trừ</label>
          <input type="text" id="charity-deduction" class="input text">
        </field>
        <field class="field">
          <label for="insurance-deduction" class="label">Tổng các khoản đóng bảo hiểm được trừ</label>
          <input type="text" id="insurance-deduction" class="input text">
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="pension-deduction" class="label">Tổng các khoản đóng quỹ HTTN được trừ</label>
          <input type="text" id="pension-deduction" class="input text">
        </field>
        <field class="field">
          <label for="total-deduction" class="label">Tổng các khoản giảm trừ</label>
          <input type="text" id="total-deduction" class="input text" disabled>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-taxable-income" class="label">Tổng thu nhập tính thuế</label>
          <input type="text" id="total-taxable-income" class="input text" disabled>
        </field>
        <field class="field">
          <label for="total-tax" class="label">Tổng số thuế TNCN phát sinh trong kỳ</label>
          <input type="text" id="total-tax" class="input text" disabled>
        </field>
      </fieldset>
      <fieldset class="fieldset row">
        <field class="field">
          <label for="total-tax-paid" class="label">Tổng số thuế đã nộp trong kỳ</label>
          <input type="text" id="total-tax-paid" class="input text" disabled>
        </field>
      </fieldset>
      <div class="footer">
        <input class="submit" type="submit" value="Thực hiện tra cứu" />
      </div>
    </form>
  </div>
</div>

<?php get_footer(); ?>