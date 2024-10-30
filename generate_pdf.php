<?php

require 'vendor/autoload.php'; // Make sure to include the autoload file

use Dompdf\Dompdf;

// Initialize Dompdf
$dompdf = new Dompdf();

// Ensure the name is set
if (isset($_POST['name'])) {
    $name = $_POST['name'];

    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
//echo $currentUrl;


    // Set the URL of the PHP page
    $url = $currentUrl.'/generate_pdf2.php'; // Replace with the actual URL

    // Prepare POST data
    $postData = [
        'name' => $name, // Send the name from the POST request
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    // Execute the cURL request
    $html = curl_exec($ch);
    curl_close($ch);

    // Check for errors
    if ($html === false) {
        die('Error fetching content.');
    }

    // Replace special characters
    $html = str_replace('☒', '[X]', $html); // Replace ☒
    $html = str_replace('☐', '[]', $html); // Replace ☐

    // Load the HTML content
    $dompdf->loadHtml($html);

    // Set the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to the browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="document.pdf"');
    echo $dompdf->output();
} else {
    die('No name provided!');
}
