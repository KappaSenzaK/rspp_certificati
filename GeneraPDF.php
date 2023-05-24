<?php

require __DIR__ . "/dompdf/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// $options = new Options();
// $options->setChroot(__DIR__)


$dompdf = new Dompdf(
// SERVE PER AGGIUNGERE LA CARTELLA IN QUI VA INSTALLATO
// [
//     "chroot" => __DIR__ . "/
// ]

// $options NEL CASO SI E' CHIESTO LE OPZIONI
);

$dompdf->setPaper("A4", "landscape");

$tipo = $_POST['tipo'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$data = $_POST['data'];
$luogo = $_POST['luogo'];
$ore = "";

switch ($tipo) {
    case "Attestato di formazione generale":
        $ore = 4;
        break;
    case  'Attestato di formazione specifica - rischio medio':
        $ore = 8;
        break;
    case  'Attestato di formazione - rischio alto':
        $ore = 12;
        break;
    case 'Attestato di formazione sicurezza per il preposto':
        $ore = 8;
        break;
    case 'Attestato di aggiornamento sicurezza per il preposto':
        $ore = 6;
        break;
    case 'Attestato di aggiornamento sicurezza':
        $ore = 6;
        break;
    case 'Attestato di formazione per RLS':
        $ore = 32;
        break;
    case 'Attestato di aggiornamento per RLS':
        $ore = 8;
        break;
    case 'Attestato di formazione aggiornamento RSPP':
        $ore = 40;
        break;
    case 'Attestato di formazione per rischio di incendio - rischio medio':
        $ore = 12;
        break;
    case 'Attestato di formazione per rischio di incendio - rischio alto':
        $ore = 16;
        break;
    case 'Attestato di formazione per il primo soccorso':
        $ore = 12;
        break;
    case 'Attestato di aggiornamento per il primo soccorso':
        $ore = 4;
        break;
    case 'Attestato di formazione BLSD':
        $ore = 5;
        break;
    case 'Attestato di aggiornamento BLSD':
        $ore = 3;
        break;
    default:
        $ore = "??";
}
 


$html = file_get_contents("./html/template.html"); //SERVE PER OTTENERE IL HTML DA UN FILE INVECE DI SCRIVERE TUTTO HTML IN STRINGA
$html = str_replace("{{ nome }}", $nome, $html);
$html = str_replace("{{ cognome }}", $cognome, $html);
$html = str_replace("{{ data }}", $data, $html);
$html = str_replace("{{ luogo }}", $luogo, $html);
$html = str_replace("{{ tipo }}", $tipo, $html);
$html = str_replace("{{ ore }}", $ore, $html);


$dompdf->loadhtml($html); //QUESTO PER CREARE PDF CON HTML SCRITTO COME STRINGA


// $dompdf->loadhtmlFile(""); QUESTO PER CREARE PDF CON HTML SCRITTO COME FILE HTML ESTERNO

$dompdf->render();

$dompdf->addInfo("Title", "Attestato di " . $nome);

$dompdf->stream("Attestato_" . $nome . ".pdf", ["Attachment" => 0]);
// SERVE NEL CASO UNO VUOLE VISUALIZZARE PRIMA DI SCARICARLO MANUALMENTE

// $dompdf->stream("Attestato_". $nome .".pdf");