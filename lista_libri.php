<!DOCTYPE html>

<?php
include "gestione_biblio.php";


if(isset($_GET['cercaLibro']))
{

    $cerca=$_GET['cercaLibro'];
    $result=filtra_libri($cerca);

}
else
{
    $qry="select * from libri inner join editori e on libri.editore = e.codice";
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


</style>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lista libri</title>
</head>
<body>

    <div align="center">
        <h1>Lista libri</h1>
    </div>

    <form action="">
        <input  size="25" style="font-size:larger" type=”text” name="cercaLibro">
        <button type=”submit” style="font-size:20px">cerca</button>
    </form><br><br><br><br><br>


    <font size="5">
        <table border="1" width="1300" align="left" cellspacing="1">

            <tr>
                <td class="corner_left" width="80" height="50"></td>
                <td width="150"><strong>ISBN</strong></td>
                <td><strong>Titolo</strong></td>
                <td><strong>Anno</strong></td>
                <td><strong>Lingua</strong></td>
                <td><strong>Editore</strong></td>

            </tr>

            <?php




                while ($row = $result->fetch_assoc())
                {
                    $isbn=$row['isbn'];
                    $titolo=$row['titolo'];
                    $lingua=$row['lingua'];
                    $anno=$row['anno'];
                    $editore=$row['nome'];


                echo"
                    <tr>
                <td class='left_bottom' width='80' height='40'><form action='libro.php'>
                    <input type='hidden' name='isbn' value='$isbn'>
                    <button type='submit'>-></button>
                </form>
                </td>
                <td>$isbn</td>
                <td>$titolo</td>
                <td width='100'>$anno</td>
                <td>$lingua</td>
                <td>$editore</td>
                
            </tr>";

                }


            ?>

        </table>
    </font>

</body>
</html>