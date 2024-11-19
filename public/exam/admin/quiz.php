<?php
ob_start();
define('TITLE','quiz.php');
define('page','quiz.php');
include_once('../dbcon.php');
session_start();
include('includes/header.php');
if(!isset($_SESSION['radmin'])) {
    header("location:adminlogin.php");
}

if(isset($_REQUEST['submit'])) {
    $title = $_REQUEST['Title'];
    $exam_type = $_REQUEST['exam_type'];
    $sub_exam_type = $_REQUEST['sub_exam_type'];
    $total = $_REQUEST['question'];
    $_SESSION['qns'] = $total;
    $correct = $_REQUEST['correct'];
    $_SESSION['right'] = $correct;
    $wrong = $_REQUEST['wrong'];
    $_SESSION['mistake'] = $wrong;
    $time = $_REQUEST['time'];
    $tabelname = $_REQUEST['tabelname']; // Retrieve the selected table name
    $eid = uniqid();
    $_SESSION['eid'] = $eid;
    if($title == '' || $time == '' || $tabelname == '') { // Check if table name is selected
        $msg = "All fields are required";
    } else {
        $sql = "INSERT INTO quiz (eid, examType, subExamType, tabelname, title, total, correct, wrong, time) VALUES ('$eid', '$exam_type', '$sub_exam_type', '$tabelname', '$title', '$total', '$correct', '$wrong', '$time')";
        if($conn->query($sql) == TRUE) {
            // Redirect to the desired URL
            header("Location: /exam/admin/showquiz.php");
            exit(); // Ensure no further code is executed
        } else {
            $msg = "Unable to insert";
        }
    }
}
ob_end_flush();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center text-white" style="background-color: #343a40;">Enter Quiz Details</div>
                <div class="card-body">
                    <form action="" method="POST" id="myForm">
                        <div class="form-group">
                            <label for="exam_type">Exam Type</label>
                            <select class="form-control" name="exam_type" id="exam_type" onchange="updateSubExamTypes()" required>
                                <option value="">Select Exam Type</option>
                                <option value="General">General</option>
                                <option value="ህዝብ 1">ህዝብ 1</option>
                                <option value="ህዝብ 2">ህዝብ 2</option>
                                <option value="ህዝብ 3">ህዝብ 3</option>
                                <option value="ደረቅ 1">ደረቅ 1</option>
                                <option value="ደረቅ 2">ደረቅ 2</option>
                                <option value="ደረቅ 3">ደረቅ 3</option>
                                <option value="ፈሳሽ 1">ፈሳሽ 1</option>
                                <option value="ፈሳሽ 2">ፈሳሽ 2</option>
                                <option value="ባለ ሶስት እግር">ባለ ሶስት እግር</option>
                                <option value="አውቶሞቢል">አውቶሞቢል</option>
                                <option value="ሞተርሳይክል">ሞተርሳይክል</option>
                                <option value="ማሽነሪ ኦፕሬተር">ማሽነሪ ኦፕሬተር</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sub_exam_type">Sub Exam Type</label>
                            <select class="form-control" name="sub_exam_type" id="sub_exam_type" disabled required>
                                <option value="">Select Sub Exam Type</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Title">Quiz Title</label>
                            <input type="text" placeholder="Enter quiz Title" class="form-control" name="Title" required>
                        </div>
                        <div class="form-group">
                            <label for="question">Number of Questions</label>
                            <input type="number" class="form-control" placeholder="Enter number of Questions" name="question" required>
                        </div>
                        <div class="form-group">
                            <label for="correct">Mark on Right</label>
                            <input type="number" class="form-control" placeholder="Enter mark on right" name="correct" required>
                        </div>
                        <div class="form-group">
                            <label for="wrong">Marks on Wrong</label>
                            <input type="number" class="form-control" placeholder="Marks on wrong without sign" name="wrong" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Time Limit</label>
                            <input type="number" class="form-control" placeholder="Enter time limit" name="time" required>
                        </div>
                        <div class="form-group">
                            <label for="tabelname">Table Name</label>
                            <select class="form-control" name="tabelname" required>
                                <option value="">Select Table Name</option>
                                <?php
                                $sql_tables = "SHOW TABLES"; // Fetch all table names
                                $result_tables = $conn->query($sql_tables);
                                $table_options = "";
                                if ($result_tables->num_rows > 0) {
                                    while($row = $result_tables->fetch_array()) {
                                        $table_name = $row[0];
                                        $table_options .= "<option value='$table_name'>$table_name</option>";
                                    }
                                }
                                echo $table_options;
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark btn-block" name="submit">Submit </button>
                        <p class="alert mt-3"><?php if(isset($msg)) echo $msg ?></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateSubExamTypes() {
    var examType = document.getElementById('exam_type').value;
    var subExamType = document.getElementById('sub_exam_type');
    subExamType.innerHTML = '<option value="">Select Sub Exam Type</option>'; // Reset subcategory options

    if (examType) {
        subExamType.disabled = false; // Enable the subcategory dropdown
        var subExamTypes = {
            'General': ['General 1', 'General 2', 'General 3'],
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

        if (examType in subExamTypes) {
            subExamTypes[examType].forEach(function(subType) {
                var option = document.createElement('option');
                option.value = subType;
                option.text = subType;
                subExamType.add(option);
            });
        }
    } else {
        subExamType.disabled = true; // Disable the subcategory dropdown if no category is selected
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