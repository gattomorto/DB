
<?php
include "gestione_biblio.php";


/*
$result=filtra_libro('Boys of St Vincent The','');

foreach ($result as $libro)
{
    echo $libro['titolo']." ".$libro['isbn']."\n";
}*/

$risultatoCopiaFuori=copia_fuori('222223087-X','1');


echo $risultatoCopiaFuori;
?>

