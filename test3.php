<!doctype html>
<?php
// Start the session
session_start();
$factors = [
    'age_gender_combinations' => [
        'Maschi' => [
            '18-45 ANNI' => 25,
            '<18 e >45 ANNI' => 20,
        ],
        'Femmine' => [
            '18-45 ANNI' => 20,
            '<18 e >45 ANNI' => 15,
        ],
    ],
    'height' => [
        '0' => 0.77,
        '25' => 0.85,
        '50' => 0.93,
        '75' => 1.00,
        '100' => 0.93,
        '125' => 0.85,
        '150' => 0.78,
        '>175' => 0.00,
    ],
    'vertical_distance' => [
        '25' => 1.00,
        '30' => 0.97,
        '40' => 0.93,
        '50' => 0.91,
        '70' => 0.88,
        '100' => 0.87,
        '170' => 0.86,
        '>175' => 0.00,
    ],
    'horizontal_distance' => [
        '25' => 1.00,
        '30' => 0.83,
        '40' => 0.63,
        '50' => 0.50,
        '55' => 0.45,
        '60' => 0.42,
        '>63' => 0.00,
    ],
    'angular_displacement' => [
        '0°' => 1.00,
        '30°' => 0.90,
        '60°' => 0.81,
        '90°' => 0.71,
        '120°' => 0.52,
        '135°' => 0.57,
        '>135°' => 0.00,
    ],
    'judgment' => [
        'Buono' => 1.00,
        'Scarso' => 0.90,
    ],
    // Define combinations for frequency and duration
    'frequency_duration_combinations' => [
        '0.20' => [
            'Continuo < 1 ora' => 1.00,
            'Continuo da 1 a 2 ore' => 0.95,
            'Continuo da 2 a 8 ore' => 0.85,
        ],
        '1' => [
            'Continuo < 1 ora' => 0.94,
            'Continuo da 1 a 2 ore' => 0.88,
            'Continuo da 2 a 8 ore' => 0.75,
        ],
        '4' => [
            'Continuo < 1 ora' => 0.84,
            'Continuo da 1 a 2 ore' => 0.72,
            'Continuo da 2 a 8 ore' => 0.45,
        ],
        '6' => [
            'Continuo < 1 ora' => 0.75,
            'Continuo da 1 a 2 ore' => 0.50,
            'Continuo da 2 a 8 ore' => 0.27,
        ],
        '9' => [
            'Continuo < 1 ora' => 0.52,
            'Continuo da 1 a 2 ore' => 0.30,
            'Continuo da 2 a 8 ore' => 0.52,
        ],
        '12' => [
            'Continuo < 1 ora' => 0.37,
            'Continuo da 1 a 2 ore' => 0.21,
            'Continuo da 2 a 8 ore' => 0.00,
        ],
        '>15' => [
            'Continuo < 1 ora' => 0.00,
            'Continuo da 1 a 2 ore' => 0.00,
            'Continuo da 2 a 8 ore' => 0.00,
        ],
    ],
    'single_lift' => [
        'No' => 1.00,
        'Si' => 0.60,
    ],
    'two_operator_lift' => [
        'No' => 1.00,
        'Si' => 0.85,
    ],
];

// Create a new MySQLi object
$conn = new mysqli("localhost", "root", null, "dvr_quiz");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = '';

// Check if the username is set in the session
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    //var_dump("nome settato");
}


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST)>2) {

    $height_choice = $_POST['choice0']; // Assuming height is the first choice
    $height_factor = $factors['height'][$height_choice];

    $vertical_distance_choice = $_POST['choice1']; // Second choice
    $vertical_distance_factor = $factors['vertical_distance'][$vertical_distance_choice];

    $horizontal_distance_choice = $_POST['choice2']; // Third choice
    $horizontal_distance_factor = $factors['horizontal_distance'][$horizontal_distance_choice];

    $angular_displacement_choice = $_POST['choice3']; // Fourth choice
    $angular_dislocation_factor = $factors['angular_displacement'][$angular_displacement_choice];

    // Handle judgment if needed
    $judgment_choice = $_POST['judgment'];
    $load_quality_factor = $factors['judgment'][$judgment_choice];

    $frequency_choice = $_POST['frequency']; // Get the user's choice for frequency
    $duration_choice = $_POST['duration']; // Get the user's choice for duration

    // Calculate frequency factor
    var_dump($frequency_choice);
    var_dump($duration_choice);
    $frequency_factor = $factors['frequency_duration_combinations'][$frequency_choice][$duration_choice];



    $gender = $_POST['gender'];
    $age_group = $_POST['age']; // Assuming you have an age selection
    $cp_factor = $factors['age_gender_combinations'][$gender][$age_group];



    // Calculate PESO LIMITE RACCOMANDATO
    $recommended_weight = $height_factor * $vertical_distance_factor * $horizontal_distance_factor * $angular_dislocation_factor * $load_quality_factor * $frequency_factor * $cp_factor;

    // Step 1: Fetch the last heavy object for the user with the highest weight
    $query = "SELECT weight FROM heavy_objects WHERE username = ? ORDER BY weight DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($heavy_object_weight);
    $stmt->fetch();
    $stmt->close();

    // Check if a heavy object was found
    if ($heavy_object_weight) {
        // Step 2: Calculate INDICE DI SOLLEVAMENTO
        if($recommended_weight!=0)
        $lifting_index = $heavy_object_weight / $recommended_weight;
        else
        $lifting_index = 99;
        /* var_dump($recommended_weight);
        var_dump($heavy_object_weight);
        var_dump($lifting_index); */
    } else {
        // Handle the case where no heavy object is found (optional)
        $lifting_index = null; // or some default value
    }
   
    // Prepare the INSERT statement directly
    $query = "INSERT INTO peso_limite (username, cp, vertical_distance_factor, horizontal_distance_factor, angular_dislocation_factor, load_quality_factor, frequency_factor, recommended_weight, r, heaviest_weight) 
    VALUES ('$name', '$cp_factor', $vertical_distance_factor, $horizontal_distance_factor, $angular_dislocation_factor, $load_quality_factor, $frequency_factor, $recommended_weight, $lifting_index, $heavy_object_weight)";

    // Execute the query
    if ($conn->query($query) !== TRUE) {
        die("Query failed: " . $conn->error);
    }

    
    // Redirect after processing
    echo "<form id='redirectForm' ";
    if ($lifting_index > 1) {
        // Check if at least one of the factors is zero
        if ($height_factor == 0 || $vertical_distance_factor == 0 || $horizontal_distance_factor == 0 || 
            $angular_dislocation_factor == 0 || $load_quality_factor == 0 || $frequency_factor == 0 || 
            $cp_factor == 0) {
            // Redirect to test4.php
            echo "action='test4.php' ";
        } else {
            // Otherwise redirect to generate_pdf.php
            echo "action='generate_pdf.php' ";
        }
    } else {
        // If lifting index is not greater than 0, redirect to generate_pdf.php
        echo "action='generate_pdf.php' ";
    }
    echo "method='post'>";
    echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
    echo "</form>";
    echo "<script>document.getElementById('redirectForm').submit();</script>";
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Seleziona Scelte</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            /*height: 100vh;*/
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 800px;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table {
            margin-bottom: 20px; /* Space between tables */
        }
        img {
            max-width: 50px; /* Adjust image size */
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form action="" method="post">
        <div>
    <strong>Genere:</strong>
    <div style="margin-bottom: 15px;">
    <input type="radio" name="gender" value="Maschi" required> Maschi
    <input type="radio" name="gender" value="Femmine" required> Femmine
    </div>
</div>
<div>
    <strong>Età:</strong>
    <div style="margin-bottom: 15px;">
        <input type="radio" name="age" value="18-45 ANNI" required> 18-45 ANNI
        <input type="radio" name="age" value="<18 e >45 ANNI" required> <18 e >45 ANNI
    </div>
</div>

<table class="table" style="width: 100%; table-layout: fixed;">
    <tbody>
        <?php
        // Dynamic choices array
        $choices = [
            [
                'img' => 'img/1.PNG',
                'desc' => "ALTEZZA DA TERRA DELLE MANI ALL'INIZIO (O ALLA FINE) DEL SOLLEVAMENTO",
                'options' => ['0', '25', '50', '75', '100', '125', '150', '>175'],
                'label' => 'Altezza (CM)'
            ],
            [
                'img' => 'img/2.PNG',
                'desc' => 'DISTANZA VERTICALE DI SPOSTAMENTO DEL PESO FRA INIZIO E FINE DEL SOLLEVAMENTO',
                'options' => ['25', '30', '40', '50', '70', '100', '170', '>175'],
                'label' => 'Dislocazione (CM)'
            ],
            [
                'img' => 'img/3.PNG',
                'desc' => 'DISTANZA ORIZZONTALE TRA LE MANI E IL PUNTO DI MEZZO DELLE CAVIGLIE',
                'options' => ['25', '30', '40', '50', '55', '60', '>63'],
                'label' => 'Distanza (CM)'
            ],
            [
                'img' => 'img/4.PNG',
                'desc' => 'DISLOCAZIONE ANGOLARE DEL PESO IN GRADI',
                'options' => ['0°', '30°', '60°', '90°', '120°', '135°', '>135°'],
                'label' => 'Dislocazione Angolare'
            ],
        ];
        
        // Loop through each choice and create rows dynamically
        foreach ($choices as $index => $choice) {
            echo "<tr>";
            echo "<td style='width: 15%; padding: 15px;'><img src='{$choice['img']}' alt='Choice Image' style='max-width: 100%;'></td>";
            echo "<td style='width: 40%; padding: 15px;'>{$choice['desc']}</td>"; // Increased padding
            echo "<td style='width: 45%; padding: 15px;'>"; // Increased padding
            echo "<div><strong>{$choice['label']}:</strong></div>"; // Custom label for each row
            foreach ($choice['options'] as $option) {
                echo "<div> <!-- Add margin for spacing -->
                        <input type='radio' name='choice{$index}' value='$option' required> $option
                      </div>";
            }
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>


<div style="margin-top: 30px;">
    <strong>GIUDIZIO SULLA PRESA DEL CARICO</strong>
    <div style="margin-bottom: 15px;">
        <input type="radio" name="judgment" value="Buono" required> Buono
        <input type="radio" name="judgment" value="Scarso" required> Scarso
    </div>
</div>

<!-- Frequency Questions -->
<div style="margin-top: 30px;">
    <strong>FREQUENZA DEI GESTI (numero di atti al minuto) IN RELAZIONE ALLA DURATA</strong>
    <div style="margin-bottom: 15px;">
        <strong>Frequenza:</strong><br>
        <input type="radio" name="frequency" value="0.20" required> 0,20<br>
        <input type="radio" name="frequency" value="1" required> 1<br>
        <input type="radio" name="frequency" value="4" required> 4<br>
        <input type="radio" name="frequency" value="6" required> 6<br>
        <input type="radio" name="frequency" value="9" required> 9<br>
        <input type="radio" name="frequency" value="12" required> 12<br>
        <input type="radio" name="frequency" value=">15" required> >15<br>
    </div>
    
    <strong>Durata:</strong><br>
    <div style="margin-bottom: 15px;">
        <input type="radio" name="duration" value="Continuo < 1 ora" required> CONTINUO < 1 ora<br>
        <input type="radio" name="duration" value="Continuo da 1 a 2 ore" required> CONTINUO da 1 a 2 ore<br>
        <input type="radio" name="duration" value="Continuo da 2 a 8 ore" required> CONTINUO da 2 a 8 ore<br>
    </div>
</div>

            <!-- Submit Button -->
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>