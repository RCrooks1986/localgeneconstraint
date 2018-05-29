<?php
include_once 'api-functions.php';
//---FunctionBreak---
/*Gets the coordinates for exons from ExAC using the gene identifier

$id is the ENSG gene identifier

Output is an array of the coordinates of each exon in the gene*/
//---DocumentationBreak---
function getexacexons($id)
	{
	//Get coordinates from ExAC API and convert to array
	$exonjson = getrawdatafromapi($id,"ExACGetTranscript");
	$exons = json_decode($exonjson,true);
	$exons = $exons['exons'];
	
	//Container for exon coordinates
	$coordinates = array();
	
	//Container for CDS coordinate starts
	$cdscoordinatesstart = array();
	
	foreach ($exons as $exon)
		{
		$transcriptid = $exon['transcript_id'];
		
		//Create transcript array where coordinates for that transcript are placed
		if (isset($coordinates[$transcriptid]) == false)
			{
			$coordinates[$transcriptid] = array();
			$cdscoordinatesstart[$transcriptid] = 1;
			}
		
		//Coordinates to place in output array
		$coordinatesline = array();
		$coordinatesline['startchrom'] = $exon['start'];
		$coordinatesline['stopchrom'] = $exon['stop'];
		$coordinatesline['strand'] = $exon['strand'];
		
		//Add CDS coffee
		if ($exon['feature_type'] == "CDS")
			{
			$coordinatesline['startcds'] = $cdscoordinatesstart[$transcriptid];
			$distance = $coordinatesline['stopchrom']-$coordinatesline['startchrom'];
			$coordinatesline['stopcds'] = $coordinatesline['startcds']+$distance;
			$cdscoordinatesstart[$transcriptid] = $coordinatesline['stopcds']+1;
			}
		
		array_push($coordinates[$transcriptid],$coordinatesline);
		}
	
	Return $coordinates;
	}
//---FunctionBreak---
/*Takes an exon array and returns the boundaries

$exons is the exons array

Output is an array of the exon boundaries*/
//---DocumentationBreak---
function exonboundaries($exons)
	{
	$boundaries = array();
	
	//CDSEnd is the exon boundary
	foreach ($exons as $exon)
		{
		if (isset($exon['cdsend']) == true)
			array_push($boundaries,$exon['cdsend']);
		}
	
	Return $boundaries;
	}
//---FunctionBreak---
?>