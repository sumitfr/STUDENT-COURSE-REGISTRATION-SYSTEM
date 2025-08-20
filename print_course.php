<?php
include 'connection.php'; // Include the connection file

// Fetch distinct levels from the course_registration table
$sql_levels = "SELECT DISTINCT level FROM course_registration";
$result_levels = $conn->query($sql_levels);

// Fetch distinct semesters from the course_registration table
$sql_semesters = "SELECT DISTINCT semester FROM course_registration";
$result_semesters = $conn->query($sql_semesters);

// Fetch all students from the database
$sql_students = "SELECT * FROM students";
$result_students = $conn->query($sql_students);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $selected_level = $_POST['level'];
    $selected_semester = $_POST['semester'];
    $selected_student_id = $_POST['student_id'];

    // Fetch courses based on selected level, semester, and student
    $sql_courses = "SELECT * FROM course_registration WHERE level = '$selected_level' AND semester = '$selected_semester' AND student_id = '$selected_student_id'";
    $result_courses = $conn->query($sql_courses);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Course</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .hide-on-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php include ("nav.php"); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 hide-on-print">
                <h2>Fetch Registered Courses</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select class="form-control" id="level" name="level" required>
                            <option value="">Select Level</option>
                            <?php
                            if ($result_levels->num_rows > 0) {
                                while ($row = $result_levels->fetch_assoc()) {
                                    echo "<option value='" . $row['level'] . "'>" . $row['level'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No levels found</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <?php
                            if ($result_semesters->num_rows > 0) {
                                while ($row = $result_semesters->fetch_assoc()) {
                                    echo "<option value='" . $row['semester'] . "'>" . $row['semester'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No semesters found</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="student_id">Student Name</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            <?php
                            if ($result_students->num_rows > 0) {
                                while ($row = $result_students->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['fullname'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No students found</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Fetch Courses</button>
                </form>
            </div>
            <div class="col-md-8">
                <h2>Print Course Information</h2>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($result_courses) && $result_courses->num_rows > 0) {
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Student Name</th>";
                    echo "<th>Course Code</th>";
                    echo "<th>Course Title</th>";
                    echo "<th>Course Unit</th>";
                    echo "<th>Course Status</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = $result_courses->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['student_id'] . "</td>";
                        echo "<td>" . $row['course_code'] . "</td>";
                        echo "<td>" . $row['course_title'] . "</td>";
                        echo "<td>" . $row['course_unit'] . "</td>";
                        echo "<td>" . $row['course_status'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<button class='btn btn-primary' onclick='window.print()'>Print</button>";
                } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($result_courses) && $result_courses->num_rows == 0) {
                    echo "No courses found for the selected level, semester, and student.";
                }
                ?>
            </div>
        </div>
    </div>

    <?php include ("footer.php"); ?>
