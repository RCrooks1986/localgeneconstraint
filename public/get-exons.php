<?php
//Test gene ID to use if no gene ID ENST is set
if (isset($ids['ENST']) == false)
  $ids = array("ENST"=>"ENST00000471181");

//Get array of exons with genomic coordinates from Ensembl API using ENST ID
$restapiurl = "https://rest.ensembl.org/map/cds/" . $ids['ENST'] . "/1..1000?content-type=application/json";
$apiexons = file_get_contents($restapiurl);
$apiexons = json_decode($apiexons,true);
$apiexons = $apiexons['mappings'];

if ($apiexons[0]['strand'] == '-1')
  array_reverse($apiexons);

//Format array into CDS coordinates
$exons = array();
$start = 1;
foreach ($apiexons as $exon)
  {
  $difference = $exon['end']-$exon['start'];
  $end = $start+$difference;
  $exon = array("Start"=>$start,"End"=>$end);
  array_push($exons,$exon);
  $start = $end+1;
  }
?>
