<?php
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

echo '<div class="col-sm-9 col-md-10" id="gob">
<p class="p-2 text-white text-center mt-5" style="background-color: rgba(0, 0, 0, 0.9);margin-left: 150px">List of quiz</p>';

$sql = "SELECT * FROM quiz";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<table class="table" style="background-color: rgba(0, 0, 0, 0.9); color:white; margin-left: 100px;">
    <tbody>
    <tr>
    <th>No</th>
    <th>Quiz</th>
    <th>Total question</th>
    <th>mark</th>
    <th>-mark</th>
    <th>Time limit</th>
    <th>Action</th>
    </tr>';
    $c = 1;
    while ($row = $result->fetch_assoc()) {
        $eid = $row['eid'];
        $total = $row['total'];
        $tabelname = $row['tabelname'];
        echo '<tr>
        <td>' . $c++ . '</td>
        <td>' . $row["title"] . '</td>
        <td class="pl-5">' . $row["total"] . '</td>
        <td class="pl-4">' . $row["correct"] . '</td>
        <td class="pl-4">' . $row["wrong"] . '</td>
        <td>' . $row["time"] . ' min</td>
        <td><a class="btn btn-secondary" href="quizquestion.php?q=quizquestion.php&eid=' . $eid . '&total=' . $total . '&table=' . $tabelname . '">Start</a></td>
        </tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo "<p>No quizzes available.</p>";
}
?>
</div>
</div>

<?php
include('includes/footer.php');
?>
<style>
    body{
        background-image: url(../image/93bgh.png);
        background-size: cover;
        background-position: center;
    }
    #gob{
        margin-top: 100px;
    }

</style>
