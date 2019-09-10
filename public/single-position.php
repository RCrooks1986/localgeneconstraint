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

//Default start and end positions for search to 0
if (isset($startrange) == false)
	$startrange = 0;
if (isset($endrange) == false)
	$endrange = 0;

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
$localconstraintranges = array();

if (($startrange == 0) OR ($endrange == 0))
	{
	$localconstraintranges[0] = array("Name"=>"+/-15","Start"=>$checknucleotide-15,"End"=>$checknucleotide+15);
	$localconstraintranges[1] = array("Name"=>"+/-30","Start"=>$checknucleotide-30,"End"=>$checknucleotide+30);
	$localconstraintranges[2] = array("Name"=>"+/-60","Start"=>$checknucleotide-60,"End"=>$checknucleotide+60);
	$localconstraintranges[3] = array("Name"=>"+/-90","Start"=>$checknucleotide-90,"End"=>$checknucleotide+90);

	//Identify the exon which a variant is contained in
	foreach ($exons as $exon)
		{
		if (($exon['Start'] <= $checknucleotide) AND ($exon['End'] >= $checknucleotide))
			{
			$exonstart = $exon['Start'];
			$exonend = $exon['End'];
			}
		}

	$localconstraintranges[4] = array("Name"=>"Exon","Start"=>$exonstart,"End"=>$exonend);

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
			array_push($localconstraintranges,$domain);
			}
		}
	}
else
	{
	$rangename = "Range: " . $startrange . "-" . $endrange;
	$localconstraintranges[0] = array("Name"=>$rangename,"Start"=>$startrange,"End"=>$endrange);
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
	echo "Local Results (Raw):<br>";
	print_r($rawlocal);
	echo "Local Results (Normalised):<br>";
	print_r($normalisedlocal);
	}
?>
