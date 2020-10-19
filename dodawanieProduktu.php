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
<title>Dodawanie produktu</title>
</head>
<body bgcolor="white">

<h2 align=center>DODAWANIE PRODUKTU</h2>



<?php

echo "<br/>";
echo "<p> <size='4pt'><align=center>W okienka poniżej należy wpisać nazwę produktu oraz jego ilość, którą chcemy dodać do magazynu, a następnie wcisnąć przycisk zatwierdź.</font> </p>";

?>

<form action="dodawanieProduktuAct.php" method="post">
  <br>
  <br>
  Dodaj do magazynu:<br>
  <br>
  Nazwa produktu: <input type="text" name="produkt" value="">
  <br>
  Ilość: <input type="text" name="ile" value="">
  <br>
  <br>
  <input type="submit" value="ZATWIERDŹ">
</form>

<br>
<br>

<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GŁÓWNE">
</form>


</body>
</html>
