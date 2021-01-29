<?php
$currentdirectory = getcwd();
$removedirs = array("/pages","/engines");
$currentdirectory = str_replace($removedirs,"",$currentdirectory);
$functiondirectory = $currentdirectory . "/functions/";

//List of functions required
$functionfiles = array("api","dna","uniprot","ensembl","mutationrate","exac","bruteforce","cycleloop");
foreach($functionfiles as $file)
  {
  include_once $functiondirectory . $file . "-functions.php";
  }
?>
