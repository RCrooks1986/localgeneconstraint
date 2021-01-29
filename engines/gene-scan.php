<?php
//Input $genesymbol to define the gene being subject to window scanning
//Input $window to define the +/- window size, default is 30

if (isset($window) == false)
	$window = 30;

//Include shared list of required files
include_once 'localconstraint-required-files.php';

//Retrieve gene wide constraint data, variant and uscore data
include 'gene-wide.php';

$checknucleotide = 4;
while ($checknucleotide < )

//Define nucleotide ranges to check
$localconstraintresults = array();
$localconstraintresults[0] = array("Name"=>"+/-15","Start"=>$checknucleotide-15,"End"=>$checknucleotide+15);
$localconstraintresults[1] = array("Name"=>"+/-30","Start"=>$checknucleotide-30,"End"=>$checknucleotide+30);
$localconstraintresults[2] = array("Name"=>"+/-60","Start"=>$checknucleotide-60,"End"=>$checknucleotide+60);
$localconstraintresults[3] = array("Name"=>"+/-90","Start"=>$checknucleotide-90,"End"=>$checknucleotide+90);

//Identify the exon which a variant is contained in
foreach ($exons as $exon)
	{
	if (($exon['Start'] <= $checknucleotide) AND ($exon['End'] >= $checknucleotide))
		{
		$exonstart = $exon['Start'];
		$exonend = $exon['End'];
		}
	}

$localconstraintresults[4] = array("Name"=>"Exon","Start"=>$exonstart,"End"=>$exonend);

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
