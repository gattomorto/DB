<?php
$con = connetti();
mysqli_set_charset($con, "utf8");
$GLOBALS['connessione']=$con;

function test_qry($result,$operazione)
{
    /*if ($result === TRUE) {
        echo "$operazione"." ok\n";
    } else {
        //echo "errore in ".$operazione .": ". $GLOBALS['connessione']->error;
        echo "errore in ".$operazione .": ". mysqli_error($GLOBALS['connessione']);
        exit();
    }*/

    if($result != false)
    {
        echo "$operazione"." ok\n";
    }
    else
    {
        echo "errore in ".$operazione .": ". mysqli_error($GLOBALS['connessione']);
        exit();
    }
}

function cinque_lingue()
{
    /*
     * descrizione:
     *      trova le cinque lingue più comuni nella tabella 'libri'
     *
     * output:
     *      vettore di strighe di cinque posizioni
     *      nella posizione 0 la più pololare, nella 1 la seconda più popolare, e coxì via...
     */

    $qry="select t1.lingua, t1.count 
          from(
                select lingua, count(*) as 'count'
                from libri
                group by lingua) as t1
          order by t1.count desc
          limit 5";
    $result = $GLOBALS['connessione']->query($qry);
    //test_qry($result,"Select lingue su libri in cinque lingue()");

    for($i=0; $i<=4; $i++)
    {

        $row = $result->fetch_assoc();
        $cinqueLingue[$i]=$row['lingua'];

    }

    return $cinqueLingue;

}

function autore_piu_libri()
{
    /*
        descrizione:
     * questa funzione mi dice l autore che ha scritto piu libri dal database della biblioteca
     *
     * restituisce: una stringa con l autore */

    $qry="select nome,cognome  as 'cnt' from libri_autori group by nome, cognome order by count(*) DESC LIMIT 1";
    $result = $GLOBALS['connessione']->query($qry);
    //test_qry($result,"Select nome cognome su tabella libri_autori in autore_piu_libri()");
    $row = $result->fetch_assoc();

    $autore[0]=$row['nome'];
    $autore[1]=$row['cognome'];

   // echo $autore[0]."--".$autore[1];
    return $autore;
}

function editore_piu_libri()
{
    /*
     * questa funzione mi restituisce l editore che ha pbblicato piu libri nel nostro database
     * restituisce: una stringa
     *
     * */

    $qry="select nome from libri inner join editori on libri.editore = editori.codice group by nome
          order by count(*) DESC LIMIT 1";
    $result = $GLOBALS['connessione']->query($qry);
    //test_qry($result,"Select su libro e editore in funzione editore_piu_libri");
    $row = $result->fetch_assoc();

    $editore[0]=$row['nome'];


    return $editore;

}

function connetti()
{
    $con = mysqli_connect('localhost:3306','root','trillkill','biblio');

    if(!$con)
    {
        echo "Errore durante la connessione".  mysqli_connect_error();
        exit();
    }
    //echo "connessione ok \n" ;

    return $con;

}

function crea_struttura_gestione_prestiti()
{
    /*
     * questa funzione serve a creare una matrice che serve a riempire la tabella nella pagina gestione_prestiti.php
     *
     * ritorna: una matrice */


    $qry="select nome,cognome, utenti.matricola, prestiti.inizio, fine, restituito, titolo from copie_prestiti inner join  utenti on 
               copie_prestiti.matricola=utenti.matricola inner join libri l on copie_prestiti.isbn = l.isbn
               inner join prestiti on copie_prestiti.matricola = prestiti.matricola and copie_prestiti.inizio = prestiti.inizio";

    $result = $GLOBALS['connessione']->query($qry);



    $primaPosizioneDisponibile=0;
    $tab=[];
    while ($row = $result->fetch_assoc())
    {
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $matricola = $row['matricola'];
        $dataInizio = $row['inizio'];
        $dataFine = $row['fine'];
        $restituito=$row['restituito'];
        $titolo = $row['titolo'];



        $indicePresito = prestito_inserito($tab,$row);


        /*if($indicePresito!=false) {
            echo $indicePresito . "\n";
        }
        else
        {
            echo "false\n";
        }*/

        //echo $indicePresito . "\n";


        if($indicePresito!==false)
        {

            end($tab[$indicePresito][6]);
            $ultimoIndiceLibri=key($tab[$indicePresito][6]);
            //$libri[$ultimoIndiceLibri+1]=$titolo;
            $tab[$indicePresito][6][$ultimoIndiceLibri+1]=$titolo;



        }
        else
        {

            $riga[0]=$nome;
            $riga[1]=$cognome;
            $riga[2]=$matricola;
            $riga[3]=$dataInizio;
            $riga[4]=$dataFine;
            $riga[5]=$restituito;

            $libri[0]=$titolo;

            $riga[6]=$libri;

            $tab[$primaPosizioneDisponibile]=$riga;

            $primaPosizioneDisponibile++;
        }


    }

    return $tab;


}

function prestito_inserito($tab,$row)
{

    $matricola = $row['matricola'];
    $dataInizio = $row['inizio'];


    $i=0;
    foreach ($tab as $riga)
    {
        if($riga[2]==$matricola and $riga[3]==$dataInizio)
        {

            return $i;
        }

        $i++;
    }

    return false;

}

function restituisci_prestito($matricola,$inizio)
{

    /* dato un certo presitito la funzione imposta a "restituito" quel presito e aggiorna la data fine con
        la data di oggi

    */
    $dataOggi=date('Y-m-d');
    $qry="update prestiti set restituito=1, fine='$dataOggi' where matricola='$matricola' and inizio='$inizio'";
    $GLOBALS['connessione']->query($qry);


    //test_qry($result,"update prestito in restituisci_prestito()");


}

function filtra_prestiti($target)
{
    /*
     * questa funzione mi restituisce  un array di prestiti che contengono la parola $target passatale
     * parametri: stringa $targhet
     * restituisce: un array $result*/

    //funzione che mi da i prestiti in formato array
    $tab=crea_struttura_gestione_prestiti();

    $i=0;

    //scorro l array $tab
    foreach ($tab as $prestito) {

        //se la funzione inizio()trova la parola $target trova corrispondenza in un prestito mi ritorna true
        //e il prestito viene inserito su un array $result
        if(iniziaPer($prestito,$target)===true)
        {
            $result[$i]=$prestito;
            $i++;

        }

    }

    return $result;
}

function iniziaPer($prestito,$target)
{

    /*
     * questa funzione serve a verificare se una stringa e contenuta in un $prestito
     * parametri: stringa $target , un di un $prestito
     * ritorna: true o false
     * */



    //metto nelle variabili il contenuto di ogni cella dell array $prestito
    $nome = $prestito[0];
    $cognome = $prestito[1];
    $matricola = $prestito[2];
    $inizio = $prestito[3];
    $fine = $prestito[4];
    $libri=$prestito[6];


    $pieces = explode(" ", $target);
    $numPieces = count($pieces);


    $count=0;
    //dato che potremmo avere piu di una parola da controllare ,serve un ciclo.
    //viene controllata una parola alla volta e se trovata incrementa un contatore
    foreach ($pieces as $piece)
    {
        //variabile che contiene un comando regex
        $pattern = "#\b$piece#iu";


        //serie di controlli che controllano ogni campo per cercare un riscontro.
        //per ogni controllo c e un if che verifica se c è corrispondenza
        //se c è corrispondenza si incrementa un contatore e poi esce dall if ricominciando dall inizio il controllo
        //nel caso di libri se c è riscontro esce dal proprio ciclo e torna a controllare dall inizio
        //ritorna true se ha trovato corrispondenza forse se non c è :
        $out = preg_match($pattern,$nome);

        if($out===false)
        {
            echo "errore";
            exit(-1);
        }

        if ($out == 1)
        {

            $count++;
            continue;
        }

        //--

        $out = preg_match($pattern,$cognome);

        if($out===false)
        {
            echo "errore";
            exit(-1);
        }

        if ($out == 1)
        {

            $count++;
            continue;
        }

        $out = preg_match($pattern,$matricola);

        if($out===false)
        {
            echo "errore";
            exit(-1);
        }

        if ($out == 1)
        {
            $count++;
            continue;
        }
        $out = preg_match($pattern,$inizio);

        if($out===false)
        {
            echo "errore";
            exit(-1);
        }

        if ($out == 1)
        {

            $count++;
            continue;
        }

        $out = preg_match($pattern,$fine);

        if($out===false)
        {
            echo "errore";
            exit(-1);
        }

        if ($out == 1)
        {

            $count++;
            continue;
        }



        foreach ($libri as $libroPrestito)
        {
            $out = preg_match($pattern,$libroPrestito);

            if($out===false)
            {
                echo "errore";
                exit(-1);
            }

            if ($out == 1)
            {

                $count++;
                break;
            }


        }

    }
    //se il contatore ha lo stesso numero di parole torna true, cioè le ha trovate tutte
    if($count==$numPieces)
    {

        return true;
    }
    else
    {
        return false;
    }

}

function stampa_tab($tab)
{
    foreach ($tab as $prestito)
    {
        echo $prestito[0]." ".$prestito[1]." ".$prestito[2]." ".$prestito[3]." ".$prestito[4]." ".$prestito[5].
            " "."libri: ";

        foreach ($prestito[6] as $libro)
        {
            echo $libro.", ";
        }

        echo "\n";
    }
}

function filtra_utenti($target)
{
    //echo $target."\n";

    $pieces = explode(" ", $target);
    $numPieces=count($pieces);



    $k=0;
    $qry = "";
    for($i=0; $i<$numPieces; $i++) {

        if ($k!=$numPieces-1){



            $qry=$qry."select * from utenti
            where indirizzo REGEXP '[[:<:]]$pieces[$i]' or nome REGEXP '[[:<:]]$pieces[$i]' or cognome REGEXP '[[:<:]]$pieces[$i]'
            or matricola REGEXP '[[:<:]]$pieces[$i]' or telefono REGEXP '[[:<:]]$pieces[$i]' union ";

        }

        else
        {


            $qry=$qry."select * from utenti
            where indirizzo REGEXP '[[:<:]]$pieces[$i]' or nome REGEXP '[[:<:]]$pieces[$i]' or cognome REGEXP '[[:<:]]$pieces[$i]'
            or matricola REGEXP '[[:<:]]$pieces[$i]' or telefono REGEXP '[[:<:]]$pieces[$i]'";

        }

        $k++;

    }

    $result= $GLOBALS['connessione']->query($qry);
   // echo $qry;

    return $result;

}

function filtra_libri($target)
{
    $pieces = explode(" ", $target);
    $numPieces = count($pieces);



    $k = 0;
    $qry = "";
    for ($i = 0; $i < $numPieces; $i++) {

        if ($k != $numPieces - 1) {
            $qry = $qry."select * from libri inner join editori e on libri.editore = e.codice
                        where isbn REGEXP '[[:<:]]$pieces[$i]' or titolo REGEXP '[[:<:]]$pieces[$i]'
                        or anno  REGEXP '[[:<:]]$pieces[$i]' or lingua REGEXP '[[:<:]]$pieces[$i]' 
                        or nome REGEXP '[[:<:]]$pieces[$i]' union ";
        }
        else
        {
            $qry = $qry."select * from libri inner join editori e on libri.editore = e.codice
                        where isbn REGEXP '[[:<:]]$pieces[$i]' or titolo REGEXP '[[:<:]]$pieces[$i]'
                        or anno REGEXP '[[:<:]]$pieces[$i]' or lingua REGEXP '[[:<:]]$pieces[$i]' 
                        or nome REGEXP '[[:<:]]$pieces[$i]'";
        }

     $k++;
    }

    $result= $GLOBALS['connessione']->query($qry);

    return $result;
}

function filtra_utenti_nuovo_prestito($nome, $cognome, $matricola)
{
    /*
     * funzione usata nella pagina nuovo_prestito.php per cercare gli utenti
     * la ricerca usa un and
     */
    $qry="select * from utenti 
          where nome REGEXP '[[:<:]]$nome' and cognome REGEXP '[[:<:]]$cognome' and matricola REGEXP '[[:<:]]$matricola'";
    $result= $GLOBALS['connessione']->query($qry);


    return $result;


}

function filtra_libro($titolo, $isbn)
{

    $pieces = explode(" ", $titolo);
    $numPieces = count($pieces);


    $v=[];
    $vf=[];


    for($i=0; $i<$numPieces; $i++)
    {
        $qry="select * from libri 
           where titolo REGEXP '[[:<:]]$pieces[$i]' and isbn REGEXP '[[:<:]]$isbn'";
        $result= $GLOBALS['connessione']->query($qry);

        $v[$i]=$result;

    }


    /*while($row = $v[0]->fetch_assoc()) {
        echo $row['titolo']." ".$row['isbn']."\n";
    }*/

/*    echo "..\n";

    while($row = $v[0]->fetch_assoc()) {
        echo $row['titolo']." ".$row['isbn']."\n";
    }

    $v[0]->data_seek(0);
    //$v[1]->data_seek(0);
    echo "------------\n";*/

    if($numPieces==1)
    {
        return $v[0];
    }


    $j=0;
    $trovato=false;
    while($rowPrimo = $v[0]->fetch_assoc())
    {
        $isbnPrimo=$rowPrimo['isbn'];
        //echo "$isbnPrimo\n";


        //tabella
        for($k=1; $k<$numPieces; $k++)
        {
            while($row = $v[$k]->fetch_assoc())
            {
                $isbn=$row['isbn'];
                //echo "$isbn\n";

                if($isbnPrimo==$isbn ){
                    //echo "$isbnPrimo\n";
                    $trovato=true;
                    if($k==$numPieces-1) {
                        $vf[$j] = $row;
                        $j++;
                        break;

                    }


                }

            }

            $v[$k]->data_seek(0);

            if($trovato === false)
            {
                break;
            }
            $trovato = false;


        }

    }


    return $vf;



}
function copia_fuori($isbn, $numCopia)
{
    /*
     * quando si assegnano le copie a presiti in maniera casuale bisogna essere sicuri che quella copia
     * non sia già fuori
     *
     * false se non è fuori
     * data rientro prevista se è in casa
     */

    $restituitoQ="select fine from copie_prestiti 
                              inner join prestiti p on copie_prestiti.matricola = p.matricola and copie_prestiti.inizio = p.inizio
                              where copie_prestiti.isbn='$isbn' and numero_copia='$numCopia' and restituito=0";
    $risul = $GLOBALS['connessione']->query($restituitoQ);


    $numRighe=mysqli_num_rows($risul);



    if($numRighe==0)
    {

        return false;
    }

    else
    {
        if($numRighe>1)
        {

            echo "errore in copia_fuori()";
            exit(-1);
        }


        $riga = $risul->fetch_assoc();
        $dataFine=$riga['fine'];
        return $dataFine;

    }

}

function info_complete_copia($isbn, $numCopia)
{
    /*
     * data una copia serve ad estratte tutte le informazioni essa riguardanti: titolo, anno, autori, editore, lingua,
     * (isbn), (numero copia), succursale
     *
     * la funzione serve in nuovo_prestito.php, nella sezione cerca libro e nella sezione riepilogo
     *
     * ritorna un array
     */


    $qry = "select * from libri where isbn='$isbn'";
    $risultato = $GLOBALS['connessione']->query($qry);

    $libro = $risultato->fetch_assoc();


    $output["titolo"] = $libro["titolo"];
    $output["anno"] = $libro["anno"];
    $output["isbn"] = $libro["isbn"];
    $output["lingua"] = $libro["lingua"];

    //////////////////////////////////////

    $qry = "select nome, cognome from libri_autori where isbn='$isbn'";
    $risultato = $GLOBALS['connessione']->query($qry);
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

    $output["autori"] = $autori;

    ///////////////////////////////////////////

    $qry = "select nome from libri inner join editori e on libri.editore = e.codice where isbn='$isbn'";
    $risultato = $GLOBALS['connessione']->query($qry);
    $row = $risultato->fetch_assoc();
    $editore = $row['nome'];

    $output["editore"] = $editore;

    ///////////////////////////////////////////


    $qry = "select * from copie where isbn='$isbn' and numero_copia = '$numCopia'";
    $risultato = $GLOBALS['connessione']->query($qry);

    $row = $risultato->fetch_assoc();

    $output["succursale"] = $row['succursale'];
    $output["numeroCopia"] = $row['numero_copia'];

    ////////////////////////////////////////////////////

    return $output;
}

function copiaGiaSelezionata($isbn_num_copieSelezionate, $isbn, $numCopia)
{
    /*
     * questa è una funzione di aiuto per nuovo_presito.php
     * data la stringa che mantiene lo stato delle copie selezionate $isbn_numCopia_selezionate e
     * una copia ($isbn, $numCopia) dice se l'ultima è contenuta nella prima.
     * E' usata quando si ricostruisce la tabella delle copie cercate: se una copia è gia stata selezionata
     * in una sessione precedente questa non può piu essere selezionata...
     */


    $copie = explode("@", $isbn_num_copieSelezionate);

    foreach ($copie as $copia) {
        $isbn_numCopia = explode("*", $copia);

        $isbn_ = $isbn_numCopia[0];
        $numCopia_ = $isbn_numCopia[1];

        if ($isbn == $isbn_ and $numCopia == $numCopia_)
        {
            return true;
        }
    }

    return false;

}

function rimuoviCopiaDaIsbn_num_copieSelezionate($isbn_num_copieSelezionate,$isbn, $numCopia)
{
    /*
     * funzione di aiuto per nuovo_prestito.php
     * usata quando il bottone rimuovi copia è premuto in riepilogo
     *
     * la funzione data la stringa che contiene tutte le copie selezionate $isbn_num_copieSelezionate e una copia,
     * restituire una nuova stringa senza la copia
     */

    $copie = explode("@", $isbn_num_copieSelezionate);

    $nuovo_isbn_num_copieSelezionate = "";

    foreach ($copie as $copia) {
        $isbn_numCopia = explode("*", $copia);

        $isbn_ = $isbn_numCopia[0];
        $numCopia_ = $isbn_numCopia[1];

        if ($isbn != $isbn_ or $numCopia != $numCopia_)
        {
            $nuovo_isbn_num_copieSelezionate = $nuovo_isbn_num_copieSelezionate . "$isbn_*$numCopia_@";
        }

    }

    return $nuovo_isbn_num_copieSelezionate;

}

function inserisci_prestito($matricola,$isbn_num_copieSelezionate)
{
    /*
     * quando si clicca su conferma in nuovo_prestito.php si rimanda su index.php dove viene l'inserimento vero e proprio
     *
     * paramentri
     *      $matricola: matricola dell'utente del prestito
     *      $isbn_num_copieSelezionate: stringa che contiene tutte le copie del prestito separati da @ e *
     */

    $dataOggi=date('Y-m-d');
    $dataFine=date('Y-m-d', strtotime('+30 days', strtotime($dataOggi)));

    $qry = "insert into prestiti values ('$matricola','$dataOggi','$dataFine',0)";
    $risultato = $GLOBALS['connessione']->query($qry);

    test_qry($risultato,"inserimento in presiti");

    ///////////////////////////////


    $copie = explode("@", $isbn_num_copieSelezionate);

    foreach ($copie as $copia) {
        $isbn_numCopia = explode("*", $copia);

        $isbn_ = $isbn_numCopia[0];
        $numCopia_ = $isbn_numCopia[1];

        $qry = "insert into copie_prestiti values ('$isbn_','$numCopia_','$matricola','$dataOggi')";
        $risultato = $GLOBALS['connessione']->query($qry);

        test_qry($risultato,"inserimento in copie_prestiti");


    }




}

function prestito_esiste($matricola, $dataInizio)
{
    /* questa funzione mi serve ad evitare di avere doppioni su popola_prestito() in quanto maricola e dataInizio sono
    chiavi primarie*/

    $qry="select matricola, inizio from prestiti where matricola='$matricola' and inizio='$dataInizio'";
    $result = $GLOBALS['connessione']->query($qry);
    //test_qry($result, "SELECT in prestito_esiste()");
    $numRighe=mysqli_num_rows($result);


    if($numRighe==1)
    {
        return true;
    }
    else
    {
        return false;
    }



}
