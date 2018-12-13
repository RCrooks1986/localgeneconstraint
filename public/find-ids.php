<?php
//Default to BRCA1
if (isset($genesymbol) == false)
  $genesymbol = "BRCA1";

//Include API functions and the pre installed gene IDs
include_once '../functions/api-functions.php';
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
?>
