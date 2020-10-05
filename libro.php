<!DOCTYPE html>
<?php

include "gestione_biblio.php";



    $isbnDaLista=$_GET['isbn'];
   // $isbnDaLista='137066182-7';

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
    .all {
        border-top: 1px solid #FFFFFF;
        border-right: 1px solid #FFFFFF;
        border-bottom: 1px solid #FFFFFF;
        border-left: 1px solid #FFFFFF;
    }


</style>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Libro</title>
</head>
<body bgcolor="#f5f5dc">

  <!--  <div align="center">
        <h1>Libro</h1>
    </div>
    <div align="left">
        <a href="index.php"><img  title="Home"  src="immagini/logo.png"></a>
    </div><br><br>-->

  <table border="0" width="1000" align="left" cellspacing="1">
      <tr>
          <td class="all"> <div align="left">
                  <a href="index.php"><img  title="Home"  src="immagini/logo.png"></a>
              </div>
          </td>

          <td class="all">
              <div align="center">
                  <h1>Lista libri</h1>
              </div><br><br>
          </td>


      </tr>
  </table><br><br><br><br><br><br><br><br><br><br>
<?php


    $libri="select anno, lingua, titolo, e.nome, c.numero_copia from libri inner join editori e on libri.editore = e.codice
    inner join copie c on libri.isbn = c.isbn
    where libri.isbn='$isbnDaLista'";
    $result = $GLOBALS['connessione']->query($libri);
    $daLibri = $result->fetch_assoc();


    $anno=$daLibri['anno'];
    $lingua=$daLibri['lingua'];
    $titolo=$daLibri['titolo'];
    $nomeEditore=$daLibri['nome'];
    $numCopia=$daLibri['numero_copia'];


 echo"   <div style='font-size:30px; line-height: 1.6;'>
        <strong>ISBN</strong>: $isbnDaLista<br>
         <strong>Titolo</strong>: $titolo<br>
        <strong>Autore</strong>: ";

        $autori="select nome, cognome from libri_autori where isbn='$isbnDaLista'";
        $risultato = $GLOBALS['connessione']->query($autori);
        $numRighe=mysqli_num_rows($risultato);



        while ($daAutori = $risultato->fetch_assoc())
        {
            $nome=$daAutori['nome'];
            $cognome=$daAutori['cognome'];

            if($numRighe!=1)
            {
                echo"$nome $cognome,"." ";

            }
            else
            {
                echo"$nome $cognome";
            }

            $numRighe--;

        }

 echo " <br>";
 echo"      
       <strong>Casa editrice</strong>: $nomeEditore<br>
        <strong>Anno</strong>: $anno<br>
        <strong>Lingua</strong>: $lingua<br>
        <strong>Succursali</strong>: ";

        $daCopie="select succursale, count(*) as numCopieG from copie where isbn='$isbnDaLista'
        group by succursale having count(*);";
        $result = $GLOBALS['connessione']->query($daCopie);
        $numRighe=mysqli_num_rows($result);

        while( $copie = $result->fetch_assoc())
        {
             $nome=$copie['succursale'];
             $quantita=$copie['numCopieG'];

             if($numRighe!=1)
             {
                 echo" $nome ($quantita),"." ";
             }
             else
             {
                 echo" $nome ($quantita)";
             }

               $numRighe--;
        }


echo "<br>";

        $countCopie="select count(*)as cop from copie where isbn='$isbnDaLista'";
        $result = $GLOBALS['connessione']->query($countCopie);
        $numCopie = $result->fetch_assoc();
        $numero=$numCopie['cop'];
 echo"  
        <strong>Numero copie</strong>: $numero<br>

    </div><br><hr><br>";


 ?>

 <?php
        echo"<div align='left'>
                <h1><strong>In prestito a:</strong></h1>

            </div><br>";



        echo " <font size='5'>
        <table border='1' width='800' align='left' cellspacing='1'>
        
            <tr>
                <td class='corner_left' width='80' height='50'></td>
                <td ><strong>Nome</strong></td>
                <td><strong>Cognome</strong></td>
                <td><strong>Matricola</strong></td>
                 <td><strong>Copia</strong></td>
                
            </tr>";


         $tab="select nome, cognome, cp.matricola, numero_copia
                from copie_prestiti cp inner join prestiti p on cp.matricola = p.matricola and cp.inizio = p.inizio
                inner  join utenti u on cp.matricola = u.matricola
                where isbn='$isbnDaLista' and restituito=0";
         $result = $GLOBALS['connessione']->query($tab);

        while($tab = $result->fetch_assoc())
        {
                $nome=$tab['nome'];
                $cognome=$tab['cognome'];
                $matricola=$tab['matricola'];
                $numCopia=$tab['numero_copia'];
            echo" 
            
             <tr>
                <td class='left_bottom'  width='80' height='40'>
                <form action='utente.php' method='get'>
                <input type='hidden' name='matricola' value='$matricola'>
                    <button type='submit'>-></button>
                </form>
                </td>
                <td>$nome</td>
                <td>$cognome</td>
                <td>$matricola</td>
                <td>$numCopia</td>
            </tr>
            
            ";

        }

  echo"  </table>
         </font><br><br><br><br><br><br><br><br><br><br><br>";




 ?>

</body>
</html>