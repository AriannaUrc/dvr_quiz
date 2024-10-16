<!doctype html>
<?php

// Create a new MySQLi object
$conn = new mysqli("localhost", "root", null, "dvr_quiz");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = ''; 
$question1 = ''; 
//var_dump($_POST);
// Check if the username is set in the session
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    //var_dump($name);
}

// Check if the form was submitted for the question
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the question from the form
    if (isset($_POST['domanda1'])) {
        $question1 = $conn->real_escape_string(trim($_POST['domanda1']));

        // Loop through the $_POST array
        foreach ($_POST as $key => $value) {
            // Skip the 'name' index
            if ($key === 'name' || $key === 'submit') {
                continue; // Skip this iteration
            }
            // Prepare the insert query
            $query = "INSERT INTO `questions` (`ID`, `qsn`, `username`, `num_domanda`) VALUES (NULL, '$value', '$name', '$key')";

            // Execute the query
            if ($conn->query($query) === TRUE) 
            {
                if ($conn->affected_rows > 0) {
                    //echo "Submitted successfully";
                }
            } 
            else 
            {
                die("Query failed: " . $conn->error);
            }
        }
    }
}

// Close the connection
$conn->close();
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Questionnaire</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .form-container {
            width: 100%;
            max-width: 600px; /* Limit the form width */
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>

            <form action="" method="post">
            <h2 class="text-center mb-4">Questionnaire</h2>
    
            <div class="form-group">
                <label for="domanda1">L’attività consiste nel sollevare un carico</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda1" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda1" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="domanda2">L’attività consiste nel deporre un carico</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda2" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda2" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="domanda3">L’attività consiste nello spingere un carico</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda3" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda3" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="domanda4">L’attività consiste nel tirare un carico</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda4" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda4" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="domanda5">L’attività consiste nel portare o spostare un carico</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda5" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="domanda5" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>



        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
