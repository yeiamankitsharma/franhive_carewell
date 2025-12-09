<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="card-box mb-30">
        <h2 class="text-center mb-4">Enrollment and Payment Agreement</h2>
        <div class="pb-20">
          <div class="container my-5">
          <form method="post" enctype="multipart/form-data" action="<?= base_url('welcome/enroll_data_submit') ?>">

              <!-- GENERAL INFORMATION -->
              <h4 class="mt-4">General Information</h4>
              <div class="mb-3">
                <label class="form-label" for="full_name">Full Name</label>
                <input type="text" class="form-control" name="full_name" id="full_name">
              </div>
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="date_of_birth">Date of Birth</label>
                  <input type="date" class="form-control" name="date_of_birth" id="date_of_birth">
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="gender">Gender</label>
                  <input type="text" class="form-control" name="gender" id="gender">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label" for="street_address">Street Address</label>
                <input type="text" class="form-control" name="street_address" id="street_address">
              </div>
              <div class="row">
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="city">City</label>
                  <input type="text" class="form-control" name="city" id="city">
                </div>
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="state">State</label>
                  <input type="text" class="form-control" name="state" id="state">
                </div>
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="zip_code">Zip Code</label>
                  <input type="text" class="form-control" name="zip_code" id="zip_code">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label" for="phone_number">Phone Number</label>
                <input type="tel" class="form-control" name="phone_number" id="phone_number">
              </div>
              <div class="mb-3">
                <label class="form-label" for="email_address">Email Address</label>
                <input type="email" class="form-control" name="email_address" id="email_address">
              </div>
              <!-- EMERGENCY CONTACT -->
              <h4 class="mt-4">Emergency Contact</h4>
              <div class="mb-3">
                <label class="form-label" for="emergency_contact_name">Name</label>
                <input type="text" class="form-control" name="emergency_contact_name" id="emergency_contact_name">
              </div>
              <div class="mb-3">
                <label class="form-label" for="emergency_contact_phone">Phone Number</label>
                <input type="tel" class="form-control" name="emergency_contact_phone" id="emergency_contact_phone">
              </div>
              <div class="mb-3">
                <label class="form-label" for="emergency_contact_relationship">Relationship to Student</label>
                <input type="text" class="form-control" name="emergency_contact_relationship" id="emergency_contact_relationship">
              </div>
              <!-- PROGRAM INFORMATION -->
              <table class="table table-bordered" id="programTable">
                  <thead>
                    <tr>
                      <th style="width: 10%; text-align: center;">Select</th>
                      <th style="width: 40%;">Program Name</th>
                      <th style="width: 25%;">Training Month</th>
                      <th style="width: 25%;">Training Year</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="text-align: center;">
                        <input type="checkbox" class="form-check-input" style="margin: auto; display: block;" />
                      </td>
                      <td>
                      <select class="form-control" name="program_id" id="program_id">
                            <option value="">Select</option>
                            <?php foreach($all_courses as $course): ?>
                              <option value="<?= $course->COURSE_ID ?>"><?= $course->NAME ?></option>
                            <?php endforeach; ?>
                          </select>
                        <!-- <input type="text" class="form-control" name="program_name[]" id="program_name"> -->
                      </td>
                      <td>
                        <select class="form-control" name="program_start_month" id="program_start_month">
                          <option value="">Select</option>
                          <option>January</option>
                          <option>February</option>
                          <option>March</option>
                          <option>April</option>
                          <option>May</option>
                          <option>June</option>
                          <option>July</option>
                          <option>August</option>
                          <option>September</option>
                          <option>October</option>
                          <option>November</option>
                          <option>December</option>
                        </select>
                      </td>
                      <td>
                        <select class="form-control" name="training_year" id="training_year">
                          <option value="">Select</option>
                          <option>2025</option>
                          <option>2026</option>
                          <option>2027</option>
                          <option>2028</option>
                          <option>2029</option>
                          <option>2030</option>
                        </select>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="row">
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="location">Location</label>
                  <input type="text" class="form-control" name="location" id="location">
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="total_with_gst">Total (inc GST Fee)</label>
                  <input type="text" class="form-control" name="total_with_gst" id="total_with_gst">
                </div>
              </div>

              <!-- PAYMENT INFORMATION -->
              <h4 class="mt-4">Payment Information</h4>
              <div class="mb-3">
                <label class="form-label" for="total">Note</label>
                <input type="text" step="0.01" class="form-control" name="note" id="note">
              </div>
              <div class="mb-3">
                <label class="form-label" for="total">Total</label>
                <input type="number" step="0.01" class="form-control" name="total" id="total">
              </div>
              <div class="row">
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="deposit_amount">Deposit Amount</label>
                  <input type="text" class="form-control" name="deposit_amount" id="deposit_amount">
                </div>
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="for_training">For Training</label>
                  <input type="text" class="form-control" name="for_training" id="for_training">
                </div>
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="deposit_paid_via">Deposit Paid Via</label>
                  <select class="form-control" name="deposit_paid_via" id="deposit_paid_via">
                    <option value="">Select</option>
                    <option>Credit Card</option>
                    <option>Debit Card</option>
                    <option>Fund Transfer</option>
                    <option>Others</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="paid_on">Paid On</label>
                  <input type="date" class="form-control" name="paid_on" id="paid_on">
                </div>
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="balance_of">Balance Of</label>
                  <input type="text" class="form-control" name="balance_of" id="balance_of">
                </div>
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="due_date">Due 21 days prior to commencement of training</label>
                  <input type="date" class="form-control" name="due_date" id="due_date">
                </div>
              </div>
              <!-- AUTHORIZATIONS -->
              <h4 class="mt-4">Authorizations</h4>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="agree_terms_1" id="agree_terms_1">
                <label class="form-check-label" for="agree_terms_1">
                  I understand that payment plans that extend beyond the training completion date means certificate will
                </label>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="agree_terms_2" id="agree_terms_2">
                <label class="form-check-label" for="agree_terms_2">
                  I hereby authorise and request Empower Your Destiny to use the same credit card details above for my
                  future product(s) purchases until I withdraw the authorization in writing.
                </label>
              </div>
              <!-- PAYMENT CONFIRMATION -->
              <h4 class="mt-4">Payment Confirmation</h4>
              <div class="mb-3">
                <label class="form-label" for="payment_option">Payment Options</label>
                <select class="form-control" name="payment_option" id="payment_option">
                  <option value="">Select</option>
                  <option>Credit Card</option>
                  <option>Debit Card</option>
                  <option>Fund Transfer</option>
                  <option>Others</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label" for="credit_card_number">Credit Card Number</label>
                <input type="text" class="form-control" name="credit_card_number" id="credit_card_number">
              </div>
              <div class="mb-3">
                <label class="form-label" for="expiry_date">Expiry Date</label>
                <input type="text" class="form-control" name="expiry_date" id="expiry_date">
              </div>
              <div class="mb-3">
                <label class="form-label" for="name_on_card">Name as on Card</label>
                <input type="text" class="form-control" name="name_on_card" id="name_on_card">
              </div>
              <div class="mb-3">
                <label class="form-label" for="cvc">CVC</label>
                <input type="text" class="form-control" name="cvc" id="cvc">
              </div>


              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms">
                <label class="form-check-label" for="agree_terms">
                I am the card holder (if the card holder is a company - I am the Director of the company).
                </label>

              
              

              </div>


                  <div class="form-check mb-3">
                  
                    <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms">
                    <label class="form-check-label" for="agree_terms">
                    I hereby authorise and request Empower Your Destiny to use the same credit card details above for my
                    future products(s) purchases) until | withdraw the authorization to Empower Your Destiny in writing.
                    </label>

                  

                  </div>

                  <p><i>COVID Conditions: If Empower Your Destiny cannot facilitate the live training as scheduled due to COVID or similar restrictions/travel restrictions because of another pandemic or epidemic then we will deliver the SAME training online. The student will have the opportunity to re-sit the live training should they choose, once, in next two years from the original scheduling of the training they were due to attend.
                </i></p>

                <p><i>Cancellation and Refund Policy: I understand that I have seven (7) days to cancel this agreement and have 100% of my deposit refunded, except where the training is due in less than 7 days then any payment is non-refundable. I understand that after 7 days of enrolment there is a cancellation fee of $1,000 per training. I acknowledge that I cannot cancel my enrolment 7 days before the commencement of training. I agree that the cancellation fee is a reasonable fee, necessary for covering the expenses that Empower Your Destiny incurs to make the training a valuable learning experience for me. I understand that prior to attending this training I may have telephone access to coaches and trainers who are available to assist me in the event that I need such assistance, in preparation for this training. The cancellation fee makes these trainers available to me. There are some additional conditions regarding cancellation which are posted at www.empoweryourdestiny.com.au/refund which is a part of this agreement.
              There are some additional conditions regarding cancellation which are posted at www.empoweryourdestiny.com.au/refund
              which is a part of this agreement.</i></p>
              <p><i> I understand that if I receive certain products(s) (online or offline) with my enrolment and I cancel my enrolment within (7) days of this agreement, I will be liable to pay for the product the Recommended Retail Price of the respective products(s) and the amount will then be deducted from any amount refundable to me.</i></p>


              <!-- SIGNATURES -->
              <h4 class="mt-4">Signatures</h4>
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="signature">Signature</label>
                  <input type="text" class="form-control" name="signature" id="signature">
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="signature_date">Date</label>
                  <input type="date" class="form-control" name="signature_date" id="signature_date">
                </div>
              </div>
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="approved_by">Approved By</label>
                  <input type="text" class="form-control" name="approved_by" id="approved_by">
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="approval_date">Date</label>
                  <input type="date" class="form-control" name="approval_date" id="approval_date">
                </div>
              </div>
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('vendors/scripts/core.js'); ?>"></script>
<script src="<?= base_url('vendors/scripts/script.min.js'); ?>"></script>
<script src="<?= base_url('vendors/scripts/process.js'); ?>"></script>
<script src="<?= base_url('vendors/scripts/layout-settings.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('src/plugins/datatables/js/responsive.bootstrap4.min.js'); ?>"></script>
</html>
