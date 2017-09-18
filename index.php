<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require 'IfoodHackedApi.php';

$restaurantes = array(
	'canto' => array(
		'Referer' 		=> 'restaurante-do-canto-jardim-higienopolis',
		'RestauranteId' => '16232',
		'Food' 	=> '19688451',
	),

	'biggs' => array(
		'Referer' 		=> 'biggs-lanches-av-maringa-vitoria',
		'RestauranteId' => '16269',
		'Food' 	=> '20340173',
	),
);

$res = $_GET['res'] ? $_GET['res'] : 'canto';

$api = new IfoodHackedApi($restaurantes[$res]);

$responseHtml = $api->apiExec();

if(strpos($responseHtml, 'Incapsula_Resource') !== false || $responseHtml == false){
	sleep(1);
	header("Location: /canto");
	die;
}

$d = new DOMDocument();
$d->loadHTML($responseHtml);

$xpathsearch = new DOMXPath($d);
$nodesCarnes = $xpathsearch->query("/html/body//ul[@class='ls-0 options-list']/li/input[@name='descriptionGarnishItem']");
$laCarnes = array();

$nodesAcompanhamentos = $xpathsearch->query("/html/body//ul[@class='ls-1 options-list']/li/input[@name='descriptionGarnishItem']");
$laAcompanhamentos = array();

$nodesSaladas = $xpathsearch->query("/html/body//ul[@class='ls-2 options-list']/li/input[@name='descriptionGarnishItem']");
$laSaladas = array();

foreach ($nodesCarnes as $i => $node) {
    $laCarnes[] = $node->getAttribute('value');
}

foreach ($nodesAcompanhamentos as $i => $node) {
    $laAcompanhamentos[] = $node->getAttribute('value');
}

foreach ($nodesSaladas as $i => $node) {
    $laSaladas[] = $node->getAttribute('value');
}



// TEMPLATE DO CANTO
if($res == 'canto'){
	$marmota_string[] = "@here Marmoooota!";
	$marmota_string[] = "Principal: Escolha 1 para tamanho baby ou 2 para tamanhos mini, média, grande";
	$marmota_string[] = implode($laCarnes, ' | ');;
	$marmota_string[] = "Acompanhamento: Escolha 2";
	$marmota_string[] = implode($laAcompanhamentos, ' | ');
	$marmota_string[] = "Salada";
	$marmota_string[] = implode($laSaladas, ' | ');

	echo implode($marmota_string, '<br>');
}


// TEMPLATE DO BIGGS
if($res == 'biggs'){
	$marmota_string[] = "@here Marmoooota!";
	$marmota_string[] = "BIGGS - Prato principal com possbilidade de trocar a carne";
	$marmota_string[] = $laCarnes[0];
	$marmota_string[] = "TROCAS";
	$marmota_string[] = implode($laAcompanhamentos, ' | ');

	echo implode($marmota_string, '<br>');
}
