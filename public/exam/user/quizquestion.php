<?php
ob_start();
define('TITLE', 'quizquestion');
define('page', 'quizquestion');
include('includes/header.php');
include('../dbcon.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}

if (isset($_SESSION['name'])) {
    $user = $_SESSION['name'];
    echo htmlspecialchars($user);
} else {
    echo "not set";
}

echo '<div class="col-sm-9 col-md-9" id="gob">';

// Initialize current question
$currentQuestion = isset($_GET['q']) ? (int)$_GET['q'] : 1; // Start at question 1

// Get the table name from the URL parameter
$tableName = isset($_GET['table']) ? $_GET['table'] : 'sign'; // Default to 'sign' if not set

// Set the table name in the session
$_SESSION['table'] = $tableName; // Store the table name in session

// Fetch the current question
$sql = mysqli_query($conn, "SELECT QNo, Question, Choice1, Choice2, Choice3, Choice4, Answer, Image FROM $tableName WHERE QNo = $currentQuestion");

if (!$sql) {
    echo '<p>Error in SQL query: ' . mysqli_error($conn) . '</p>';
    exit();
}

echo '<div id="question-container">'; // Container for the question
if (mysqli_num_rows($sql) > 0) {
    while ($row = mysqli_fetch_array($sql)) {
        $qns = $row['Question'];
        $qid = $row['QNo'];
        $correctAnswer = $row['Answer']; // Fetch the correct answer
        $total = $_REQUEST['total'] ?? 0; // Default to 0 if not set
        $_SESSION['total'] = $total;

        // echo '<h3>' . htmlspecialchars($qns) . '</h3>'; // Display the question

        // Display question image if it exists
        if (!empty($row['Image'])) {
            $binaryData = hex2bin(substr($row['Image'], 2));
            $imageData = base64_encode($binaryData);
            echo "<div class='question-image'>
                    <img src='data:image/png;base64," . $imageData . "' alt='Question Image' style='width: 100px; height: 100px;'><br>
                  </div>";
        }

        // Initialize result variable
        $result = null; // Variable to store the result of the answer check

        // After displaying choices
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Store the user's answer in the session
            foreach ($_POST['uid'] as $questionNumber => $userAnswer) {
                $_SESSION['answers'][$questionNumber] = (int)$userAnswer; // Store the answer as an integer in session

                // Check if the user's answer matches the correct answer
                if ($userAnswer == $correctAnswer) {
                    $result = 'Correct';
                } else {
                    $result = 'Incorrect. Correct answer: ' . htmlspecialchars($correctAnswer); // Store result as incorrect with correct answer
                }

                // Display the result
                echo '<p>' . $result . '</p>';
            }
        }
    }
} else {
    echo '<p>No questions found.</p>'; // Debugging message
}
echo '</div>'; // Close question-container
?>


<head>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Add Font Awesome -->
    <style>
        body{
            background-color: white;     /* Set the gray background */
            /* background-image: url('../image/93bgh.png'); 
            background-size: cover;
            background-position: center;*/
        }
         .card {
            border: none;
            width: 100%; /* Fixed width */
            height: 500px; /* Fixed height */
            font-size: 28px; 
            padding: auto;/* Font size */
            color:darkblue;
            background: linear-gradient(rgba(255, 255, 255, 1) 70%, rgba(114, 103, 4, 0));
            margin-left: 140px;
            margin-top: 10px;
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
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px; /* Size of the custom icon */
            height: 30px; /* Size of the custom icon */
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
        /* Add this CSS to your existing styles */
        #skipped-questions-list li {
            cursor: pointer; /* Change cursor to pointer */
            padding: 5px; /* Add some padding for better click area */
            transition: background-color 0.3s; /* Smooth transition for background color */
        }

        #skipped-questions-list li:hover {
            background-color: green; /* Change background color on hover */
        }
        #pass, #scoretext, #wrong, #skipped, #score {
            font-size: 24px;
            color: red;
            font-weight: bold;
            margin-top: 10px;
            display: none;
            opacity: 1;
        }
</style>
</head>

<div id="scoretext" style="background-color: white; display: block; display: none; position: relative; width: 100%; height: 100%; padding: 100px;padding-bottom: 20px; border-radius: 60px; margin-left: 140px;margin-top:100px;">
    
        <p style="color:darkblue;font-size: 34px;opacity: 1;" id="celebration">ውድ <?php echo htmlspecialchars($_SESSION['name']); ?>  እንኳን ደስ አለዎ ፈተናውን በተሳካ ሁኔታ አጠናቀዋለ በፈተናው ያገኙት ውጤት ከታች ይመልከቱ፡፡</p>
        
        <p style="font-size: 24px; color: green; font-weight: bold; margin-top: 10px; display: none;opacity: 1;" id="wrong">በትክክል ያልተመለሱ: </p>
        <p style="font-size: 24px; color: green; font-weight: bold; margin-top: 10px; display: none;opacity: 1;" id="skipped">የተዘለሉ ጥያቂዎች: </p>
        <p style="color:green;font-size: 24px; font-weight: bold; margin-top: 10px;display: none;opacity: 1;" id="score">አጠቃላይ ውጤት : </p>
        <p id="pass" style="font-size: 24px; color: red; font-weight: bold; margin-top: 10px; display: none;opacity: 1; "></p>
        <div id="scoretextbg" ><img src="../image/fail.jpg" alt="93" style="width: 30%; height: 30%; object-fit: cover;  opacity: 0.5; z-index: -1;margin-left:200px"></div>
        
</div>

<p style="font-size: 24px; color: green; font-weight: bold; margin: 0; opacity: 1; display: inline-flex; position: absolute; top: 0; right: -230px;" id="skipped-count" title="Number of skipped questions" onclick="toggleSkippedQuestions()">
    <img src="../image/skip.png" alt="93" style="width: 30px; height: 30px; object-fit: cover; margin-right: 10px; border:2px solid red; border-radius: 50%; background-color: red;"><span id="skippedc">0</span>
</p>
<div id="skipped-questions-dropdown" style="display: none; position: absolute; top: 30px; right: -230px; background-color: white; border: 1px solid #ccc; z-index: 1000;">
    <ul id="skipped-questions-list" style="list-style-type: none; padding: 10px; margin: 0;">
        <!-- Skipped questions will be populated here -->
    </ul>
</div>
<script>
    let pass = document.getElementById('pass');
    let scoretext = document.getElementById('scoretext');
    let wrong = document.getElementById('wrong');
    let scoreU = document.getElementById('score');
    var score = 1;
    let correctANS = [];
    let wrongANS = [];
    let skippedQuestions = [];
    let title = "<?php echo htmlspecialchars($tableName); ?>"; 
    var nextQuestion = "";
    let currentQuestion = <?php echo $currentQuestion; ?>; 
    let skipped=0;
    let skippedQuestionsA = [];
    console.log(title);

    // Timer variables
    let timeLeft = 60 * 50; // Set timer for 60 seconds
    const timerElement = document.getElementById('time'); // Assuming you have an element with id 'time' in the header
    document.getElementById('timer').style.display = 'block'; // Show the timer in the header
    // Start the timer
    const timer = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(timer);
            alert("Time's up!");
            saveScore(); // Call saveScore when time is up
        } else {
            // Format minutes and seconds
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`; // Update the timer display
            timeLeft -= 1; // Decrease time left
        }
    }, 1000);
    let previousQuestion = null; // Variable to store the previous question number

    function loadQuestion(questionNumber) {
        console.log('Loading question:', questionNumber); // Debugging log
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_question.php?q=' + questionNumber + '&tablename=' + encodeURIComponent(title), true); // Send table name
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('question-container').innerHTML = this.responseText;
                currentQuestion = questionNumber; // Update current question after loading
            } else {
                console.error('Error loading question:', this.status, this.statusText); // Debugging message
            }
        };
        xhr.onerror = function() {
            console.error('Request failed'); // Debugging message
        };
        xhr.send();
        if(questionNumber >= 50){
            
            document.getElementById('prevBtn').style.display = 'block';
            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('submitBtn').style.display = 'block';                       
        }
        else{
            document.getElementById('prevBtn').style.display = 'block';
            document.getElementById('nextBtn').style.display = 'block';
            document.getElementById('submitBtn').style.display = 'none';
            console.log(currentQuestion);            
        }            
    }

    document.addEventListener('DOMContentLoaded', function() {
        let currentQuestion = <?php echo $currentQuestion; ?>; // Initialize current question in JavaScript
        loadQuestion(currentQuestion); // Fetch the current question on load
     
        function loadQuestion(questionNumber) {
            console.log('Loading question:', questionNumber); // Debugging log
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_question.php?q=' + questionNumber + '&tablename=' + encodeURIComponent(title), true); // Send table name
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('question-container').innerHTML = this.responseText;
                    currentQuestion = questionNumber; // Update current question after loading
                } else {
                    console.error('Error loading question:', this.status, this.statusText); // Debugging message
                }
            };
            xhr.onerror = function() {
                console.error('Request failed'); // Debugging message
            };
            xhr.send();
            if(questionNumber >= 50){
                
                document.getElementById('prevBtn').style.display = 'block';
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('submitBtn').style.display = 'block';                               
            }
            else{
                document.getElementById('prevBtn').style.display = 'block';
                document.getElementById('nextBtn').style.display = 'block';
                document.getElementById('submitBtn').style.display = 'none';
                console.log(currentQuestion);                
            }                       
        }

        document.querySelector('.next-btn').addEventListener('click', function() {
      
            saveScore(); // Load the next question
        });

        let skippedQuestions = []; // Array to store skipped question numbers

        document.querySelector('.prev-btn').addEventListener('click', function() {
            // Decrement the current question number
           
                skippedQuestionsA.push(currentQuestion); // Save the current question number before loading the previous one
              // Load the previous question
              populateSkippedQuestions();
            skipped++;
            
            document.getElementById('skippedc').innerText = skipped; // Update the skipped count display
            
            nextQuestion = currentQuestion + 1; // Increment the question number
            loadQuestion(nextQuestion);
            // Load the next question
        });        

        // After loading the 50th question, display skipped questions
        if (currentQuestion >= 50) {
            let skippedQuestions = []; // Call function to display skipped questions
        }

        function displaySkippedQuestions() {
            if (skippedQuestions.length > 0) {
                console.log('Skipped Questions:', skippedQuestions); // Display skipped questions in console
                // You can also display this information in the UI as needed
            }
        }
    });

    function sendUserChoice(questionNumber, userChoice, correctAnswer) {
    if (userChoice == correctAnswer) {
        if (!correctANS.includes(questionNumber)) {
            correctANS.push(questionNumber);
            if (wrongANS.includes(questionNumber)) {
                wrongANS.splice(wrongANS.indexOf(questionNumber), 1);
            }
        }
    } else {
        if (!wrongANS.includes(questionNumber)) {
            wrongANS.push(questionNumber);
            if (correctANS.includes(questionNumber)) {
                correctANS.splice(correctANS.indexOf(questionNumber), 1);
            }
        }
        // Display the modal with the incorrect message
        // showModal(`ትክትክለኛውን መልስ የያዘው ምርጫ ምርጫ ${correctAnswer} ነው።`);
    }
    nextQuestion = questionNumber + 1;
    loadQuestion(nextQuestion);
}

function showModal(message) {
    const modal = document.getElementById('incorrectModal');
    const modalMessage = document.getElementById('modalMessage');
    const okButton = document.getElementById('okButton');

    modalMessage.textContent = message;
    modal.style.display = 'block';

    okButton.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

    document.addEventListener('DOMContentLoaded', function() {
        const pass = document.getElementById('pass');
        const scoretextbg = document.getElementById('scoretextbg');
        // const scoretext = document.getElementById('scoretext');

        window.saveScore = function() {
            let total = correctANS.length + wrongANS.length;
            let correct = correctANS.length;
            let wrong = wrongANS.length;
            let skipped = skippedQuestions.length;
            let score = correct;
            let user = "<?php echo htmlspecialchars($_SESSION['name']); ?>";
            let traineeId = <?php echo isset($_SESSION['trainee_id']) ? $_SESSION['trainee_id'] : 'null'; ?>;


            console.log("saveScore function called");
            console.log("Total:", total, "Correct:", correct, "Wrong:", wrong);

            if (correct * 2 >= 74) {
                pass.innerHTML = "😄😃Dear <?php echo htmlspecialchars($_SESSION['name']); ?>, you passed! 😄😃";
                scoretextbg.style.backgroundImage = 'url(../image/pass.jpg)';
                pass.style.display = 'block';
                scoretext.style.display = 'block'; // Show the scoretext container
            } else {
                pass.innerHTML = "😥😥Dear <?php echo htmlspecialchars($_SESSION['name']); ?>, you failed the exam. 😥😥";
                scoretextbg.style.backgroundImage = 'url(../image/fail.jpg)';
                pass.style.display = 'block';
                scoretext.style.display = 'block'; // Show the scoretext container
            }

            console.log("Pass message displayed:", pass.innerHTML);

            // Send data using fetch
            const payload = {
            total: total || 0,
            correct: correct || 0,
            wrong: wrong || 0,
            user: user || '',
            title: title || '',
            trainee_id: traineeId || 0,
            score: score || 0,
        };

        console.log("Data to be sent:", payload);
        
        fetch('http://127.0.0.1:8000/api/save-exam-score', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Score saved successfully:', data);
                displayScore(total, correct, wrong, score, user, title);
            })
            .catch(error => {
                console.error('Error saving score:', error);
            });
        };

        const submitButton = document.getElementById('submitBtn');
        if (submitButton) {
            submitButton.addEventListener('click', saveScore);
        }
    });

   
    function displayScore(total, correct, wrong, score, user,title) {
        // Create a new XMLHttpRequest to send score data            
        document.getElementById('question-container').style.display = 'none';
        document.getElementById('scoretext').style.display = 'block'; // Display score
        document.getElementById('score').style.display = 'block';
        document.getElementById('wrong').style.display = 'block';
        // document.getElementById('skipped').style.display = 'block';
        document.getElementById('score').innerHTML = 'በትክክል የተመለሱ: ' + correctANS.length;
        document.getElementById('wrong').innerHTML = 'በትክክልያልተመለሱ: ' + wrongANS.length;
        document.getElementById('skipped').innerHTML = 'የተዘለሉ ጥያቂዎች: ' + skippedQuestions.length;

        console.log('correctANS: ' + correctANS.length);
        console.log('wrongANS: ' + wrongANS.length);
        console.log('skippedQuestions: ' + skippedQuestions.length);
        document.getElementById('prevBtn').style.display = 'none';
        document.getElementById('nextBtn').style.display = 'none';
        document.getElementById('submitBtn').style.display = 'none';
    }

    // Update the existing event listener for radio buttons
    document.querySelectorAll('input[type=radio]').forEach(radio => {
        radio.addEventListener('change', function() {
            const questionNumber = this.name.match(/\d+/)[0]; // Extract question number from name
            const userChoice = this.value; // Get the selected choice
            sendUserChoice(questionNumber, userChoice, correctAnswer); // Call the function to send choice
        });
    });

    document.addEventListener("keydown", function(event) {
        if ("1234".includes(event.key)) {
            var choiceId = "choice" + event.key; 
            document.getElementById(choiceId).checked = true;
            
        }
    });
            
    // Assuming this array is populated with skipped question numbers
    function toggleSkippedQuestions() {
        const dropdown = document.getElementById('skipped-questions-dropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none'; // Toggle dropdown visibility
         // Populate the dropdown with skipped questions
         populateSkippedQuestions();
    }

    function populateSkippedQuestions() {
        let list = document.getElementById('skipped-questions-list');
        // Clear existing list items
        list.innerHTML = ''; // Clear existing items first
        for (let i = 0; i < skippedQuestionsA.length; i++) {
            let listItem = document.createElement('li'); // Create a new list item
            listItem.textContent = skippedQuestionsA[i]; // Set the text content to the skipped question number
            
            // Add click event listener to the list item
            listItem.addEventListener('click', function() {
                previousQuestion = currentQuestion; // Store the current question before loading a skipped question
                loadQuestion(skippedQuestionsA[i]); // Load the skipped question when clicked
            });

            list.appendChild(listItem); // Append the list item to the list
            console.log(skippedQuestionsA + " skipped");
        }
    }

    // After answering the skipped question, return to the previous question
    function returnToPreviousQuestion() {
        if (previousQuestion !== null) {
            loadQuestion(previousQuestion); // Load the previous question
            previousQuestion = null; // Reset previous question
        }
    }
</script>

<div class="button-container">
    <button type="button" id="prevBtn" class="btn btn-secondary m-2 prev-btn" <?php echo ($currentQuestion < 1 ? 'style="display:block;"' : ''); ?>>ዝለል(0)</button>
    <button type="button" id="nextBtn" class="btn btn-secondary m-2 next-btn">አስረክብ</button>
    <button type="button" id="submitBtn" class="btn btn-secondary m-2 submit-btn" style="display: none;">አስረክብ</button>  
</div>

<style>
.button-container {
    display: flex;
    justify-content: space-between; /* Aligns buttons to the left and right */
    margin-top: 20px; /* Optional: Adds some space above the buttons */
    margin-left: 180px;
}
.prev-btn,.next-btn,.submit-btn{
    font-size: 34px;
    padding: 5px 30px;
    color: darkblue;
    box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.8) inset;
    background-color: white;
}
</style>

<?php
ob_end_flush();
include('includes/footer.php');
?>