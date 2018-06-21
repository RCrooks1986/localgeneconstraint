<?php
include_once 'api-functions.php';
//---FunctionBreak---
/*Turns a gene symbol into an array with gene sequence and sequence names which can be used to appropriately query databases

$id is the gene symbol (e.g. BRCA1)

Output is an array in the format array("Symbol"=>"","ENSG"=>,"LRG"=>)*/
//---DocumentationBreak---
function getsequenceids($id)
	{
	//Get sequence identifiers in JSON format
	$json = getrawdatafromapi($id,"EnsemblID");
	$json = json_decode($json,true);
	
	$output = array("Symbol"=>$id);
	
	foreach($json as $identifier)
		{
		//Check that ID is set
		if (isset($identifier['id']) == true)
			{
			//Determine if ENSG number or LRG number
			if (strpos($identifier['id'],"ENSG") !== false)
				$output['ENSG'] = $identifier['id'];
			elseif (strpos($identifier['id'],"LRG") !== false)
				$output['LRG'] = $identifier['id'];
			}
		}
	
	Return $output;
	}
//---FunctionBreak---
/*Gets a full length sequence from Ensembl

$id is either the ENST number, or an array containing it identified by ['ENST']

Output is the sequence as plain text*/
//---DocumentationBreak---
function getensemblsequencefromenst($id)
	{
	//Get ENSG ID from array if needed
	if (is_array($id) == true)
		$id = $id['ENST'];
	
	$sequence = getrawdatafromapi($id,"EnsemblSequenceFromENST");
	
	Return $sequence;
	}
//---FunctionBreak---
/*Gets a full length sequence from Ensembl from the CDS identifier and the CDS coordinates

$id is either the ENSG number, or an array containing it identified by ['ENSG']

Output is the sequence as plain text*/
//---DocumentationBreak---
function getensemblsequencefromcds($id,$range)
	{
	//Get ENSG ID from array if needed
	if (is_array($id) == true)
		$id = $id['ENSG'];
	
	$sequence = getrawdatafromapi($id,"EnsemblSequence");
	
	Return $sequence;
	}
//---FunctionBreak---
?>