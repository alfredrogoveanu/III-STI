<?php
session_start();
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

        if ($_SESSION["nrprod"] < 1)
            echo '</br><h3>Nu aveți produse în coșul de cumpărături.</h3></br>';
        else {
            echo '<div class="container"><h2>COȘ DE CUMPĂRĂTURI</h2>
            <p>Aici regăsiți produsele adăugate în coșul dumneavoastră de cumpărături.</p>            
            <table class="table table-striped">
              <thead><tr>
              <th>NR.</th><th>NUME</th><th>PREȚ</th><th>CANTITATE</th><th>TOTAL</th></tr></thead><tbody>';

            for ($i = 0; $i < $_SESSION["nrprod"]; $i++)
                echo '<tr>
                  <td>' . ($i + 1) . '</td><td>' . $_SESSION["np"][$i] . '</td>
                  <td>' . $_SESSION["pp"][$i] . '</td><td>' . $_SESSION["ca"][$i] . '</td>
                  <td>' . $_SESSION["pp"][$i] * $_SESSION["ca"][$i] . ' lei</td></tr>';
            echo '</tbody></table></div>';

            if ($_SESSION["nrprod"] > 0)
                echo '<div class="container"><table class="table table-striped">
                  <thead><tr><th>TOTAL</th><th style="text-align:right">' . $_SESSION["total"] . ' lei</th></tr></thead></table></div>';

            echo '<a class="btn btn-secondary" href="cumparat.php" role="button">CUMPĂRĂ</a><a class="btn btn-secondary" href="golit.php" role="button">GOLEȘTE COȘ</a>';
        }
        ?>
        <hr>

        <footer class="w3-row-padding w3-padding-32">
            <div class="w3-third">
                <h3>Magazinul „Chez Nous”</h3>
                <p>Vă mulțumim că ați cumpărat de la noi. Mai poftiți și altădată!</p>
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