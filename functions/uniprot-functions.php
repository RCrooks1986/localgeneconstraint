<?php
include_once 'api-functions.php';
//---FunctionBreak---
/*Turn a ENTS transcript ID into a UniProtKB ID

$id is the ENTS transcript identifier

Output is the UniProt accession number*/
//---DocumentationBreak---
function getuniprotaccession($id)
	{
	$id = "Ensembl:" . $id;
	$uniprotkb = getrawdatafromapi($id,"UniProtAccession");
	$uniprotkb = json_decode($uniprotkb,true);
	print_r($uniprotkb);
	}
//---FunctionBreak---
?>