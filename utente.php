<?php
include "gestione_biblio.php";


$matricolaPrestito=$_GET['matricola'];

//$matricolaPrestito = '118366';

//quando si preme restituisci
if(isset($_GET['matricola']) and isset($_GET['inizio'])) {
    restituisci_prestito($_GET['matricola'], $_GET['inizio']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>utente</title>
</head>
<body bgcolor="#f5f5dc">

<div align="center">
    <h1>Utente</h1>
</div><br>


<?php

//si fanno vedere solo
if(isset($_GET['modifica']) and isset($_GET['matricola']))
{
    $mat = $_GET['matricola'];
    $dati="select * from utenti where matricola='$mat'";
    $result=$GLOBALS['connessione']->query($dati);
    $riga=$result->fetch_assoc();
    $nome=$riga['nome'];
    $cognome=$riga['cognome'];
    $indirizzo=$riga['indirizzo'];
    $telefono=$riga['telefono'];

    echo"
    <div style='font-size:30px; line-height: 1.6;'>
        <form action='' method='get'>
        <strong>Matricola</strong>: $mat<br>
        <strong>Nome</strong>: 
        <input  size='25' style='font-size:medium' type=”text” name='testoNome' value='$nome' ><br>
        <strong>Cognome</strong>: 
        <input  size='25' style='font-size:medium' type=”text” name='testoCognome' value='$cognome' ><br>
        <strong>Indirizzo</strong>: 
        <input  size='25' style='font-size:medium' type=”text” name='testoIndirizzo' value='$indirizzo'><br>
        <strong>Telefono</strong>: 
        <input  size='25' style='font-size:medium' type=”text” name='testoTelefono' value='$telefono' ><br>
        <input type='hidden' name='matricola' value='$mat'>
        <input type='hidden' name='salva' value='1'>
    </div>
    
    </div><br><br>
    <table border='0' width='200' align='left' cellspacing='1'>

    <tr>

        <td>
                <button type=”submit” style='font-size: large'>Salva</button>
            </form>
        </td>
    </tr>

</table><br><br><hr><br><br><br>";



}
else {


    if(isset($_GET['salva']))
    {

        $nomeGet=$_GET['testoNome'];
        $cognomeGet=$_GET['testoCognome'];
        $indirizzoGet=$_GET['testoIndirizzo'];
        $telefonoGet=$_GET['testoTelefono'];
        $matt=$_GET['matricola'];


        if(isset($nomeGet))
        {
            $nome="update utenti set nome='$nomeGet'where matricola='$matt'";
            $GLOBALS['connessione']->query($nome);
        }
        if(isset($cognomeGet))
        {
            $cognome="update utenti set cognome='$cognomeGet'where matricola='$matt'";
            $GLOBALS['connessione']->query($cognome);
        }
        if(isset($indirizzoGet))
        {
            $indirizzo="update utenti set indirizzo='$indirizzoGet'where matricola='$matt'";
            $GLOBALS['connessione']->query($indirizzo);
        }
        if(isset($telefonoGet))
        {
            $telefono="update utenti set telefono='$telefonoGet'where matricola='$matt'";
            $GLOBALS['connessione']->query($telefono);
        }

    }



    $qry = "select * from utenti where matricola='$matricolaPrestito'";
    $result = $GLOBALS['connessione']->query($qry);
    $row = $result->fetch_assoc();

    $nome = $row['nome'];
    $cognome = $row['cognome'];
    $indirizzo = $row['indirizzo'];
    $telefono = $row['telefono'];
    $matricola = $row['matricola'];


    echo "
    <div style='font-size:30px; line-height: 1.6;'>
        <strong>Matricola</strong>: $matricola<br>
        <strong>Nome</strong>: $nome<br>
        <strong>Cognome</strong>: $cognome<br>
        <strong>Indirizzo</strong>: $indirizzo<br>
        <strong>Telefono</strong>: $telefono<br>";


    echo "
            </div><br><br>


<table border='0' width='200' align='left' cellspacing='1'>

    <tr>

        <td>
            <form action=''>
                <button type=”submit” style='font-size: large'>Modifica</button>
                
                <input type='hidden' name='matricola' value='$matricola'>
                <input type='hidden'  name='modifica' value='1'>
                
            </form>
        </td>
    </tr>

</table><br><br><hr><br><br><br>";


    $prestitiUtente = "select * from prestiti where matricola='$matricolaPrestito' and restituito = 0";
    $result = $GLOBALS['connessione']->query($prestitiUtente);
    $numBlocchi = mysqli_num_rows($result);

    echo "<table border='0' width='1500' align='center' cellspacing='1'>";

//ciclo dei blocchi
    for ($i = 0; $i < $numBlocchi; $i++) {
        //questo blocco crea una riga della tabella esterna e la riempie con una tabella interna
        echo "<tr >
              <td >";

        //questo blocco è per le data della tabella interna
        $row = $result->fetch_assoc();
        $dataFine = $row['fine'];
        $dataInizio = $row['inizio'];
        echo "
                   <div style='font-size:30px; line-height: 1.6;'>
                        <u>Data uscita</u>: $dataInizio &nbsp;&nbsp; <u>Scadenza</u>: $dataFine
                    </div>
                    ";

        // tabella interna
        echo
        "<font size='5'>
                     <table border='1'  height='50' width='800' align='left' cellspacing='1'>";

        //prendo tutti i libri del presito (blocco) i
        $qry = "select l.isbn, titolo, numero_copia from copie_prestiti inner join libri l on copie_prestiti.isbn = l.isbn where matricola='$matricolaPrestito' and inizio='$dataInizio'";
        $prestito = $GLOBALS['connessione']->query($qry);

        // intestazione della tabella interna
        echo "
                            <tr align='center'>
                                <td height='50' width='150'><strong>Isbn</strong></td>
                                <td width='600'><strong>Titolo</strong></td>
                                <td width='30'><strong>Numero Copia</strong></td>
                             </tr> ";

        // riempio la tabella interna con i libri
        while ($riga = $prestito->fetch_assoc()) {
            $isbn = $riga['isbn'];
            $titolo = $riga['titolo'];
            $numCopia = $riga['numero_copia'];

            echo
            "<tr>
                                    <td height='50'>$isbn</td>
                                    <td>$titolo</td>
                                    <td width='200'>$numCopia</td>
                                 </tr>";


        }

        //il bottone che va dopo tutti i libri di un blocco
        echo "
                            <tr>
                                <td colspan='3'>
                                    <form action='' method='get'>
                                     <input type='hidden' name='matricola' value='$matricola'>
                                     <input type='hidden' name='inizio' value='$dataInizio'>
                                     <button style='font-size:large' type='submit'>restituito</button>
                                    </form>
                                </td>
                            </tr>";


        // fine tabella interna
        echo
        "</table>
                     </font>";
        echo "</td>
          </tr>";

        //cella aggiunta che da spazio alla tabella interna 1
        echo "
        <tr>
            <td height='50'></td>
        
        
        </tr>";


    }


    $prestitiUtente = "select * from prestiti where matricola='$matricolaPrestito' and restituito = 1";
    $result = $GLOBALS['connessione']->query($prestitiUtente);
    $numBlocchi = mysqli_num_rows($result);

    echo "<table border='0' width='1500' align='center' cellspacing='1'>";

//ciclo dei blocchi
    for ($i = 0; $i < $numBlocchi; $i++) {
        //questo blocco crea una riga della tabella esterna e la riempie con una tabella interna
        echo "<tr >
              <td>";

        //questo blocco è per le data della tabella interna
        $row = $result->fetch_assoc();
        $dataFine = $row['fine'];
        $dataInizio = $row['inizio'];
        echo "
                   <div style='font-size:30px; line-height: 1.6;'>
                        <u>Data uscita</u>: $dataInizio &nbsp;&nbsp; <u>Rientro</u>: $dataFine
                    </div>
                    ";

        // tabella interna
        echo
        "<font size='5'>
                     <table border='1'  height='50' width='800' align='left' cellspacing='1'>";

        //prendo tutti i libri del presito (blocco) i
        $qry = "select l.isbn, titolo, numero_copia from copie_prestiti inner join libri l on copie_prestiti.isbn = l.isbn where matricola='$matricolaPrestito' and inizio='$dataInizio'";
        $prestito = $GLOBALS['connessione']->query($qry);

        // intestazione della tabella interna
        echo "
                            <tr align='center'>
                                <td height='50' width='150'><strong>Isbn</strong></td>
                                <td width='600'><strong>Titolo</strong></td>
                                <td width='30'><strong>Numero Copia</strong></td>
                             </tr> ";

        // riempio la tabella interna con i libri
        while ($riga = $prestito->fetch_assoc()) {
            $isbn = $riga['isbn'];
            $titolo = $riga['titolo'];
            $numCopia = $riga['numero_copia'];

            echo
            "<tr>
                                    <td height='50'>$isbn</td>
                                    <td>$titolo</td>
                                    <td width='200'>$numCopia</td>
                                 </tr>";


        }


        // fine tabella interna
        echo
        "</table>
                     </font>";
        echo "</td>
          </tr>";


        //cella aggiunta che da spazio alla tabella interna 2
        echo "
        <tr>
            <td height='50'></td>
        
        
        </tr>";
    }

}


?>






</body>
</html>