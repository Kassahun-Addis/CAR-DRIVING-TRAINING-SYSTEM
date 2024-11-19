<?php
ob_start();
define('TITLE','quizquestion');
define('page','quizquestion');
include('includes/header.php');
include('../dbcon.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}

if(isset($_SESSION['name']))
{
$user= $_SESSION['name'];
echo htmlspecialchars($user);
}
else
{
   echo "not set";
}
echo '<div class="col-sm-9 col-md-9" id="gob">';

// Initialize current question
$currentQuestion = isset($_GET['q+1']) ? (int)$_GET['q'] : 1; // Start at question 1

// Get the table name from the URL parameter
$tableName = isset($_GET['table']) ? $_GET['table'] : 'sign1'; // Default to 'sign' if not set

// Set the table name in the session
$_SESSION['table'] = $tableName; // Store the table name in session

// Fetch the current question
$sql = mysqli_query($conn, "SELECT QNo, Question, Choice1, Choice2, Choice3, Choice4, Answer, Image FROM $tableName WHERE QNo = $currentQuestion");
$canswer = mysqli_query($conn, "SELECT Answer FROM $tableName WHERE QNo = $currentQuestion");
$correctAnswerRow = mysqli_fetch_assoc($canswer); // Fetch the correct answer
$correctAnswer = $correctAnswerRow['Answer'] ?? ''; // Get the correct answer or set to empty if not found
// echo $correctAnswer; // Remove this line as it causes the error
// Debugging: Check the SQL query
if (!$sql) {
    echo '<p>Error in SQL query: ' . mysqli_error($conn) . '</p>';
}

echo '<div id="question-container">'; // Container for the question
if (mysqli_num_rows($sql) > 0) {
    while($row = mysqli_fetch_array($sql)) {
        $qns = $row['Question'];
        $qid = $row['QNo'];
        $total = $_REQUEST['total'] ?? 0; // Default to 0 if not set
        $_SESSION['total'] = $total;
        // Display question image if it exists
        // Initialize result variable
        $result = null; // Variable to store the result of the answer check

        // After displaying choices
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Store the user's answer in the session
            foreach ($_POST['uid'] as $questionNumber => $userAnswer) {
                $_SESSION['answers'][$questionNumber] = (int)$userAnswer; // Store the answer as an integer in session
            }

            $correctAnswer = (string)$row['Answer']; // Get the correct answer from the database
            
            // Check if the user's answer matches the correct answer
            if ($userAnswer == $correctAnswer) {
                $result = 'Correct';
                 // Store result as correct
            } else {
                $result = 'Incorrect. Correct answer: ' . htmlspecialchars($correctAnswer); // Store result as incorrect with correct answer
            }   
        }
        ?>
        
        <?php
    }
} else {
    echo '<p>No questions found.</p>'; // Debugging message
}
echo '</div>'; // Close question-container

// Add AJAX script
?>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Add Font Awesome -->
    <style>
         .card {
            border: none;
            width: 100%; /* Fixed width */
            height: 500px; /* Fixed height */
            font-size: 24px; 
            padding: auto;/* Font size */
            color:darkblue;
            background: linear-gradient(rgba(255, 255, 255, 1) 70%, rgba(114, 103, 4, 0));
        }
        #scoretext{
            display: none;
            font-size: 24px; /* Set font size */
            color: green; /* Change text color to green */
            font-weight: bold; /* Make the text bold */
            margin-top: 10px; /* Add some space above the score text */
        }
        /* Internal CSS for radio buttons */
        input[type="radio"] {
            display: none; /* Hide the default radio button */
        }

        .custom-radio {
            position: relative;
            padding-left: 30px; /* Space for the custom icon */
            cursor: pointer;
        }

        .custom-radio:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px; /* Size of the custom icon */
            height: 20px; /* Size of the custom icon */
            border: 2px solid blue; /* Border color */
            border-radius: 50%; /* Make it circular */
            background-color: white; /* Background color */
        }

        input[type="radio"]:checked + .custom-radio:before {
            background-color: blue; /* Change background when checked */
        }
        li {
            list-style-type: none; /* Remove default list styling */
        }
    </style>
</head>

<div id="scoretext" style="display: block; display: none; position: relative; width: 100%; height: 100%; padding: 100px; border-radius: 20px;">
    <div id="scoretextbg" style="background-image: url('93.webp'); background-size: cover; background-position: center; width: 100%; height: 100%; position: absolute; top: 0; left: 0; opacity: 0.5; z-index: -1;"></div>
    <p style="color:darkblue;font-size: 34px;opacity: 1;" id="celebration">ውድ <?php echo htmlspecialchars($_SESSION['name']); ?>  እንኳን ደስ አለዎ ፈተናውን በተሳካ ሁኔታ አጠናቀዋለ በፈተናው ያገኙት ውጤት ከታች ይመልከቱ፡፡</p>
    <p style="color:green;font-size: 24px; font-weight: bold; margin-top: 10px;display: none;opacity: 1;" id="score">SCORE: </p>
    <p style="font-size: 24px; color: green; font-weight: bold; margin-top: 10px; display: none;opacity: 1;" id="result">RESULT: </p>
    <p id="pass" style="font-size: 24px; color: red; font-weight: bold; margin-top: 10px; display: none;opacity: 1; "></p>
    
 </div> <!-- Initially hidden -->
 <div id="timer" style="display: none;">00:00</div>
 <script>
 document.addEventListener('DOMContentLoaded', function() {
    let pass = document.getElementById('pass');
    let scoretext = document.getElementById('scoretext');
    let result = document.getElementById('result');
    let scoreU = document.getElementById('score');
    let timerElement = document.getElementById('time');
    let timerContainer = document.getElementById('timer');
    let questionContainer = document.getElementById('question-container');
    let prevBtn = document.getElementById('prevBtn');
    let nextBtn = document.getElementById('nextBtn');
    let submitBtn = document.getElementById('submitBtn');

    if (timerContainer) {
        timerContainer.style.display = 'block';
    } else {
        console.error('Timer element not found.');
    }

    let score = 1;
    let correctANS = [];
    let wrongANS = [];
    let title = "<?php echo htmlspecialchars($tableName); ?>";
    console.log(title);

    // Timer variables
    let timeLeft = 60 * 50; // Set timer for 60 seconds

    // Start the timer
    const timer = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(timer);
            alert("Time's up!");
            // saveScore(); // Call saveScore when time is up (remove if not needed)
        } else {
            // Format minutes and seconds
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            if (timerElement) {
                timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`; // Update the timer display
            }
            timeLeft -= 1; // Decrease time left
        }
    }, 1000);

    let currentQuestion = <?php echo $currentQuestion; ?>; // Initialize current question in JavaScript
    loadQuestion(currentQuestion); // Fetch the current question on load

    function loadQuestion(questionNumber) {
        console.log('Loading question:', questionNumber); // Debugging log
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_question.php?q=' + questionNumber + '&tablename=' + encodeURIComponent(title), true); // Send table name
        xhr.onload = function() {
            if (this.status === 200) {
                if (questionContainer) {
                    questionContainer.innerHTML = this.responseText;
                }
                currentQuestion = questionNumber; // Update current question after loading
            } else {
                console.error('Error loading question:', this.status, this.statusText); // Debugging message
            }
        };
        xhr.onerror = function() {
            console.error('Request failed'); // Debugging message
        };
        xhr.send();

        if (questionNumber >= 50) {
            if (prevBtn) prevBtn.style.display = 'block';
            if (nextBtn) nextBtn.style.display = 'none';
            if (submitBtn) submitBtn.style.display = 'block';
        } else {
            if (prevBtn) prevBtn.style.display = 'block';
            if (nextBtn) nextBtn.style.display = 'block';
            if (submitBtn) submitBtn.style.display = 'none';
            console.log(currentQuestion);
        }
    }

    // Event listeners for buttons
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            const nextQuestion = currentQuestion + 1; // Increment the current question number
            loadQuestion(nextQuestion); // Load the next question
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            const prevQuestion = currentQuestion - 1; // Decrement the current question number
            if (prevQuestion > 0) {
                loadQuestion(prevQuestion); // Load the previous question
            }
        });
    }

function sendUserChoice(questionNumber, userChoice, correctAnswer) {
    // Check if the user answered the question
    if (userChoice == correctAnswer) {
        if (!correctANS.includes(questionNumber)) { // Check if questionNumber is NOT in correctANS
            correctANS.push(questionNumber);
            if (wrongANS.includes(questionNumber)) {
                wrongANS.splice(wrongANS.indexOf(questionNumber), 1);
            }
        }
    } else {
        if (!wrongANS.includes(questionNumber)) { // Check if questionNumber is NOT in wrongANS
            wrongANS.push(questionNumber);
            if (correctANS.includes(questionNumber)) {
                correctANS.splice(correctANS.indexOf(questionNumber), 1);
            }
        }
        // Show an alert if the answer is incorrect
        alert('Incorrect answer. Please try again.');
    }
    // Automatically load the next question
    nextQuestion = questionNumber + 1; // Increment the question number
    loadQuestion(nextQuestion); // Load the next question
}
  // Update the existing event listener for radio buttons
  document.querySelectorAll('input[type=radio]').forEach(radio => {
        radio.addEventListener('change', function() {
            const questionNumber = this.name.match(/\d+/)[0]; // Extract question number from name
            const userChoice = this.value; // Get the selected choice
            sendUserChoice(questionNumber, userChoice, correctAnswer); // Call the function to send choice
            
            // Show score after question 50
            
        });
    });

});
</script>

<div class="button-container">
    <button type="button" id="prevBtn" class="btn btn-secondary m-2 prev-btn" <?php echo ($currentQuestion < 1 ? 'style="display:block;"' : ''); ?>>Previous</button>
    <button type="button" id="nextBtn" class="btn btn-secondary m-2 next-btn">Next</button>
    <button type="button" id="submitBtn" class="btn btn-secondary m-2 submit-btn" style="display: none;" onclick="saveScore()">Submit</button>
   
</div>

<style>
.button-container {
    display: flex;
    justify-content: space-between; /* Aligns buttons to the left and right */
    margin-top: 20px; /* Optional: Adds some space above the buttons */
}
</style>

<?php
ob_end_flush();
include('includes/footer.php');
?>