<!DOCTYPE html>

<?php
include "gestione_biblio.php";


?>

<style>

    table, th, td {
        border: 1px solid #090404;
        border-collapse: collapse;
    }
    .corner_left {
        border-top: 1px solid #ffffff;
        border-left: 1px solid #FFFFFF;
        border-bottom: 1px solid #FFFFFF;
    }

    .left_bottom {
        border-left: 1px solid #FFFFFF;
        border-bottom: 1px solid #FFFFFF;

    }


</style>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestione utenti</title>
</head>

    <body bgcolor="#f5f5dc">

    <div align="center">
        <h1>Gestione utenti</h1>
    </div>

    <form action="">
        <input  size="25" style="font-size:larger" type=”text”>
        <button type=”submit” style="font-size:20px">cerca</button>
    </form><br><br>

    <font size="5">
    <table border="1" width="1200" align="center" cellspacing="1">

    <tr>
        <td width="80" height="40" class="corner_left"></td>
        <td><strong>Nome</strong></td>
        <td><strong>Cognome</strong></td>
        <td><strong>Matricola</strong></td>
        <td><strong>Indirizzo</strong></td>
        <td><strong>Telefono</strong></td>

    </tr>
    <?php

    $qry="select * from utenti";
    $result = $GLOBALS['connessione']->query($qry);
    $numRighe=$qry->affected_rows;


    for($i=0; $i<=$numRighe; $i++)
    {
       while( $row = $result->fetch_assoc())
       {
           $nome=$row['nome'];
           $cognome=$row['cognome'];
           $matricola=$row['matricola'];
           $indirizzo=$row['indirizzo'];
           $telefono=$row['telefono'];

           echo"
                <tr>
                    <td  class='left_bottom' width='80' height='40'>
                        <form action=''>
                            <button type='submit'>-></button>
                        </form>
                     </td>
                    <td>$nome</td>
                    <td>$cognome</td>
                    <td>$matricola</td>
                    <td>$indirizzo</td>
                    <td>$telefono</td>

                </tr>";

       }

    }

    ?>

    </table><br>
    </font>

    <form action="">
        <button type=”submit” style="font-size: large">Nuovo Uente</button>
    </form>


    </body>
</html>