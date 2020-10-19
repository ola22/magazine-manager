<?php

session_start();

?>


<html>
<head>
<title>Strona główna</title>
<meta charset="UTF-8">
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
</head>
<body bgcolor="white">

<h2 align=center>MENU GŁÓWNE</h2>

<br>
<br>

<form action="wypisywanieStanuMagazynu.php" method="post">
<input type="submit" value="STAN MAGAZYNU">
</form>	

<br>
<br>

<form action="wypisywanieZlecen.php" method="post">
<input type="submit" value="ZLECENIA DO REALIZACJI">
</form>  

<br>
<br>

<form action="dodawanieZlecenia.php" method="post">
<input type="submit" value="DODAJ ZLECENIE">
</form>  

<br>
<br>

<form action="dodawanieProduktu.php" method="post">
<input type="submit" value="DODAJ DO MAGAZYNU">
</form>  

<br>
<br>

<form action="realizacjaZlecenia.php" method="post">
<input type="submit" value="ZLECENIE ZREALIZOWANE">
</form>

<br>
<br>

<form action="szukanieKlienta.php" method="post">
<input type="submit" value="WYSZUKAJ KLIENTA">
</form>

<br>
<br>

</body>
</html>	
