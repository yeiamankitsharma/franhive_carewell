<!DOCTYPE html>
<html lang="en">


    <?php $this->load->view('includes/header'); ?>
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

<body>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Add a New Question</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a class="btn btn-warning" href="<?= base_url('/questions'); ?>" role="button">
                            Back to Questions List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="pd-20 card-box mb-30">
                <form action="<?= base_url('admin/addQuestion') ?>" method="post">

                    <!-- Question Type Toggle -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Question Type</label>
                        <div class="col-sm-12 col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question_type" id="subjective" value="2" required checked onclick="toggleOptions()">
                                <label class="form-check-label" for="subjective">Subjective</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question_type" id="mcq" value="1" required onclick="toggleOptions()">
                                <label class="form-check-label" for="mcq">MCQ Type</label>
                            </div>
                        </div>
                    </div>

                    <!-- Question Title -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Question Title</label>
                        <div class="col-sm-12 col-md-10">
                        <textarea class="form-control" name="question_name" id="ckeditor" placeholder="Enter question title" required> </textarea>	   
                        <!-- <input class="form-control" type="text" name="question_name" placeholder="Enter question title" required /> -->
                        </div>
                    </div>

                    <!-- Question Group -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Question Group</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="question_group" placeholder="Enter question group" required />
                        </div>
                    </div>

                    <!-- Marks -->
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Marks</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="number" name="question_marks" placeholder="Enter marks" required min="0" />
                        </div>
                    </div>

                    <!-- Question Type -->
                   

                    <!-- MCQ Options (Initially Hidden) -->
                    <div id="mcq-options" style="display: none;">
    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 1</label>
        <div class="col-sm-8 col-md-8">
            <input class="form-control" type="text" name="option_1" placeholder="Enter option 1">
        </div>
        <div class="col-sm-4 col-md-2 d-flex align-items-center">
            <label class="m-0">
                <input type="checkbox" name="is_correct_1" value="1">
                Correct Option
            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 2</label>
        <div class="col-sm-8 col-md-8">
            <input class="form-control" type="text" name="option_2" placeholder="Enter option 2">
        </div>
        <div class="col-sm-4 col-md-2 d-flex align-items-center">
            <label class="m-0">
                <input type="checkbox" name="is_correct_2" value="1">
                Correct Option
            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 3</label>
        <div class="col-sm-8 col-md-8">
            <input class="form-control" type="text" name="option_3" placeholder="Enter option 3">
        </div>
        <div class="col-sm-4 col-md-2 d-flex align-items-center">
            <label class="m-0">
                <input type="checkbox" name="is_correct_3" value="1">
                Correct Option
            </label>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Option 4</label>
        <div class="col-sm-8 col-md-8">
            <input class="form-control" type="text" name="option_4" placeholder="Enter option 4">
        </div>
        <div class="col-sm-4 col-md-2 d-flex align-items-center">
            <label class="m-0">
                <input type="checkbox" name="is_correct_4" value="1">
                Correct Option
            </label>
        </div>
    </div>
</div>

                    <!-- Submit Button -->
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-10 offset-md-2">
                            <button type="submit" class="btn btn-warning">Add Question</button>
                        </div>
                    </div>
                </form>
   



                <script>
function toggleOptions() {
    var mcqOptions = document.getElementById("mcq-options");
    var mcqRadio = document.getElementById("mcq");

    if (mcqRadio.checked) {
        mcqOptions.style.display = "block";
    } else {
        mcqOptions.style.display = "none";
    }
}
</script>