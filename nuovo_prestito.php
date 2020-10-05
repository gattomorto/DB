<!DOCTYPE html>

<?php
include "gestione_biblio.php";

if(isset($_GET['utenteCercato']))
{
    $utenteCercato=$_GET['utenteCercato'];
    $libroCercato = $_GET['libroCercato'];
    $utenteSelezionato = $_GET["utenteSelezionato"];
    $copieSelezionate = $_GET["copieSelezionate"];

    $vN= $_GET['testoNome'];
    $vC= $_GET['testoCognome'];
    $vM = $_GET['testoMatricola'];

    $vT = $_GET['testoTitolo'];
    $vI = $_GET['testoIsbn'];

    $matricolaSelezionato = $_GET['matricolaSelezionato'];


    $isbn_num_copieSelezionate = $_GET['isbn_num_copieSelezionate'];
    $ultimo = substr($isbn_num_copieSelezionate, -1);
    if($ultimo == '@')
    {
        // rimuove l'ultimo delimitatore @
        $isbn_num_copieSelezionate =  substr($isbn_num_copieSelezionate, 0, -1);
    }
    if ($isbn_num_copieSelezionate=="")
    {
        $copieSelezionate = 0;
    }




}
else
{
    $utenteCercato=0;
    $libroCercato = 0;
    $utenteSelezionato = 0;
    $vN= "";
    $vC= "";
    $vM = "";

    $vT = "";
    $vI = "";

    $matricolaSelezionato = -1;

    $isbn_num_copieSelezionate = "";
    $copieSelezionate = 0;


}


?>


<html lang="en">
<head>
    <style>

        table, th, td {
            border: 1px solid #090404;
            border-collapse: collapse;
        }

        .all {
            border-top: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
            border-left: 1px solid #FFFFFF;
        }
        .corner_left{
            border-top: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;

        }
        .right_bottom {
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;

        }

        .top {
            border-top: 1px solid #FFFFFF;

        }


    </style>
    <meta charset="UTF-8">
    <title>nuovo prestito</title>
</head>
<body bgcolor="#f5f5dc">

    <table border="0" width="1000" align="left" cellspacing="1">
        <tr>
                <td class="all"> <div align="left">
                        <a href="index.php"><img  title="Home"  src="immagini/logo.png"></a>
                    </div>
                </td>

                <td class="corner_left">
                    <div align="center">
                        <h1>Nuovo prestito</h1>
                    </div><br><br>
                </td>


        </tr>
    </table><br><br><br><br><br><br>
<!--tabella esterna   1 -->
    <table border="0" width="1700" align="center" cellspacing="1">

 <?php

if($utenteSelezionato == 0) {


    echo "  <tr> 
        <td>
            <font size='5'>
            <table border='0' width='1500' align='left' cellspacing='1'>

                 <tr>
                    <form action='' method='get'>
                    <td width='350' class='all'>Nome<br>
                        <input  size='15' style='font-size:larger' type='text' name='testoNome' value='$vN'>
                    </td>
                    <td width='350' class='all'>Cognome
                        <input  size='15' style='font-size:larger' type='text' name='testoCognome' value='$vC'>
                    </td>
                    <td width='350' class='all'>Matricola
                        <input  size='15' style='font-size:larger' type='text' name='testoMatricola' value='$vM'>
                    </td>
                    <td valign='bottom' class='all'>

                    <!--form cerca utente-->
                    <button type='submit' style='font-size: 29.5px'>cerca</button>
                    <input type='hidden' name='utenteCercato' value='1'>
                    
                    <input type='hidden' name='libroCercato' value='$libroCercato'>
                    <input type='hidden' name='testoTitolo' value='$vT'>
                    <input type='hidden' name='testoIsbn' value='$vI'>
                    
                    <input type='hidden' name='utenteSelezionato' value=$utenteSelezionato>
                    <input type='hidden' name='matricolaSelezionato' value='$matricolaSelezionato'>
                    
                    <input type='hidden' name='copieSelezionate' value=$copieSelezionate>
                    <input type='hidden' name='isbn_num_copieSelezionate' value='$isbn_num_copieSelezionate'>
                    
                    
                    
                    
                    

                </form>
            </tr>

        </table>
    </font>

    <br><br><br><br><br><br> ";


    echo "   <font size='5'>
        <table border='1' width='1200' align='left' cellspacing='1'>
        
               <tr>

                <td align='center'><strong>Nome</strong></td>
                <td align='center'><strong>Cognome</strong></td>
                <td align='center'><strong>Matricola</strong></td>
                <td align='center'><strong>Indirizzo</strong></td>
                <td align='center'><strong>Telefono</strong></td>
                <td width='100' height='40' class='corner_left'></td>

            </tr>";


    if ($utenteCercato == 1) {


        $tNome = $_GET['testoNome'];
        $tCognome = $_GET['testoCognome'];
        $tMatricola = $_GET['testoMatricola'];


        //la unzione filtra i dati
        $utenti = filtra_utenti_nuovo_prestito($tNome, $tCognome, $tMatricola);

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
                        <td width='80' height='40' class='right_bottom'>
                        
                        <form action=''>";
                            $dataOggi = date('Y-m-d');

                            if(prestito_esiste($matricola,$dataOggi))
                            {
                                echo "prestito di oggi già effettuato";
                            }
                            else {
                                echo "<button type='submit' style='font-size:20px'>seleziona</button>";
                            }
                             
                           echo  "<input type='hidden' name='matricolaSelezionato' value='$matricola'>
                             <input type='hidden' name='utenteSelezionato' value='1'>
                             
                             <input type='hidden' name='libroCercato' value=$libroCercato>
                             <input type='hidden' name='testoTitolo' value='$vT'>
                             <input type='hidden' name='testoIsbn' value='$vI'>
                             
                             <input type='hidden' name='utenteCercato' value=$utenteCercato>
                             <input type='hidden' name='testoNome' value='$vN'>
                             <input type='hidden' name='testoCognome' value='$vC'>
                             <input type='hidden' name='testoMatricola' value='$vM'>
                             
                            <input type='hidden' name='copieSelezionate' value=$copieSelezionate>
                            <input type='hidden' name='isbn_num_copieSelezionate' value='$isbn_num_copieSelezionate'>
                            
                          
                         </form>
                         </td>

                  </tr>";



        }
    }


    echo "  </table>
          </font><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
    echo "</td>";
    echo "</tr>";
}
?>

<!-- tabella 2 -->


<tr>
     <td>
                <font size="5">
                <table border="0" width="1500" align="left" cellspacing="1">

                <tr>
                    <form action="" method="get">
                    <td width="350" class="all">Titolo<br>

                        <input  size="15" style="font-size:larger" type=”text” name="testoTitolo" value='<?php echo $vT;?>'>
                    </td>

                     <td width="350" class="all"> Isbn<br>
                        <input  size="15" style="font-size:larger" type=”text” name="testoIsbn" value='<?php echo $vI;?>'>
                     </td>

                    <td valign="bottom" class="all">

                    <!-- form cerca libro -->
                    <input type="hidden" name="utenteCercato" value= <?php echo $utenteCercato; ?>>

                    <input type="hidden" name="testoNome" value='<?php echo $vN;?>'>
                    <input type="hidden" name="testoCognome" value='<?php echo $vC;?>'>
                    <input type="hidden" name="testoMatricola" value='<?php echo $vM;?>'>
                    <input type="hidden" name="libroCercato" value="1">

                    <input type="hidden" name="utenteSelezionato" value=<?php echo $utenteSelezionato;?>>
                    <input type="hidden" name="matricolaSelezionato" value='<?php echo $matricolaSelezionato;?>'>

                    <input type='hidden' name='copieSelezionate' value= <?php echo $copieSelezionate; ?>>
                    <input type='hidden' name='isbn_num_copieSelezionate' value= '<?php echo $isbn_num_copieSelezionate; ?>'>

                    <button type=”submit” style="font-size: 29.5px">cerca</button>


                    </form>
            </tr>

        </table>
        </font><br><br><br><br><br><br>



        <font size="5">
        <table border="1" width="1700" align="left" cellspacing="1">

            <tr>
                <td width="150" align="center"><strong>ISBN</strong></td>
                <td align="center"><strong>Titolo</strong></td>
                <td align="center"><strong>Autore</strong></td>
                <td align="center"><strong>Editore</strong></td>
                <td align="center"><strong>Anno</strong></td>
                <td align="center"><strong>Lingua</strong></td>
                <td width="50" align="center"><strong>Numero copia</strong></td>
                <td align="center"><strong>Succursale</strong></td>
                <td width="80" height="50" class="corner_left"></td>
            </tr>





<?php



if($libroCercato) {

    $ricerca = filtra_libro($vT, $vI);


    foreach ($ricerca as $row) {


        $isbn = $row['isbn'];
        $copieQ = "select * from copie where isbn='$isbn'";
        $risultat = $GLOBALS['connessione']->query($copieQ);

        $i = 0;
        while ($ris = $risultat->fetch_assoc()) {

            /*$succursale = $ris['succursale'];*/
            $numCopia = $ris['numero_copia'];

            $info_copia = info_complete_copia($isbn,$numCopia);

            $titolo = $info_copia["titolo"];
            $editore= $info_copia["editore"];
            $anno= $info_copia["anno"];
            $autori= $info_copia["autori"];
            $lingua= $info_copia["lingua"];
            $succursale= $info_copia["succursale"];


            echo "<td>$isbn</td>
                  <td>$titolo</td>";

            echo "    <td> $autori</td>
                         <td>$editore</td>
                         <td>$anno</td>
                         <td>$lingua</td>";

            echo "       <td align='center'>$numCopia</td>
                         <td>$succursale</td>";


            $restituitoQ = "select fine from copie_prestiti 
                          inner join prestiti p on copie_prestiti.matricola = p.matricola and copie_prestiti.inizio = p.inizio
                          where copie_prestiti.isbn='$isbn' and numero_copia='$numCopia' and restituito=0";
            $risul = $GLOBALS['connessione']->query($restituitoQ);


            $risultatoCopiaFuori = copia_fuori($isbn, $numCopia);

            if(!copiaGiaSelezionata($isbn_num_copieSelezionate,$isbn,$numCopia))
            {


                if ($risultatoCopiaFuori === false) {

                    if($copieSelezionate == 0)
                    {
                        $x = "$isbn*$numCopia";
                    }
                    else
                    {
                        $x = $isbn_num_copieSelezionate . "@$isbn*$numCopia";
                    }



                    //form aggiungi copia
                    echo "
                      <td width='80' height='40' class='right_bottom'>
                      <form action='' method='get'>
                          <button type='submit' style='font-size:20px'>aggiungi</button>
                          
                          <input type='hidden' name='copieSelezionate' value='1'>
                          <input type='hidden' name='isbn_num_copieSelezionate' value='$x'>
                          
                          <input type='hidden' name='matricolaSelezionato' value='$matricolaSelezionato'>
                          <input type='hidden' name='utenteSelezionato' value=$utenteSelezionato>
                         
                          <input type='hidden' name='libroCercato' value='$libroCercato'>
                          <input type='hidden' name='testoTitolo' value='$vT'>
                          <input type='hidden' name='testoIsbn' value='$vI'>
                         
                          <input type='hidden' name='utenteCercato' value=$utenteCercato>
                          <input type='hidden' name='testoNome' value='$vN'>
                          <input type='hidden' name='testoCognome' value='$vC'>
                          <input type='hidden' name='testoMatricola' value='$vM'>
                        
                      </form>
                      </td>
                  </tr>";


                } else {

                    echo " <td width='150' class='right_bottom'>disponibile entro<br>$risultatoCopiaFuori</td>";
                    echo "  </tr>";


                }
            }
            else
            {
                //echo "<< $isbn_num_copieSelezionate >> << $isbn >> << $numCopia >> <br>";
                echo " <td width='150' class='right_bottom'>selezionata</td>";
                echo "  </tr>";
            }


        }


    }
}



?>

            </table>
             </font><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
     </td>
</tr><br><br><br><br>


<!-- tabella 3-->

<?php

if($utenteSelezionato== 1 or $copieSelezionate==1)
{
echo "<tr>
    <td>

        <div align='center'>

            <strong><h2>Riepilogo</h2></strong>
        </div><br><br>";

}

if($utenteSelezionato==1)
{
    $qry="select * from utenti where matricola='$matricolaSelezionato'";
    $result= $GLOBALS['connessione']->query($qry);
    $row = $result->fetch_assoc();


    $nome=$row['nome'];
    $cognome=$row['cognome'];
    $indirizzo=$row['indirizzo'];
    $telefono=$row['telefono'];



 echo"       <font size='5'>
        <table border='1' width='1200' align='left' cellspacing='0'>
            <tr>
                <td height='50'>$nome</td>
                <td>$cognome</td>
                <td>$matricolaSelezionato</td>
                <td>$indirizzo</td>
                <td>$telefono</td>
                
                    <form action=''>
                         
               <td class='all'>    <!-- form elimina utente -->
                        <input type='hidden' name='matricolaSelezionato' value='-1'>
                        <input type='hidden' name='utenteSelezionato' value='0'>
                                      
                         <input type='hidden' name='libroCercato' value='$libroCercato'>
                         <input type='hidden' name='testoTitolo' value='$vT'>
                         <input type='hidden' name='testoIsbn' value='$vI'>
                         
                         <input type='hidden' name='utenteCercato' value=$utenteCercato>
                         <input type='hidden' name='testoNome' value='$vN'>
                         <input type='hidden' name='testoCognome' value='$vC'>
                         <input type='hidden' name='testoMatricola' value='$vM'>
                         
                         <input type='hidden' name='copieSelezionate' value=$copieSelezionate>
                         <input type='hidden' name='isbn_num_copieSelezionate' value='$isbn_num_copieSelezionate'>
                        
                        <button type='submit' style='font-size:20px'>X</button>
                    </form>
                </td>

            </tr>

        </table><br><br><br><br>
        </font>";
}

?>
 <?php

 //"isbn*numero@"
 if($copieSelezionate==1)
 {


    echo
        "
        <font size='5'>
        <table border='1'  width='1700' align='left' cellspacing='1'>
            <tr>
                <td width='150'><strong>ISBN</strong></td>
                <td align='center'><strong>Titolo</strong></td>
                <td align='center'><strong>Autore</strong></td>
                <td align='center'><strong>Editore</strong></td>
                <td align='center'><strong>Anno</strong></td>
                <td align='center'><strong>Lingua</strong></td>
                 <td width='50'align='center'><strong>Numero copia</strong></td>
                <td align='center'><strong>Succursale</strong></td>
                <td width='150' height='50' align='center'>da restituire entro</td>
                <td class='all'></td>
            </tr>
        ";

     $copie = explode("@", $isbn_num_copieSelezionate);

     foreach ($copie as $copia)
     {

         $isbn_numCopia = explode("*",$copia);

         $isbn = $isbn_numCopia[0];
         $numCopia = $isbn_numCopia[1];



         $info_copia = info_complete_copia($isbn,$numCopia);

         $titolo = $info_copia["titolo"];
         $editore= $info_copia["editore"];
         $anno= $info_copia["anno"];
         $autori= $info_copia["autori"];
         $lingua= $info_copia["lingua"];
         $succursale= $info_copia["succursale"];


         $dataOggi=date('Y-m-d');
         $dataFine=date('Y-m-d', strtotime('+30 days', strtotime($dataOggi)));

         $x = rimuoviCopiaDaIsbn_num_copieSelezionate($isbn_num_copieSelezionate,$isbn,$numCopia);

         //form rimuovi copia
         echo
         "             
            <tr>   
                <td>$isbn</td>
                <td>$titolo</td>
                <td>$autori</td>
                <td>$editore</td>
                <td>$anno</td>
                <td>$lingua</td>
                <td align='center'>$numCopia</td>
                <td>$succursale</td>
                <td>$dataFine</td>
                <td class='right_bottom'><form action=''>
                    <button type='submit'>X</button>
                    
                    <input type='hidden' name='matricolaSelezionato' value='$matricolaSelezionato'>
                    <input type='hidden' name='utenteSelezionato' value='$utenteSelezionato'>
                                  
                     <input type='hidden' name='libroCercato' value='$libroCercato'>
                     <input type='hidden' name='testoTitolo' value='$vT'>
                     <input type='hidden' name='testoIsbn' value='$vI'>
                     
                     <input type='hidden' name='utenteCercato' value=$utenteCercato>
                     <input type='hidden' name='testoNome' value='$vN'>
                     <input type='hidden' name='testoCognome' value='$vC'>
                     <input type='hidden' name='testoMatricola' value='$vM'>
                     
                     <input type='hidden' name='copieSelezionate' value=$copieSelezionate>
                     <input type='hidden' name='isbn_num_copieSelezionate' value='$x'>
                </form></td>
            </tr>            
            ";

     }
 }
 ?>
       </tr>
        </table>
    </font><br><br><br><br><br><br><br><br><br><br><br>

<?php
//echo  $utenteSelezionato . " ". $copieSelezionate;
if($utenteSelezionato ==1 and $copieSelezionate==1) {
    echo "<form action='index.php'>
        <button type='submit' style='font-size: x-large'>conferma</button>
        <input type='hidden' name='nuovoPrestito' value='1'>
        <input type='hidden' name='matricolaSelezionato' value='$matricolaSelezionato'>               
         <input type='hidden' name='isbn_num_copieSelezionate' value='$isbn_num_copieSelezionate'>
    </form><br>";
}
?>

     </td>
</tr>



    </table>
<!--fine tabella esterna-->
</body>
</html>