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
<title>Wyszukiwanie klienta act</title>
</head>
<body bgcolor="white">

<h2 align=center>DANE KLIENTA</h2>



<?php

$conn = oci_connect("af394182", "baboonek26");
if (!$conn) {
    $e = oci_error();
    echo "Wystąpił problem z połączeniem się z bazą danych ({$e['message']})";
    exit;
}

echo "<br/>";


// Pobieranie danych z formularza.
$firma = $_POST['nazwaFirmy'];

// Sprawdzenie, czy użytkownik wprowadził wszystkie niezbędne dane.
if ($firma == "") {
    echo "<p> Nie podano nazwy firmy. </p>";
    exit;
}


$r = oci_parse($conn, "SELECT * FROM klienci WHERE nazwa_firmy = '" . $firma . "'");
oci_execute($r);
$rowCount = oci_fetch_all($r, $klientSzukany, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);


// Użytkownik podał nieistniejącą nazwę firmy.
if ($rowCount == 0) {
    echo "<p> Błędna nazwa firmy. Spróbuj ponownie. </p>";
    exit;
}


// Wypisywanie danych klienta.
echo "Nazwa firmy: " . $klientSzukany[0][1] . "<br/>";
echo "Imię i nazwisko właściciela: " . $klientSzukany[0][2] . " " . $klientSzukany[0][3] . "<br/>";
echo "Email: " . $klientSzukany[0][4] . "<br/> <br/>";


// Wypisywanie numerów kontaktowych do klientów.
$r2 = oci_parse($conn, "SELECT * FROM kontakty WHERE klient = '" . $klientSzukany[0][0] . "'");
oci_execute($r2);
$rowCount2 = oci_fetch_all($r2, $numeryTel, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

echo "Numer(y) telefonu : <br/>";
for ($i = 0; $i < $rowCount2; $i++) {
    echo "</font><li>" . $numeryTel[$i][0] . "</font></li>";
}


// Wypisywanie danych adresowych do wysyki.
echo "<br/> <br/>";
echo "Wszystkie możliwe adresy danego klienta : <br/>";

$r3 = oci_parse($conn, "SELECT * FROM adresy WHERE klient = '" . $klientSzukany[0][0] . "'");
oci_execute($r3);
$rowCount3 = oci_fetch_all($r3, $daneAdresowe, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

for ($i = 0; $i < $rowCount3; $i++) {
    $numer = $i + 1;
    echo "Dane adresowe do wysyłki " . $numer . ": <br/>";
    echo "</font> <li>Kraj: " . $daneAdresowe[$i][0] . "</font></li>";
    echo "</font> <li>" . $daneAdresowe[$i][2] . " " . $daneAdresowe[$i][1] . " " . $daneAdresowe[$i][3] . " " . $daneAdresowe[$i][4] . "</font></li>";
    echo "</font> <li> Dodatkowe informacje: " . $daneAdresowe[$i][5] . " </font></li>";
    
    echo "<br/>";
}


oci_close($conn);

?>

<br>
<br>

<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GŁÓWNE">
</form>

</body>
</html>
