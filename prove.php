<?php
include "gestione_biblio.php";

$tab=crea_struttura_gestione_prestiti();


foreach ($tab as $presito)
{

    echo $presito[0]." ".$presito[1]." ".$presito[2]." ".$presito[3]." ".$presito[4]." Libri: ";
    foreach ($presito[5] as $libro)
    {
        echo $libro.", ";
    }

    echo "\n";

}





















/*$tab = crea_struttura_gestione_prestiti();
$size = sizeof($tab);

$dataOggi = date('Y-m-d');
for ($i = 0; $i <= $size; $i++) {
    $nome = $tab[$i][0];
    $cognome = $tab[$i][1];
    $matricola = $tab[$i][2];
    $dataInizio = $tab[$i][3];
    $dataFine = $tab[$i][4];
    $libriPerIlPrestitoI = $tab[$i][5];


    if ($dataFine > $dataOggi) {

        echo "
                      <tr>
                             <td width='80' height='40'> <form action=''>
                             <button type='submit'>-></button>
                             </form>
                             </td>
                             <td>$nome</td>
                             <td>$cognome</td>
                             <td>$matricola</td>
                             <td>$dataInizio</td>
                             <td>$dataFine</td>";

        echo "<td>";
        foreach ($libriPerIlPrestitoI as $libro) {
            echo $libro . "<br>";
        }
        echo "</td>";

        echo "<td width='70'><form action=''>
                             <button type='submit'>restituito</button>
                             </form>
                             </td>
                       </tr>";

    } else {
        echo "
                      <tr>
                             <td width='80' height='40'> <form action=''>
                             <button type='submit'>-></button>
                             </form>
                             </td>
                             <td>$nome</td>
                             <td>$cognome</td>
                             <td>$matricola</td>
                             <td>$dataInizio</td>
                             <td>$dataFine</td>";

        echo "<td>";
        foreach ($libriPerIlPrestitoI as $libro) {
            echo $libro . "<br>";
        }
        echo "</td>";

        echo "<td width='70'>
                            
                             </td>
                       </tr>";


    }


}*/




