<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
   $(function() {
      tinymce.init({
         selector: 'textarea',
         plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
         toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
         tinycomments_mode: 'embedded',
         tinycomments_author: 'Author name',
         branding: false,
         setup: function(editor) {
            editor.on('change', function() {
               tinymce.triggerSave(); // Update the textarea with TinyMCE content
            });
         }
      });
   });
</script>

<div class="main-container">
   <div class="pd-ltr-20 xs-pd-20-10">
      <div class="page-header">
         <div class="row">
            <div class="col-md-6 col-sm-12">
               <div class="title">
                  <h4>Add A new Template</h4>
               </div>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
               <a class="btn btn-warning" href="<?= base_url('/templates'); ?>" role="button">
                  Back To Campaign List
               </a>
            </div>
         </div>
      </div>
      <div class="pd-20 card-box mb-30">
         <form action="<?= base_url('CampaignController/createTemplate') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Module <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <select class="custom-select col-12" name="MODULE_NAME">
                     <option selected="">Choose...</option>
                     <option value="Lead">Lead</option>
                     <option value="Client">Client </option>
                  </select>
               </div>
            </div>
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Name <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" type="text" name="TEMPLATE_NAME" placeholder="Template title" />
               </div>
            </div>
            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Subject <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" name="TEMPLATE_SUBJECT" type="text" placeholder="Subject" />
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Content <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <textarea name="TEMPLATE_BODY" id="templateBody" required></textarea>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Template Signature <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <textarea name="TEMPLATE_SIGN" id="templateSign" required></textarea>
               </div>
            </div>

            <div class="form-group row">
               <label class="col-sm-12 col-md-2 col-form-label">Attachments <span style="color: red;">*</span></label>
               <div class="col-sm-12 col-md-10">
                  <input class="form-control" id="fileInput" name="ATTACHMENTS[]" type="file" multiple />
                  <div id="previewContainer" style="margin-top: 10px;"></div>
               </div>
            </div>
            <div class="form-group row">
               <div class="col-sm-6 col-md-4">
                  <button type="submit" class="btn btn-warning">Add Template</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>

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