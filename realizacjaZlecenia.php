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

<h2 align=center>ZLECENIE ZREALIZOWANE</h2>



<?php

echo "<br/>";
echo "<p> <size='4pt'><align=center>W okienku poniżej należy wpisać numer zlecenia już zrealizowanego w celu aktualizacji stanu magazynu, a następnie wcisnąć przycisk zatwierdź.</font> </p>";

?>


<form action="realizacjaZleceniaAct.php" method="post">
  <br>
  <br>
  Zrealizowano zlecenie: <input type="text" name="numer" value="">
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
