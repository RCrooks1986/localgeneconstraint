<?php
$id = "P10275";
//Get the length of the ID, which is important for reading the GFF file
$idlen = strlen($id);

//Get the GFF file from UniProt
$uniproturl = "https://www.uniprot.org/uniprot/" . $id . ".gff";
$gff = file_get_contents($uniproturl);

//Get lines from GFF file
$gff = explode("\n",$gff);

//Get UniProt domains
$uniprotdomains = array();
foreach ($gff as $line)
  {
  if (substr($line,0,$idlen) == $id)
    {
    $line = explode("\t",$line);

    //Start and end of the region
    $startprotein = $line[3];
    $endprotein = $line[4];
    $regionsize = $endprotein-$startprotein+1;

    //Add domain to array if it is allowed
    $notdomains = array("Alternative sequence","Chain");
    if (($regionsize > 1) AND (in_array($line[2],$notdomains) == false))
      {
      $startdna = ($startprotein*3)-2;
      $enddna = $endprotein*3;

      $domain = array("StartDNA"=>$startdna,"EndDNA"=>$enddna,"StartProtein"=>$startprotein,"EndProtein"=>$endprotein);
      array_push($uniprotdomains,$domain);
      }
    }
  }

print_r($uniprotdomains);
?>
