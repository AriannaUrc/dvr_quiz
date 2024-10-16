<!doctype html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dvr_quiz";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Capture the username from the previous form
if (isset($_POST['name'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
}

if (isset($_POST['submit'])) {
    $question1 = mysqli_real_escape_string($conn, $_POST['domanda1']);
    $query = "INSERT INTO `questions` ('ID', 'qsn', ) VALUES (NULL, '$question1')";

    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            echo("Submitted successfully");
        }
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}
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
