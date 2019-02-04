<?php
//Input $genesymbol and $checknucleotide to define the gene and which nucleotide is being checked for local constraint
if ((isset($genesymbol) == false) OR ((isset($checknucleotide) == false)))
	{
	$genesymbol = "COL1A1";
	$checknucleotide = 1679;
	$testingsingleposition = true;
	}
else
	$testingsingleposition = false;

//Include shared list of required files
include_once 'required-files.php';

//Retrieve gene wide constraint data, variant and uscore data
include 'gene-wide.php';

//Automatically define a nucleotide around which to search if not already specified
if (isset($checknucleotide) == false)
	$checknucleotide = 350;

//Get protein domains
include 'protein-domains.php';

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

//Also identify any domains in which the variant is in
foreach ($uniprotdomains as $domain)
	{
	if (($domain['StartDNA'] <= $checknucleotide) AND ($domain['EndDNA'] >= $checknucleotide))
		{
		//Format start, end and name of protein
		$domainstart = $domain['StartDNA'];
		$domainend = $domain['EndDNA'];
		$domainname = "Domain: " . $domain['StartProtein'] . "-" . $domain['EndProtein'];

		$domain = array("Name"=>$domainname,"Start"=>$domainstart,"End"=>$domainend);
		array_push($localconstraintresults,$domain);
		}
	}



include 'local-regions.php';

/*
Global (gene wide) result can be retrieved by calling $globalresults
Local (by range specified) results can be retrieved by calling $localconstraintresults
*/

//For troubleshooting for downsteam processing print these to check output
if ($testingsingleposition == true)
	{
	echo "Global Results:<br>";
	print_r($globalresults);
	echo "<br>";
	echo "Local Results:<br>";
	print_r($localconstraintresults);
	}
?>
