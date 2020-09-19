<?php
$con = connetti();
mysqli_set_charset($con, "utf8");
$GLOBALS['connessione']=$con;

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

    $qry="select lingua ,count(*) from libri  group by lingua order by count(*) DESC ";
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

    $qry="select nome,cognome ,count(*)from libri_autori group by nome, cognome order by count(*) DESC";
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

    $qry="select nome ,count(*) from libri inner join editori on libri.editore = editori.codice group by nome
          order by count(*) DESC ";
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

    /* dato un certo presitito la funzione imposta a "restituito" quel presito

    */

    $qry="update prestiti set restituito=1 where matricola='$matricola' and inizio='$inizio'";
    $GLOBALS['connessione']->query($qry);

    //test_qry($result,"update prestito in restituisci_prestito()");


}

function filtra($target)
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
