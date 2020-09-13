<?php

rimmuovi_doppioni();
$con = connetti();
mysqli_set_charset($con, "utf8");
$GLOBALS['connessione']=$con;


/*echo "------------------------TEST----------------------------\n";
test_libro_esiste();
test_autore_esiste();*/
echo "------------------------MAIN----------------------------\n";
cancella_e_crea_database();
popola_editori();
popola_succursali();
popola_libri();
popola_utenti();
popola_autori();

popola_prestiti(30,20);

popola_libri_autori();
popola_copie();
popola_copie_prestiti();

///////////////////////////////////////////////////////FUNZIONI/////////////////////////////////////////////////////////

function cancella_e_crea_database()
{
    $qry="drop table if exists copie_prestiti,libri_autori,copie,libri,succursali,editori,autori,prestiti,utenti";

    $result =$GLOBALS['connessione']->query($qry);

    test_qry($result,"eliminazione tabelle in cancella_e_crea_database",$GLOBALS['connessione']);
////////////////////////////////////////////////////////////////
    $qry="CREATE TABLE `autori` (
          `nome` varchar(255),
           cognome varchar(255),
          `data_di_nascita` date,
          `luogo_di_nascita` varchar(255),
          PRIMARY KEY (`nome`,cognome)
        )";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella autori",$GLOBALS['connessione']);

///////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE `editori` (
          `nome` varchar(255) NOT NULL,
          `codice` smallint(6) NOT NULL,
          `indirizzo` varchar(255),
          `telefono` varchar(255),
           PRIMARY KEY (`codice`) )";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella editori",$GLOBALS['connessione']);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE `succursali` (
          `nome` varchar(255) NOT NULL,
          `indirizzo` varchar(255),
           PRIMARY KEY (`nome`))";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella succursale",$GLOBALS['connessione']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE `libri` (
          `isbn` varchar(255) NOT NULL,
          `titolo` varchar(255) NOT NULL ,
          `anno` smallint(6),
          `lingua` varchar(255),
          `editore` smallint(6),
          
           PRIMARY KEY (`isbn`),
           FOREIGN KEY (`editore`) REFERENCES `editori` (`codice`)  ON DELETE SET NULL ON UPDATE CASCADE)";


    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella libri",$GLOBALS['connessione']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE copie (
          isbn varchar(255) NOT NULL,
          numero_copia tinyint(4) NOT NULL,
          succursale varchar(255),
           
           PRIMARY KEY (isbn, numero_copia),
           FOREIGN KEY (succursale) REFERENCES succursali (nome)  ON DELETE set null ON UPDATE CASCADE,
           FOREIGN KEY (isbn) REFERENCES libri (isbn)  ON DELETE NO ACTION ON UPDATE CASCADE)";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella copie",$GLOBALS['connessione']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $qry="CREATE TABLE `libri_autori` (
        `isbn` varchar(255) NOT NULL,
        `nome` varchar(255) NOT NULL,
        cognome varchar(255) NOT NULL,
        PRIMARY KEY (`isbn`,`nome`,cognome),
        FOREIGN KEY (`isbn`) REFERENCES `libri` (`isbn`)  ON DELETE NO ACTION ON UPDATE CASCADE ,
        FOREIGN KEY (`nome`,cognome) REFERENCES `autori` (`nome`,cognome)  ON DELETE NO ACTION ON UPDATE CASCADE)";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella editori",$GLOBALS['connessione']);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE `utenti` (
          `nome` varchar(255),
          `cognome` varchar(255),
          `indirizzo` varchar(255),
          `telefono` varchar(255),
          `matricola` varchar(255) NOT NULL,
           PRIMARY KEY (`matricola`))";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella utenti",$GLOBALS['connessione']);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE `prestiti` (
          `matricola` varchar(255) NOT NULL,
          `inizio` date NOT NULL,
          `fine` date not null,
          restituito boolean not null,
          PRIMARY KEY (`matricola`,`inizio`),
          FOREIGN KEY (`matricola`) REFERENCES `utenti` (`matricola`)  ON DELETE NO ACTION ON UPDATE CASCADE)";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella prestiti",$GLOBALS['connessione']);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $qry="CREATE TABLE `copie_prestiti` (
          `isbn` varchar(255) NOT NULL,
          `numero_copia` tinyint(4) NOT NULL,
          `matricola` varchar(255) NOT NULL,
          `inizio` date NOT NULL,
          PRIMARY KEY (`isbn`,`numero_copia`,`matricola`,`inizio`),
          FOREIGN KEY (`isbn`, `numero_copia`) REFERENCES copie (isbn, numero_copia)  ON DELETE NO ACTION ON UPDATE CASCADE,
          FOREIGN KEY (`matricola`, `inizio`) REFERENCES `prestiti` (`matricola`, `inizio`)  ON DELETE NO ACTION ON UPDATE CASCADE);";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"creazione tabella copie_prestiti",$GLOBALS['connessione']);

}
function popola_libri()
{
    $var = fopen("../Dati_25_05_2020.csv", "r");
    //la usiamo per saltare la prima riga dell intestazione
    $prima_volta=true;

    if ($var == false) {

        echo "file non aperto";
        exit();
    }
    while (!feof($var)) {


        // legge una riga dal file
        $riga_file = fgets($var);
        // split della riga sulla virgola; variabile $libro è un array
        $libro = explode(",", $riga_file);
        // tolto tuttle le virgolette
        $libro = str_replace("\"", "", $libro);
        // fgets() legge anche il carattere \n finale
        $libro = str_replace("\n", "", $libro);


        //salto la prima riga
        if($prima_volta==false) {

            //echo "\n\n";

            if (libro_esiste($libro[1]) == false) {

                // seleziono da editori il codice che corrisponde al nome dell'editore
                // perchè sul file c'è il nome, ma quando si inserisce il libro, ci vuole il codice
                $qry="select codice from  editori where nome in (select nome from editori where nome='$libro[8]' )";
                $result = $GLOBALS['connessione']->query($qry);
                test_qry($result,"SELECT codice da editori in popola_libri() per editore = $libro[8]");
                $codiceEditore= $result->fetch_assoc();
                $codiceEditore = $codiceEditore['codice'];

                //echo "codice editore ".$codiceEditore["codice"]. "$libro[8]"."\n";

               // echo $libro[1]."---"."$libro[0]"."___"."$libro[3]"."----"."$libro[2]"."____"."$codiceEditore";



                // iserimeto vero e proprio di UNA COPIA di libro
                $qry = "insert into libri values ('$libro[1]','$libro[0]' ,$libro[3],'$libro[2]',$codiceEditore)";
                //echo $qry."\n";
                $result = $GLOBALS['connessione']->query($qry);
                test_qry($result,"INSERT libro ($libro[1]) su popola_libri()");

                //echo "\n";

            }

        }

        $prima_volta=false;
    }

}
function libro_esiste($isbn)
{
    /*
     * La funzione controlla se è presente o no un libro con ISBN = $isbn nella tabella libri
     *
     * parametri:
     * $isbn: ISBN del libro da controllare
     *
     * ritorna: TRUE|FALSE
     */



    $qry="select * from libri where isbn='$isbn'";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"SELECT su libro_esiste() (ISBN=$isbn):");

    if ($result->num_rows > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function copia_prestito_esiste($isbn,$numCopia,$matricola, $inizio){

    /*
     * descrizione:
         * questa funzione viene usata su popola_copie_prestito(), serve a essere certi di non avere due chievi uguali
         * nella tabella copie_prestiti
     *
     * parametri:
         * $isbn, $numCopia, $matricola, $inizio: è la chiave da controllare
     *
     * ritorno:
         * true se esiste già una riga con la chiave passata, false se non
     */


    $qry="select * from copie_prestiti";
    $result = $GLOBALS['connessione']->query($qry);
    test_qry($result,"SELECT da 'copie_prestito' in copie_prestiti_esiste()");


    while ($row = $result->fetch_assoc())
    {
        if($row['isbn']==$isbn and $row['numero_copia']==$numCopia and $row['matricola']==$matricola and $row['inizio']==$inizio)
        {
            return true;
        }

    }

        return false;
}
function autore_esiste($nome,$cognome)
{
    /*
    dato un autore (nome, cognome), mi dice se e presente o no nella tabella 'autori'

    parametri:
        $nome: nome dell'autore
        $cognome: cognome dell'autore

    ritorna: true o false
    */
    $qry="select 'x' from autori where nome='$nome' and cognome='$cognome'";
    $result = $GLOBALS['connessione']->query($qry);
    test_qry($result,"SELECT su autore_esiste() ($nome $cognome)");


    if ($result->num_rows > 0)
    {
        return true;

    }
    else
    {
        return false;
    }
}
function popola_editori()
{
    $qry = "insert into editori values 
                           ('Apogeo', '1', 'Via Andegari 6, 20121 Milan','02-3596681'),
                           ('Pearson Italia',  '2',  'Corso Trapani 16, 10139 Turin', '011-75021510'),
                           ('Tecniche Nuove', '3',  'Via Eritrea 21, 20157 Milan', '02-390901'),
                           ('Packt Publishing', '4', 'Tennessee Circle 4, 89654 Boston', '903-259-1261'),
                           ('Addison-Wesley', '5', 'Coolidge Junction 12, 7030 Oboken', '638-435-4004'),
                           ('Pst Edizioni', '6', 'Via degli Editori Riuniti 8, 20157 Milan', '02-546722'),
                           ('Dunning Editions', '7', 'Dunning Drive 988, 23445 Lawrance', '16-339-1269'),
                           ('Europa Edizioni', '8', 'Via degli Editori Riuniti 34, 20157 Milan', '02-835610'),
                           ('Plaza Publicacionès', '9', 'Strada Grande 944, 2264 Madrid', '03-567234'),
                           ('Meaning', '10', 'Lakewood Gardens Place 28063, 8734 New York', '309-306-5066')";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"popolazione editori",$GLOBALS['connessione']);
}
function popola_succursali()
{
    $qry = "insert into succursali values 
                      ('Architettura','Via Ghiara, 36 - 44121 Ferrara'),
                      ('Economia e management','Via Voltapaletto n. 11 - 44121 Ferrara'),
                      ('Fisica e Scienze della Terra','Via Saragat, 1 - 44122 Ferrara'),
                      ('Giurisprudenza','Corso Ercole I d\'Este n. 37 - 44121 Ferrara'),
                      ('Ingegneria',' Via Saragat 1 - 44122 Ferrara'),
                      ('Matematica e informatica',' Via Machiavelli 30 - 44121 Ferrara'),
                      ('Morfologia chirurgia e medicina sperimentale',' Via Luigi Borsari 46 - 44121 Ferrara'),
                      ('Scienze biomediche e chirurgico specialistiche','Via Luigi Borsari 46 - 44121 Ferrara'),
                      ('Scienze chimiche e farmaceutiche',' Via Luigi Borsari 46 - 44121 Ferrara'),
                      ('Scienze della vita e biotecnologie','Via Luigi Borsari 46 - 44121 Ferrara'),
                      ('Scienze mediche',' Via Fossato di Mortara 64/B - 44121 Ferrara'),
                      ('Studi umanistici','Via Paradiso 12 - 44121 Ferrara')";

    $result = $GLOBALS['connessione']->query($qry);

   test_qry($result,"polazione tabella succursale",$GLOBALS['connessione']);
}
function popola_autori()
{
    /* si legge dal file csv e per ogni riga si inserisce l'autore nella tabella 'autori' */

    $var = fopen("../Dati_25_05_2020.csv", "r");
    //la usiamo per saltare la prima riga dell intestazione
    $prima_volta=true;

    if ($var == false) {

        echo "file non aperto";
        exit();
    }

    while (!feof($var)) {

        // legge una riga dal file
        $riga_file = fgets($var);
        // split della riga sulla virgola; variabile $autore è un array
        $autore = explode(",", $riga_file);
        // tolto tuttle le virgolette
        $autore = str_replace("\"", "", $autore);
        // fgets() legge anche il carattere \n finale, per cui lo si toglie
        $autore = str_replace("\n", "", $autore);




        if ($prima_volta == false) // salto la prima riga
        {
            if(autore_esiste($autore[4],$autore[5])==false) // si controlla se un certo autore è gia stato inserito o no
            {
                //inserisco gli autori nella tabella 'autori'
                $qry="insert into autori values('$autore[4]','$autore[5]','$autore[6]','$autore[7]')";
                //echo $qry."\n";
                $result = $GLOBALS['connessione']->query($qry);
                test_qry($result,"inserimento su popola_autori() ($autore[4])");
                //echo $autore[4],$autore[5],$autore[6],$autore[7] ;
            }

        }

        $prima_volta=false;

    }

fclose($var);
}
function popola_utenti()
{
    /*
        questa funzione serve ad inserire alcuni utenti nella tabella 'utenti' scelti in modo arbitrario
    */

    $qry="insert into utenti values('Luca','Laurenti','via borgo Rivoli 32, Ferrara','3385633332','123946'),
                                   ('Ilari','Blasi','corso del popolo 2, Ferrara','3396392210','882134'),
                                   ('Sara','Monte','piazza San Giovanni rotondo 55, Occhiobello','3387271393','214737'),
                                   ('Marco','Rossi','via Vittorio Cavour 18, Rovigo','3383257933','118366'),
                                   ('Karim','Kash','via Verdi 9, Monselice','3394356982','537291')";

    $result = $GLOBALS['connessione']->query($qry);
    test_qry($result,"inserimento in tabella utenti()");



}
function popola_prestiti($numPresitiScaduti,$numPrestitiNonScaduti)
{
    /*
     *
     * Si generano dei presiti scaduti (il perdiodo concesso di 30 gioni è finito) e presiti non scaduti (i libri del
     * presito possono ancora essere tenuti)
     * I prestiti scaduti sono tutti restituiti, mentre di quelli non scaduti alcuni sono restituiti alcunu no
     */

    $dataOggi=date('Y-m-d');
    //prestiti conclusi

    while($numPresitiScaduti!=0)
    {
        //seleziono una matricola in modo casuale dalla tabella utenti
        $qry = "select matricola from utenti order by rand()limit 1";
        $result = $GLOBALS['connessione']->query($qry);
        test_qry($result,"SELECT matricola casuale in popola_prestiti()");
        $utente= $result->fetch_assoc();


        //uso la funzione che mi genera le date ottenedo un array
        $prestitoConclusoArray=generaInizioFinePrestito("concluso");
        $matricolaRand=$utente['matricola'];
        //inserisco matricola data inizio e data fine del prestito e che è resituito


        if(!prestito_esiste($matricolaRand,$prestitoConclusoArray[0]))
        {

            $qry="insert into prestiti values ('$matricolaRand','$prestitoConclusoArray[0]','$prestitoConclusoArray[1]',1)";
            //echo  $qry."\n";
            $result = $GLOBALS['connessione']->query($qry);
            test_qry($result,"INSERT prestito concluso su tabella popla_prestiti()");
            $numPresitiScaduti--;
        }


    }


    //prestiti attivi
    while($numPrestitiNonScaduti!=0) {

        $qry = "select matricola from utenti order by rand()limit 1";
        $result = $GLOBALS['connessione']->query($qry);
        test_qry($result, "SELECT in tabella utenti, per la funzione popla_prestito()");
        $utente = $result->fetch_assoc();
        test_qry($result, "SELECT da utenti la matricola rand");

        $prestiAttiviArray = generaInizioFinePrestito("attivi");
        $matricolaRand = $utente['matricola'];

        $bool = rand(0, 1);

        if(!prestito_esiste($matricolaRand,$prestiAttiviArray[0]))
        {
            $qry = "insert into prestiti values ('$matricolaRand','$prestiAttiviArray[0]','$prestiAttiviArray[1]','$bool')";
            echo $qry."\n";
            $result = $GLOBALS['connessione']->query($qry);
            test_qry($result, "INSERT prestiti attivi su tabella popola_prestiti()");
            $numPrestitiNonScaduti--;
        }



    }


}
function popola_libri_autori()
{
    /*
     La funzione riempie la tabella 'libri_autori' (n-n tra libri e autori)
     Si ligge la riga del file e la si inserisce "direttamente" senza nessuna alterazione particolare
     */

    $var = fopen("../Dati_25_05_2020.csv", "r");
    //la usiamo per saltare la prima riga dell intestazione
    $prima_volta=true;

    if ($var == false) {

        echo "file non aperto";
        exit();
    }

    while (!feof($var)) {

        // legge una riga dal file
        $riga_file = fgets($var);
        // split della riga sulla virgola; variabile $libro è un array
        $libro = explode(",", $riga_file);
        // tolto tuttle le virgolette
        $libro = str_replace("\"", "", $libro);
        // fgets() legge anche il carattere \n finale
        $libro = str_replace("\n", "", $libro);

        if($prima_volta==false)
        {

            $qry="insert into libri_autori values('$libro[1]','$libro[4]','$libro[5]')";
            //echo $qry."\n";
            $result = $GLOBALS['connessione']->query($qry);

            test_qry($result, "INSERT su libro_autore()");


        }
       $prima_volta=false;
    }

}
function popola_copie(){

    $qry="select isbn from libri";
    $resultIsbn = $GLOBALS['connessione']->query($qry);
    test_qry($resultIsbn,"SELECT sulla tabella libri in popola_copie()");



    while ($row = $resultIsbn->fetch_assoc())
    {
        $isbn=$row['isbn'];

        $numcopie=rand(1,5);



        for($numCopia=1; $numCopia<=$numcopie; $numCopia++)
        {
            $qry="select nome from succursali order by rand()limit 1";
            $resultSuccursali = $GLOBALS['connessione']->query($qry);
            test_qry($resultSuccursali,"SELECT sulla tabella succursali");
            $datoSuccursale= $resultSuccursali->fetch_assoc();
            $succursale=$datoSuccursale['nome'];

            $qry="insert into copie values('$isbn',$numCopia,'$succursale')";
            $result = $GLOBALS['connessione']->query($qry);
            test_qry($result,"INSERT su tabella copia in popola_copie()");


        }


    }
}
function popola_copie_prestiti()
{
    /*
     * questa funzione serve a popolare la tabella copie_prestiti.
     * parametro: $numPrestiti che e un numero arbitrario che mi dice quanti prestiti generare
     */

    $qry="select matricola, inizio from prestiti";
    $daPrestiti = $GLOBALS['connessione']->query($qry);
    test_qry($daPrestiti,"SELECT da tabella 'prestiti' in popola_copie_prestiti()");
    //il ciclo genera copie_prestito numPrestito righe

    while( $row = $daPrestiti->fetch_assoc())
    {
        $numLibriPerPrestito=rand(1,4);

        for($i=0; $i<$numLibriPerPrestito; $i++)
        {
            //estraggo una copia a caso
            $qry="select isbn, numero_copia from copie order by rand()limit 1 ";
            $daCopie = $GLOBALS['connessione']->query($qry);
            test_qry($daCopie,"SELECT da tabella 'copie' in popola_copie_prestiti()");
            //questo ciclo prende ad ogni giro una riga dell array associativo generato dalla tabella copie
            $rowCopia = $daCopie->fetch_assoc();

            $isbn=$rowCopia['isbn'];
            $numCopia=$rowCopia['numero_copia'];
            //echo "$isbn"."---"."$numCopia\n";


            $matricola=$row['matricola'];
            $inizio=$row['inizio'];

            if(!copia_prestito_esiste($isbn,$numCopia,$matricola,$inizio))
            {
                //questa query mi inserisce i valori generari dentro la tabella copia_prestito
                $qry="insert into copie_prestiti value ('$isbn','$numCopia','$matricola','$inizio')";
                $result = $GLOBALS['connessione']->query($qry);
                test_qry($result,"INSERT in tabella copia_prestito()");
             }


        }



    }
    
}

function connetti()
{
    $con = mysqli_connect('localhost:3306','root','trillkill','biblio');

    if(!$con)
    {
        echo "Errore durante la connessione".  mysqli_connect_error();
        exit();
    }
    echo "connessione ok \n" ;

    return $con;

}
function generaInizioFinePrestito($tipo)
{
    /*
     * questa funzione serve a generare date di inizio e fine prestito di due tipi:
     * prestiti conclusi, cioè prestiti che sono iniziati nel passato e conclusi prima di oggi
     * prestiti attivi, cioè prestiti iniziati nel passato e non ancora conclusi
     * questa funzione prende un $tipo che è una stringa che indica se si vuole generare un prestito concluso o attivo
     * restutuisce un array di stringhe dove in posizione zero c è la data di inizio e in posizione 1 la data di fine
     *
     * questa funzione e utilizzata su popola prestito
     *
     *
     * */

    if (strcmp($tipo, "concluso") == 0) {

        //tolgo 32 giorni alla data di oggi in modo che i prestiti non vadano oltre la data di oggi cosi sono conclusi
        //quindi e la data finale del range
        $dataLimite = date('Y-m-d', strtotime("-32 days"));

        //scelgo una data arbitraria per definire dove inizia il range di inizio
        $dataOggi = date('Y-m-d');
        //tolgo 3 anni alla data di oggi cosi un prestito e sicuramente concluso
        $dataLimiteInizio = date('Y-m-d', strtotime('-3 years', strtotime($dataOggi)));

        //genero una data random che inizia in data limite inizio e finisce in data limite
        $dataInizioRand = rand(strtotime($dataLimiteInizio), strtotime($dataLimite));
        $dataPrestito = date('Y-m-d', $dataInizioRand);

        $dataFinePrestito = date('Y-m-d', strtotime('+30 days', strtotime($dataPrestito)));
        // echo "$dataPrestito=>$dataFinePrestito";


        $prestiti[0] = "$dataPrestito";
        $prestiti[1] = "$dataFinePrestito";

    } else {

        //prendo la data di oggi
        $dataOggi = date('Y-m-d');
        //tolgo 28 giorni dalla data di oggi perche un prestito al massimo va restituito entro 30 giorni
        $dataLimiteInizio = date('Y-m-d', strtotime('-29 days', strtotime($dataOggi)));

        //genero una data random che inizia in data limite inizio e finisce in data limite
        $dataInizioRand = rand(strtotime($dataLimiteInizio), strtotime($dataOggi));
        $dataPrestito = date('Y-m-d', $dataInizioRand);

        $dataFinePrestito = date('Y-m-d', strtotime('+30 days', strtotime($dataPrestito)));


        $prestiti[0] = "$dataPrestito";
        $prestiti[1] = "$dataFinePrestito";


    }

    return $prestiti;

}
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
function rimmuovi_doppioni()
{
    /*
     il file Dati_25_05_2020.csv contiene righe esattamente uguali
     la funzione rimuove quelle righe
     */
    $lines = file('../Dati_25_05_2020.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_unique($lines);
    file_put_contents('../Dati_25_05_2020.csv', implode(PHP_EOL, $lines));

}
function prestito_esiste($matricola, $dataInizio)
{
    /* questa funzione mi serve ad evitare di avere doppioni su popola_prestito() in quanto maricola e dataInizio sono
    chiavi primarie*/

   $qry="select matricola, inizio from prestiti where matricola='$matricola' and inizio='$dataInizio'";
   $result = $GLOBALS['connessione']->query($qry);
   test_qry($result, "SELECT in prestito_esiste()");
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


/////////////////////////////////////////////////////////////TEST///////////////////////////////////////////////////////

function test_libro_esiste()
{
    cancella_e_crea_database();
    popola_editori();
    popola_succursali();

    $qry="insert into libri values 
                         ('386397507-3', 'Gunfighter', 2013, 'Albanian', 2),
                         ('712463375-3', 'Fear', 2012, 'English', 2)";

    $result = $GLOBALS['connessione']->query($qry);

    test_qry($result,"inserimento libri in test_libro_esiste()");

    if(libro_esiste('386397507-3')==false)
    {
        echo "test libro_esiste fallito ISBN=386397507-3";
        exit();
    }

    if(libro_esiste('607745120-7')==true)
    {
        echo "test libro_esiste fallito ISBN=607745120-7";
        exit();
    }

    echo "test libro_esiste superato\n";

}
function test_autore_esiste()
{
    //serve a testare la funzione autore_esiste.passo metto nella tabella alcuni autori e vedo se la funzione risponde
    //correttamente

    cancella_e_crea_database();


    $qry="insert into autori values('Gregorius','McPhater','1976-12-04','Pleasantville'),
                                   ('Bruno','Goodsall','1963-04-27','London')";

    $result = $GLOBALS['connessione']->query($qry);
    test_qry($result,"INSERT su test_autore_esiste()");



    if(autore_esiste('Gregorius','McPhater')==false)
    {
        echo "fallito test_autore_esiste() (Gregorius McPhater)";
        exit();
    }

    if(autore_esiste('Reta','Philippsohn')==true)
    {
        echo "fallito test_autore_esiste() (Reta Philippsohn)";
        exit();
    }

    echo "test_autore_esiste superato\n";

}




