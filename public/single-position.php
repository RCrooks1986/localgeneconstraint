<?php
//Input $genesymbol and $checknucleotide to define the gene and which nucleotide is being checked for local constraint

//Include shared list of required files
include_once 'required-files.php';

//Retrieve gene wide constraint data, variant and uscore data
include 'gene-wide.php';

//Automatically define a nucleotide around which to search if not already specified
if (isset($checknucleotide) == false)
	$checknucleotide = 350;

//Define nucleotide ranges to check
$localconstraintresults = array();
$localconstraintresults[0] = array("Name"=>"+/-15","Start"=>$checknucleotide-15,"End"=>$checknucleotide+15);
$localconstraintresults[1] = array("Name"=>"+/-30","Start"=>$checknucleotide-30,"End"=>$checknucleotide+30);
$localconstraintresults[2] = array("Name"=>"+/-60","Start"=>$checknucleotide-60,"End"=>$checknucleotide+60);
$localconstraintresults[2] = array("Name"=>"+/-60","Start"=>$checknucleotide-90,"End"=>$checknucleotide+90);

include 'local-regions.php';

/*
Global (gene wide) result can be retrieved by calling $globalresults
Local (by range specified) results can be retrieved by calling $localconstraintresults
*/

//For troubleshooting for downsteam processing print these to check output
//print_r($globalresults);
//echo "<br>";
//print_r($localconstraintresults);
?>
