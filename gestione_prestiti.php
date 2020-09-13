<!DOCTYPE html>

<?php
include "gestione_biblio.php";
?>
<html lang="en">
<head>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .corner_left {
            border-top: 1px solid #FFFFFF;
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
        <input  size="25" style="font-size:larger" type=”text”>
        <button type=”submit” style="font-size:20px">cerca</button>
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


        $tab = crea_struttura_gestione_prestiti();
        $size = sizeof($tab);

        //$dataOggi = date('Y-m-d');

        for ($i = 0; $i < $size; $i++) {
            $nome = $tab[$i][0];
            $cognome = $tab[$i][1];
            $matricola = $tab[$i][2];
            $dataInizio = $tab[$i][3];
            $dataFine = $tab[$i][4];
            $restituisci=$tab[$i][5];
            $libriPerIlPrestitoI = $tab[$i][6];


            if ($restituisci==0) {

                echo "
                  <tr>
                        <td class='left_bottom'width='80' height='40'> <form action=''>
                        <button type='submit'>-></button>
                         </form>
                         </td>
                             <td>$nome</td>
                             <td>$cognome</td>
                             <td>$matricola</td>
                             <td>$dataInizio</td>
                             <td>$dataFine</td>";

                echo "<td>";
                foreach ($libriPerIlPrestitoI as $libro) {
                    echo $libro . "<br>";
                }
                echo "</td>";

                echo "<td class='right_bottom'' width='70'><form action=''>
                             <button style='font-size: medium' type='submit'>restituito</button>
                             </form>
                             </tdclass>
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

            $tab = crea_struttura_gestione_prestiti();
            $size = sizeof($tab);

            //$dataOggi = date('Y-m-d');

            for ($i = 0; $i < $size; $i++) {
                $nome = $tab[$i][0];
                $cognome = $tab[$i][1];
                $matricola = $tab[$i][2];
                $dataInizio = $tab[$i][3];
                $dataFine = $tab[$i][4];
                $restituisci=$tab[$i][5];
                $libriPerIlPrestitoI = $tab[$i][6];


                if ($restituisci==1) {

                    echo "
                  <tr>
                        <td class='left_bottom'width='80' height='40'> <form action=''>
                        <button type='submit'>-></button>
                         </form>
                         </td>
                             <td>$nome</td>
                             <td>$cognome</td>
                             <td>$matricola</td>
                             <td>$dataInizio</td>
                             <td>$dataFine</td>";

                    echo "<td>";
                    foreach ($libriPerIlPrestitoI as $libro) {
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