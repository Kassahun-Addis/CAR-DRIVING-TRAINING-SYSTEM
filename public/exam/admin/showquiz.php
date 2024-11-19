<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();
define('page', 'showquiz');
define('TITLE', 'quiz.php');
include('includes/header.php');
include_once('../dbcon.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['ruser'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
$selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
$selectedSubCategory = isset($_POST['subcategory']) ? $_POST['subcategory'] : '';

echo '<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<div class="card-header text-center text-white" style="background-color: #343a40;">Select Exam Category</div>
<div class="card-body">';

// Display the dropdown form with hardcoded categories
echo '<form method="POST" action="" id="categoryForm">
<div class="form-group">
<label for="category">Category</label>
<select class="form-control" name="category" id="category" onchange="updateSubcategories()">
    <option value="">Select Category</option>
    <option value="ህዝብ 1" ' . ($selectedCategory == 'ህዝብ 1' ? 'selected' : '') . '>ህዝብ 1</option>
    <option value="ህዝብ 2" ' . ($selectedCategory == 'ህዝብ 2' ? 'selected' : '') . '>ህዝብ 2</option>
    <option value="ህዝብ 3" ' . ($selectedCategory == 'ህዝብ 3' ? 'selected' : '') . '>ህዝብ 3</option>
    <option value="ደረቅ 1" ' . ($selectedCategory == 'ደረቅ 1' ? 'selected' : '') . '>ደረቅ 1</option>
    <option value="ደረቅ 2" ' . ($selectedCategory == 'ደረቅ 2' ? 'selected' : '') . '>ደረቅ 2</option>
    <option value="ደረቅ 3" ' . ($selectedCategory == 'ደረቅ 3' ? 'selected' : '') . '>ደረቅ 3</option>
    <option value="ፈሳሽ 1" ' . ($selectedCategory == 'ፈሳሽ 1' ? 'selected' : '') . '>ፈሳሽ 1</option>
    <option value="ፈሳሽ 2" ' . ($selectedCategory == 'ፈሳሽ 2' ? 'selected' : '') . '>ፈሳሽ 2</option>
    <option value="ባለ ሶስት እግር" ' . ($selectedCategory == 'ባለ ሶስት እግር' ? 'selected' : '') . '>ባለ ሶስት እግር</option>
    <option value="አውቶሞቢል" ' . ($selectedCategory == 'አውቶሞቢል' ? 'selected' : '') . '>አውቶሞቢል</option>
    <option value="ሞተርሳይክል" ' . ($selectedCategory == 'ሞተርሳይክል' ? 'selected' : '') . '>ሞተርሳይክል</option>
    <option value="ማሽነሪ ኦፕሬተር" ' . ($selectedCategory == 'ማሽነሪ ኦፕሬተር' ? 'selected' : '') . '>ማሽነሪ ኦፕሬተር</option>
</select>
</div>

<div class="form-group">
<label for="subcategory">Subcategory</label>
<select class="form-control" name="subcategory" id="subcategory" disabled onchange="redirectToQuiz()">
    <option value="">Select Subcategory</option>
</select>
</div>
</form>';

echo '</div></div></div></div></div>';
?>

<script>
function updateSubcategories() {
    var category = document.getElementById('category').value;
    var subcategory = document.getElementById('subcategory');
    subcategory.innerHTML = '<option value="">Select Subcategory</option>'; // Reset subcategory options

    if (category) {
        subcategory.disabled = false; // Enable the subcategory dropdown
        var subcategories = {
            'ህዝብ 1': ['ህዝብ 1.1', 'ህዝብ 1.2', 'ህዝብ 1.3'],
            'ህዝብ 2': ['ህዝብ 2.1', 'ህዝብ 2.2', 'ህዝብ 2.3'],
            'ህዝብ 3': ['ህዝብ 3.1', 'ህዝብ 3.2', 'ህዝብ 3.3'],
            'ደረቅ 1': ['ደረቅ 1.1', 'ደረቅ 1.2', 'ደረቅ 1.3'],
            'ደረቅ 2': ['ደረቅ 2.1', 'ደረቅ 2.2', 'ደረቅ 2.3'],
            'ደረቅ 3': ['ደረቅ 3.1', 'ደረቅ 3.2', 'ደረቅ 3.3'],
            'ፈሳሽ 1': ['ፈሳሽ 1.1', 'ፈሳሽ 1.2', 'ፈሳሽ 1.3'],
            'ፈሳሽ 2': ['ፈሳሽ 2.1', 'ፈሳሽ 2.2', 'ፈሳሽ 2.3'],
            'ባለ ሶስት እግር': ['ባለ ሶስት እግር 1', 'ባለ ሶስት እግር 2'],
            'አውቶሞቢል': ['አውቶሞቢል 1', 'አውቶሞቢል 2'],
            'ሞተርሳይክል': ['ሞተርሳይክል 1', 'ሞተርሳይክል 2'],
            'ማሽነሪ ኦፕሬተር': ['ማሽነሪ ኦፕሬተር 1', 'ማሽነሪ ኦፕሬተር 2']
        };

        if (category in subcategories) {
            subcategories[category].forEach(function(subcat) {
                var option = document.createElement('option');
                option.value = subcat;
                option.text = subcat;
                subcategory.add(option);
            });
        }
    } else {
        subcategory.disabled = true; // Disable the subcategory dropdown if no category is selected
    }
}

function redirectToQuiz() {
    var subcategory = document.getElementById('subcategory').value;
    if (subcategory) {
        // Redirect to quizquestion.php with the selected subcategory
        window.location.href = 'quizquestion.php?subcategory=' + encodeURIComponent(subcategory);
    }
}
</script>

<?php
include('includes/footer.php');
?>
<style>
    body {
        background-color: #f8f9fa; /* Light gray background */
    }
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
    }
    .form-control {
        border-radius: 0.25rem; /* Rounded corners for inputs */
    }
    .card-header {
        font-weight: bold;
    }
</style>