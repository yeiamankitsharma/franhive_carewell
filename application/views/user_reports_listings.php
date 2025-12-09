<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>



<div class="mobile-menu-overlay"></div>

<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Your Test Reports</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <!-- <a
										class="btn btn-warning"
										href="<?= base_url('/add-test'); ?>"
										role="button"
										
									>
										Add new Test
									</a> -->
								
							</div>
						</div>
					</div>
					<!-- Simple Datatable start -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<!-- <h4 class="text-blue h4">Listing of all Test</h4> -->
							
						</div>
						<div class="pb-20">
							<table class="data-table table stripe hover nowrap">
                            <thead>
									<tr>
										<th>
											Sr No.
										</th>
										
                    <th>Test Name</th>
                    <th> User Name</th>
                    <!-- <th>Test Marks</th> -->
					<th>Submit Date</th>
                    <th>Status</th>
									</tr>
								</thead>
                                <tbody>
                <?php foreach ($my_test_reports as $my_test_reports): ?>
                    <tr>
                <tr></td>
                        <td><?= $my_test_reports->ID ?></td>
                        <td><?= $my_test_reports->TEST_NAME ?></td>
						<td><?= $my_test_reports->NAME ?></td>    
                        <!-- <td><?= $my_test_reports->TOTAL_MARKS ?></td>     -->
                        <td><?= $my_test_reports->updated_at ?></td>                
                        <td>
                           
							<?php if($my_test_reports->IS_EVAL == 1){ ?>
                                <button class="btn btn-success">Qualified</button>
						<?php 	}else{ ?>
							
							<p>Not Evaluated Yet</p>
							
							<?php }?>
                           
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
							</table>
						</div>
					</div>
					<!-- Simple Datatable End -->
					
				</div>
				
			</div>
		</div>


</body>
	<?php $this->load->view('includes/footer'); ?>
</html>

