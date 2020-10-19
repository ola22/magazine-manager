<?php

session_start();

?>

<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

?>


<html>
<head>
<meta http-equiv='Content-type' content='text/html; charset=utf-8'>
<title>Dodaj zlecenie</title>
</head>
<body bgcolor="white">

<h2 align=center>DODAWANIE ZLECENIA</h2>


<?php

// Zczytanie danych od użytkownika.
$data = $_POST['data'];
$klient = $_POST['klient'];
$flop6 = $_POST['flop6'];
$flop7 = $_POST['flop7'];
$flop8 = $_POST['flop8'];
$flop9 = $_POST['flop9'];
$flop12 = $_POST['flop12'];
$flop13 = $_POST['flop13'];

// Sprawdzenie, czy użytkownik wprowadzą wszystkie niezbędne dane.
if ($data == "" || $klient == "" || $flop6 == "" || $flop7 == "" ||
       $flop8 == "" || $flop9 == "" || $flop12 == "" || $flop13 == "") {
    echo "<p> Nie podano wszystkich danych. </p>";
    exit;
}



$conn = oci_connect("af394182", "baboonek26");
if (!$conn) {
    $e = oci_error();
    echo "Wystąpił problem z połączeniem się z bazą danych ({$e['message']})";
    exit;
}


// Funkcje pomocnicze.
function policz_zysk($p, $ile, $conn) {
    $r = oci_parse($conn, "SELECT licz_zysk('". $p . "', " . $ile . ") AS x FROM dual");
    oci_execute($r);
    $rowCount3= oci_fetch_all($r, $z, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);
    $pom = $z[0][0];

    return $pom;
}


function dodaj_produkt_do_zlecenia($p, $ilosc, $idZlecenia, $conn) {
    if ($ilosc == 0) {
        return;
    }

    $r = oci_parse($conn,"INSERT INTO produkty_zlecen VALUES (" . $ilosc . ", " . $idZlecenia . ", '" . $p . "')");
    oci_execute($r);
    oci_commit($conn);
}



// Szukanie id klienta.
$r = oci_parse($conn, "SELECT id FROM klienci WHERE nazwa_firmy = '" . $klient . "'");
oci_execute($r);
$rowCount = oci_fetch_all($r, $idKlienta, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

if ($rowCount == 0) {
    echo "<p> Podano błędną nazwę, firma o podanej nazwie nie istnieje. </p>";
    exit;
}

// Ustalanie id zlecenia.
$r2 = oci_parse($conn,"SELECT * from nowe_id");
oci_execute($r2);
$rowCount2 = oci_fetch_all($r2, $noweId, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

// Liczenie zysku.
$zysk = 0;
$zysk += policz_zysk('FLOP 6', $flop6, $conn);
$zysk += policz_zysk('FLOP 7', $flop7, $conn);
$zysk += policz_zysk('FLOP 8/6', $flop8, $conn);
$zysk += policz_zysk('FLOP 9', $flop9, $conn);
$zysk += policz_zysk('FLOP 12', $flop12, $conn);
$zysk += policz_zysk('FLOP 13/7', $flop13, $conn);

// Dodanie zlecenia do tabeli zleceń do realizacji.
$r3 = oci_parse($conn,"INSERT INTO zlecenia VALUES (" . $noweId[0][0] . ", '" .$data . "', " . $zysk . ", " . $idKlienta[0][0] . ")");
oci_execute($r3);
oci_commit($conn);


// Dodanie kolejnych produktów zleceń do tabeli produkty_zlecen.
dodaj_produkt_do_zlecenia('FLOP 6', $flop6, $noweId[0][0], $conn);
dodaj_produkt_do_zlecenia('FLOP 7', $flop7, $noweId[0][0], $conn);
dodaj_produkt_do_zlecenia('FLOP 8/6', $flop8, $noweId[0][0], $conn);
dodaj_produkt_do_zlecenia('FLOP 9', $flop9, $noweId[0][0], $conn);
dodaj_produkt_do_zlecenia('FLOP 12', $flop12, $noweId[0][0], $conn);
dodaj_produkt_do_zlecenia('FLOP 13/7', $flop13, $noweId[0][0], $conn);

echo "<p> Pomyślnie dodano nowe zlecenie do listy zleceń do realizacji. </p>";

oci_close($conn);

?>

<br>
<br>

<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GŁÓWNE">
</form>

</body>
</html>
