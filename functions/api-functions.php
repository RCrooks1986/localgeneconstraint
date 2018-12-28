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
		//id is the UniProt Accession
		$url = "https://www.ebi.ac.uk/proteins/api/features/" . $id . "&format=json";
	elseif ($url == "UniProtDetails")
		//Get UniProtKB accession number
		$url = "https://www.ebi.ac.uk/proteins/api/proteins?offset=0&size=1&gene=" . $id . "&organism=Human&format=json";
	elseif ($url == "ExACVariants")
		//Get variant list from ExAC
		$url = "http://exac.hms.harvard.edu/rest/awesome?query=" . $id . "&service=variants_in_gene";
	elseif ($url == "ExACGetTranscript")
		//Get transcript features from ExAC
		$url = "http://exac.hms.harvard.edu/rest/gene/transcript/" . $id . "";
	elseif ($url == "EnsemblSequenceFromENST")
		//Get sequence from Ensembl
		$url = "https://rest.ensembl.org/sequence/id/" . $id . "?type=cds&content-type=text/plain";
	elseif ($url == "EnsemblID")
		//Get Ensemble gene ID
		$url = "https://rest.ensembl.org/xrefs/symbol/homo_sapiens/" . $id . "?content-type=application/json";
	elseif ($url == "EnsemblSequenceFromCoords")
		//Get an Ensembl sequence from the coordinates
		$url = "https://rest.ensembl.org/sequence/region/human/" . $id . "?content-type=text/plain";
	elseif ($url == "SequenceFromCDS")
		//Get an Ensembl sequence from a CDS indentifier
		$url = "https://rest.ensembl.org/sequence/id/" . $id . "?type=cds&content-type=text/plain";
	elseif ($url == "ConstraintMetrics")
		//Get ExAC constaint metrics using an ENST transcript identifier
		$url = "http://mygene.info/v3/query?q=exac.transcript:" . $id . "&fields=exac";

	//$brcaid = "17:43115738..43115779:-1";
	//$brcaid = "ENST00000471181/1..122";

	$resttext = file_get_contents($url);

	Return $resttext;
	}
//---FunctionBreak---
?>
