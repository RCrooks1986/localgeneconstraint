<?php
//Input $genesymbol to define the gene being subject to window scanning
//Input $window to define the +/- window size, default is 30

if (isset($window) == false)
	$window = 30;

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
	$localconstraintresults[$centreposition] = array("Name"=>$centreposition,"Start"=>$centreposition-$window,"End"=>$centreposition+$window);
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
