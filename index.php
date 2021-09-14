<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
if (!isset($_SESSION["intrat"])) {
    $_SESSION["intrat"] = 1;
    $_SESSION["nrprod"] = 0;
    $_SESSION["total"] = 0;
    $_SESSION["cp"] = array();
    $_SESSION["np"] = array();
    $_SESSION["ca"] = array();
    $_SESSION["pp"] = array();
}
$conn = oci_connect("SYSTEM", "PAROLA12!", "localhost/XE");
if (!$conn) {
    $m = oci_error();
    trigger_error('Could not connect to database: ' . $m['message'], E_USER_ERROR);
}

$s = oci_parse($conn, "select * from sortimente");
oci_execute($s);
?>

<html>

<head>
    <title>Magazinul „Chez Nous”</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Karma", sans-serif
        }

        .w3-bar-block .w3-bar-item {
            padding: 20px
        }
    </style>
</head>

<body>
    <nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:40%;min-width:300px" id="mySidebar">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button">Închide</a>
        <a href="cos.php" onclick="w3_close()" class="w3-bar-item w3-button">Coș de cumpărături</a>
        <a href="./" onclick="w3_close()" class="w3-bar-item w3-button">Categorii</a>
        <a href="istoric.php" onclick="w3_close()" class="w3-bar-item w3-button">Istoric achiziții</a>
    </nav>
    <div class="w3-top">
        <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
            <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
            <a href="cos.php" class="w3-right w3-padding-16"><i class="fas fa-shopping-cart"></i>
                <?php
                echo " " . $_SESSION["nrprod"];
                ?>
            </a>
            <div class="w3-center w3-padding-16">MAGAZINUL „CHEZ NOUS”</div>
        </div>
    </div>
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <?php
        $exista = 0;
        $count = 0;

        while (($linie = oci_fetch_array($s, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            $exista = 1;
            $count++;
            if ($count % 4 == 1) echo '<div class="w3-row-padding w3-padding-16 w3-center" id="' . $linie['SCURT'] . '">';
            echo '<a href="produse.php?cat=' . $linie['SCURT'] . '"><div class="w3-quarter">' . '<img src="' . $linie['LOGO_PATH'] . '" style="width:272px;height:272px">' . '<h3>' . $linie['NUME'] . '</h3>' . '<p>' . $linie['DESCRIERE'] . '</p></div></a>';
            if ($count % 4 == 0) {
                echo '</div>';
                $exista = 0;
            }
        }
        if ($exista) echo '</div>';
        ?>
        <hr>
        <footer class="w3-row-padding w3-padding-32">
            <div class="w3-third">
                <h3>Magazinul „Chez Nous”</h3>
                <p>Vă mulțumim că ați cumpărat de la noi. Mai poftiți și altadată!</p>
                <p>© 2020 Rogoveanu Alfred</p>
            </div>
        </footer>
    </div>
    <script>
        function w3_open() {
            document.getElementById("mySidebar").style.display = "block";
        }

        function w3_close() {
            document.getElementById("mySidebar").style.display = "none";
        }
    </script>
</body>

</html>

<?php
oci_free_statement($s);
oci_close($conn);
?>