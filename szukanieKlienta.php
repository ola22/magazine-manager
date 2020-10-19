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
<title>Wyszukiwanie klienta</title>
</head>
<body bgcolor="white">

<h2 align=center>WYSZUKAJ KLIENTA</h2>



<?php

echo "<br/>";
echo "<p> <size='4pt'><align=center>W celu zdobycia informacji odnośnie adresu i danych kontaktowych danego klienta, w okienku poniżej należy wpisać nazwę danej firmy.</font> </p>";

?>


<form action="szukanieKlientaAct.php" method="post">
  <br>
  <br>
  <input type="text" name="nazwaFirmy" value="">
  <br>
  <br>
  <input type="submit" value="WYSZUKAJ">
</form>

<br>
<br>

<form action="stronaGlowna.php" method="post">
<input type="submit" value="MENU GÓŁÓWNE">
</form>

</body>
</html>
