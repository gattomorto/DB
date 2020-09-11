<!DOCTYPE html>

<?php
include "gestione_biblio.php";
?>
<html lang="en">
<head>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .corner_left {
            border-top: 1px solid #FFFFFF;
            border-left: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;

        }

        .corner_right {
            border-top: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .left_right {
            border-left: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
        }

        .top_left_bottom {
            border-top: 1px solid #FFFFFF;
            border-left: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .left_bottom {
            border-left: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;

        }

        .top_right_bottom {
            border-top: 1px solid #FFFFFF;
            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .right_bottom {

            border-right: 1px solid #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }
    </style>


    <meta charset="UTF-8">
    <title>Gestione prestiti</title>
</head>
<body bgcolor="#f5f5dc">

    <div align="center">
        <h1>Gestione prestiti</h1>
    </div>


    <form action="">
        <input  size="25" style="font-size:larger" type=”text”>
        <button type=”submit” style="font-size:20px">cerca</button>
    </form><br>

    <div align="left">

        <strong><h2>prestiti attivi</h2></strong>
    </div>



    <font size="5">
    <table border="1" width="1500" align="center" cellspacing="1">

        <tr >
            <td class="corner_left" width="80" height="40"></td>
            <td > <strong>Nome</strong></td>
            <td><strong>Cognome</strong></td>
            <td><strong>Matricola</strong></td>
            <td><strong>Data uscita</strong></td>
            <td><strong>Data rientro</strong></td>
            <td><strong>Libri</strong></td>
            <td class="corner_right" width="70"></td>
        </tr>




      <tr>
             <td class="top_left_bottom" width='80' height='40'> <form action=''>
             <button type='submit'>-></button>
             </form>
             </td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td class="top_right_bottom"width='70'><form action=''>
             <button type='submit'>restituito</button>
             </form>
             </td>
       </tr>

        <tr>
            <td class="corner_left" width='80' height='40'> <form action=''>
                    <button type='submit'>-></button>
                </form>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="right_bottom" width='70'><form action=''>
                    <button type='submit'>restituito</button>
                </form>
            </td>
        </tr>

        <tr>
            <td class="left_bottom" width='80' height='40'> <form action=''>
                    <button type='submit'>-></button>
                </form>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="right_bottom"width='70'><form action=''>
                    <button type='submit'>restituito</button>
                </form>
            </td>
        </tr>





        <!-- Da qui inizia la tabella dei prestiti conclusi -->
        <tr>
            <td class="left_right" style="vertical-align:bottom" colspan="8" width="80" height="40"  ><strong>prestiti conclusi</strong></td class="left_right">

        </tr>


        <tr>
            <td class="top_left_bottom" width="80" height="40"><form action="">
                <button type=”submit”>-></button>
            </form>
            </td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
            <td>8</td>
            <td>9</td>
        </tr>

        <tr>
            <td class="left_bottom"width="80" height="40"><form action="">
                <button type=”submit”>-></button>
            </form>
            </td>
            <td>11</td>
            <td>12</td>
            <td>13</td>
            <td>14</td>
            <td>15</td>
            <td>16</td>
            <td>17</td>
        </tr>

        <tr>
            <td class="left_bottom"width="80" height="40"><form action="">
                <button type=”submit”>-></button>
            </form>
            </td>
            <td>19</td>
            <td>20</td>
            <td>21</td>
            <td>22</td>
            <td>23</td>
            <td>24</td>
            <td>25</td>
        </tr>

    </table><br>
    </font>

    <form action="">
        <button type=”submit” style="font-size: large">Nuovo prestito</button>
    </form>



</body>
</html>