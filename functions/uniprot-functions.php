<?php
include_once 'api-functions.php';
//---FunctionBreak---
/*Get sequence and domain information from UniProt using a gene symbol

$id is the Gene symbol

Output is an array with UniProt ['ID'], ['Sequence'] and ['Domains']*/
//---DocumentationBreak---
function getuniprotdetails($id)
	{
	//Retrieve JSON data from UniProt API
	$uniprotkb = getrawdatafromapi($id,"UniProtDetails");
	$uniprotkb = json_decode($uniprotkb,true);
	$uniprotkb = $uniprotkb[0];
	
	//Get UniProt ID
	$uniprotid = $uniprotkb['accession'];
	
	//Get Amino Acid Sequence
	$sequence = $uniprotkb['sequence']['sequence'];
	
	//Get sequence features and domains
	$features = $uniprotkb['features'];
	$featureslist = array();	
	foreach ($uniprotkb['features'] as $feature)
		{
		//Move feature information to new array and add to output array
		$featureoutput = array();
		$featureoutput['type'] = $feature['type'];
		$featureoutput['category'] = $feature['category'];
		if (isset($feature['description']) == true)
			$featureoutput['description'] = $feature['description'];
		else
			$featureoutput['description'] = "";
		$featureoutput['start'] = $feature['begin'];
		$featureoutput['end'] = $feature['end'];
		
		array_push($featureslist,$featureoutput);
		}
	
	$output = array("UniProtID"=>$uniprotid,"Sequence"=>$sequence,"Features"=>$featureslist);
	Return $output;
	}
//---FunctionBreak---
?>