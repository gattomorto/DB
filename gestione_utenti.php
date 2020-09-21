<!DOCTYPE html>

<?php
include "gestione_biblio.php";

if(isset($_GET['cerca']))
{
    $result=filtra_utenti($_GET['cerca']);
}
else
{
    $qry="select * from utenti";
    $result = $GLOBALS['connessione']->query($qry);
}

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

    .left_bottom_right {
        border-left: 1px solid #FFFFFF;
        border-bottom: 1px solid #FFFFFF;
        border-right: 1px solid #FFFFFF;
    }

    .c {
        border-right: 1px solid #FFFFFF;
        border-bottom: 1px solid #FFFFFF;
        border-top: 1px solid #ffffff;
        border-left: 1px solid #FFFFFF;
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

    <form action="" method="get">
        <input size="25" style="font-size:larger" type=”text” name="cerca">
        <button type=”submit” style="font-size:20px">cerca</button>
    </form><br><br>

    <font size="5">
    <table border="1" width="1200" align="left" cellspacing="1">

    <tr>
        <td width="80" height="40" class="corner_left"></td>
        <td><strong>Nome</strong></td>
        <td><strong>Cognome</strong></td>
        <td><strong>Matricola</strong></td>
        <td><strong>Indirizzo</strong></td>
        <td><strong>Telefono</strong></td>

    </tr>
    <?php





   // for($i=0; $i<=$numRighe; $i++)
    //{
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
                        <form action='utente.php' method='get'>
                         <input type='hidden' name='matricola' value='$matricola'>
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

       echo"
            <tr>
            
                    <td  class='c'></td>
                <td colspan='5' height='50' class='c'>
                         <form action=''>
                         <button type=”submit” style='font-size: large'>Nuovo Uente</button>
                          </form>
                
                </td>
            
            
            </tr>
            
              </table><br>
              </font>";


   // }

    ?>






    </body>
</html>