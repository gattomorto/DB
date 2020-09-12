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


    $qry="select nome,cognome, utenti.matricola, prestiti.inizio, fine, titolo from copie_prestiti inner join  utenti on 
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

            end($tab[$indicePresito][5]);
            $ultimoIndiceLibri=key($tab[$indicePresito][5]);
            //$libri[$ultimoIndiceLibri+1]=$titolo;
            $tab[$indicePresito][5][$ultimoIndiceLibri+1]=$titolo;



        }
        else
        {

            $riga[0]=$nome;
            $riga[1]=$cognome;
            $riga[2]=$matricola;
            $riga[3]=$dataInizio;
            $riga[4]=$dataFine;

            $libri[0]=$titolo;

            $riga[5]=$libri;

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




