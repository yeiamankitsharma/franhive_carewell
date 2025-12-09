<!DOCTYPE html>
<html>
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
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Edit Question Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/questions'); ?>" role="button">
                        Back To Questions List
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <form action="<?= base_url('admin/update_question') ?>" method="post">
                <input type="hidden" name="question_id" value="<?= isset($questionData['QUESTION_ID']) ? $questionData['QUESTION_ID'] : '' ?>">

                <!-- Question Title -->
                <div class="form-group row">
    <label class="col-sm-12 col-md-2 col-form-label">Question Title</label>
    <div class="col-sm-12 col-md-10">
        <textarea class="form-control" name="question_name" id="ckeditor" placeholder="Enter question title" required><?= isset($questionData['QUESTION_NAME']) ? htmlspecialchars($questionData['QUESTION_NAME']) : '' ?></textarea>
        <!-- <input class="form-control" type="text" name="question_name" placeholder="Question title" value="<?= isset($questionData['QUESTION_NAME']) ? htmlspecialchars($questionData['QUESTION_NAME']) : '' ?>" /> -->
    </div>
</div>


                <!-- Marks -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Marks</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" value="<?= isset($questionData['QUESTION_MARKS']) ? $questionData['QUESTION_MARKS'] : '' ?>" name="question_marks" type="number" />
                    </div>
                </div>

                <!-- Question Type -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Question Type</label>
                    <div class="col-sm-12 col-md-10">
                        
                        <select class="custom-select col-12" name="question_type" id="question_type">
                        <option value="-1" <?= empty($questionData['QUESTION_TYPE']) ? 'selected' : '' ?>>Choose...</option>
                            <!-- <option <?= empty($questionData['QUESTION_TYPE']) ? 'selected' : '' ?>>Choose...</option> -->
                            <option value="2" <?= isset($questionData['QUESTION_TYPE']) && $questionData['QUESTION_TYPE'] == 2 ? 'selected' : '' ?>>Subjective</option>
                            <option value="1" <?= isset($questionData['QUESTION_TYPE']) && $questionData['QUESTION_TYPE'] == 1 ? 'selected' : '' ?>>MCQ</option>
                        </select>
                    </div>
                </div>

                <!-- Options Section -->
                <div id="options_section" style="display: none;">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">Option <?= $i ?></label>
                            <div class="col-sm-8 col-md-8">
                                <input class="form-control" type="text" name="option_<?= $i ?>" placeholder="Option <?= $i ?>" value="<?= isset($questionData['option_' . $i]) ? $questionData['option_' . $i] : '' ?>" />
                            </div>
                            <div class="col-sm-4 col-md-2 d-flex align-items-center">
                                <label class="m-0">
                                    <input type="checkbox" name="is_correct_<?= $i ?>" value="1" <?= isset($questionData['is_correct_' . $i]) && $questionData['is_correct_' . $i] == 1 ? 'checked' : '' ?> />
                                    Correct Option
                                </label>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-6 col-md-4">
                        <button type="submit" class="btn btn-warning">Update Question</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to Show/Hide Options -->
<script>
    (function() {
        var select = document.getElementById('question_type');
        var optionsSection = document.getElementById('options_section');
        function toggleOptions() {
            var qType = (select && select.value) ? String(select.value).trim() : '';
            optionsSection.style.display = (qType === '1') ? 'block' : 'none';
        }
        if (select && optionsSection) {
            select.addEventListener('change', toggleOptions);
            // Run immediately for first paint
            toggleOptions();
            // Also ensure it runs on DOM ready in case other scripts delay load
            document.addEventListener('DOMContentLoaded', toggleOptions);
        }
    })();
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
