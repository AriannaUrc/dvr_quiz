<!doctype html>
<?php
// Start the session
session_start();

// Create a new MySQLi object
$conn = new mysqli("localhost", "root", null, "dvr_quiz");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = ''; 
$continue = true; // Default to false

// Check if the username is set in the session
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    //var_dump("nome settato");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the question from the form
    foreach ($_POST as $key => $value) {
        // Skip the 'name' index and the submit button
        if ($key === 'name' || $key === 'submit') {
            continue; 
        }

        //die(var_dump($_POST));

        $value = $conn->real_escape_string(trim($value)); // Escape input

        // Prepare the insert query
        $query = "INSERT INTO `questions` (`ID`, `qsn`, `num_domanda`, `username`) VALUES (NULL, '$value', '$key', '$name')";
        
        // Check if the value is 'Yes' to determine redirection 
        if ($value === 'No') {
            $continue = false;
        }

        // Execute the query
        if ($conn->query($query) !== TRUE) {
            die("Query failed: " . $conn->error);
        }
    }


    if (count($_POST) > 2) {
        // Prepare the name to be sent
        echo "<form id='redirectForm' action='";
    
        // Choose the action based on the condition
        if ($continue) {
            echo "generate_pdf.php"; 
        } else {
            echo "test3.php";
        }
    
        echo "' method='post'>";
        echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
        echo "</form>";
    
        // Use JavaScript to submit the form
        echo "<script>
            document.getElementById('redirectForm').submit();
        </script>";
        exit(); // Always call exit after the form submission
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
            /*height: 100vh;*/
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
        .form-group{
            max-width: 600px;
        }
    </style>
</head>

<body>
    <main>
        <form action="" method="post">
            <h2 class="text-center mb-4">Questionnaire</h2>

            <div class="form-group">
                <label>c’è una buona interfaccia tra piedi e pavimenti?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_1" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_1" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>le attività di movimentazione manuale diverse dal sollevamento sono minime e gli oggetti da sollevare non sono molto freddi, molto caldi o contaminati?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_2" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_2" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>l’ambiente termico è moderato (per ambiente termico moderato si intende un ambiente in cui vi sia una temperatura tra i 19 e i 26°C, con umidità relativa tra il 30% ed il 60% e velocità dell’aria < 0,2 m/s) EN ISO 7730?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_3" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_3" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>l'operazione può essere eseguita utilizzando solo due mani?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_4" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_4" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>c'è una buona interfaccia tra piedi e pavimenti?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_5" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_5" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>la postura è eretta e i movimenti non sono limitati?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_6" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_6" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>le attività di movimentazione manuale diverse dal sollevamento sono minime e gli oggetti da sollevare non sono molto freddi, molto caldi o contaminati?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_7" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_7" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>la movimentazione avviene da parte di un'unica persona?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_8" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_8" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>Il sollevamento è graduale?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_9" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_9" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>l'ambiente termico è moderato (per ambiente termico moderato si intende un ambiente in cui vi sia una temperatura tra i 19 e i 26 °C, con umidità relativa tra il 30% ed il 60% e velocità dell'aria minore di 0,2 m/s) EN ISO 7730? </label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_10" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_10" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>Il peso movimentato è compreso tra 5,1-10,5 Kg, viene spostato in verticale nella zona compresa tra le anche e le spalle, per una volta ogni 5 minuti?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_11" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_11" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>la movimentazione avviene a tronco eretto e non ruotato?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_12" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_12" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>durante la movimentazione il carico è tenuto vicino al corpo?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_13" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_13" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Il peso movimentato è compreso tra 3-5 Kg, viene spostato in verticale nella zona compresa tra le anche e le spalle, per una frequenza massima di 1 volta al minuto? </label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_14" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_14" value="Yes">
                        <label class="form-check-label">Yes</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Il peso movimentato è compreso tra 5,1-10,5 Kg, viene spostato in verticale nella zona compresa tra le anche e le spalle, per una volta ogni 5 minuti?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_15" value="No" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="PPR_15" value="Yes">
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
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
