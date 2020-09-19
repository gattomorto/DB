<?php
include "gestione_biblio.php";

if(isset($_GET['matricola']) and isset($_GET['inizio']))
{
    restituisci_prestito($_GET['matricola'],$_GET['inizio']);
}

if (isset($_GET['testo'])) {

    $tab = filtra($_GET['testo']);
}
else
{
    $tab = crea_struttura_gestione_prestiti();
}


?>


<!DOCTYPE html>


<html lang="en">
<head>

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

        .corner_right {
            border-top: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .left_right {
            border-left: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
        }

        .top_left_bottom {
            border-top: 1px solid #FFFFFF;
            border-left: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .left_bottom {
            border-left: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;

        }

        .top_right_bottom {
            border-top: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .right_bottom {

            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }
    </style>


    <meta charset="UTF-8">
    <title>Gestione prestiti</title>
</head>
<body bgcolor="#f5f5dc">

    <div align="center">
        <h1>Gestione prestiti</h1>
    </div>


    <form action="">
        <input  size="25" style="font-size:larger" type=”text” name="testo" >
        <button  type=”submit” style="font-size:20px">cerca</button>
    </form><br>


    <div align="left">

        <strong><h2>Prestiti attivi</h2></strong>
    </div>



    <font size="5">
    <table border="1" width="1500" align="center" cellspacing="1">

        <tr >
            <td class="corner_left" width="80" height="40"></td>
            <td > <strong>Nome</strong></td>
            <td><strong>Cognome</strong></td>
            <td><strong>Matricola</strong></td>
            <td><strong>Data uscita</strong></td>
            <td><strong>Data rientro</strong></td>
            <td><strong>Libri</strong></td>
            <td class="corner_right" width="70"></td>
        </tr>

        <?php


        $size = sizeof($tab);

        //$dataOggi = date('Y-m-d');

        for ($i = 0; $i < $size; $i++) {
            $nome = $tab[$i][0];
            $cognome = $tab[$i][1];
            $matricola = $tab[$i][2];
            $dataInizio = $tab[$i][3];
            $dataFine = $tab[$i][4];
            $restituito=$tab[$i][5];
            $libriDelPrestito = $tab[$i][6];


            if ($restituito==0) {

                echo "
                  <tr>
                        <td class='left_bottom'width='80' height='40'> 
                            <form action='utente.php' method='get'>
                                <input type='hidden' name='matricola' value='$matricola'>
                                <button type='submit'>-></button>
                            </form>
                        </td>
                             <td align='center'>$nome</td>
                             <td align='center'>$cognome</td>
                             <td align='center'>$matricola</td>
                             <td align='center'>$dataInizio</td>
                             <td align='center'>$dataFine</td>";

                echo "<td>";
                foreach ($libriDelPrestito as $libro) {
                    echo $libro . "<br>";
                }
                echo "</td>";

                echo "<td class='right_bottom'' width='70'>
                             <form action='' method='get'>
                                 <input type='hidden' name='matricola' value='$matricola'>
                                 <input type='hidden' name='inizio' value='$dataInizio'>
                                 <button style='font-size: medium' type='submit'>restituito</button>
                             </form>
                      </td>
                </tr>";



            }


        }


        ?>

    </table><br><br>

<div align="left">

    <strong><h4>Prestiti conclusi</h4></strong>
</div>

        <table border="1" width="1500" align="center" cellspacing="1">

            <tr >
                <td class="corner_left" width="80" height="40"></td>
                <td > <strong>Nome</strong></td>
                <td><strong>Cognome</strong></td>
                <td><strong>Matricola</strong></td>
                <td><strong>Data uscita</strong></td>
                <td><strong>Data rientro</strong></td>
                <td><strong>Libri</strong></td>
                <td class="corner_right" width="70"></td>
            </tr>


            <?php
            //$dataOggi = date('Y-m-d');

            for ($i = 0; $i < $size; $i++) {
                $nome = $tab[$i][0];
                $cognome = $tab[$i][1];
                $matricola = $tab[$i][2];
                $dataInizio = $tab[$i][3];
                $dataFine = $tab[$i][4];
                $restituito=$tab[$i][5];
                $libriDelPrestito = $tab[$i][6];


                if ($restituito==1) {

                    echo "
                  <tr>
                        <td class='left_bottom'width='80' height='40'> 
                            <form action='utente.php' method='get'>
                                <input type='hidden' name='matricola' value='$matricola'>
                                <button type='submit'>-></button>
                            </form>
                         </td>
                             <td align='center'>$nome</td>
                             <td align='center'>$cognome</td>
                             <td align='center'>$matricola</td>
                             <td align='center'>$dataInizio</td>
                             <td align='center'>$dataFine</td>";

                    echo "<td>";
                    foreach ($libriDelPrestito as $libro) {
                        echo $libro . "<br>";
                    }
                    echo "</td>";


                }


            }

            ?>

        </table><br><br><br>

    </font>

    <form action="">
        <button type=”submit” style="font-size:x-large">nuovo prestito</button>
    </form>



</body>
</html>