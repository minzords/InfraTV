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
    $programme->addCountry(new Tv\Elements\Country('Russie', 'ru'));
    $programme->language =  new Tv\Elements\Language('fr');

    switch($data[$i][3]){
        case "JOURNAL D'ACTUALITE":
            $programme->premiere = new Tv\Elements\Premiere('Inedit');
            $programme->lastChance = new Tv\Elements\LastChance('Dernière diffusion');
            break;
        case "DOCUMENTAIRE":
            $programme->addIcon(new Tv\Elements\Icon('https://mf.b37mrtl.ru/french/images/2018.01/thumbnail/5a6f431709fac2e5558b4567.png'));
            break;
        case "L'ECHIQUIER MONDIAL":
            $programme->addIcon(new Tv\Elements\Icon('https://mf.b37mrtl.ru/french/images/2023.05/original/646e2dd06f7ccc66d1525226.png'));
            break;
        case "POLIT'MAG":
            $programme->addIcon(new Tv\Elements\Icon('https://mf.b37mrtl.ru/french/images/2022.06/original/629b56716f7ccc25e00b8f80.png'));
            break;
        case "LA GRANDE INTERVIEW":
            $programme->addIcon(new Tv\Elements\Icon('https://mf.b37mrtl.ru/french/images/2019.04/original/5ca3568809fac2a7758b4567.PNG'));
            break;
        case "AFRICONNECT":
            $programme->addIcon(new Tv\Elements\Icon('https://mf.b37mrtl.ru/french/images/2021.09/original/614a6b5a87f3ec39ff641e09.PNG'));
            break;
        default:
            $programme->addIcon(new Tv\Elements\Icon('https://i.f1g.fr/media/eidos/orig/2019/10/29/XVM5b3bd926-f975-11e9-ba50-e460b16f9313.jpg'));
    }

    $tv->addProgramme($programme);
}

$tv->addChannel($channel);

$xmltv = new XmlTv();
$xml = $xmltv->generate($tv);
file_put_contents('epg.xml', $xml);