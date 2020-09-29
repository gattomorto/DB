<!DOCTYPE html>

<?php
include "gestione_biblio.php";

if(isset($_GET['utenteCercato']))
{
    $utenteCercato=$_GET['utenteCercato'];
    $libroCercato = $_GET['libroCercato'];
    $vN= $_GET['testoNome'];
    $vC= $_GET['testoCognome'];
    $vM = $_GET['testoMatricola'];

    $vT = $_GET['testoTitolo'];
    $vI = $_GET['testoIsbn'];
}
else
{
    $utenteCercato=0;
    $libroCercato = 0;
    $vN= "";
    $vC= "";
    $vM = "";

    $vT = "";
    $vI = "";


}


?>
<?php


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
    <table border="1" width="1700" align="center" cellspacing="1">

 <?php
 //se seleziona e stato premuto il primo blocco sparisce



     echo "  <tr> //prima riga esterna
        <td>//tabella esterna 1 cella
            <font size='5'>
            <table border='0' width='1500' align='left' cellspacing='1'>

                 <tr>
                    <form action='' method='get'>
                    <td width='350'>Nome<br>
                        <input  size='15' style='font-size:larger' type='text' name='testoNome' value=$vN>
                    </td>
                    <td width='350'>Cognome
                        <input  size='15' style='font-size:larger' type='text' name='testoCognome' value='$vC'>
                    </td>
                    <td width='350'>Matricola
                        <input  size='15' style='font-size:larger' type='text' name='testoMatricola' value='$vM'>
                    </td>
                    <td valign='bottom'>


                    <button type='submit' style='font-size: 29.5px'>cerca</button>
                    <input type='hidden' name='utenteCercato' value='1'>
                    <input type='hidden' name='libroCercato' value=$libroCercato>
                    <input type='hidden' name='testoTitolo' value=$vT>
                    <input type='hidden' name='testoIsbn' value=$vI>
                    
                    
                    

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



     if($utenteCercato==1) {


         $tNome=$_GET['testoNome'];
         $tCognome=$_GET['testoCognome'];
         $tMatricola=$_GET['testoMatricola'];


         //la unzione filtra i dati
         $utenti=filtra_utenti_nuovo_prestito($tNome, $tCognome,$tMatricola);

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
     }


     echo "  </table>
          </font><br><br><br><br><br><br><br><br><br><br><br><br><br><hr><br><br>";
     echo "</td>";//chiusa prima cella
     echo "</tr>"; //  chiusa prima riga

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
                    <form action="" method="get">
                    <td width="350">Titolo<br>
                        <input  size="15" style="font-size:larger" type=”text” name="testoTitolo" value=<?php echo "$vT";?>>
                    </td>

                     <td width="350"> Isbn<br>
                        <input  size="15" style="font-size:larger" type=”text” name="testoIsbn" value=<?php echo "$vI";?>>
                     </td>

                    <td valign="bottom">

                    <input type="hidden" name="utenteCercato" value= <?php echo $utenteCercato; ?>>
                    <input type="hidden" name="testoNome" value=<?php echo $vN;?>>
                    <input type="hidden" name="testoCognome" value=<?php echo $vC;?>>
                    <input type="hidden" name="testoMatricola" value=<?php echo $vM;?>>
                    <input type="hidden" name="libroCercato" value="1">

                    <button type=”submit” style="font-size: 29.5px">cerca</button>


                    </form>
            </tr>

        </table>
        </font><br><br><br><br><br><br><br><br>



        <font size="5">
        <table border="1" width="1700" align="left" cellspacing="1">

            <tr>
                <td width="150"><strong>ISBN</strong></td>
                <td><strong>Titolo</strong></td>
                <td><strong>Autore</strong></td>
                <td><strong>Editore</strong></td>
                <td><strong>Anno</strong></td>
                <td><strong>Lingua</strong></td>
                <td><strong>Numero copia</strong></td>
                <td><strong>Succursale</strong></td>
                <td width="80" height="50">bot1</td>
            </tr>





<?php



if($libroCercato) {

    $ricerca = filtra_libro($vT, $vI);


    foreach ($ricerca as $row) {

        $isbn = $row['isbn'];
        $titolo = $row['titolo'];
        $anno = $row['anno'];
        $lingua = $row['lingua'];


        $autoriQ = "select nome, cognome from libri_autori where isbn='$isbn'";
        $risultato = $GLOBALS['connessione']->query($autoriQ);
        $numRighe = mysqli_num_rows($risultato);

        $autori = "";
        while ($risult = $risultato->fetch_assoc()) {
            $nome = $risult['nome'];
            $cognome = $risult['cognome'];


            if ($numRighe != 1) {
                $autori = $autori . '' . $nome . " " . $cognome . ', ';

            } else {
                $autori = $autori . '' . $nome . " " . $cognome;
            }
            $numRighe--;

        }


        $editoreQ = "select nome from libri inner join editori e on libri.editore = e.codice
                 where isbn='$isbn'";
        $risultato = $GLOBALS['connessione']->query($editoreQ);
        $row = $risultato->fetch_assoc();
        $editore = $row['nome'];


        $copieQ = "select * from copie where isbn='$isbn'";
        $risultat = $GLOBALS['connessione']->query($copieQ);

        while ($ris = $risultat->fetch_assoc()) {

            $succursale = $ris['succursale'];
            $numCopia = $ris['numero_copia'];


            echo "<td>$isbn</td>
                  <td>$titolo</td>";

            echo "    <td> $autori</td>
                         <td>$editore</td>
                         <td>$anno</td>
                         <td>$lingua</td>";

            echo "       <td>$numCopia</td>
                         <td>$succursale</td>";


            $restituitoQ = "select fine from copie_prestiti 
                          inner join prestiti p on copie_prestiti.matricola = p.matricola and copie_prestiti.inizio = p.inizio
                          where copie_prestiti.isbn='$isbn' and numero_copia='$numCopia' and restituito=0";
            $risul = $GLOBALS['connessione']->query($restituitoQ);


            $risultatoCopiaFuori = copia_fuori($isbn, $numCopia);


            if ($risultatoCopiaFuori === false) {
                echo "

                      <td width='80' height='40'>
                      <form action='' method='get'>
                          <button type='submit' style='font-size:20px'>aggiungi</button>
                          <input type='hidden' name='isbn' value='$isbn'>
                          <input type='hidden' name='titolo' value='$titolo'>
                          <input type='hidden' name='autori' value='$autori'>
                          <input type='hidden' name='editore' value='$editore'>
                          <input type='hidden' name='anno' value='$anno'>
                          <input type='hidden' name='lingua' value='$lingua'>
                          <input type='hidden' name='numCopia' value='$numCopia'>
                          <input type='hidden' name='succursale' value='$succursale'>
                      </form>
                      </td>
                  </tr>";


            } else {

                echo " <td width='150'>disponibile entro $risultatoCopiaFuori</td>";
                echo "  </tr>";


            }


        }


    }
}



?>

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
 <?php


 if(isset($_GET['isbn']))
 {
     $isbn=$_GET['isbn'];
     $titolo=$_GET['titolo'];
     $autori=$_GET['autori'];
     $editore=$_GET['editore'];
     $anno=$_GET['anno'];
     $lingua=$_GET['lingua'];
     $numCopia=$_GET['numCopia'];
     $succursale=$_GET['succursale'];

     $dataOggi=date('Y-m-d');
     $dataFine=date('Y-m-d', strtotime('+30 days', strtotime($dataOggi)));

    echo"
    
              <font size='5'>
        <table border='1'  width='1700' align='left' cellspacing='1'>

            <tr>
                <td width='150'><strong>ISBN</strong></td>
                <td><strong>Titolo</strong></td>
                <td><strong>Autore</strong></td>
                <td><strong>Editore</strong></td>
                <td><strong>Anno</strong></td>
                <td><strong>Lingua</strong></td>
                 <td width='50'><strong>Numero copia</strong></td>
                <td><strong>Succursale</strong></td>
                <td width='150' height='50'>da restituire entro</td>
                <td>bott1</td>
            </tr>";





            echo "
                             
            <tr>

                
                <td>$isbn</td>
                <td>$titolo</td>
                <td>$autori</td>
                <td>$editore</td>
                <td>$anno</td>
                <td>$lingua</td>
                <td>$numCopia</td>
                <td>$succursale</td>
                <td>$dataFine</td>
                <td><form action=''>
                    <button type='submit'>X</button>
                </form></td>
            </tr>";

















 }








 ?>




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