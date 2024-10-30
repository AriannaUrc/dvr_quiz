<?php
$servername = "localhost"; // Your server name
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "dvr_quiz";     // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Specify the username to filter
$specific_username = ''; // Replace with the actual username
if (isset($_POST['name'])) {
    $specific_username = $_POST['name'];
}

// Prepare the queries
$queries = [
    'questions' => "SELECT ID AS question_id, qsn AS question, num_domanda FROM questions WHERE username = '$specific_username' ORDER BY num_domanda",
    'heavy_objects' => "SELECT ID AS heavy_object_id, description AS heavy_object_description, num_objects, num_lifts_per_object, weight, duration, num_workers FROM heavy_objects WHERE username = '$specific_username'",
    'peso_limite' => "SELECT ID AS peso_limite_id, cp, vertical_distance_factor, horizontal_distance_factor, angular_dislocation_factor, load_quality_factor, frequency_factor, recommended_weight, r, heaviest_weight FROM peso_limite WHERE username = '$specific_username'",
    'critical_situation' => "SELECT ID AS critical_situation_id, qsn AS critical_question, num_domanda FROM critical_situation WHERE username = '$specific_username' ORDER BY num_domanda",
];

// Execute the queries and store results
$results = [];

foreach ($queries as $key => $query) {
    $result = $conn->query($query);
    if ($result) {
        $results[$key] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the connection
$conn->close();

/* // Process and display the results
foreach ($results as $table => $rows) {
    echo "<h2>Results from {$table}:</h2>";
    echo "<ul>";
    foreach ($rows as $row) {
        echo "<li>";
        foreach ($row as $column => $value) {
            echo "{$column}: {$value}, ";
        }
        echo "</li>";
    }
    echo "</ul>";
} */

/* echo "<hr>";
var_dump($results); */

// Initialize the qsn_answers array
$qsn_answers = [];

// Sort of questions
foreach ($results["questions"] as $question) {
    // Assign the question to the qsn_answers array with num_domanda as the key
    $qsn_answers[$question['num_domanda']] = $question['question'];
}

// Initialize variables to track the highest ID and its corresponding entry for peso_limite
$highest_id_entry = null;
$highest_id = 0;

// Sort of peso_limite
foreach ($results['peso_limite'] as $entry) {
    // Compare the current entry's ID with the highest ID found so far
    if ((int)$entry['peso_limite_id'] > $highest_id) {
        $highest_id = (int)$entry['peso_limite_id'];
        $highest_id_entry = $entry; // Store the entry with the highest ID
    }
}


// Sort of critical_situation
foreach ($results["critical_situation"] as $question) {
    // Assign the question to the qsn_answers array with num_domanda as the key
    $qsn_answers[$question['num_domanda']] = $question['critical_question'];
}

function renderCheckboxes($input) {
    // Normalize input
    //var_dump($input);
    $input = strtolower(trim($input));

    // Initialize checkbox states
    $yesChecked = '☐';
    $noChecked = '☐';

    // Determine which checkbox to check
    if ($input === 'yes' || $input === 'si') {
        $yesChecked = '☒';
    } elseif ($input === 'no') {
        $noChecked = '☒';
    }

    // Return the HTML for the checkboxes
    echo '
    <td style="width:60.1pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt;">
            '.$yesChecked.'
            <span style="line-height:115%; font-size:12pt;">sì</span>
        </p>
    </td>
    <td style="width:60.2pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt;">
            '.$noChecked.'
            <span style="line-height:115%; font-size:12pt;">no</span>
        </p>
    </td>';
}


function renderPPRCheckboxes($input) {
    // Normalize input
    //var_dump($input);
    $input = strtolower(trim($input));

    // Initialize checkbox states
    $yesChecked = '☐';
    $noChecked = '☐';

    // Determine which checkbox to check
    if ($input === 'yes' || $input === 'si') {
        $yesChecked = '☒';
    } elseif ($input === 'no') {
        $noChecked = '☒';
    }

    // Return the HTML for the checkboxes
   echo '<td style="width:54.1pt; border-top:0.75pt solid #ffff00; border-left:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt;">'.$yesChecked.' <span style="line-height:115%; font-size:12pt;">s&igrave;</span></p>
                </td>
                <td style="width:60.35pt; border-top:0.75pt solid #ffff00; border-left:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:115%; font-size:12pt;">'.$noChecked.' <span style="line-height:115%; font-size:12pt;">no</span></p>
                </td>' ;
}



function YESCheckOrUnchecked($input) {
    // Normalize input
    //var_dump($input);
    $input = strtolower(trim($input));


    // Determine which checkbox to check
    if ($input === 'yes' || $input === 'si') {
        echo '☒';
    } elseif ($input === 'no') {
        echo '☐';
    }
}


function NOCheckOrUnchecked($input) {
    // Normalize input
    //var_dump($input);
    $input = strtolower(trim($input));


    // Determine which checkbox to check
    if ($input === 'yes' || $input === 'si') {
        echo '☐';
    } elseif ($input === 'no') {
        echo '☒';
    }
}

// Example usage
//echo renderCheckboxes('no'); // Call with 'si', 'yes', 'no', or any other input

?>

<style>
    .page-break {
        page-break-before: always; /* Force a new page before this element */
    }
</style>




<div>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt solid rgb(255, 255, 0); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr style="height:56.7pt;">
                <td style="width:99.6pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top; background-color:#76923c;">
                    <p style="margin-top:0pt; margin-bottom:0pt; font-size:14pt;"><span style="font-family:Impact; color:#ffffff;">CO.RE.CO</span><span style="font-family:Impact; color:#ffffff;">&nbsp;&nbsp;</span><span style="font-family:Impact; color:#ffffff;">VENETO Indicazioni per stesura DVR STD Versione</span><span style="font-family:Impact; color:#ffffff;">&nbsp;&nbsp;</span><span style="font-family:Impact; color:#ffffff;">2012</span></p>
                </td>
                <td style="width:366.4pt; border-left:0.75pt solid #ffff00; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:right; line-height:150%; font-size:20pt;"><span style="font-family:Impact; color:#76923c;">Lista di controllo</span><span style="font-family:Impact; color:#76923c;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="line-height:150%; font-family:Impact; font-size:16pt; color:#ff0000;">Allegato</span><span style="line-height:150%; font-family:Impact; font-size:16pt; color:#ff0000;">&nbsp;&nbsp;</span><span style="line-height:150%; font-family:Impact; font-size:16pt; color:#ff0000;">03</span></p>
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:150%; font-size:20pt;"><span style="font-family:Impact; color:#76923c;">MOVIMENTAZIONE MANUALE DEI CARICHI/MMC</span></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>&nbsp;</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>SE RISPONDE S&igrave;: CONTINUARE CON LA VALUTAZIONE</strong></p>
    <table cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td colspan="3" style="width:483.8pt; border-top:1pt solid #ffff00; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;"><strong><span style="color:#4f6228;">CARATTERISTICHE DELL&rsquo;ATTIVIT&Agrave; LAVORATIVA</span></strong></p>
                </td>
            </tr>
            <tr style="height:18.1pt;">
                <td style="width:341.9pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">L&rsquo;attivit&agrave; consiste nel sollevare un carico</p>
                </td>
                <?php renderCheckboxes($qsn_answers["domanda1"] ?? '');?>
            </tr>
            <tr>
                <td style="width:341.9pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">L&rsquo;attivit&agrave; consiste nel deporre un carico</p>
                </td>
                <?php renderCheckboxes($qsn_answers["domanda2"] ?? '');?>
            </tr>
            <tr>
                <td style="width:341.9pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">L&rsquo;attivit&agrave; consiste nello spingere un carico</p>
                </td>
                <?php renderCheckboxes($qsn_answers["domanda3"] ?? '');?>
            </tr>
            <tr style="height:23.5pt;">
                <td style="width:341.9pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">L&rsquo;attivit&agrave; consiste nel tirare un carico</p>
                </td>
                <?php renderCheckboxes($qsn_answers["domanda4"] ?? '');?>
            </tr>
            <tr style="height:26pt;">
                <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">L&rsquo;attivit&agrave; consiste nel portare o spostare un carico</p>
                </td>
                <?php renderCheckboxes($qsn_answers["domanda5"] ?? '');?>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>&nbsp;</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>Presenza di oggetti di peso superiore o uguale a 3 kg da sollevare manualmente, almeno una volta all&rsquo;ora?</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:18pt;"><?php NOCheckOrUnchecked($qsn_answers["pre2"] ?? '')?><span style="line-height:115%; font-size:10pt;">&nbsp;&nbsp;&nbsp;</span><span style="line-height:115%; font-size:11pt;">NO: terminare la valutazione</span></p>
    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:18pt;"><?php YESCheckOrUnchecked($qsn_answers["pre2"] ?? '')?> <span style="line-height:115%; font-size:11pt;">&nbsp;</span><span style="line-height:115%; font-size:10pt;">SI: continuare la compilazione della lista di controllo</span></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>Possibilit&agrave; di evitare la movimentazione manuale dei carichi con attrezzature meccaniche o ausili?</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:18pt;"><?php NOCheckOrUnchecked($qsn_answers["pre3"] ?? '')?><span style="line-height:115%; font-size:11pt;">&nbsp;&nbsp;&nbsp;</span><span style="line-height:115%; font-size:10pt;">NO: continuare la compilazione della lista di controllo&nbsp;</span></p>
    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:18pt;"><?php YESCheckOrUnchecked($qsn_answers["pre3"] ?? '')?> <span style="line-height:115%; font-size:11pt;">&nbsp;</span><span style="line-height:115%; font-size:10pt;">SI: terminare la valutazione</span></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>&nbsp;</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt solid rgb(0, 0, 0); border-collapse: collapse; width: 100%;">
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt solid rgb(0, 0, 0); border-collapse: collapse; width: 100%;">
    <tbody>
        <tr style="height:26.6pt;">
            <td colspan="6" style="width:488.1pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#76923c;">
                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;">
                    <strong><span style="color:#ffffff;">OGGETTI DI PESO SUPERIORE O UGUALE A 3 KG MOVIMENTATI MANUALMENTE&nbsp;</span></strong>
                </p>
                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:11pt;">
                    <strong><span style="color:#ffffff;">NELL’ARCO DELLA GIORNATA LAVORATIVA</span></strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="width:123.45pt; border-top-style:solid; border-top-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="text-align:center; font-size:10pt;"><strong><span style="color:#4f6228;">DESCRIZIONE</span></strong></p>
            </td>
            <td style="width:65.9pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="text-align:center; font-size:10pt;"><strong><span style="color:#4f6228;">NUMERO DI OGGETTI SOLLEVATI</span></strong></p>
            </td>
            <td style="width:67.15pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                <p style="text-align:center; font-size:10pt;"><strong><span style="color:#4f6228;">NUMERO DI SOLLEVAMENTI PER OGGETTO</span></strong></p>
            </td>
            <td style="width:49.05pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="text-align:center; font-size:10pt;"><strong><span style="color:#4f6228;">PESO (kg)</span></strong></p>
            </td>
            <td style="width:58.2pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="text-align:center; font-size:10pt;"><strong><span style="color:#4f6228;">DURATA</span></strong></p>
            </td>
            <td style="width:70.35pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="text-align:center; font-size:10pt;"><strong><span style="color:#4f6228;">NUMERO LAVORATORI COINVOLTI</span></strong></p>
            </td>
        </tr>

        <?php foreach ($results["heavy_objects"] as $object): ?>
        <tr>
            <td style="width:123.45pt; border-top-style:solid; border-top-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="font-size:11pt;"><?= htmlspecialchars($object["heavy_object_description"]) ?></p>
            </td>
            <td style="width:65.9pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="font-size:11pt;"><?= htmlspecialchars($object["num_objects"]) ?></p>
            </td>
            <td style="width:67.15pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                <p style="font-size:11pt;"><?= htmlspecialchars($object["num_lifts_per_object"]) ?></p>
            </td>
            <td style="width:49.05pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="font-size:11pt;"><?= htmlspecialchars($object["weight"]) ?></p>
            </td>
            <td style="width:58.2pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="font-size:11pt;"><?= htmlspecialchars($object["duration"]) ?></p>
            </td>
            <td style="width:70.35pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle;">
                <p style="font-size:11pt;"><?= htmlspecialchars($object["num_workers"]) ?></p>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:8pt;"><strong>&nbsp;</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt; page-break-before:always;"><strong>Da ISO 11228-1 di cui all&rsquo;allegato XXXIII del D.LGS 81/08</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>VALUTAZIONE PRELIMINARE: PROBABILE PRESENZA DI RISCHIO DA MMC IN CASO DI RISPOSTA NEGATIVA AD UNO DEI SEGUENTI QUESITI</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt solid rgb(255, 255, 0); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr style="height:12.7pt;">
                <td style="width:345.6pt; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">c&rsquo;&egrave; una buona interfaccia tra piedi e pavimenti?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_1"] ?? '');?>
            </tr>
            <tr style="height:49.55pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;"><span style="font-size:11pt;">le attivit&agrave; di movimentazione manuale diverse dal sollevamento sono minime e gli oggetti da sollevare non sono molto freddi, molto caldi o contaminati?</span></p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_2"] ?? '');?>
            </tr>
            <tr style="height:32.75pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">l&rsquo;ambiente termico &egrave; moderato (per ambiente termico moderato si intende un ambiente in cui vi sia una temperatura tra i 19 e i 26&deg;C, con umidit&agrave; relativa tra il 30% ed il 60% e velocit&agrave; dell&rsquo;aria <u>&lt;</u> 0,2 m/s) <strong>EN ISO 7730?</strong></p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_3"] ?? '');?>
            </tr>
            <tr style="height:30.2pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">l&apos;operazione pu&ograve; essere eseguita utilizzando solo due mani?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_4"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">c&apos;&egrave; una buona interfaccia tra piedi e pavimenti?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_5"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">la postura &egrave; eretta e i movimenti non sono limitati?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_6"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">le attivit&agrave; di movimentazione manuale diverse dal sollevamento sono minime e gli oggetti da sollevare non sono molto freddi, molto caldi o contaminati?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_7"] ?? '');?>
            </tr>
            <tr style="height:30.2pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">la movimentazione avviene da parte di un&apos;unica persona?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_8"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Il sollevamento &egrave; graduale?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_9"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">l&apos;ambiente termico &egrave; moderato (per ambiente termico moderato si intende un ambiente in cui vi sia una temperatura tra i 19 e i 26 &deg;C, con umidit&agrave; relativa tra il 30% ed il 60% e velocit&agrave; dell&apos;aria &lt;0,2 m/s) <strong>EN ISO 7730?&nbsp;</strong></p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_10"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Il peso movimentato &egrave; compreso tra 5,1-10,5 Kg, viene spostato in verticale nella zona compresa tra le anche e le spalle, per una volta ogni 5 minuti?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_11"] ?? '');?>
            </tr>
            <tr style="height:30.2pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">la movimentazione avviene a tronco eretto e non ruotato<strong>?</strong></p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_12"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">durante la movimentazione il carico &egrave; tenuto vicino al corpo<strong>?</strong></p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_13"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; border-bottom:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Il peso movimentato &egrave; compreso tra 3-5 Kg, viene spostato in verticale nella zona compresa tra le anche e le spalle, per una frequenza massima di 1 volta al minuto?<u>&nbsp;</u></p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_14"] ?? '');?>
            </tr>
            <tr style="height:33.4pt;">
                <td style="width:345.6pt; border-top:0.75pt solid #ffff00; padding-right:5.4pt; padding-left:5.03pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Il peso movimentato &egrave; compreso tra 5,1-10,5 Kg, viene spostato in verticale nella zona compresa tra le anche e le spalle, per una volta ogni 5 minuti?</p>
                </td>
                <?php renderPPRCheckboxes($qsn_answers["PPR_15"] ?? '');?>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;"><br></p>
    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;">Se tutte le voci analizzate sono positive (risposta SI) significa che siamo in una situazione accettabile e non &egrave; necessario procedere ad ulteriore valutazione del rischio da movimentazione manuale dei carichi e all&rsquo;individuazione di misure di prevenzione e protezione.</p>
    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;">Se anche una sola voce &egrave; negativa, si deve procedere con una valutazione pi&ugrave; approfondita, utilizzando la scheda NIOSH per calcolare il peso limite raccomandato e l&rsquo;indice di sollevamento (All. 1).</p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>VALUTAZIONE RISCHIO E ADOZIONE DI MISURE CORRETTIVE (TECNICHE, ORGANIZZATIVE E PROCEDURALI)&nbsp;</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify;"><strong>Effettuata la valutazione del rischio da movimentazione manuale dei carichi con la Tabella NIOSH in allegato 1, dove si andranno a ricavare i valori dei 7 fattori considerati e calcolato l&rsquo;indice di sollevamento: siamo in una condizione ottimale quando l&rsquo;indice di sollevamento &egrave; inferiore a 1.&nbsp;</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify;"><strong>In caso l&rsquo;indice di sollevamento superi il valore di 1, per l&rsquo;individuazione delle misure correttive dovranno essere valutati i punteggi dei singoli fattori [es. altezza da terra delle mani inizio sollevamento (A), oppure distanza orizzontale del peso dal corpo (C), etc.], che possono variare da 1 a 0: quanto pi&ugrave; il singolo fattore si discosta dal valore 1 e si avvicina al valore di 0 tanto pi&ugrave; siamo lontani dalla situazione ottimale e quindi andranno adottate misure correttive. Se uno dei singoli fattori assume valore 0 siamo nelle situazioni critiche sotto elencate che richiedono azioni correttive immediate.</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>SITUAZIONI CRITICHE: RICHIEDONO PROVVEDIMENTI IMMEDIATI</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>In caso sia presente anche solo uno dei fattori critici sotto elencati, il rischio va considerato elevato ed &egrave; necessario procedere al pi&ugrave; presto alla riprogettazione del compito, al fine di far rientrare all&rsquo;interno di un valore pi&ugrave; accettabile il fattore misurato.</strong></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><br></p>
    <p class ="page-break" style="margin-top:0pt; margin-bottom:10pt;"><strong><strong>ADOZIONE DI ULTERIORI MISURE CORRETTIVE SE RISPONDE NO AD UNO DEI SEGUENTI QUESITI</strong></strong></p>
    
    
    
    <table cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
        <tbody>
            <tr style="height:18.1pt;">
            <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Distanza verticale (altezza da terra all’inizio presa) > 175 cm?</p>
                </td>
                <?php renderCheckboxes($qsn_answers["vertical_distance_critical"] ?? '');?>
            </tr>
            <tr>
                <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Dislocazione verticale (spostamento verticale del peso dall’inizio alla fine del movimento) > 175 cm?</p>
                </td>
                <?php renderCheckboxes($qsn_answers["displacement_vertical_critical"] ?? '');?>
            </tr>
            <tr>
            <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Distanza orizzontale del peso dal corpo > 63 cm?</p>
                </td>
                <?php renderCheckboxes($qsn_answers["horizontal_distance_critical"] ?? '');?>
            </tr>
            <tr style="height:23.5pt;">
            <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Rotazione del tronco > 135 gradi?</p>
                </td>
                <?php renderCheckboxes($qsn_answers["trunk_rotation_critical"] ?? '');?>
            </tr>
            <tr style="height:26pt;">
                <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Frequenza di sollevamento in base al tempo dedicato alla movimentazione carichi? <br> -     N. sollevamenti superiori o uguali a 13/minuto, tempo dedicato inferiore ad un'ora? <br> -     N. sollevamenti superiori o uguali a 11/minuto, tempo dedicato inferiore 1-2 ore? <br> -    N. sollevamenti superiori o uguali a 9/minuto, tempo dedicato superiore a 2 ore?  </p>
                </td>
                <?php renderCheckboxes($qsn_answers["lifting_frequency_1"] ?? '');?>
            </tr>
            <tr style="height:26pt;">
                <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Presenza di pesi maggiori a 25 Kg per l’uomo e 20 Kg per la donna (di età compresa tra i 18 e i 45 anni)?</p>
                </td>
                <?php renderCheckboxes($qsn_answers["weight_critical_1"] ?? '');?>
            </tr>
            <tr style="height:26pt;">
                <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;">Presenza di pesi maggiori a 20 Kg per l’uomo e 15 Kg per la donna (di età inferiore ai 18 e superiore ai 45 anni)?</p>
                </td>
                <?php renderCheckboxes($qsn_answers["weight_critical_2"] ?? '');?>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt;"><br></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><br></p>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong><br></strong></p>
    <table  cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td colspan="3" style="width:483.8pt; border-top:1pt solid #ffff00; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; line-height:115%; font-size:11pt;"><strong><span style="color:#4f6228;">FORMAZIONE/INFORMAZIONE/ADDESTRAMENTO:</span></strong></p>
                </td>
            </tr>
            <tr style="height:18.1pt;">
                <td style="width:341.9pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:11pt;">sono state fornite ai lavoratori le informazioni adeguate relativamente al peso ed alle altre caratteristiche del carico movimentato</p>
                </td>
                <?php renderCheckboxes($qsn_answers["info_provided"] ?? '');?>
            </tr>
            <tr>
                <td style="width:341.9pt; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:11pt;">&egrave; stata fornita formazione, ai lavoratori, adeguata in relazione ai rischi lavorativi ed alle modalit&agrave; di corretta esecuzione delle attivit&agrave;</p>
                </td>
                <?php renderCheckboxes($qsn_answers["training_provided"] ?? '');?>
            </tr>
            <tr>
                <td style="width:341.9pt; border-bottom:1pt solid #ffff00; padding-right:5.4pt; padding-left:5.4pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:10pt; line-height:115%; font-size:11pt;">&egrave; stato fornito ai lavoratori l&rsquo;addestramento adeguato in merito alle corrette manovre e procedure da adottare nella movimentazione manuale dei carichi</p>
                </td>
                <?php renderCheckboxes($qsn_answers["adequate_training"] ?? '');?>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>Allegato 1</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt solid rgb(0, 0, 0); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr style="height:34.95pt;">
                <td style="width:484.3pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:middle; background-color:#76923c;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:14pt;"><strong><span style="color:#ffffff;">Scheda NIOSH per il calcolo degli indici di sollevamento</span></strong></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt;"><strong>&nbsp;</strong></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">COSTANTE DI PESO</span></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt solid rgb(0, 0, 0); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:108pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">ET&Agrave;</span></p>
                </td>
                <td style="width:187.65pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">MASCHI</span></p>
                </td>
                <td style="width:167.05pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">FEMMINE</span></p>
                </td>
            </tr>
            <tr>
                <td style="width:108pt; border-top-style:solid; border-top-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">18-45 ANNI</span></p>
                </td>
                <td style="width:187.65pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">25</span></p>
                </td>
                <td style="width:167.05pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">20</span></p>
                </td>
            </tr>
            <tr>
                <td style="width:108pt; border-top-style:solid; border-top-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&lt;18 e &gt;45 ANNI</span></p>
                </td>
                <td style="width:187.65pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; padding-right:5.4pt; padding-left:5.03pt; vertical-align:top; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">20</span></p>
                </td>
                <td style="width:167.05pt; border-top-style:solid; border-top-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">15</span></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">ALTEZZA DA TERRA DELLE MANI ALL&apos;INIZIO (O ALLA FINE) DEL SOLLEVAMENTO</span><span style="font-size:10pt;">&nbsp;</span><strong><span style="font-size:10pt;">(A)</span></strong></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <div style="text-align:center;">
        <table cellspacing="0" cellpadding="0" style="margin-right: auto; margin-left: auto; border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td rowspan="2" style="width:76.88pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;"><img src="data:image/png;base64,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" width="102" height="106" alt=""></p>
                    </td>
                    <td style="width:96.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">ALTEZZA (cm)</p>
                    </td>
                    <td style="width:38.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0</p>
                    </td>
                    <td style="width:38.48pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">25</p>
                    </td>
                    <td style="width:39.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">50</p>
                    </td>
                    <td style="width:39.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">75</p>
                    </td>
                    <td style="width:39.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">100</p>
                    </td>
                    <td style="width:39.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">125</p>
                    </td>
                    <td style="width:39.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">150</p>
                    </td>
                    <td style="width:41.7pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">&gt;175</p>
                    </td>
                </tr>
                <tr>
                    <td style="width:96.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">FATTORE</p>
                    </td>
                    <td style="width:38.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,77</p>
                    </td>
                    <td style="width:38.48pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,85</p>
                    </td>
                    <td style="width:39.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,93</p>
                    </td>
                    <td style="width:39.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1,00</p>
                    </td>
                    <td style="width:39.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,93</p>
                    </td>
                    <td style="width:39.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,85</p>
                    </td>
                    <td style="width:39.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,78</p>
                    </td>
                    <td style="width:41.7pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p style="margin-top:0pt; margin-bottom:0pt; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">DISTANZA VERTICALE DI SPOSTAMENTO DEL PESO FRA INIZIO E FINE DEL SOLLEVAMENTO</span><span style="font-size:10pt;">&nbsp;</span><strong><span style="font-size:10pt;">(B)</span></strong></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <div style="text-align:center;">
        <table cellspacing="0" cellpadding="0" style="margin-right: auto; margin-left: auto; border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td rowspan="2" style="width:76.88pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;"><img src="data:image/png;base64,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" width="102" height="119" alt=""></p>
                    </td>
                    <td style="width:93.88pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">DISLOCAZIONE (cm)</p>
                    </td>
                    <td style="width:38.67pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">25</p>
                    </td>
                    <td style="width:38.67pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">30</p>
                    </td>
                    <td style="width:39.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">40</p>
                    </td>
                    <td style="width:39.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">50</p>
                    </td>
                    <td style="width:39.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">70</p>
                    </td>
                    <td style="width:39.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">100</p>
                    </td>
                    <td style="width:39.42pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">170</p>
                    </td>
                    <td style="width:42.05pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">&gt;175</p>
                    </td>
                </tr>
                <tr>
                    <td style="width:93.88pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">FATTORE</p>
                    </td>
                    <td style="width:38.67pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1,00</p>
                    </td>
                    <td style="width:38.67pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,97</p>
                    </td>
                    <td style="width:39.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,93</p>
                    </td>
                    <td style="width:39.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,91</p>
                    </td>
                    <td style="width:39.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,88</p>
                    </td>
                    <td style="width:39.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,87</p>
                    </td>
                    <td style="width:39.42pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,86</p>
                    </td>
                    <td style="width:42.05pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">DISTANZA ORIZZONTALE TRA LE MANI E IL PUNTO DI MEZZO DELLE CAVIGLIE</span><span style="font-size:10pt;">&nbsp;</span><strong><span style="font-size:10pt;">(C)</span></strong></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">DISTANZA DEL PESO DEL CORPO (DISTANZA MASSIMA RAGGIUNTA DURANTE IL SOLLEVAMENTO)</span></p>
    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:12pt;"><span style="font-size:10pt;">&nbsp;</span></p>
    <div style="text-align:center;">
        <table cellspacing="0" cellpadding="0" style="margin-right: auto; margin-left: auto; border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td rowspan="2" style="width:76.88pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGYAAABzCAYAAABqxHdKAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAkiSURBVHhe7Z3rThRJFMcH5CIgqCjKAl5i4iXxDXaz2WTfaffDfthP+0h+0QfwFTQajSCoKBcVvIEyO7+mT3umqOnpga7q6pn6J/9AX6fn/Dh1qqtrhkYzKkhFMIEqgglUEUygimACVQQTqCKYQBXBBKoIJlBFMIEqgglUEUygimAC1UCB+f79e0eHpr4Gs7+/n3lzc7PZaDSsPnfuXLZfKJD6EgwB3traygI/0hgpZPY9f/58BqlK9R0YE8hsY/bwXXbxVmOrDdDsbOu4CsVl9Y0+fvyYQSkKxLQAAkyVWcPl9IX29vaybJlpzBwJeJ73GnvN/UYLQrq82TisR1VmDZdSe+nmC083ppNgS6DzvN3Ybo42Rptzjbls3XpjPTnP5cuXWyuqEZdSa5lQRkdHk59kDXCKAsLsK1BijTmmdNMlnp6eTuoMcGyA8ixAMMdWmS2olmBMIGNjY0kwASP68OFDsl4soDpZ9qsaiKhWYL59+3YEypkzZ5JtZApg2McmE5T2pUuX0r3CUW3AmEBsYFjWWVNn1QKMrenCEUyFMqFIb2lnZ6dtOYLxoK9fvyYWKOPj44l1FxYwki2IZfbJqzN1UnBgtre3s+zADCraxGixBoMka2ZmWnf+NVdQYIpC2d3dTbZHMI715cuXNiinT5/uCAVFMB5UNEu08sAAlToDbFxXVQrmOFDQxsaGFQySrMFkTl0BVQbmuFAkW/DFixfTtT8lWSNmvzoC8g7m8+fPR6AQwKISMFNTU8m58oLNMMzExEQG6OzZs8kx3Y4LQV7BmEDERbMFAYZgY46lOZNgd5IJCAukooAODg68AvUGRkPRge0lW7QEkJxTAOXp/fv3ba+NmSFjAysgxKurq8n+Fy5cSPdwKy9gNBQCgeSuvpdsMcXdvg50kexBGpAcqwFpEHio5YnR4cSA6Xb+MuQcDMMj8gYFCsMtLB83W0wBaHJyMgsy3eWiweOPRo7TBsbk6KnESzPjzeZ/fzY3/vk92WbrdJQtb2AYxxKVDUaku8rA+fTp0xFANFGs115bW8uOEyCLKQztvgSjmyxpxsp+ri6ZgyXQdJU1hFevXmXbxDo7bEDEmy0wvpoz52BoKjQUV9miReZoODZrGAs5MEz7yhqnYIDCAy0tV2BoouipiV+/ft0GYWrsVJsXpovD0K49GGnCfIAByps3bzIQWMP4Zbp1DZYg9+qDlpf/+jU5P80ZTaMrOQPTqTvsAgy1xQQyXxIMDJDdf//IoIhdZo0TMMz54sLNbEGuwUyPn7IGtxcLCPFKCmRoaCgZCsL8fu3atfQKylclYHhjLsEQTFvA8wyMndZx+OVfv2Xnw0Dg5vXq1avJ6717966eNSYPDAKOKzB4ZnzEGnxtDSKB8fdPGAJCDBDqGK+DX7x4EcEUkQmGrCHYeTBWFQisYVy5cqUNBF5eXm7bz2UzhpyC6XQDCRhGAnjDZUjASND4nawxs0LDMLNiaWmpDcbKykq2r97fNRCRMzCdsgWV3QEgkBJgubMna9YMEAzTiAGBgMENKdYwzP19ARE5A8Mkbt6sTWWDQXqczBbYxcXFZD8NIg+GFPqqVFmNYXvZdUaCyrn5SeBNEC9fvrSCwNSWUNQ3YEQEXwJPsPVzFQwMBjaxBiEAQ1FlYAiaazAmCCy1xRQjFSd5aFe2nIEhCHkdAJ6duwBDk1YEhKmBAIMYxAQMEyFsAgxd5k7bfWugwOQ1Z4Bhu5k1tPU8k/cNrC/BEEjTjCcJGNv29fXDD6MCRq+X4Xuew+v1rs0wS1+B4dsjmKNFMMVys1fUDGrq46ty32WMhjMyMtJ88OBB4ocPHxbyvXv3kmPpQXGeKkxHoS9rjJk5AsgGQltDmZ+fT8/mXzwGv379erpUvUot/nSTmTsmgLrBCQVKiCoVjEgAEXTGzFxDoSfHX7w2Bb3OcgIGSXfZljVlZwo3lZxPm5GFXgBJNz0UOQdD5pA19+/fb4OCy7jzJ6AyMAloCrhkK8tFR4kH5gaT+xTuYQQQcDQUglcGGMkWIMigJONlBLmXofuBGSsjWAKGNyxAMM1XWWNlAoam6yQaODBIskagIBkrIyAnkYDhRpWagmjeOC+Wdd000GD0jP9OY2W9iumwkpHA4YsZ9MSJoo+EI5hUZYFBZI1uLgHCRJBentNHMKnKBIOAA4xegYgGCozUEx9gTipq0Y0bN9Kl6uUMjGQLqgOY0FQZGJ770+xEMHY5AcOHhkwwfJ5kYWEhXXMossYnGLrRfN0JPTdTbLOtr0qlgzELf54EDMFyLQL//Pnz5Np49mJCsBV/ASn2Ca5yML7qDAOUdKOZpU8TyqMJHWgTDFCePn3adoyvL19AAwGGID979iyBgZjoQZAZrxNpMBrKzZs3k3VkTK3ByKRuDYY3yuQMrOULDNnC6wgYRPe4Exgyif3JEhHbb926lS65V6lgJFuwLvS2XhnyCYa/fn2fAhhASX3rBgb9+PEj29+1nIBhiF8rBDA6WxBgeG3JGhOMbsZEPpuzSsFwL0NhDRGMmS1oYMAgssYXGGrd27dvE1PcuSbWs/zkyZMEkvm79uPHj+sNxryRLAKGN+5KAobe2NzcXGIylWuiyZJl2+/arK8tGDNbEGD4jxO2mfdSZ5gS60rHacpYb/Yia9uU8fVSNjB58gWGu32dlYDRBb5Ir4z1d+7cSZfcqjQwnepLN/kCw2vorAGMZAvqBoausplBLjUQYJg1Q40QMHQCpMCLNBigsb8G47MZQ17AEAg+XsGUJlM+wCDdnJn1BWkwSOoM140fPXrUf2DyemXcy9AxcA2GrOF1gEO2UF/0EIsJBnj8vzIyh2sfHh72Vl9Q5WAQWeMaDKK7TLCxOe5lghGxnv19QkGlg7F1iUMBk6dOYKpSqWA6Ff4iYLjJpC2vStSg27dvp0vVqzQwTOw+CRi2V501IakUMHyajMBGMOUpgglUEUygCgYMszYjmJ/qCoZn+N0s3/nFx/ps2/lyA7YzD8C2HTO1iJ6ZbVsI9t1jzAXDwB0Bjfb3f2NEXcHw0Ct6oXn37t00Kn5USo2JKl8RTKCKYAJVBBOoIphAFcEEqggmUEUwgSqCCVQRTKCKYAJVBBOoIpgg1Wz+D5x5UXFKLmdtAAAAAElFTkSuQmCC" width="102" height="115" alt=""></p>
                    </td>
                    <td style="width:92.52pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">DISTANZA (cm)</p>
                    </td>
                    <td style="width:45.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">25</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">30</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">40</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">50</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">55</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">60</p>
                    </td>
                    <td style="width:47.55pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">&gt;63</p>
                    </td>
                </tr>
                <tr>
                    <td style="width:92.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">FATTORE</p>
                    </td>
                    <td style="width:45.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1,00</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,83</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,63</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,50</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,45</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,42</p>
                    </td>
                    <td style="width:47.55pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p style="margin-top:14pt; margin-bottom:14pt; text-align:center; line-height:normal; font-size:10pt;">DISLOCAZIONE ANGOLARE DEL PESO IN GRADI&nbsp;<strong>(D)</strong></p>
    <div style="text-align:center;">
        <table cellspacing="0" cellpadding="0" style="margin-right: auto; margin-left: auto; border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td rowspan="2" style="width:76.88pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGYAAABmCAYAAAA53+RiAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAATNSURBVHhe7ZxZS/NAGIWroLjv4r6g/n+8885Lf4WgoqLgvm9gvp5x3n5jOklTm0netOeBA2kyaZM8nSVD2lpEVEIxSqEYpVCMUihGKRSjFIpRCsUohWKUQjFKoRilUIxSKEYpFKMUilEKxSiFYpRCMUpJFfP5+akyX19f9gjzx/d5SMjP9JEo5vv7O6rVatHAwICq4JhGR0ftUebL7e2teX9fZmdnbaliSBXT399vX+ng+fnZXCSKoZhGKCYFiqlDMcrFfHx8pCYNX/l44vjKSOTCQQxeC/FySRF825DLy8smIRJXjG9fN0ngmvrKS1xSxeCABgcHEzMyMmJLN4Mhpm8fNxMTE7b0D6+vr95ybuRCzczMmH2enp685eKZn5835e/v773bfZHPwvLS0pLZ//r6uqmcm5WVFVPOB8T79kE2NjZsqR/YlCVwc3Pz6wtQNBSTAMW0AcXUoRiKyUwoMW9vb9H7+7t99QNGgENDQ41Ov2h6Xszd3Z15z6mpqSY5ZUIxdTGoGXjf6elpU3sQnL8slwGbsjoixxV0fn4e9fX1RcvLy7ZUsVCMQ3xKpqyOH/ScGJwXZhjcSHPlNmsU0wadiBEhFxcXpokaHh5uREZeKCNNGNanTa+EpuvF+IQsLi7arf8RKWXXFKGrxeAcZMYYUnxCgCsF5aSmiNQy6FoxIgUXGrPgSVKAdPquFIC7f975Z6CVGBzzy8uLCaS0EiKg00fZeJ9CMRlJEyM1RL75WYS0gmIykibm8fHRCMG2PKTg/E9PTykmC0lipLYsLCzYNZ3D2eU28IkRKePj43ZNPmBAgM9ZW1uza4ql0mJESqs+BeWwbzxpuPuUQWXFZJUC8AAG9pM+CMuY5k+jJ5oyPMnSSeRb64rBeiyPjY2ZbWmImMnJycZyz4vB81I4QcSdn2on8piUKyZrbQEU40HE4En9g4ODtrO/v98kRpKltgCK8RBKTNbaAkQGHjA8OzszI7jV1VW71Q9GZSi3vr5u1xRLZcVkrS3g4eHBXOQsQrTQE2L+As4fswkYZJRBJcWgGQv9WBH7mBbxiZEbzJBQTIuULQZPzKCPcoMmLjQUk4CIkaD5xKgOAwj8Via0HIpJAMNliBApm5ubZj2EQc7c3Jx5HYrKikGTEhKc/9HR0S8pwtXVlak1IY+hkmKQkMNlkYLPQR+DG1RXAn5VhtoUstZUWgyOERcN6QS8j1x4V4objM7icra2tuyr/KmMGDwwIY8YIWjOML2CZUjC9r/m+PjYzJ1h2SdFUuQvlyshBu08LhwmId0LJevzTPwz3FCME19TJkJCPMKKWpMkh2Kc+MSEHi775ODL4PYpuD6d9m1pUEwCcoMJIRiZxaUcHh5yVFaGGNxgormEFNQgF9zH4DgopgQxQGqN27dIbUFN2tnZsWvzh2JSiNcakYJj4JRMiWIA5ODmcnt729QgLCMhawugGKVQjFIoRikUo5RCxezt7bWd3d1dinHJUwzuAzqJ/BSCYurkJSZPKKYOxZQLxSilpRhM2GnJyckJxUAMLoLG9LwY/FexxsT/o7gbSRRDyoVilEIxSqEYpVCMUihGKRSjFIpRCsUohWKUQjFKoRilUIxSKEYpFKMUilEKxSiFYpRCMUqhGJVE0T+M3j3H04WpiQAAAABJRU5ErkJggg==" width="102" height="102" alt=""></p>
                    </td>
                    <td style="width:91.88pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">Dislocazione Angolare</p>
                    </td>
                    <td style="width:45.08pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0&deg;</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">30&deg;</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">60&deg;</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">90&deg;</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">120&deg;</p>
                    </td>
                    <td style="width:45.12pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">135&deg;</p>
                    </td>
                    <td style="width:48.2pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">&gt;135&deg;</p>
                    </td>
                </tr>
                <tr>
                    <td style="width:91.88pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">FATTORE</p>
                    </td>
                    <td style="width:45.08pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1,00</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,90</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,81</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,71</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,52</p>
                    </td>
                    <td style="width:45.12pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,57</p>
                    </td>
                    <td style="width:48.2pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                        <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="page-break"></div>
    <p style="margin-top:14pt; margin-bottom:14pt; text-align:center; line-height:normal; font-size:10pt;">GIUDIZIO SULLA PRESA DEL CARICO&nbsp;<strong>(E)</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:161.02pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">GIUDIZIO</p>
                </td>
                <td style="width:161.02pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">BUONO</p>
                </td>
                <td style="width:168.55pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">SCARSO</p>
                </td>
            </tr>
            <tr>
                <td style="width:161.02pt; border-top:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">FATTORE</p>
                </td>
                <td style="width:161.02pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1,00</p>
                </td>
                <td style="width:168.55pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,90</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:14pt; margin-bottom:14pt; text-align:center; line-height:normal; font-size:10pt;">FREQUENZA DEI GESTI (numero di atti al minuto) IN RELAZIONE ALLA DURATA&nbsp;<strong>(F)</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:157.57pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">FREQUENZA</p>
                </td>
                <td style="width:45.48pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,20</p>
                </td>
                <td style="width:45.52pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1</p>
                </td>
                <td style="width:45.52pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">4</p>
                </td>
                <td style="width:45.52pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">6</p>
                </td>
                <td style="width:45.52pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">9</p>
                </td>
                <td style="width:50.58pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">12</p>
                </td>
                <td style="width:53pt; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">&gt;15</p>
                </td>
            </tr>
            <tr>
                <td style="width:157.57pt; border-top:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">CONTINUO &lt; 1 ora</p>
                </td>
                <td style="width:45.48pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1,00</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,94</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,84</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,75</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,52</p>
                </td>
                <td style="width:50.58pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,37</p>
                </td>
                <td style="width:53pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                </td>
            </tr>
            <tr>
                <td style="width:157.57pt; border-top:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">CONTINUO da 1 a 2 ore</p>
                </td>
                <td style="width:45.48pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,95</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,88</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,72</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,5</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,3</p>
                </td>
                <td style="width:50.58pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,21</p>
                </td>
                <td style="width:53pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; border-bottom:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                </td>
            </tr>
            <tr>
                <td style="width:157.57pt; border-top:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">CONTINUO da 2 a 8 ore</p>
                </td>
                <td style="width:45.48pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,85</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,75</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,45</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,27</p>
                </td>
                <td style="width:45.52pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,52</p>
                </td>
                <td style="width:50.58pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                </td>
                <td style="width:53pt; border-top:0.75pt double #808080; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,00</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:14pt; margin-bottom:14pt; text-align:center; line-height:normal; font-size:10pt;">SOLLEVA CON UN SOLO GESTO&nbsp;<strong>(G)</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:245.68pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">NO</p>
                </td>
                <td style="width:245.3pt; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1</p>
                </td>
            </tr>
            <tr>
                <td style="width:245.68pt; border-top:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">SI</p>
                </td>
                <td style="width:245.3pt; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,6</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:14pt; margin-bottom:14pt; text-align:center; line-height:normal; font-size:10pt;">SOLLEVANO IN DUE OPERATORI&nbsp;<strong>(H)</strong></p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt double rgb(128, 128, 128); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:245.68pt; border-bottom:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">NO</p>
                </td>
                <td style="width:245.3pt; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">1</p>
                </td>
            </tr>
            <tr>
                <td style="width:245.68pt; border-top:0.75pt double #808080; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">SI</p>
                </td>
                <td style="width:245.3pt; border-left:0.75pt double #808080; vertical-align:middle; background-color:#d6e3bc;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:10pt;">0,85</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt;">&nbsp;</p>
    <table cellspacing="0" cellpadding="0" style="border: 0.75pt double rgb(0, 0, 0); border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:491.35pt; vertical-align:middle;">
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; font-size:12pt;"><strong>PESO LIMITE RACCOMANDATO =</strong> CP x A x B x C x D x E x F x G x H</p>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:0pt; margin-bottom:10pt; text-indent:14.2pt; text-align:justify; line-height:11pt;">&nbsp;</p>
    <table cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:107.2pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:10.85pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:144.85pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">peso sollevato</span></strong></p>
                </td>
                <td style="width:14.4pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:101.05pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;"><?php echo ($highest_id_entry['heaviest_weight'] ?? '')?></span></strong></p>
                </td>
                <td style="width:12.5pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:55.5pt; border-top-style:solid; border-top-width:0.75pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; padding-right:3.12pt; padding-left:3.12pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
            </tr>
            <tr>
                <td style="width:107.2pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">INDICE DI SOLLEVAMENTO</span></strong></p>
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">R</span></strong></p>
                </td>
                <td style="width:10.85pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">=</span></strong></p>
                </td>
                <td style="width:144.85pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">-------------------------------------------</span></strong></p>
                </td>
                <td style="width:14.4pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">=</span></strong></p>
                </td>
                <td style="width:101.05pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">------------------------------</span></strong></p>
                </td>
                <td style="width:12.5pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">=</span></strong></p>
                </td>
                <td style="width:55.5pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; padding-right:3.12pt; padding-left:3.12pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
            </tr>
            <tr>
                <td style="width:107.2pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:10.85pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:144.85pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">peso limite raccomandato</span></strong></p>
                </td>
                <td style="width:14.4pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:101.05pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;"><?php echo ($highest_id_entry['recommended_weight'] ?? '')?></span></strong></p>
                </td>
                <td style="width:12.5pt; padding-right:3.5pt; padding-left:3.5pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;">&nbsp;</span></strong></p>
                </td>
                <td style="width:55.5pt; border-right-style:solid; border-right-width:0.75pt; border-left-style:solid; border-left-width:0.75pt; border-bottom-style:solid; border-bottom-width:0.75pt; padding-right:3.12pt; padding-left:3.12pt; vertical-align:top;">
                    <p style="margin-top:0pt; margin-bottom:10pt; text-align:center; line-height:10.8pt;"><strong><span style="font-size:11pt;"><?php echo ($highest_id_entry['r'] ?? '')?></span></strong></p>
                </td>
            </tr>
        </tbody>
    </table>

<style>
.highlight {
background-color: yellow; /* Use a color that resembles a highlighter */
font-weight: bold;
}
</style>

    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;">&nbsp;</p>
    <p style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;"><strong>Livelli di rischio e misure di prevenzione:</strong></p>
    <p class= <?php if(($highest_id_entry['r'] ?? -1) <= 0.85){echo "'highlight'";}?>  style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;"><strong>Se R = &lt; 0,85 (area verde): la situazione &egrave; accettabile e non &egrave; richiesto alcuno specifico intervento.</strong></p>
    <p class= <?php if(($highest_id_entry['r'] ?? -1) > 0.85 && ($highest_id_entry['r'] ?? -1) < 1){echo "'highlight'";}?>  style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;"><strong>Se 0,85 &lt;</strong><strong>&nbsp;&nbsp;</strong><strong>R</strong><strong>&nbsp;&nbsp;</strong><strong>&lt; 1 (area gialla): la situazione si avvicina ai limiti; una quota della popolazione (a dubbia esposizione) pu&ograve; essere non protetta e pertanto occorrono cautele, anche se non &egrave; necessario un intervento immediato. E&rsquo; comunque consigliato attivare la formazione e, a discrezione del medico, la sorveglianza sanitaria del personale addetto.</strong></p>
    <p class= <?php if(($highest_id_entry['r'] ?? -1) >= 1){echo "'highlight'";}?>  style="margin-top:0pt; margin-bottom:10pt; text-align:justify; line-height:11pt;"><strong>Se R &gt; 1 (area rossa): la situazione pu&ograve; comportare un rischio per quote crescenti di popolazione e pertanto richiede un intervento di prevenzione primaria. Il rischio &egrave; tanto pi&ugrave; elevato quanto maggiore &egrave; l&rsquo;indice. Vi &egrave; necessit&agrave; di un intervento immediato di prevenzione per situazioni con indice maggiore di 3; l&rsquo;intervento &egrave; comunque necessario anche con indici compresi tra 1,25 e 3. E&rsquo; utile programmare gli interventi identificando le priorit&agrave; di rischio. Successivamente riverificare l&rsquo;indice di rischio dopo ogni intervento. Va comunque attivata la sorveglianza sanitaria periodica del personale esposto con periodicit&agrave; bilanciata in funzione del livello di rischio.</strong></p>
    <div style="clear:both;">
        <p style="margin-top:0pt; margin-right:18pt; margin-bottom:10pt; line-height:115%; font-size:9pt;">Allegato 03 - Movimentazione manuale dei carichi - Lista di controllo - Versione 2012<span style="width:123.58pt; display:inline-block;">&nbsp;</span> pagina 1 di 5</p>
    </div>
</div>
<p style="bottom: 10px; right: 10px; position: absolute;"><a href="https://wordtohtml.net" target="_blank" style="font-size:11px; color: #d0d0d0;">Converted to HTML with WordToHTML.net</a></p>
