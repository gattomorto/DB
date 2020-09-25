<!DOCTYPE html>

<?php
include "gestione_biblio.php";

//e stato premuto il bottone cerca
if(isset($_GET['testoNome']) or isset($_GET['testoCognome']) or isset($_GET['testoMatricola']))
{

    $tNome=$_GET['testoNome'];
    $tCognome=$_GET['testoCognome'];
    $tMatricola=$_GET['testoMatricola'];


    //la unzione filtra i dati
    $utenti=filtra_nuovo_prestito($tNome, $tCognome,$tMatricola);

}
else
{   //alrimenti la tabella e piena di utenti
    $qry="select * from utenti";
    $utenti= $GLOBALS['connessione']->query($qry);



}


?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>nuovo prestito</title>
</head>
<body bgcolor="#f5f5dc">

    <div align="center">
        <h1>Nuovo prestito</h1>
    </div><br><br>

<!--tabella esterna    -->
    <table border="1" width="1500" align="center" cellspacing="1">

 <?php
 //se seleziona e stato premuto il primo blocco sparisce
 if(isset($_GET['matricola']))
 {

 }//altrimenti il bolocco e normale
 else {
     echo "  <tr> //prima riga esterna
        <td>//tabella esterna 1 cella
            <font size='5'>
            <table border='0' width='1500' align='left' cellspacing='1'>

                 <tr>
                    <form action='' method='get'>
                    <td width='350'>Nome<br>
                        <input  size='15' style='font-size:larger' type='text' name='testoNome'>
                    </td>
                    <td width='350'>Cognome
                        <input  size='15' style='font-size:larger' type='text' name='testoCognome'>
                    </td>
                    <td width='350'>Matricola
                        <input  size='15' style='font-size:larger' type='text' name='testoMatricola'>
                    </td>
                    <td valign='bottom'>


                            <button type='submit' style='font-size: 29.5px'>cerca</button>

                </form>
            </tr>

        </table>
    </font>

    <br><br><br><br><br><br> ";


     echo "   <font size='5'>
        <table border='1' width='1200' align='left' cellspacing='1'>
        
               <tr>

                <td><strong>Nome</strong></td>
                <td><strong>Cognome</strong></td>
                <td><strong>Matricola</strong></td>
                <td><strong>Indirizzo</strong></td>
                <td><strong>Telefono</strong></td>
                <td width='100' height='40'>bot1</td>

            </tr>";


     while ($row = $utenti->fetch_assoc()) {
         $nome = $row['nome'];
         $cognome = $row['cognome'];
         $matricola = $row['matricola'];
         $indirizzo = $row['indirizzo'];
         $telefono = $row['telefono'];


         echo "
                  <tr>

                        <td>$nome</td>
                        <td>$cognome</td>
                        <td>$matricola</td>
                        <td>$indirizzo</td>
                        <td>$telefono</td>
                        <td width='80' height='40'>
                        <form action=''>
                             <button type='submit' style='font-size:20px'>seleziona</button>
                             <input type='hidden' name='matricola' value='$matricola'>
                         </form>
                         </td>

                  </tr>";

     }


     echo "  </table>
          </font><br><br><br><br><br><br><br><br><br><br><br><br><br><hr><br><br>";
     echo "</td>";//chiusa prima cella
     echo "</tr>"; //  chiusa prima riga
 }
?>
        //tabella1
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////7
//tabella 2
?>
<tr>//seconda riga
     <td>//seconda cella
                <font size="5">
                <table border="0" width="1500" align="left" cellspacing="1">

                <tr>
                    <form action="">
                    <td width="350">Titolo<br>
                        <input  size="15" style="font-size:larger" type=”text” name="testoTitolo">
                    </td>

                     <td width="350"> Isbn<br>
                        <input  size="15" style="font-size:larger" type=”text” name="testoIsbn">
                     </td>

                    <td valign="bottom">


                        <button type=”submit” style="font-size: 29.5px">cerca</button>
                    </form>
            </tr>

        </table>
        </font><br><br><br><br><br><br><br><br>



        <font size="5">
        <table border="1" width="1000" align="left" cellspacing="1">

            <tr>
                <td ><strong>ISBN</strong></td>
                <td><strong>Titolo</strong></td>
                <td><strong>Autore</strong></td>
                <td><strong>Editore</strong></td>
                <td><strong>Anno</strong></td>
                <td><strong>Lingua</strong></td>
                <td width="80" height="50">bot1</td>
            </tr>

<?php
if(isset($_GET['testoTitolo'])  and $_GET['testoIsbn'])
{
    $titolo=$_GET['testoTitolo'];
    $isbn=$_GET['testoIsbn'];

    $result=filtra_libro($titolo, $isbn);


    while($row = $result->fetch_assoc())
    {
        $isbn=$row['isbn'];
        $titolo=$row['titolo'];
        $anno=$row['anno'];
        $lingua=$row['lingua'];

        echo"
             <tr>

                <td>$isbn</td>
                <td>$titolo</td>";


        $autoriQ="select nome, cognome from libri_autori where isbn='$isbn'";
        $risultato = $GLOBALS['connessione']->query($autoriQ);
        $numRighe=mysqli_num_rows($risultato);

        while ($risultato = $risultato->fetch_assoc())
        {
            $nome=$risultato['nome'];
            $cognome=$risultato['cognome'];

            if($numRighe!=1)
            {
                echo"
                <td>$nome.' '.$cognome, </td>";

            }
            else
            {
                echo"
                <td>$nome.' '.$cognome</td>";
            }
            $numRighe--;

        }

       $editoreQ="select nome from libri inner join editori e on libri.editore = e.codice
                 where isbn='$isbn'";
        $risultato = $GLOBALS['connessione']->query($editoreQ);
        $row = $risultato->fetch_assoc();
        $editore=$row['nome'];


        echo"   <td>$editore</td>
                <td>$anno</td>
                <td>$lingua</td>
                <td width='80' height='40'><form action=''>
                    <button type='submit' style='font-size:20px'>aggiungi</button>
                </form>
                </td>
            </tr>";




    }
}


?>


            <tr>

                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
                <td width="150">disponibile il 21/08/2020</td>


            </tr>
            </table>
             </font><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><hr><br><br><br><br
     </td>//chiusa seconda cell
</tr>//chiusa seconda riga

tabella2
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
tabella3
<tr>//aperta terza riga
    <td>//aperta terza cella

        <div align="center">

            <strong><h2>Riepilogo</h2></strong>
        </div>
<?php
// e stato premuto il bottone seleziona  sulla tabella di utenti
if(isset($_GET['matricola']))
{

    $matricola=$_GET['matricola'];

    $qry="select * from utenti where matricola='$matricola'";
    $result= $GLOBALS['connessione']->query($qry);
    $row = $result->fetch_assoc();



    $nome=$row['nome'];
    $cognome=$row['cognome'];
    $indirizzo=$row['indirizzo'];
    $telefono=$row['telefono'];




 echo"       <font size='5'>
        <table border='1' width='1500' align='left' cellspacing='1'>
            <tr>
                <td height='50'>$nome</td>
                <td>$cognome</td>
                <td>$matricola</td>
                <td width='350'>$indirizzo</td>
                <td width='250'>$telefono</td>
                <td width='50'>
                    <form action=''>
                        <button type='submit' style='font-size:20px'>X</button>
                    </form>
                </td>

            </tr>

        </table><br><br><br><br>
        </font>";
}
 else
     {
         //se non e stato prenuto il bottone seleziona rimane invariata

     }

?>
        <font size='5'>
        <table border='1'  width='1500' align='left' cellspacing='1'>

            <tr>
                <td><strong>ISBN</strong></td>
                <td><strong>Titolo</strong></td>
                <td><strong>Autore</strong></td>
                <td><strong>Editore</strong></td>
                <td><strong>Anno</strong></td>
                <td><strong>Lingua</strong></td>
                <td width='150' height='50'>da restituire entro</td>
                <td>bott1</td>
            </tr>


            <tr>

                <td height='50'>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td><form action=''>
                    <button type='submit'>X</button>
                </form></td>
            </tr>

            <tr>

                <td height='50'>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
                <td>13</td>
                <td><form action=''>
                    <button type=”submit”>X</button>
                </form></td>


            </tr>
        </table>
    </font><br><br><br><br><br><br><br><br><br><br><br><br><br>


    <form action=''>
        <button type='submit' style='font-size:20px'>conferma</button>
    </form><br>
     </td>//terza cella chiudo
</tr>//terza riga chiudo
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    </table>
<!--fine tabella esterna-->
</body>
</html>