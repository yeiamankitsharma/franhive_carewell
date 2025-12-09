<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
   $(function() {
      // CKEDITOR.replace('ckeditor', {
      //    filebrowserImageBrowseUrl: '<?php echo base_url('assets/kcfinder/browse.php'); ?>',
      //    height: '400px'
      // });

      tinymce.init({
         selector: 'textarea',
         plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
         toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
         tinycomments_mode: 'embedded',

         branding: false,
      });
   });
</script>
<div class="main-container">

   <div class="pd-ltr-20 xs-pd-20-10">
      <div class="page-header">
         <div class="row">
            <div class="col-md-6 col-sm-12">
               <div class="title">
                  <h4>Update Template: [<?= $template_data['TEMPLATE_NAME'] ?>]</h4>
               </div>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
               <a class="btn btn-warning" href="<?= base_url('/templates'); ?>" role="button">
                  Back To campaign List
               </a>
            </div>
         </div>
      </div>
      <div class="pd-20 card-box mb-30">
         <div class="clearfix">
            <div class="pull-left">
               <!-- <h4 class="text-blue h4">Add A new Question</h4> -->
            </div>
         </div>
         <form action="<?= base_url('CampaignController/updateTemplate') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="TEMPLATE_ID" value="<?= $template_data['TEMPLATE_ID'] ?>">
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Create Template for</label>
               <div class="col-sm-12 col-md-10">
                  <select class="custom-select col-12" name="MODULE_NAME">
                     <option value="">Choose...</option>
                     <option value="Lead" <?= $template_data['MODULE_NAME'] == 'Lead' ? 'selected' : '' ?>>Lead</option>
                     <option value="Client" <?= $template_data['MODULE_NAME'] == 'Client' ? 'selected' : '' ?>>Client</option>
                  </select>
               </div>

            </div>
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Name <span style="color: red;">*</span></label>
               <!-- <p>The campaign name is shown in your reports and your email archive.</p> -->
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" type="text" name="TEMPLATE_NAME" placeholder="Template title" value="<?= $template_data['TEMPLATE_NAME'] ?>" />

               </div>
            </div>
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Subject <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <div class="row">
                     <div class="col-md-12">
                        <input class="form-control" name="TEMPLATE_SUBJECT" type="text" placeholder="Name" value="<?= $template_data['TEMPLATE_SUBJECT'] ?>" />

                     </div>

                  </div>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Content <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <textarea name="TEMPLATE_BODY" id="ckeditor" class="form-control" required><?= $template_data['TEMPLATE_BODY'] ?></textarea>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Signature <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <textarea name="TEMPLATE_SIGN" id="templateSign" required><?= $template_data['TEMPLATE_SIGN'] ?></textarea>
               </div>
            </div>


            <!-- <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Signature <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <div class="row">
                     <div class="col-md-12">
                        <select class="custom-select col-12" name="TEMPLATE_SIGN">
                           <option <?= $template_data['TEMPLATE_SIGN'] == "" ? 'selected' : '' ?>>Choose...</option>
                           <option value="1" <?= $template_data['TEMPLATE_SIGN'] == "1" ? 'selected' : ''; ?>>Barinderjeet</option>
                        </select>
                     </div>
                  </div>
               </div>
            </div> -->



            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Attachments <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <div class="row">
                     <div class="col-md-12">
                        <div id="existingFiles">
                           <?php if (!empty($template_data['ATTACHMENTS'])): ?>
                              <?php foreach (explode(',', $template_data['ATTACHMENTS']) as $attachment): ?>
                                 <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $attachment)): ?>
                                    <img src="<?= $attachment ?>" style="width: 150px; margin: 5px;" />
                                 <?php elseif (preg_match('/\.pdf$/i', $attachment)): ?>
                                    <embed src="<?= $attachment ?>" type="application/pdf" style="width: 150px; height: 150px; margin: 5px;" />
                                 <?php else: ?>
                                    <p><?= basename($attachment) ?></p>
                                 <?php endif; ?>
                              <?php endforeach; ?>
                           <?php endif; ?>
                        </div>
                        <input class="form-control" id="fileInput" value="<?= $template_data['ATTACHMENTS'] ?>" name="ATTACHMENTS[]" type="file" placeholder="Name" multiple />
                        <div id="previewContainer" style="margin-top: 10px;"></div>
                     </div>
                  </div>
               </div>
            </div>

         
            


            <div class="form-group row">
               <!-- <label class="col-sm-12 col-md-2 col-form-label">MarksL <span style="color: red;">*</span></label> -->
               <div class="col-sm-6 col-md-4">
                  <button type="submit" class="btn btn-warning">Update Template</button>
               </div>
            </div>
         </form>
      </div>
      <!-- Default Basic Forms End -->
   </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>
<!-- Load CKEditor -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
      $('#fileInput').on('change', function(event) {
         var files = event.target.files;
         var $previewContainer = $('#previewContainer');

         $previewContainer.empty(); // Clear existing previews

         $.each(files, function(index, file) {
            var reader = new FileReader();

            reader.onload = function(e) {
               var $preview;

               if (file.type.startsWith('image/')) {
                  // Create an image element for image files
                  $preview = $('<img>').attr('src', e.target.result)
                     .css({
                        'width': '150px',
                        'margin': '5px'
                     });
               } else if (file.type === 'application/pdf') {
                  // Create a PDF preview element for PDF files
                  $preview = $('<embed>').attr('src', e.target.result)
                     .attr('type', 'application/pdf')
                     .css({
                        'width': '150px',
                        'height': '150px',
                        'margin': '5px'
                     });
               } else {
                  // Handle other file types if necessary
                  $preview = $('<p>').text(file.name);
               }

               $previewContainer.append($preview);
            };

            reader.readAsDataURL(file); // Read file as data URL
         });
      });
   });
</script>




</html>