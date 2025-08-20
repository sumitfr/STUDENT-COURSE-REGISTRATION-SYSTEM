<?php
// Include the connection file
include 'connection.php';

// Check if form is submitted
if (isset($_POST["addStudent"] )) {
    // Process form data
    $fullname = $_POST['fullname'];
    $matric_no = $_POST['matric_no'];
    $school = $_POST['school'];
    $department = $_POST['department'];
    $course_of_study = $_POST['course_of_study'];
    $level = $_POST['level'];

    // Insert data into the database
    $sql = "INSERT INTO students (fullname, matric_no, school, department, course_of_study, level) VALUES ('$fullname', '$matric_no', '$school', '$department', '$course_of_study', '$level')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all students from the database
$sql = "SELECT * FROM students";
$result = $conn->query($sql);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include ("nav.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Student Registration</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="matric_no">Matric No</label>
                        <input type="text" class="form-control" id="matric_no" name="matric_no" required>
                    </div>
                    <div class="form-group">
                        <label for="school">School</label>
                        <input type="text" class="form-control" id="school" name="school" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="course_of_study">Course of Study</label>
                        <input type="text" class="form-control" id="course_of_study" name="course_of_study" required>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <input type="text" class="form-control" id="level" name="level" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name='addStudent'>Submit</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Registered Students</h2>
                <div class="table-responsive">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Matric No</th>
                                <th>School</th>
                                <th>Department</th>
                                <th>Course of Study</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['fullname'] . "</td>";
                                    echo "<td>" . $row['matric_no'] . "</td>";
                                    echo "<td>" . $row['school'] . "</td>";
                                    echo "<td>" . $row['department'] . "</td>";
                                    echo "<td>" . $row['course_of_study'] . "</td>";
                                    echo "<td>" . $row['level'] . "</td>";
                                    echo "<td><form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'><input type='hidden' name='student_id' value='".$row['id']."'><button type='submit' name='remove' class='btn btn-danger'>Remove</button></form></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No students registered yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include ("footer.php");
    
    
    

// Check if remove button is clicked
if (isset($_POST['remove'])) {
    $student_id = $_POST['student_id'];
    
    // Delete student from the database
    $sql_delete = "DELETE FROM students WHERE id = '$student_id'";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Record deleted successfully";
        // Redirect to refresh the page after deletion
        header("Location: student_registration.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
    
    ?>