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

$nome = "Vittorio";
$cognome = "Zhang";
$data = "09/02/2022";
$luogo = "Prato";
$ore = "34";

$html = file_get_contents("./html/template.html"); //SERVE PER OTTENERE IL HTML DA UN FILE INVECE DI SCRIVERE TUTTO HTML IN STRINGA
$html = str_replace("{{ nome }}", $nome, $html);
$html = str_replace("{{ cognome }}", $cognome, $html);
$html = str_replace("{{ data }}", $data, $html);
$html = str_replace("{{ luogo }}", $luogo, $html);
$html = str_replace("{{ ore }}", $ore, $html);


 $dompdf->loadhtml($html); //QUESTO PER CREARE PDF CON HTML SCRITTO COME STRINGA


// $dompdf->loadhtmlFile(""); QUESTO PER CREARE PDF CON HTML SCRITTO COME FILE HTML ESTERNO

$dompdf->render();

$dompdf->addInfo("Title", "Attestato di ".$nome);

$dompdf->stream("Attestato_". $nome .".pdf", ["Attachment" => 0]);
// SERVE NEL CASO UNO VUOLE VISUALIZZARE PRIMA DI SCARICARLO MANUALMENTE

// $dompdf->stream("Attestato_". $nome .".pdf");