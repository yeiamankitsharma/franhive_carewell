<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Series Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <!-- CKEditor script -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #FCB22B;
        }
        .question-container {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
        }
        button {
            background-color: #FCB22B;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        div#question {
    font-size: 22px;
    padding: 10px 0px;
}


/* Add this in your <style> tag or CSS file */
#finishTestModal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

#finishTestModal > div {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

#buttonContainer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}

#buttonContainer > div {
    display: flex;
}

#buttonContainer button {
    margin: 5px;
}
@media (max-width: 600px) {
    #buttonContainer {
        flex-direction: column;
    }

    #buttonContainer > div {
        width: 100%;
        justify-content: space-around;
    }
}


    </style>
</head>
<body>



<div class="container">
    <h1 class="mt-4"><?=$test_details['TEST_NAME']?></h1>

    <div id="progressBarContainer" style="width: 100%; background-color: #eee;">
    <div id="progressBar" style="width: 0%; height: 20px; background-color: #4CAF50;"></div>
    <!-- New element for displaying question count -->
    <div id="questionCount" style="text-align: center; margin-top: 5px;"></div>
    </div>

    <!-- </div> -->

    <!-- Your existing question and button elements -->
    <div id="questionContainer">
        <div class="question-name" id="question">[Question will be displayed here]</div>
        <textarea id="answer"></textarea>
        <div id="wordCountDisplay">Word Count: 0</div>

        </br>
    <!-- Existing Next button -->



   <div id="buttonContainer" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <button class="btn btn-warning" id="backButton" onclick="previousQuestion()" style="display: none;">Back</button>
        <button  class="btn btn-primary" id="nextButton" onclick="nextQuestion()">Next</button>
    </div>
    <div>
        <button class="btn btn-warning"  id="skipButton" onclick="skipQuestion()">Skip</button>
        <button class="btn btn-success" id="finishButton" onclick="finishTest()">Save My Responses</button>
        <button  class="btn btn-success" id="submitButton" onclick="submitAnswers()" style="display: none;">Submit</button>
    </div>
</div>


    </div>

    <!-- Modal Structure -->
<div id="finishTestModal" style="display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 15% auto; padding: 20px; width: 30%;">
        <p id="modalContent">You have unattempted questions. Do You Want to Exit(Your responses will be saved, You can resume later)</p>
        <button class="btn btn-warning" onclick="saveResponse(true)">Yes, Exit</button>
        <button class="btn btn-success" onclick="closeModal()">Close</button>
    </div>
</div>

<!-- Modal Structure -->
<div id="submitTestModal" style="display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 15% auto; padding: 20px; width: 30%;">
        <p id="modalContent1">You have attempted X questions and skipped Y questions.</p>
        <!-- <p>Are you sure you want to submit the test?</p> -->
        <button onclick="confirmSubmit()">Yes, Submit</button>
        <button onclick="closeModal1()">Cancel</button>
    </div>
</div>



</div>



<!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script> -->


<script>
let currentQuestionIndex = 0;
let testQuestions = <?php echo json_encode($test_questions); ?>;
let responses = [];
let editor; // CKEditor instance

function initializeCKEditor() {
    CKEDITOR.replace('answer', {
    on: {
        instanceReady: function(evt) {
            var editor = evt.editor;
            // Listen for any change in the editor
            editor.on('change', function() {
                var wordCount = countWords(editor.getData());
                document.getElementById('wordCountDisplay').innerText = 'Word Count: ' + wordCount;
            });
        }
    }
});
editor = CKEDITOR.instances.answer;
}

function displayCurrentQuestion() {
    if (Array.isArray(testQuestions) && currentQuestionIndex < testQuestions.length) {
        let currentQuestion = testQuestions[currentQuestionIndex];
        if (currentQuestion && 'QUESTION_NAME' in currentQuestion) {
            // Update the question text with the question number and question name
            document.getElementById('question').innerText = "Question " + (currentQuestionIndex + 1) + ": " + currentQuestion.QUESTION_NAME;

            // Load previous response if available
            if (responses[currentQuestionIndex] && responses[currentQuestionIndex].answer) {
                editor.setData(responses[currentQuestionIndex].answer);
            } else {
                editor.setData(''); // Reset CKEditor content if no response
            }

        } else {
            document.getElementById('question').innerText = 'Invalid question format or missing QUESTION_NAME';
        }
    } else {
        document.getElementById('questionContainer').innerText = 'End of Questions or No Questions Loaded';
    }

    // Show or hide the Back button based on the current question index
    if (currentQuestionIndex > 0) {
        document.getElementById('backButton').style.display = 'block';
    } else {
        document.getElementById('backButton').style.display = 'none';
    }

    // Update the progress bar
    updateProgressBar();
}



function countWords(text) {
    // Regular expression to match words
    return text.trim().split(/\s+/).filter(function(word) {
        return word.length > 0;
    }).length;
}


// function nextQuestion() {
//     // Check if the CKEditor has text
//     let answerText = editor.getData().trim();

//     document.getElementById('backButton').style.display = 'block';
    
//     if(answerText === '') {
//         alert('Please write an answer before proceeding to the next question.');
//         return; // Exit the function if there's no answer
//     }

//     //saveResponse();

//     if (currentQuestionIndex < testQuestions.length - 1) {
//         currentQuestionIndex++;
//         updateProgressBar(); // Update the progress bar
//         displayCurrentQuestion();
//     } else {
//         document.getElementById('nextButton').style.display = 'none';
//         document.getElementById('submitButton').style.display = 'block';
//         updateProgressBar(); // Ensure the progress bar is full at the last question
//     }
// }

function nextQuestion() {
    // Save the current response before moving to the next question
    saveResponse(false);

    // Check if the CKEditor has text
    let answerText = editor.getData().trim();

    if (answerText === '') {
        alert('Please write an answer before proceeding to the next question.');
        return; // Exit the function if there's no answer
    }

    if (currentQuestionIndex < testQuestions.length - 1) {
        currentQuestionIndex++;
        updateProgressBar(); // Update the progress bar
        displayCurrentQuestion();
    } else {
        document.getElementById('nextButton').style.display = 'none';
        document.getElementById('submitButton').style.display = 'block';
        updateProgressBar(); // Ensure the progress bar is full at the last question
    }
}



// function saveResponse() {
//     let answer = editor.getData();
//     console.log("answeranswer",answer);
//     let currentQuestion = testQuestions[currentQuestionIndex]; // Access the current question from the testQuestions array
//     console.log("currentQuestion",currentQuestion);
//     responses.push({
//         questionId: currentQuestionIndex,
//         question: currentQuestion, // Save the entire currentQuestion object
//         answer: answer
//     });

//     var currentUrl = window.location.href;
//     var test_id = currentUrl.split('/').pop()

//     $.ajax({
//         type: "POST",
//         url: "<?php echo base_url('/save-test-response')?>",
//         contentType: "application/json",
//         data: JSON.stringify({
//             responses: responses,
//             test_id: test_id,
//             is_submitted:false,
//             // user_id: 2
//         }),
//         success: function(response) {
//             console.log("Response from server: ", response);
//             window.location.href = '<?php echo base_url('my-test-thankyou')?>';
//             // Handle successful response
//         },
//         error: function() {
//             console.error("An error occurred during the request");
//             // Handle errors here
//         }
//     });


// }
function saveResponse(is_nxt) {
    let answer = editor.getData();
    let currentQuestion = testQuestions[currentQuestionIndex]; // Access the current question from the testQuestions array

    // Check if a response for the current question already exists
    let existingResponseIndex = responses.findIndex(response => response.questionId === currentQuestionIndex);

    if (existingResponseIndex !== -1) {
        // Update the existing response
        responses[existingResponseIndex].answer = answer;
    } else {
        // Add a new response
        responses.push({
            questionId: currentQuestionIndex,
            question: currentQuestion, // Save the entire currentQuestion object
            answer: answer
        });
    }

    console.log("Updated Responses: ", responses);
if(is_nxt){
    var currentUrl = window.location.href;
    var test_id = currentUrl.split('/').pop();

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('/save-test-response')?>",
        contentType: "application/json",
        data: JSON.stringify({
            responses: responses,
            test_id: test_id,
            is_submitted:false,
            // user_id: 2
        }),
        success: function(response) {
            console.log("Response from server: ", response);
            window.location.href = '<?php echo base_url('my-test-thankyou')?>';
            // Handle successful response
        },
        error: function() {
            console.error("An error occurred during the request");
            // Handle errors here
        }
    });
}
}




// function updateProgressBar() {
//     let totalQuestions = testQuestions.length;
//     let currentQuestionNumber = currentQuestionIndex + 1; // Current question number (1-based index)
//     let progress = (currentQuestionNumber / totalQuestions) * 100;

//     document.getElementById('progressBar').style.width = progress + '%';

//     // Update the question count display
//     document.getElementById('questionCount').innerText = "Question " + currentQuestionNumber + " of " + totalQuestions;
// }


function updateProgressBar() {
    let totalQuestions = testQuestions.length;
    let answeredCount = calculateAttemptedQuestions();
    let progress = (answeredCount / totalQuestions) * 100;

    document.getElementById('progressBar').style.width = progress + '%';
    document.getElementById('questionCount').innerText = 
        `Question ${currentQuestionIndex + 1} of ${totalQuestions}`;
}


// Initialize the progress bar on page load
window.onload = function() {
    initializeCKEditor();
    displayCurrentQuestion();
    updateProgressBar(); // Initialize the progress bar
};

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        displayCurrentQuestion();
        loadPreviousResponse();
        updateProgressBar();
        document.getElementById('nextButton').style.display = 'block';
        document.getElementById('submitButton').style.display = 'none';
    }

    // Hide the Back button if on the first question
    if (currentQuestionIndex === 0) {
        document.getElementById('backButton').style.display = 'none';
    }
}

function loadPreviousResponse() {
    if (responses[currentQuestionIndex] && responses[currentQuestionIndex].answer) {
        // Assuming the CKEditor instance is named 'editor'
        editor.setData(responses[currentQuestionIndex].answer);
    } else {
        editor.setData('');
    }
}

function confirmSubmit() {
    // Close the modal
    document.getElementById('submitTestModal').style.display = 'none';

    var currentUrl = window.location.href;
    var test_id = currentUrl.split('/').pop();

    // alert(test_id);

    // var testId= this.href.substring(this.href.lastIndexOf('/') + 1);
    // Prepare the data for submission
    var testData = {
        responses: responses,
        testId: test_id // Replace 'yourTestId' with your actual test ID variable
    };

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('/save-test-response')?>",
        contentType: "application/json",
        data: JSON.stringify({
            responses: responses,
            test_id: test_id,
            is_submitted:true,
            // user_id: 2
        }),
        success: function(response) {
            console.log("Response from server: ", response);
            window.location.href = '<?php echo base_url('my-test-thankyou')?>';
            // Handle successful response
        },
        error: function() {
            console.error("An error occurred during the request");
            // Handle errors here
        }
    });
}



function submitAnswers() {
    let attemptedCount = calculateAttemptedQuestions();
    let skippedCount = testQuestions.length - attemptedCount;

    document.getElementById('modalContent1').innerText = "You have attempted " + attemptedCount + " questions and skipped " + skippedCount + " questions. Are you sure you want to submit the test?";
    document.getElementById('submitTestModal').style.display = 'block';
}

// function calculateAttemptedQuestions() {
//     let attemptedCount = 0;
//     console.log(responses);
//     responses.forEach(response => {
//         if (response.answer !== undefined && response.answer.trim() !== '') {
//             attemptedCount++;
//         }
//     });
//     return attemptedCount;
// }

function calculateAttemptedQuestions() {
    let attemptedCount = 0;

    testQuestions.forEach((question, index) => {
        let response = responses.find(r => r.questionId === index);
        if (response && response.answer.trim() !== '') {
            attemptedCount++;
        }
    });

    return attemptedCount;
}


function closeModal1() {
    document.getElementById('submitTestModal').style.display = 'none';
}


// function skipQuestion() {
//     if (currentQuestionIndex < testQuestions.length - 1) {
//         currentQuestionIndex++;
//         updateProgressBar(); // Update the progress bar
//         displayCurrentQuestion();
//     } else {
//         // Optionally handle the case where there are no more questions to skip
//     }
// }

function skipQuestion() {
    let currentQuestion = testQuestions[currentQuestionIndex];

    // Save the skip status for the current question
    let existingResponse = responses.find(response => response.questionId === currentQuestionIndex);
    if (!existingResponse) {
        responses.push({
            questionId: currentQuestionIndex,
            question: currentQuestion,
            answer: '' // Empty response to indicate skipped
        });
    }

    if (currentQuestionIndex < testQuestions.length - 1) {
        currentQuestionIndex++;
        updateProgressBar();
        displayCurrentQuestion();
    } else {
        alert("You have reached the end of the test.");
    }
}


function finishTest() {

    let unattemptedCount = calculateUnattemptedQuestions();

    if (unattemptedCount > 0) {
        document.getElementById('modalContent').innerText = "You have " + unattemptedCount + " unattempted questions.Do You Want to Exit(Your responses will be saved, You can resume later)";
        document.getElementById('finishTestModal').style.display = 'block';
    } else {
        submitAnswers(); // Call the function to submit the test
    }
}


function calculateUnattemptedQuestions() {
    let unattemptedCount = testQuestions.length; // Start with total questions

    // Assuming responses is an array of objects
    responses.forEach(response => {
        // Check if a response is present for a question
        if (response.answer !== undefined && response.answer.trim() !== '') {
            unattemptedCount--; // A question has been attempted
        }
    });

    return unattemptedCount;
}



function closeModal() {
    document.getElementById('finishTestModal').style.display = 'none';
}




</script>

</body>
</html>