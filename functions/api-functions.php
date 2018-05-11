<?php
//---FunctionBreak---
/*This function retrieves data from a specified REST-API and a sequence ID

$id is the sequence ID in that database
$url is the URL where the API is located
$url can also be the string UniProt or ExAc which are the pre-programmed APIs

Currently supported APIs are the ExAc, Ensembl and UniProt APIs*/
//---DocumentationBreak---
function getrawdatafromapi($id,$url)
	{
	if ($url == "UniProtFeatures")
		//Get features from UniProt
		$url = "https://www.ebi.ac.uk/proteins/api/features/<id>";
	elseif ($url == "UniProtAccession")
		//Get UniProtKB accession number
		$url = "https://www.ebi.ac.uk/proteins/api/proteins/<id>?offset=0&size=100";
	elseif ($url == "ExACVariants")
		//Get variant list from ExAC
		$url = "http://exac.hms.harvard.edu/rest/awesome?query=<id>&service=variants_in_gene";
	elseif ($url == "ExACGetTranscript")
		//Get transcript features from ExAC
		$url = "http://exac.hms.harvard.edu/rest/gene/transcript/<id>";
	elseif ($url == "EnsemblSequence")
		//Get sequence from Ensembl
		$url = "https://rest.ensembl.org/sequence/id/<id>?content-type=text/plain";
	elseif ($url == "EnsemblID")
		//Get Ensemble gene ID
		$url = "https://rest.ensembl.org/xrefs/symbol/homo_sapiens/<id>?content-type=application/json";
	elseif ($url == "EnsemblSequenceFromCoords")
		$url = "https://rest.ensembl.org/sequence/region/human/<id>?content-type=text/plain"
	elseif ($url == "SequenceFromCDS")
		$url = "https://rest.ensembl.org/sequence/id/<id>?type=cds&content-type=text/plain";
	
	//$brcaid = "17:43115738..43115779:-1";	
	//$brcaid = "ENST00000471181/1..122";
		
	//Use URL to retrieve contents and return
	$url = str_replace("<id>",$id,$url);
	$resttext = file_get_contents($url);
	
	Return $resttext;
	}
//---FunctionBreak---
?>