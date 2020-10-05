<?php
include "gestione_biblio.php";
if(isset($_GET["nuovoPrestito"]))
{
    $matricola = $_GET['matricolaSelezionato'];
    $isbn_num_copieSelezionate = $_GET['isbn_num_copieSelezionate'];
    inserisci_prestito($matricola,$isbn_num_copieSelezionate);
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<style>

    table, th, td {
        border: 1px solid #090404;
        border-collapse: collapse;
    }

    .right {
        border-right: 1px solid #FFFFFF;


    }

    .top {
        border-top: 1px solid #FFFFFF;

    }

    .bottom {

        border-bottom: 1px solid #FFFFFF;

    }

    .bottom_right {

        border-bottom: 1px solid #FFFFFF;
        border-right: 1px solid #FFFFFF;

    }






</style>
<head>
    <title>Gestione biblioteca</title>

</head>
<body>


        <font size="5">
        <table width='1500' border='1' align='center' bgcolor='#f5f5dc'>

            <tr height="200">
                <td width="300" align="center" class="bottom_right"> <img src="immagini/logo.png" width="250"> </td>
                <td colspan="2" align="left"><h1>Gestione biblioteca</h1></td>
            </tr>

            <tr height="200" >
                <td width="500" class="bottom"><h2>Menu'</h2><br>
                    <h4><a href="lista_libri.php">Lista libri<a/></h4><hr>


                </td>
                <td rowspan="2" width="500" align="right" class="right"><img src="immagini/lingue.jpg" width="100" ></td>
                <td rowspan="2">
                        <h3 align="center">Le cinque lingue piu comuni:</h3>

                        <div align="center">
                               <?php
                                    $lingue = cinque_lingue();
                                    echo $lingue[0]."<br>";
                                    echo $lingue[1]."<br>";
                                    echo $lingue[2]."<br>";
                                    echo $lingue[3]."<br>";
                                    echo $lingue[4]."<br>";


                               ?>
                        </div>



                </td>
            </tr>

            <tr height="100">
                <td  class="bottom" rowspan="2"><h3>Gestione:</h3><hr>
                    <h4><a href="gestione_utenti.php">gestione utenti</a></h4><hr>
                    <h4><a href="gestione_prestiti.php"> gestione prestiti</a></h4><hr>
                </td>

            </tr>

            <tr height="200">
                <td align="right" class="right"><img src="immagini/scrittore-logo.png" width="100"></td>
                <td>
                    <div align="center">
                        <h3>L'autore che ha scritto piu libri nel nostro archivio</h3>
                        <?php
                            $maxAutore=autore_piu_libri();
                            echo $maxAutore[0]." ";
                            echo $maxAutore[1]."<br>"; ?>
                    </div>
                </td>

            </tr>

            <tr height="300">
                <td class="bottom"><h3>Nuovo:</h3><hr>
                    <h4><a href="nuovo_modifica_utente.php"> nuovo utente</a></h4><hr>
                    <h4><a href="nuovo_prestito.php"> nuovo prestito</a></h4><hr>


                </td>
                <td align="right" class="right"><img src="immagini/editrice.jpg" width="100"></td>
                <td>
                    <div align="center">
                        <h3>La casa editrice che ha pubblicato piu libri</h3>
                        <?php
                            $maxEditore=editore_piu_libri();
                            echo $maxEditore[0]."<br>";

                            ?></div>
                </td>
            </tr>
            <tr height="100">
                <td colspan="3"></td>


            </tr>



        </table>
        </font>


</body>
</html>