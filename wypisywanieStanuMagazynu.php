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

<h2 align=center>STAN MAGAZYNU</h2>


<?php



$conn = oci_connect("af394182", "baboonek26");
if (!$conn) {
    $e = oci_error();
    echo "Wystąpił problem z połączeniem się z bazą danych ({$e['message']})";
    exit;
}


// Pobieranie i wypisywanie tabeli produktow.
$r = oci_parse($conn, "SELECT * FROM produkty");
oci_execute($r);
$rowCount = oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);

echo"<table border=\"1\" align=center>";
    echo"<tr>";
    echo"<th>PRODUKT</th>";	
    echo"<th>ILOŚĆ</th>";
    echo"<th>CENA</th>";
    echo"</tr>\n";
    
for ($i = 0; $i < $rowCount; $i++) {
    echo "<tr>\n";
    echo "<td>" . $all[$i][0] . "</td> <td>" . $all[$i][1] . "</td> <td>" . $all[$i][2] . " zł</td> </tr>";
}


echo "</table>";


oci_close($conn);

?>

<br>
<br>

<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GŁÓWNE">
</form>

</body>
</html>
