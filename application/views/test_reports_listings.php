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
									<h4>Listing of all Test Reports</h4>
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
											<div class="dt-checkbox">
												<input
													type="checkbox"
													name="select_all"
													value="1"
													id="example-select-all"
												/>
												<span class="dt-checkbox-label"></span>
											</div>
										</th>
										
                    <th>Test Name</th>
                    <th> User Name</th>
                    <th>Test Marks</th>
					<th>Marks Obtained</th>
					<!-- <th>Evaluator</th> -->
                    <th>Actions</th>
									</tr>
								</thead>
                                <tbody>
                <?php foreach ($tests as $tests): 
					
					?>
                    <tr>
                <tr></td>
                        <td><?= $tests->ID ?></td>
                        <td><?= $tests->TEST_NAME ?></td>
						<td><?= $tests->NAME ?></td>       
						<td><?= $tests->TOTAL_MARKS ?></td> 
						<td><?= $tests->MARKS_OBTAINED ?></td>  
						<!-- <td><?= $tests->NAME ?></td>                -->
						<td>
							<?php if ($tests->IS_EVAL == 1) { ?>
								<a href="<?= base_url('eval/' . $tests->ID) ?>" class="btn btn-success btn-sm">Evaluated</a>
							<?php } elseif ($tests->IS_EVAL != 1 && $tests->TEST_TYPE == 1) { ?>
								<a href="<?= base_url('eval/' . $tests->ID) ?>" class="btn btn-success btn-sm">See Report</a>
							<?php }elseif ($tests->IS_EVAL != 1 && $tests->TEST_TYPE == 3) { ?>
								<a href="<?= base_url('eval/' . $tests->ID) ?>" class="btn btn-success btn-sm">View Result</a>
							<?php } else { ?>
								<a href="<?= base_url('eval/' . $tests->ID) ?>" class="btn btn-primary btn-sm">Evaluate</a>
							<?php } ?>
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

