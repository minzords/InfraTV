<?php

use XmlTv\Tv;
use XmlTv\XmlTv;
use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__.'/vendor/autoload.php';

$tv = new Tv();

$channel = new Tv\Channel('rt.fr');
$channel->addDisplayName(new Tv\Elements\DisplayName('RTFrance', 'fr'));
$channel->addIcon(new Tv\Elements\Icon('https://upload.wikimedia.org/wikipedia/fr/thumb/9/9e/RT_France.svg/1200px-RT_France.svg.png', '200', '200'));

$spreadsheet = IOFactory::load('rt.xlsx');
$worksheet = $spreadsheet->getActiveSheet();
$data = $worksheet->toArray();

for ($i = 2; $i < count($data); $i++) {
    // Date de Début
    $date_str = trim($data[$i][0] . " " . $data[$i][1]);
    $date = DateTime::createFromFormat('d/n/y H:i', $date_str);
    $debut = $date->format('YmdHis +0300');

    // Date de fin
    $date_str = trim($data[$i][0] . " " . $data[$i][2]);
    $date = DateTime::createFromFormat('d/n/y H:i', $date_str);
    $fin = $date->format('YmdHis +0300');

    $programme = new Tv\Programme('rt.fr', $debut, $fin);
    $programme->addTitle(new Tv\Elements\Title($data[$i][3], 'fr'));
    $programme->addDescription(new Tv\Elements\Desc(strval($data[$i][6]), 'fr'));
    //$programme->addCategory(new Tv\Elements\Category(".", 'fr'));
    $programme->addIcon(new Tv\Elements\Icon('https://i.f1g.fr/media/eidos/orig/2019/10/29/XVM5b3bd926-f975-11e9-ba50-e460b16f9313.jpg'));
    $tv->addProgramme($programme);
}

$tv->addChannel($channel);
$tv->addProgramme($programme);

$xmltv = new XmlTv();
$xml = $xmltv->generate($tv);
file_put_contents('epg.xml', $xml);