<?php
include "gestione_biblio.php";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Gestione biblioteca</title>

</head>
<body>
        <table width="800" border="1" align="center" bgcolor="#f5f5dc">

            <tr height="200">
                <td width="250" align="center"> <img src="immagini/logo.png" width="250"> </td>
                <td colspan="2" align="center"><h1>Gestione biblioteca</h1></td>
            </tr>

            <tr height="200">
                <td><h2>Menu'</h2><br>
                    <h4>Lista libri</h4><hr>


                </td>
                <td rowspan="2" width="200" align="right"><img src="immagini/lingue.jpg" width="100" ></td>
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
                <td rowspan="2"><h3>Gestione:</h3><hr>
                                <h4>gestione utenti</h4><hr>
                                <h4>gestione prestiti</h4><hr>
                </td>

            </tr>

            <tr height="200">
                <td align="right"><img src="immagini/scrittore-logo.png" width="100"></td>
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
                <td><h3>Nuovo:</h3><hr>
                    <h4>nuovo utente</h4><hr>
                    <h4>nuovo prestito</h4><hr>


                </td>
                <td align="right"><img src="immagini/editrice.jpg" width="100"></td>
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
                <td colspan="3">16</td>


            </tr>



        </table>


</body>
</html>