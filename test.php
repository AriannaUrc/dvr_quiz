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
var_dump($_POST);
// Check if the username is set in the session
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    var_dump($name);
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
                    echo "Submitted successfully";
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
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>

            <form action="" method="post">
                <!-- TODO: css version -->
                <table align = "center">
                    <tr>
                        <td><label for="domanda1">L’attività consiste nel sollevare un carico</label></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="domanda1" value="No" checked>No</input>
                        </td>

                        <td>
                            <input type="radio" name="domanda1" value="Yes">Yes</input>
                        </td>

                    </tr>


                    <tr>
                        <td><label for="domanda2">L’attività consiste nel deporre un carico</label></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="domanda2" value="No" checked>No</input>
                        </td>

                        <td>
                            <input type="radio" name="domanda2" value="Yes">Yes</input>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input name="name" value="<?php echo $name;?>" type="hidden">
                            <input type="submit" name="submit" value="submit">
                        </td>
                    </tr>
  
                </table>
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
