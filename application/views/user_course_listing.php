<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>My COURSES</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="/eyd">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											My Courses
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="product-wrap">
					<div class="product-list">
						<ul class="row">
							<?php foreach ($my_courses as $key => $value) { ?>
								<li class="col-lg-4 col-md-6 col-sm-12">
									<div class="product-box">
										<div class="producct-img">
											<!-- <img src="vendors/images/product-img1.jpg" alt="" /> -->
										</div>
										<div class="product-caption">
										<img src="<?= $value['THUMNAIL_IMAGE'] ?>" style="width: 330px; height: 130px; ">
							</br>
											<h3><?= $value['NAME'] ?></h3>
											<div class="price">OBJECTIVE: <?= $value['OBJECTIVE'] ?></div>

											<div class="d-flex justify-content-between mt-2">
    <a href="<?= base_url('my-lessons/' . $value['COURSE_ID']) ?>" class="btn btn-outline-success">Start Learning</a>
    <a href="<?=$value['REGISTRARTION_LINK'] ?>" class="btn btn-outline-primary" target="_blank">Register</a>
</div>


										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>

						
					</div>
				</div>
				
			</div>
		</div>
		
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
		 <!-- Bootstrap Modal -->
		 <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="welcomeModalLabel">Welcome Note</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> <!-- Cross button -->
        </button>
      </div>
      <div class="modal-body" style="font-size: 15px; line-height: 1.5; padding: 15px; color: #333;">
  <p><strong>Welcome to Global NLP Training for Life & Wealth Mastery</strong></p>
  <p>We’re excited to have you on this transformative journey toward the relationships you truly deserve.</p>

  <h6 style="margin-top: 15px; font-weight: bold;">Next Steps</h6>

  <div style="margin-top: 8px;">
    <p><strong>Step 4:</strong> Visit your "<strong>My Courses</strong>" section and complete the <strong>[Zoom registration form]</strong>.</p>
    <p style="margin-bottom: 8px;">You’ll receive a confirmation email with your Zoom link once submitted.</p>

    <p><strong>Step 5:</strong> Update your profile details to ensure accurate identification.</p>
    <ul style="margin-left: 15px; margin-bottom: 10px;">
      <li>Click your "<strong>Name</strong>" (top right).</li>
      <li>Select "<strong>Profile</strong>".</li>
      <li>Add a profile picture, email, phone, country, and address.</li>
    </ul>

    <p><strong>Step 6:</strong> Need support or have questions? We’re here for you!</p>
  </div>

  <div style="margin-top: 12px; background: #f8f9fa; border-radius: 8px; padding: 10px; border: 1px solid #e0e0e0;">
    <h6 style="margin-bottom: 10px; font-weight: 600;">🗓️ Training Dates</h6>
    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Mon, Oct 13</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Wed, Oct 15</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Fri, Oct 17</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Wed, Oct 22</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Fri, Oct 24</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Wed, Oct 29</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Tue, Nov 11</span>
      <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 6px; font-size: 14px;">Thu, Nov 13</span>
    </div>
  </div>

  <div style="margin-top: 15px; text-align: left;">
    <p style="margin-bottom: 6px;">
      <a href="mailto:nlp@empoweryourdestiny.com.au" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: #007bff;">
        <i class="fas fa-envelope"></i> Email: nlp@empoweryourdestiny.com.au
      </a>
    </p>
    <p style="margin-bottom: 6px;">
      <a href="http://m.me/empoweryourdestiny" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: #007bff;">
        <i class="fab fa-facebook-messenger"></i> Messenger
      </a> |
      <a href="https://chat.whatsapp.com/Lx5xjiM1m2F4caEhAqlXPr" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: #007bff;">
        <i class="fab fa-whatsapp"></i> WhatsApp
      </a>
    </p>
  </div>
</div>

<div class="modal-footer" style="padding: 10px 15px; text-align: right;">
  <button type="button" class="btn btn-success btn-sm" data-dismiss="modal" style="padding: 6px 15px;">OK</button>
</div>

  </div>
</div>

<!-- jQuery and Bootstrap JS (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Show the Modal on Page Load -->
<script>
$(document).ready(function() {
    $('#welcomeModal').modal('show');
});
</script>

		
	</body>
</html>
