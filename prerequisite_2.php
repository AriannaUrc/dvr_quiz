<?php
// Include database connection
$conn = new mysqli("localhost", "root", null, "dvr_quiz");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = '';
if (isset($_POST['name'])) {
    $name = $_POST['name'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question1'])) {
    $response = $_POST['question1'];

    // Prepare the insert query for the response
    $query = "INSERT INTO `questions` (`ID`, `qsn`, `username`, `num_domanda`) VALUES (NULL, '$response', '$name', 'pre2')";

    if ($conn->query($query) === TRUE) {
        // Check response and redirect accordingly
        if ($response === 'No') {
            echo "<form id='redirectForm' action='generate_pdf.php' method='post'>";
            echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
            echo "</form>";
            echo "<script>
                document.getElementById('redirectForm').submit();
            </script>";
            exit(); // Always call exit after the form submission
        } elseif ($response === 'Yes') {
            echo "<form id='redirectForm' action='prerequisite_3.php' method='post'>";
            echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
            echo "</form>";
            echo "<script>
                document.getElementById('redirectForm').submit();
            </script>";
            exit(); // Always call exit after the form submission
        }
    } else {
        die("Query failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>New Form</title>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Checklist</h2>
    
    <form method="post">
        <div class="form-group">
            <label for="question1">Presenza di oggetti di peso superiore o uguale a 3 kg da sollevare manualmente, almeno una volta all’ora?</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="question1" value="No" required>
                    <label class="form-check-label">No</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="question1" value="Yes">
                    <label class="form-check-label">Si</label>
                </div>
            </div>
        </div>

        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
        
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
