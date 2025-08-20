<?php
include 'connection.php'; // Include the connection file

// Check if form is submitted
if (isset($_POST["addCourse"] )) {
    // Process form data
    $student_id = $_POST['student_id'];
    $course_code = $_POST['course_code'];
    $course_title = $_POST['course_title'];
    $course_unit = $_POST['course_unit'];
    $course_status = $_POST['course_status'];
    $level = $_POST['level'];
    $semester = $_POST['semester'];

    // Insert data into the database
    $sql = "INSERT INTO course_registration (student_id, course_code, course_title, course_unit, course_status, level, semester) VALUES ('$student_id', '$course_code', '$course_title', '$course_unit', '$course_status', '$level', '$semester')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: course_registration.php");

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all students from the database
$sql_students = "SELECT * FROM students";
$result_students = $conn->query($sql_students);

// Fetch all course registrations with student names from the database
$sql_courses = "SELECT course_registration.*, students.fullname FROM course_registration INNER JOIN students ON course_registration.student_id = students.id";
$result_courses = $conn->query($sql_courses);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include ("nav.php"); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Register a New Course</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="student_id">Student Name</label>
                        <select class="form-control" id="student_id" name="student_id" required>
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
                    <div class="form-group">
                        <label for="course_code">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" required>
                    </div>
                    <div class="form-group">
                        <label for="course_title">Course Title</label>
                        <input type="text" class="form-control" id="course_title" name="course_title" required>
                    </div>
                    <div class="form-group">
                        <label for="course_unit">Course Unit</label>
                        <input type="number" class="form-control" id="course_unit" name="course_unit" required>
                    </div>
                    <div class="form-group">
                        <label for="course_status">Course Status</label>
                        <select class="form-control" id="course_status" name="course_status" required>
                            <option value="Compulsory">Compulsory</option>
                            <option value="Elective">Elective</option>
                            <option value="Required">Required</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <input type="text" class="form-control" id="level" name="level" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input type="text" class="form-control" id="semester" name="semester" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="addCourse">Submit</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Registered Courses</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Course Unit</th>
                            <th>Course Status</th>
                            <th>Level</th>
                            <th>Semester</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_courses->num_rows > 0) {
                            while ($row = $result_courses->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['fullname'] . "</td>";
                                echo "<td>" . $row['course_code'] . "</td>";
                                echo "<td>" . $row['course_title'] . "</td>";
                                echo "<td>" . $row['course_unit'] . "</td>";
                                echo "<td>" . $row['course_status'] . "</td>";
                                echo "<td>" . $row['level'] . "</td>";
                                echo "<td>" . $row['semester'] . "</td>";
                                echo "<td><form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'><input type='hidden' name='course_reg_id' value='" . $row['id'] . "'><button type='submit' name='remove_course' class='btn btn-danger'>Remove</button></form></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No courses registered yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
// Check if remove course button is clicked
if (isset($_POST['remove_course'])) {
    $course_reg_id = $_POST['course_reg_id'];

    // Delete course registration from the database
    $sql_delete_course = "DELETE FROM course_registration WHERE id = '$course_reg_id'";
    if ($conn->query($sql_delete_course) === TRUE) {
        echo "Record deleted successfully";
        // Redirect to refresh the page after deletion
        header("Location: course_registration.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
    <?php include ("footer.php"); ?>
