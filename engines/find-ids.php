<?php
//Default user inputs
if (isset($userensg) == false)
  $userensg = '';
if (isset($userenst) == false)
  $userenst = '';
if (isset($useruniprot) == false)
  $useruniprot = '';

//Include API functions and the pre installed gene IDs
include_once 'gene-ids.php';

if (isset($genelist[$genesymbol]) == true)
  {
  $ids = $genelist[$genesymbol];
  }
else
  {
  $json = getrawdatafromapi($genesymbol,"EnsemblID");
  $json = json_decode($json,true);

  $ids = array("GeneSymbol"=>$genesymbol);

  foreach($json as $identifier)
    {
    //Check that ID is set
    if (isset($identifier['id']) == true)
      {
      //Determine if ENSG number or LRG number
      if (strpos($identifier['id'],"ENSG") !== false)
        $ids['ENSG'] = $identifier['id'];
      }
    }

  //Get coordinates from ExAC API and convert to array
  $exonjson = getrawdatafromapi($ids['ENSG'],"ExACGetTranscript");
  $exons = json_decode($exonjson,true);
  $exons = $exons['exons'];

  //Get list of transcripts and find unique transcripts
  $transcripts = array();
  foreach ($exons as $exon)
    {
    if (in_array($exon['transcript_id'],$transcripts) === false)
      array_push($transcripts,$exon['transcript_id']);
    }

  if (count($transcripts) == 1)
    $ids['ENST'] = $transcripts[0];
  }

//Get IDs from use input if available
if ($userenst != '')
  $ids['ENST'] = $userenst;
if ($userensg != '')
  $ids['ENSG'] = $userensg;
if ($useruniprot != '')
  $ids['UniProt'] = $useruniprot;

//Make sure there are some variables specified
if (isset($ids['GeneSymbol']) == false)
  $ids['GeneSymbol'] = '';
if (isset($ids['ENST']) == false)
  $ids['ENST'] = '';
if (isset($ids['ENSG']) == false)
  $ids['ENSG'] = '';
if (isset($ids['UniProt']) == false)
  $ids['UniProt'] = '';
?>
