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

// Check if the username is set in the session
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    //var_dump($name);
}


// Check if the form was submitted for the table
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["items"][0])) {
   // die(var_dump($_POST));
    // Loop through the submitted data
    foreach ($_POST['items'] as $item) {
        $description = $conn->real_escape_string(trim($item['description']));
        $num_objects = (int) $item['num_objects'];
        $num_lifts_per_object = (int) $item['num_lifts_per_object'];
        $weight = (float) $item['weight'];
        $duration = $conn->real_escape_string(trim($item['duration']));
        $num_workers = (int) $item['num_workers'];

        
        // Prepare the insert query
        $query = "INSERT INTO `heavy_objects` (`ID`, `description`, `num_objects`, `num_lifts_per_object`, `weight`, `duration`, `num_workers`, `username`) VALUES (NULL, '$description', $num_objects, $num_lifts_per_object, $weight, '$duration', $num_workers, '$name')";
        //die(var_dump(($query)));
        // Execute the query
        if ($conn->query($query) !== TRUE) {
            die("Query failed: " . $conn->error);
        }
    }

    // Redirect after successful submission
    echo "<form id='redirectForm' action='test2.php' method='post'>";
    echo "<input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
    echo "</form>";
    echo "<script>
        document.getElementById('redirectForm').submit();
    </script>"; 
            
    exit(); // Always call exit after header redirection
}

// Close the connection
$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Heavy Objects Submission</title>
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
            max-width: 800px; /* Limit the form width */
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .peso-input {
            width: 80px; /* Set width for peso input */
        }
    </style>
</head>

<body>
    <main>
        <div class="form-container">
            <form action="" method="post">
                <h2 class="text-center mb-4">Movimentazione Oggetti Pesanti</h2>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descrizione</th>
                            <th>Numero di Oggetti Sollevati</th>
                            <th>Numero di Sollevamenti per Oggetto</th>
                            <th>Peso (kg)</th>
                            <th>Durata in minuti</th>
                            <th>Numero Lavoratori Coinvolti</th>
                            <th>Azione</th>
                        </tr>
                    </thead>
                    <tbody id="items-container">
                        <tr>
                            <td><input type="text" class="form-control" name="items[0][description]" required></td>
                            <td><input type="number" class="form-control" name="items[0][num_objects]" required></td>
                            <td><input type="number" class="form-control" name="items[0][num_lifts_per_object]" required></td>
                            <td><input type="number" class="form-control peso-input" step="0.1" name="items[0][weight]" required></td>
                            <td><input type="number" class="form-control" min="1" name="items[0][duration]" required></td>
                            <td><input type="number" class="form-control" name="items[0][num_workers]" required></td>
                            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center">
                    <button type="button" id="add-item" class="btn btn-secondary">Aggiungi Oggetto</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </form>
        </div>
    </main>

    <script>
        let itemIndex = 1;

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = `
                <tr>
                    <td><input type="text" class="form-control" name="items[${itemIndex}][description]" required></td>
                    <td><input type="number" class="form-control" name="items[${itemIndex}][num_objects]" required></td>
                    <td><input type="number" class="form-control" name="items[${itemIndex}][num_lifts_per_object]" required></td>
                    <td><input type="number" class="form-control peso-input" step="0.1" name="items[${itemIndex}][weight]" required></td>
                    <td><input type="text" class="form-control" name="items[${itemIndex}][duration]" required></td>
                    <td><input type="number" class="form-control" name="items[${itemIndex}][num_workers]" required></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
            `;
            container.insertAdjacentHTML('beforeend', newItem);
            itemIndex++;
        });

        document.getElementById('items-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
</body>
</html>
