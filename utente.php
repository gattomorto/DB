<!DOCTYPE html>
<?php
include "gestione_biblio.php";


//$matricolaPrestito=$_GET['matricola'];
//$dataInizioPrestito=$_Get['inizio']+
$matricolaPrestito = '118366';



?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Utente</title>
</head>
<body>

    <div align="center">
        <h1>Utente</h1>
    </div><br>
<?php

    $qry="select * from utenti where matricola='$matricolaPrestito'";
    $result = $GLOBALS['connessione']->query($qry);
    $row = $result->fetch_assoc();

    $nome=$row['nome'];
    $cognome=$row['cognome'];
    $indirizzo=$row['indirizzo'];
    $telefono=$row['telefono'];
    $matricola=$row['matricola'];


echo"
    <div style='font-size:30px; line-height: 1.6;'>
        <strong>Nome</strong>: $nome<br>
        <strong>Cognome</strong>: $cognome<br>
        <strong>Matricola</strong>: $matricola<br>
        <strong>Indirizzo</strong>: $indirizzo<br>
        <strong>Telefono</strong>: $telefono<br>";

?>
    </div><br><br>


    <table border="0" width="200" align="left" cellspacing="1">

        <tr>

            <td>
                <form action="">
                    <button type=”submit” style="font-size: large">Elimina</button>
                </form>
            </td>

            <td>
                <form action="">
                    <button type=”submit” style="font-size: large">Modifica</button>
                </form>
            </td>


        </tr>

    </table><br><br><hr><br><br><br>
<?php

    $prestitiUtente="select * from prestiti where matricola='$matricolaPrestito'";
    $result = $GLOBALS['connessione']->query($prestitiUtente);
    $numBlocchi=mysqli_num_rows($result);

    for($i=0; $i<$numBlocchi; $i++)
    {
        $row = $result->fetch_assoc();
        $dataFine=$row['fine'];
        $dataInizio=$row['inizio'];
        $restituito=$row['restituito'];

        if($restituito==0)
        {

           echo"
                   <div style='font-size:30px; line-height: 1.6;'>
                        <u>Data uscita</u>: $dataInizio &nbsp;&nbsp; <u>Scadenza</u>: $dataFine
                    </div><br>";

           $qry="select l.isbn, titolo, numero_copia from copie_prestiti inner join libri l on copie_prestiti.isbn = l.isbn
                  where matricola='$matricolaPrestito' and inizio='$dataInizio'";
            $prestito = $GLOBALS['connessione']->query($qry);

            echo"
                     <font size='5'>
                     <table border='1'  height='50' width='800' align='left' cellspacing='1'>

                            <tr align='center'>
                                <td height='50'><strong>Isbn</strong></td>
                                 <td><strong>Titolo</strong></td>
                                 <td width='200'><strong>Numero Copia</strong></td>

                            </tr>";

            while( $riga = $prestito->fetch_assoc())
            {
                $isbn=$riga['isbn'];
                $titolo=$riga['titolo'];
                $numCopia=$riga['numero_copia'];

                echo"
                
                             <tr>
                                <td height='50'>$isbn</td>
                                <td>$titolo</td>
                                <td width='200'>$numCopia</td>

                              </tr>";

            }

            echo"
            
            </table>
            </font><br><br><br><br><br><br><br><br><br><br><br>
            
             <form action='' method='get'>
            <button style='font-size:large' type='submit'>restituito</button>
             </form><br><br><br><br><br><br><br><br>";


        }



    }



?>




////////////////////////////////////////////////////////////////////

    <?php

    $presUtente="select * from prestiti where matricola='$matricolaPrestito'";
    $result = $GLOBALS['connessione']->query($presUtente);
    $numBlocchi=mysqli_num_rows($result);

    for($i=0; $i<$numBlocchi; $i++)
    {
        $row = $result->fetch_assoc();
        $dataFine=$row['fine'];
        $dataInizio=$row['inizio'];
        $restituito=$row['restituito'];

        if($restituito==1)
        {

            echo"
                   <div style='font-size:30px; line-height: 1.6;'>
                        <u>Data uscita</u>: $dataInizio &nbsp;&nbsp; <u>Scadenza</u>: $dataFine
                    </div><br>";

            $qry="select l.isbn, titolo, numero_copia from copie_prestiti inner join libri l on copie_prestiti.isbn = l.isbn
                  where matricola='$matricolaPrestito' and inizio='$dataInizio'";
            $pre = $GLOBALS['connessione']->query($qry);

            echo"
                     <font size='5'>
                     <table border='1'  height='50' width='800' align='left' cellspacing='1'>

                            <tr align='center'>
                                <td height='50'><strong>Isbn</strong></td>
                                 <td><strong>Titolo</strong></td>
                                 <td width='200'><strong>Numero Copia</strong></td>

                            </tr>";

            while( $line = $pre->fetch_assoc())
            {
                $isbn=$line['isbn'];
                $titolo=$line['titolo'];
                $numCopia=$line['numero_copia'];

                echo"
                
                             <tr>
                                <td height='50'>$isbn</td>
                                <td>$titolo</td>
                                <td width='200'>$numCopia</td>

                              </tr>";

            }

            echo"
            
            </table>
            </font><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <form action='' method='get'>
            <button style='font-size:large' type='submit'>restituito</button>
            </form><br><br><br><br>";

        }



    }



    ?>





</body>
</html>