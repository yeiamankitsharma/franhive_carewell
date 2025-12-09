<!DOCTYPE html>
<html lang="en">
    <?php  //$this->load->view('includes/header'); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCQ Test</title>
    <link rel="shortcut icon" type="image/png" href="https://www.franhive.com/images/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-check-input:disabled~.form-check-label, .form-check-input[disabled]~.form-check-label {
    cursor: default;
    opacity: 1;
}
        .test-container {
            max-width: 900px;
            margin: auto;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .option-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
        }
        .form-check-input {
            width: 20px;
            height: 20px;
            transform: scale(1.5);
            cursor: pointer;
            margin: 3px 10px;
        }
        #timer {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            background: #e9ffe9;
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0px 0px 5px rgba(0, 255, 0, 0.3);
        }
        .option-header small {
            display: inline-block;
            width: 35px;
            text-align: center;
            margin-bottom: 5px;
            margin-left: 10px;
            font-weight: bold;
        }
        /* .option-container {
            display: flex;
            align-items: center;
            gap: 10px;
        } */

       
        /* Dim only the disabled checkbox (not the label text) */
        input[type='checkbox']:disabled {
            opacity: 0.5; /* Dim the checkbox only */
        }

        /* Ensure the text label always stays black */
        .option-container .form-check-label {
            color: #000 !important; /* Force label text to stay black */
        }
</style>


    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <div id="instructionPage" class="test-container">
            <h2 class="mb-3"><?php echo $test_details['TEST_NAME']; ?></h2>
            <p class="lead bg-white p-3 border rounded shadow-sm"><?php echo nl2br($test_details['TEST_INSTRUCTIONS']); ?></p>
            <button class="btn btn-primary" id="startTestBtn">Start Test</button>
        </div>
        
        <div id="questionPage" style="display:none;">
            <div id="questionContainer"></div>
        </div>
    </div>



    <script>
$(document).ready(function () {
    let questions = <?php echo json_encode($test_questions); ?>;
    let testType = <?php echo (int)$test_details['TEST_TYPE']; ?>;
    let currentIndex = 0;
    let timerInterval;
    let elapsedSeconds = 0;
    let responses = [];
    let clearResponseCount = 2;
    let questionStartTime = 0;

    function startTest() {
        $("#instructionPage").hide();
        $("#questionPage").show();
        questionStartTime = elapsedSeconds;
        startTimer();
        showQuestion(currentIndex);
    }

    function formatTime(totalSeconds) {
        const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        return `${hours}:${minutes}:${seconds}`;
    }

    function startTimer() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            elapsedSeconds++;
            $("#timer").text(formatTime(elapsedSeconds));
        }, 1000);
    }

    function showQuestion(index) {
    if (index < 0 || index >= questions.length) return;

    if (responses[currentIndex]) {
        responses[currentIndex].time_spent = elapsedSeconds - questionStartTime;
    }

    let question = questions[index];
    let questionContainer = $("#questionContainer");

    let html = `<h2 class="mb-3"><?php echo $test_details['TEST_NAME']; ?></h2>
                <div class='test-container'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <h5 class='text-primary'>Question ${index + 1} of ${questions.length}</h5>
                        <div id="timer">00:00:00</div>
                    </div>
                    <h4 class='text-center mb-4'>${question.QUESTION_NAME}</h4>`;

    if (testType === 1) {
        // --- Type 1: Most/Least logic ---
        html += `<div class='d-flex align-items-center mb-2'>
                    <div class='text-center' style='width: 6%; font-weight: bold;'>Most</div>
                    <div class='text-center' style='width: 6%; font-weight: bold;'>Least</div>
                    <div class='option-text' style='width: 35%; text-align: left;'></div>
                </div>`;

        for (let i = 1; i <= 4; i++) {
            html += `<div class='option-container d-flex align-items-center'>
                        <div class='text-center' style='width: 6%;'>
                            <input type='checkbox' class='form-check-input most-likely' 
                                name='most_likely_${index}' 
                                value='option_${i}' 
                                onclick='handleMostLeastSelection("most", ${index}, this)'>
                        </div>
                        <div class='text-center' style='width: 6%;'>
                            <input type='checkbox' class='form-check-input least-likely' 
                                name='least_likely_${index}' 
                                value='option_${i}' 
                                onclick='handleMostLeastSelection("least", ${index}, this)'>
                        </div>
                        <div class='option-text' style='width: 35%;text-align: left;'>
                            <label class='form-check-label'>${question[`option_${i}`]}</label>
                        </div>
                    </div>`;
        }

        html += `<div class='btn-container mt-4'>
                    <button id='nextBtn' class='btn btn-primary' onclick='nextQuestion()' ${index === questions.length - 1 ? "disabled" : ""}>Next</button>
                    <button id='submitBtn' class='btn btn-success' onclick='submitTest()' disabled>Submit</button>
                </div>
                <button id="clearResponseBtn" class="btn btn-warning mt-3" onclick="clearResponses()">Clear Responses (${clearResponseCount})</button>`;

    } else if (testType === 3) {
        // --- Type 2: Normal MCQ ---
        for (let i = 1; i <= 4; i++) {
            html += `<div class='option-container'>
                        <input type='radio' name='mcq_${index}' value='option_${i}' class='form-check-input' onclick='handleMCQSelection(${index}, this)'>
                        <label class='form-check-label'>${question[`option_${i}`]}</label>
                    </div>`;
        }

        html += `<div class='btn-container mt-4'>
                    <button id='nextBtn' class='btn btn-primary' onclick='nextQuestion()' ${index === questions.length - 1 ? "disabled" : ""}>Next</button>
                    <button id='submitBtn' class='btn btn-success' onclick='submitTest()' disabled>Submit</button>
                </div>`;
    }

    html += `</div>`;
    questionContainer.html(html);
    questionStartTime = elapsedSeconds;

    if (index === questions.length - 1) {
        toggleSubmitButton();
    }
}


    // --- Handlers for Type 1 ---
    window.handleMostLeastSelection = function (type, index, selectedCheckbox) {
        const groupName = type === "most" ? `most_likely_${index}` : `least_likely_${index}`;
        $(`input[name='${groupName}']`).not(selectedCheckbox).prop("checked", false).prop("disabled", true);

        if (!responses[index]) {
            responses[index] = { question_id: questions[index]?.QUESTION_ID || null, most_likely: null, least_likely: null, time_spent: 0 };
        }

        if (type === "most") responses[index].most_likely = $(selectedCheckbox).val();
        else if (type === "least") responses[index].least_likely = $(selectedCheckbox).val();

        responses[index].time_spent = elapsedSeconds - questionStartTime;
        toggleSubmitButton();
    };

    // --- Handler for Type 2 ---
    window.handleMCQSelection = function (index, selectedRadio) {
        responses[index] = { question_id: questions[index]?.QUESTION_ID || null, selected_option: $(selectedRadio).val(), time_spent: elapsedSeconds - questionStartTime };
        toggleSubmitButton();
    };

    // --- Clear for Type 1 only ---
    window.clearResponses = function () {
        if (clearResponseCount > 0) {
            $(`input[name='most_likely_${currentIndex}'], input[name='least_likely_${currentIndex}']`).prop("checked", false).prop("disabled", false);
            responses[currentIndex].most_likely = null;
            responses[currentIndex].least_likely = null;
            clearResponseCount--;
            $("#clearResponseBtn").text(`Clear Responses (${clearResponseCount})`);
            if (clearResponseCount === 0) {
                alert("You have used all your clear attempts.");
                $("#clearResponseBtn").prop("disabled", true);
            }
        }
    };

    function toggleSubmitButton() {
        const lastResponse = responses[questions.length - 1] || {};
        const submitBtn = $('#submitBtn');
        if (testType === 1) {
            submitBtn.prop('disabled', !(lastResponse.most_likely && lastResponse.least_likely));
        } else {
            submitBtn.prop('disabled', !lastResponse.selected_option);
        }
    }

    window.nextQuestion = function () {
        const currentResponse = responses[currentIndex] || {};
        if (testType === 1 && (!currentResponse.most_likely || !currentResponse.least_likely)) {
            alert("Please select both Most and Least before proceeding.");
            return;
        }
        if (testType === 3 && !currentResponse.selected_option) {
            alert("Please select an answer before proceeding.");
            return;
        }
        if (currentIndex < questions.length - 1) {
            currentIndex++;
            showQuestion(currentIndex);
        }
    };

    window.submitTest = function () {
        clearInterval(timerInterval);
        let responseJSON = JSON.stringify({
            responses: responses,
            test_id: window.location.href.split('/').pop(),
            is_submitted: true
        });
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('/save-test-response')?>",
            contentType: "application/json",
            data: responseJSON,
            success: function() {
                window.location.href = '<?php echo base_url('my-test-thankyou')?>';
            }
        });
    };

    $("#startTestBtn").click(startTest);
});
</script>


</body>
</html>
