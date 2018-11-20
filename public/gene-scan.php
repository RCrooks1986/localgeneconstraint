<?php
//Input $genesymbol and $checknucleotide to define the gene and which nucleotide is being checked for local constraint

//Include shared list of required files
include_once 'required-files.php';

//Retrieve gene wide constraint data, variant and uscore data
include 'gene-wide.php';

//Automatically define a nucleotide around which to search if not already specified
if (isset($checknucleotide) == false)
	$checknucleotide = 350;

//Define nucleotide ranges to check, a window of +/- 30 for each position
$localconstraintresults = array();
$sequencelength = count($cdnasequence);
$centreposition = 1;
while ($centreposition < $sequencelength)
	{
	$localconstraintresults[$centreposition] = array("Name"=>$centreposition,"Start"=>$centreposition-30,"End"=>$centreposition+30);
	$centreposition++;
	}

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
