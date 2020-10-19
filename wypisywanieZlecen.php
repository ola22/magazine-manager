<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<html>
<head>
<meta http-equiv='Content-type' content='text/html; charset=utf-8'>
<title>Stan magazynu</title>
</head>
<body bgcolor="white">

<h2 align=center>ZLECENIA DO REALIZACJI</h2>



<?php

$conn = oci_connect("af394182", "baboonek26");
if (!$conn) {
    $e = oci_error();
    echo "Wystąpił problem z połączeniem się z bazą danych ({$e['message']})";
    exit;
}


// Pobieranie i wypisywanie tabeli zlecen.
$r = oci_parse($conn, "SELECT * FROM zlecenia ORDER BY data");
oci_execute($r);
$rowCount = oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);


// Wypisywanie kolejnych zleceń.
for ($i = 0; $i < $rowCount; $i++) {
    $r2 = oci_parse($conn, "SELECT nazwa_firmy FROM klienci WHERE id =" . $all[$i][3] . "");
    oci_execute($r2);
    $rowCount2 = oci_fetch_all($r2, $nazwaFirmy, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

    echo "<p> <font color=blue size='6pt'>ZLECENIE NUMER " . $all[$i][0] . "</font> </p>";
    echo "<p> <size='4pt'>Data realizacji: " . $all[$i][1] . "</font> </p>";
    echo "<p> <size='4pt'>Spodziewany zysk: " . $all[$i][2] . " zł </font> </p>";
    echo "<p> <size='4pt'>Klient: " . $nazwaFirmy[0][0] . "</font> </p>";

    
    // Szukanie produktów danego zlecenia.
    $r3 = oci_parse($conn, "SELECT * FROM produkty_zlecen WHERE id_zlecenia =" . $all[$i][0] . "");
    oci_execute($r3);
    $rowCount3 = oci_fetch_all($r3, $produktyZlecen, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

    echo "<p> <size='4pt'>Zamówione produkty: </font> </p>";

    
    // Wypisywanie produktow danego zlecenia.
    for ($j = 0; $j < $rowCount3; $j++) {
        $r4 = oci_parse($conn, "SELECT ile_brakuje('". $produktyZlecen[$j][2] . "', " . $produktyZlecen[$j][0] . ") AS x FROM dual");
        oci_execute($r4);
        $rowCount4 = oci_fetch_all($r4, $brak, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);
        echo "</font> <li>" . $produktyZlecen[$j][2] . ": zamÃ³wiono " . $produktyZlecen[$j][0] . " paczek, do realizacji zamówienia brakuje: ";
        echo "<font color=red>" . $brak[0][0] . " paczek.</font></font></li>";
    }

    echo "<br/> <br/>";
}


oci_close($conn);

?>

<br>

<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GŁÓWNE">
</form>

</body>
</html>
