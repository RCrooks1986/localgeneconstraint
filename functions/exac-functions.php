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
function exonsandcds($exons)
	{
	//Boundaries and CDS to output
	$boundaries = array();
	$cds = array();
	
	//ENST and exons
	$enst = array_keys($exons);
	$enst = array_shift($enst);
	$exons = array_shift($exons);
	
	//Get all Exon boundaries in the CDS
	foreach ($exons as $exon)
		{
		if ((isset($exon['startcds']) == true) AND (isset($exon['stopcds']) == true))
			array_push($boundaries,$exon['stopcds']);
		}
	
	//Get the end of the CDS
	sort($boundaries);
	$endcds = end($boundaries);
	
	//Format output as array and return
	$cds = array("ENST"=>$enst,"End"=>$endcds);
	$output = array("CDS"=>$cds,"Boundaries"=>$boundaries);
	Return $output;
	}
//---FunctionBreak---
/*Extracts ExAC constraint scores from the API at mygene.info

$ids is the array of sequence IDs from which the ENTS ID must be present

Output is an array containing the constraint score details*/
//---DocumentationBreak---
function exacconstraint($ids)
	{
	//Retrieve data from API and convert JSON format output to PHP array
	$constaintscores = getrawdatafromapi($ids['ENST'],"ConstraintMetrics");
	$constaintscores = json_decode($constaintscores,true);
	
	Return $constaintscores;
	}
//---FunctionBreak---
/*Extract ExAC variant list and returns them as an array 

$ids is the array of sequence IDs from which the ENSG ID must be present

Output is an array containing the list of variants*/
//---DocumentationBreak---
function exacconstraint($ids)
	{
	//Retrieve variants from API and convert JSON format output to PHP array
	$variants = getrawdatafromapi($ids['ENSG'],"ExACVariants");
	$variants = json_decode($variants,true);
	
	Return $variants;
	}
//---FunctionBreak---
?>