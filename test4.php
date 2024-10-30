<!doctype html>
<?php
// Include database connection
$conn = new mysqli("localhost", "root", null, "dvr_quiz");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = '';
$continue = false; // Default to false

if (isset($_POST['name'])) {
    $name = $_POST['name'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST)>2) {
    // Loop through POST data
    foreach ($_POST as $key => $value) {
        // Skip the 'name' index and the submit button
        if ($key === 'name' || $key === 'submit') {
            continue; 
        }

        $value = $conn->real_escape_string(trim($value)); // Escape input

        // Prepare the insert query for the response
        $query = "INSERT INTO `critical_situation` (`ID`, `qsn`, `username`, `num_domanda`) VALUES (NULL, '$value', '$name', '$key')";

        // Execute the query
        if ($conn->query($query) !== TRUE) {
            die("Query failed: " . $conn->error);
        }
    }

    // Prepare redirection based on responses
    echo "<form id='redirectForm' action='";
    echo "generate_pdf.php";
    echo "' method='post'>";
    echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
    echo "</form>";

    // Use JavaScript to submit the form
    echo "<script>
        document.getElementById('redirectForm').submit();
    </script>";
    exit(); // Always call exit after the form submission
}

// Close the connection
$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Critical Situations Questionnaire</title>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
        h3 {
            color: #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <form action="" method="post">
        <h3>SITUAZIONI CRITICHE: RICHIEDONO PROVVEDIMENTI IMMEDIATI</h3>

        <div class="form-group">
            <label><strong>Distanza verticale (altezza da terra all’inizio presa) > 175 cm?</strong></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vertical_distance_critical" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vertical_distance_critical" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Dislocazione verticale (spostamento verticale del peso dall’inizio alla fine del movimento) > 175 cm?</strong></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="displacement_vertical_critical" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="displacement_vertical_critical" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Distanza orizzontale del peso dal corpo > 63 cm?</strong></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="horizontal_distance_critical" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="horizontal_distance_critical" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Rotazione del tronco > 135 gradi?</strong></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="trunk_rotation_critical" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="trunk_rotation_critical" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Frequenza di sollevamento in base al tempo dedicato alla movimentazione carichi?</strong></label> 
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="lifting_frequency_1" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="lifting_frequency_1" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            <div>
                <strong>N. sollevamenti superiori o uguali a 13/minuto, tempo dedicato inferiore ad un'ora?</strong>
            </div>
            <div>
                <strong>N. sollevamenti superiori o uguali a 11/minuto, tempo dedicato inferiore 1-2 ore?</strong>
            </div>
            <div>
                <strong>N. sollevamenti superiori o uguali a 9/minuto, tempo dedicato superiore a 2 ore?</strong>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Presenza di pesi maggiori a 25 Kg per l’uomo e 20 Kg per la donna (di età compresa tra i 18 e i 45 anni)?</strong></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="weight_critical_1" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="weight_critical_1" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><strong>Presenza di pesi maggiori a 20 Kg per l’uomo e 15 Kg per la donna (di età inferiore ai 18 e superiore ai 45 anni)?</strong></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="weight_critical_2" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="weight_critical_2" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <h3>ADOZIONE DI ULTERIORI MISURE CORRETTIVE SE RISPONDE NO AD UNO DEI SEGUENTI QUESITI</h3>

        <div class="form-group">
            <label><strong>FORMAZIONE/INFORMAZIONE/ADDESTRAMENTO:</strong></label>
            <div>
                <strong>Sono state fornite ai lavoratori le informazioni adeguate relativamente al peso ed alle altre caratteristiche del carico movimentato?</strong>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="info_provided" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="info_provided" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
            <div>
                <strong>È stata fornita formazione, ai lavoratori, adeguata in relazione ai rischi lavorativi ed alle modalità di corretta esecuzione delle attività?</strong>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="training_provided" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="training_provided" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
            <div>
                <strong>È stato fornito ai lavoratori l’addestramento adeguato in merito alle corrette manovre e procedure da adottare nella movimentazione manuale dei carichi?</strong>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="adequate_training" value="si" required>
                    <label class="form-check-label">Sì</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="adequate_training" value="no" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <!-- Hidden input to keep track of the username -->
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
