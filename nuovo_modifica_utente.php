<!DOCTYPE html>

<?php
include "gestione_biblio.php";



?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>nuovo</title>
</head>
<body bgcolor="#f5f5dc">
<!--<div align="center">
    <h1>Nuovo Utente</h1>
    <div align="left">
        <a href="index.php"><img  title="Home"  src="immagini/logo.png"></a>
    </div><br><br><br>


</div>-->
<table border="0" width="1000" align="left" cellspacing="1">
    <tr>
        <td class="all"> <div align="left">
                <a href="index.php"><img  title="Home"  src="immagini/logo.png"></a>
            </div>
        </td>

        <td class="all">
            <div align="center">
                <h1>Nuovo utente</h1>
            </div><br><br>
        </td>


    </tr>
</table><br><br><br><br><br><br><br><br><br><br><br>
<div style='font-size:30px; line-height: 1.6;'>
    <form action='gestione_utenti.php' method='get'>
        <strong>Matricola</strong>:
        <input  size='25' style='font-size:medium' type=”text” name='testoMatricola'><br>
        <strong>Nome</strong>:
        <input  size='25' style='font-size:medium' type=”text” name='testoNome'><br>
        <strong>Cognome</strong>:
        <input  size='25' style='font-size:medium' type=”text” name='testoCognome'><br>
        <strong>Indirizzo</strong>:
        <input  size='25' style='font-size:medium' type=”text” name='testoIndirizzo'><br>
        <strong>Telefono</strong>:
        <input  size='25' style='font-size:medium' type=”text” name='testoTelefono'><br>
        <input type='hidden' name='salva' value='1'>
        <input type="hidden" name="modifica" value="2">

</div><br><br>
        <table border='0' width='200' align='left' cellspacing='1'>

        <tr>

            <td>
                <button type=”submit” style='font-size: large'>Salva</button>
            </td>


            </form>

    </tr>

</table><br><br><hr><br><br><br>";

</body>
</html>