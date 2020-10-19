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

echo "<br/>";
echo "<p> <size='4pt'><align=center>W okienka poniżej należy wpisać kolejne informacje, dotyczące nowo dodawanego zlecenia.</font> </p>";
echo "<p> <size='4pt'><align=center></font>UWAGA!!!  Datę należy wpisać w formacie dd-mmm-rr (gdzie miesiąc jest trzyliterowym skrótem angielskiej nazwy)</p>";

?>


<form action="dodawanieZleceniaAct.php" method="post">
  <br>
  <br>
  Dane nowego zlecenia:<br>
  <br>
  Data wykonania: <input type="text" name="data" value="">
  <br>
  Nazwa firmy: <input type="text" name="klient" value="">
  <br>
  <br>
  FLOP 6: <input type="text" name="flop6" value="">
  <br>
  FLOP 7: <input type="text" name="flop7" value="">
  <br>
  FLOP 8/6: <input type="text" name="flop8" value="">
  <br>
  FLOP 9: <input type="text" name="flop9" value="">
  <br>
  FLOP 12: <input type="text" name="flop12" value="">
  <br>
  FLOP 13/7: <input type="text" name="flop13" value="">
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
