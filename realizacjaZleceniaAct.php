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
<title>Zlecenie zrealizowane</title>
</head>
<body bgcolor="white">

<h2 align=center>REALIZACJA ZLECENIA</h2>


<?php

echo "<br/>";

// Zczytywanie danych od użytkownika.
$numer = $_POST['numer'];

// Sprawdzenie, czy użytkownik wypełnił cały formularz.
if ($numer == "") {
    echo "<p> Nie podano żadnego zlecenia. </p>";
    exit;
}


$conn = oci_connect("af394182", "baboonek26");
if (!$conn) {
    $e = oci_error();
    echo "Wystąpił problem z połączeniem się z bazą danych ({$e['message']})";
    exit;
}


// Funkcja pomocnicza.
function sprawdz_czy_mozna($conn, $idZlecenia) {
    $czyMozna = 1;

    $r = oci_parse($conn, "SELECT * FROM produkty_zlecen WHERE id_zlecenia = " . $idZlecenia . "");
    oci_execute($r);
    $rowCount = oci_fetch_all($r, $produktyZlecen, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);
    
    for ($i = 0; $i < $rowCount; $i++) {
        $r2 = oci_parse($conn, "SELECT ile_brakuje('". $produktyZlecen[$i][2] . "', " . $produktyZlecen[$i][0] . ") AS x FROM dual");
        oci_execute($r2);
        $rowCount2 = oci_fetch_all($r2, $brak, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);
        
        if ($brak[0][0] != 0) {
            $czyMozna = 0;
            return $czyMozna;
        }
    }
    
    return $czyMozna;
}


// Sprawdzenie, czy w magazynie znajduje się wystarczająca liczba produktów na realizacją zamówienia.
$czyMozna = sprawdz_czy_mozna($conn, $numer);

if ($czyMozna == 0) {
    echo "<p> Zlecenie o danym identyfikatorze nie może być jeszcze zrealizowane ze względu na zbyt małą ilość produktów w magazynie </p>";
    exit;
}


// Usuwanie zlecenia z tabeli zleceń do realizacji.
$r = oci_parse($conn, "DELETE FROM zlecenia WHERE id = " . $numer . "");
oci_execute($r);
$usuniete = oci_num_rows($r);

if ($usuniete == 0) {
    echo "Zlecenie o podanym identyfikatorze nie istnieje, podano błędny numer zlecenia.";
    exit;
}

oci_commit($conn);
oci_close($conn);

echo "<p> Zlecenie zostało pomyślnie zrealizowane. </p>";

?>


<br>
<br>


<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GŁÓWNE">
</form>

</body>
</html>
