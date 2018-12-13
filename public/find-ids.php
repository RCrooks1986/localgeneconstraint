<?php
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
  }
?>
