<?php
//---FunctionBreak---
/*This function retrieves data from a specified REST-API and a sequence ID

$id is the sequence ID in that database
$url is the URL where the API is located
$url can also be the string UniProt or ExAc which are the pre-programmed APIs

Currently supported APIs are the ExAc and UniProt APIs

Output data is in JSON format, for which there is a built in PHP function to convert to an array*/
//---DocumentationBreak---
function getfromapi($id,$url)
	{
	if ($url == "UniProt")
		$url = "https://www.ebi.ac.uk/proteins/api/features/<id>";
	elseif ($url == "ExAC")
		$url = "http://exac.hms.harvard.edu/rest/awesome?query=<id>&service=variants_in_gene";
	
	//Use URL to retrieve contents and return
	$resturl = str_replace("<id>",$id);
	$resttext = file_get_contents($resturl);
	
	Return $resttext;
	}
//---FunctionBreak---
?>