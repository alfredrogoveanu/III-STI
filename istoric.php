<?php
session_start();
$conn = oci_connect('SYSTEM', 'PAROLA12!', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
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

        echo '<div class="container"><h2>ISTORIC</h2>
            <p>Aici găsiți cumpărăturile pe care le-ați făcut la magazinul nostru.</p>';

        $stid = oci_parse($conn, "select max(id_c) from cumparat");
        oci_execute($stid);
        $linie = oci_fetch_array($stid, OCI_BOTH);

        if ($linie[0] < 1) {
            echo '</br><h3>Nu sunt cumpărături de afișat.</h3></br>';
        }

        for ($i = 1; $i <= $linie[0]; $i++) {
            $suma_totala = 0;
            $dt;
            $np = 1;
            $stid2 = oci_parse($conn, "select * from cumparat where id_c=$i");
            oci_execute($stid2);
            $stid3 = oci_parse($conn, "select data_achizitie,total from cumparat where id_c=$i");
            oci_execute($stid3);

            while (($lin = oci_fetch_array($stid3, OCI_BOTH)) != false) {
                $suma_totala += $lin['TOTAL'];
                $dt = date_create($lin['DATA_ACHIZITIE']);
            }

            echo '</br><h3>BONUL #' . $i . ' din data de ' . date_format($dt, "Y-m-d G:i:s") . ' în valoare de ' . $suma_totala . ' lei</h3>
                <table class="table table-striped">
                <thead><tr>
                <th>NR.</th><th>NUME</th><th>PREȚ</th><th>CANTITATE</th><th>TOTAL</th></tr></thead>
                <tbody>';

            while (($lin = oci_fetch_array($stid2, OCI_BOTH)) != false) {
                echo '<tr>
                <td>' . $np . '</td>
                <td>' . $lin['NUME'] . '</td><td>' . $lin['PRET'] . '</td><td>' . $lin['CANT'] . '</td>
                <td>' . $lin['PRET'] * $lin['CANT'] . ' lei</td></tr>';
                $np++;
            }

            echo '</tbody></table>';
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
oci_free_statement($stid3);
oci_close($conn);
?>