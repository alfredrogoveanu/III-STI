<?php
session_start();
$cod = $_GET['cod'];
$cant = $_GET['cant'];

$conn = oci_connect('SYSTEM', 'PAROLA12!', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "select codp, nume, pret, stoc from produse where codp = '$cod'");
oci_execute($stid);
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
                echo " " . $_SESSION["nrprod"] + 1;
                ?>
            </a>
            <div class="w3-center w3-padding-16">MAGAZINUL „CHEZ NOUS”</div>
        </div>
    </div>
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <?php
        $stocok = 1;
        while (($linie = oci_fetch_array($stid, OCI_BOTH)) != false) {
            $_SESSION["nrprod"]++;
            $_SESSION["cp"][$_SESSION["nrprod"] - 1] = $linie['CODP'];
            $_SESSION["np"][$_SESSION["nrprod"] - 1] = $linie['NUME'];
            if ($cant <= $linie['STOC']) {
                $_SESSION["ca"][$_SESSION["nrprod"] - 1] = $cant;
                $_SESSION["total"] = $_SESSION["total"] + $linie['PRET'] * $cant;
            }
            else {
                $stocok = 0;
                $_SESSION["ca"][$_SESSION["nrprod"] - 1] = $linie['STOC'];
                $_SESSION["total"] = $_SESSION["total"] + $linie['PRET'] * $linie['STOC'];
            }
            $_SESSION["pp"][$_SESSION["nrprod"] - 1] = $linie['PRET'];


            echo '<div class="container"><h2>SUCCES!</h2>
            <p>Produsul dvs a fost adăugat în coș.</p>';
            if (!$stocok) {
                echo '<p>Datorită stocului redus, puteți adăuga în coș doar ' . $linie['STOC'] . ' de bucăți din acest produs în loc de ' . $cant . '.</p>';
                $cant = $linie['STOC'];
            }
            $cp = $linie['CODP'];
            $sp = $linie['STOC'] - $cant;
            $upd_stck = "update produse set stoc='$sp' where codp='$cp'";

            $stid2 = oci_parse($conn, "update produse set stoc='$sp' where codp='$cp'");
            oci_execute($stid2);

            echo '<table class="table table-striped">
              <thead><tr>
                  <th>NUME</th><th>PREȚ</th><th>CANTITATE</th><th>TOTAL</th>
                </tr></thead><tbody><tr>
                  <td>' . $linie['NUME'] . '</td><td>' . $linie['PRET'] . '</td>
                  <td>' . $cant . '</td><td>' . $linie['PRET'] * $cant . ' lei</td>
                </tr></tbody></table></div>';
        }
        ?>
        <a class="btn btn-secondary" href="index.php" role="button">Înapoi la lista de produse.</a>
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