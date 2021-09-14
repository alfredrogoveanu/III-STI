<?php
session_start();
$cat = $_GET['cat'];

$conn = oci_connect('SYSTEM', 'PAROLA12!', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "select * from produse where tip='$cat'");
oci_execute($stid);
$stid2 = oci_parse($conn, "select nume from sortimente where scurt='$cat'");
oci_execute($stid2);
$rezultat2 = oci_fetch_array($stid2, OCI_BOTH);

$numecat = $rezultat2;
$numefinal = $numecat[0];
?>

<html>

<head>
    <title>Magazinul „Chez Nous”</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
        echo '<h2>PRODUSELE DIN GAMA ' . $numefinal . '</h2></br>';
        $exista = 0;
        while (($linie = oci_fetch_array($stid, OCI_BOTH)) != false) {
            $exista = 1;
            echo '<div id="produse">
                <div id="prod' . $linie['CODP'] . '">
                    <div class="row">
                        <div class="col-xs-3">
                            <img src="' . $linie['LOGO_PATH'] . '" width="250px" height="250px">
                        </div>
                        <div style="col-xs-3">
                            <h3>' . $linie['NUME'] . '</h3>
                            <h4>' . $linie['PRET'] . ' lei</h4>';
            if ($linie['STOC'] > 0)
                echo '<h4 style="color:green">În stoc: ' . $linie['STOC'] . '</h4>';
            else
                echo '<h4 style="color:red">STOC EPUIZAT</h4>';
            echo '<p>' . $linie['DESCRIERE'] . '</p>';
            if ($linie['STOC'] > 0)
                echo '<form method="get" action="adaugare.php" id="' . $linie['CODP'] . '">
                                <p>Cantitate: <input type="text" name="cant"></p>    
                                <input name="cod" value="' . $linie['CODP'] . '" style="display:none">
                                <button type="submit" form="' . $linie['CODP'] . '" class="btn btn-primary" value="1">ADAUGĂ ÎN COȘ</button>
                            </form>';
            echo '</div>
                    </div>
                </div><br/>';
        }
        if ($exista)
            echo '</div>';
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
oci_free_statement($stid);
oci_free_statement($stid2);
oci_close($conn);
?>