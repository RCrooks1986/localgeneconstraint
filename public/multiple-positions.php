<?php
//Sort variants by gene symbol
function variantsort($a,$b)
	{
	if ($a['GeneSymbol'] == $b['GeneSymbol'])
		{
		return 0;
		}
	return ($a['GeneSymbol'] < $b['GeneSymbol']) ? -1 : 1;
	}

//Input a 2 dimensional array containing ['GeneSymbol'] and ['Nucleotide'] elements in each element

//Include shared list of required files
include_once 'required-files.php';

//Default to an example list of variants
if (isset($variants) == false)
	{
	$variants = array();
	$variants[0] = array("GeneSymbol"=>"BRCA1","Nucleotide"=>3632);
	$variants[1] = array("GeneSymbol"=>"NF1","Nucleotide"=>2823);
	}

usort($variants,'variantsort');

$variantresults = array();

$genesymbol = "";
foreach($variants as $inputvariantdetails)
	{
	if ($inputvariantdetails['GeneSymbol'] != $genesymbol)
		{
		$genesymbol = $inputvariantdetails['GeneSymbol'];

		echo $genesymbol . "<br>";

		//Retrieve gene wide constraint data, variant and uscore data
		include 'gene-wide.php';

		//Gene wide constraint scores placed in array
		$generesults = array();
		$generesults['GeneMisConstraint'] = $constaintscores['hits'][0]['exac']['all']['mis_z'];
		$generesults['GeneSynConstraint'] = $constaintscores['hits'][0]['exac']['all']['syn_z'];
		}

	//Automatically define a nucleotide around which to search if not already specified
	if (isset($inputvariantdetails['Nucleotide']) == false)
		$inputvariantdetails['Nucleotide'] = 350;

	//Define nucleotide ranges to check
	$localconstraintresults = array();
	$localconstraintresults[0] = array("Name"=>"+/-15","Start"=>$inputvariantdetails['Nucleotide']-15,"End"=>$inputvariantdetails['Nucleotide']+15);
	$localconstraintresults[1] = array("Name"=>"+/-30","Start"=>$inputvariantdetails['Nucleotide']-30,"End"=>$inputvariantdetails['Nucleotide']+30);
	$localconstraintresults[2] = array("Name"=>"+/-60","Start"=>$inputvariantdetails['Nucleotide']-60,"End"=>$inputvariantdetails['Nucleotide']+60);
	$localconstraintresults[3] = array("Name"=>"+/-90","Start"=>$inputvariantdetails['Nucleotide']-90,"End"=>$inputvariantdetails['Nucleotide']+90);

	include 'local-regions.php';

	//Constraint results for each window placed in array
	$localresults = array("+/-15Mis"=>$localconstraintresults[0]['ConstraintMissense'],"+/-30Mis"=>$localconstraintresults[1]['ConstraintMissense'],"+/-60Mis"=>$localconstraintresults[2]['ConstraintMissense'],"+/-90Mis"=>$localconstraintresults[3]['ConstraintMissense'],"+/-15Syn"=>$localconstraintresults[0]['ConstraintSynonymous'],"+/-30Syn"=>$localconstraintresults[1]['ConstraintSynonymous'],"+/-60Syn"=>$localconstraintresults[2]['ConstraintSynonymous'],"+/-90Syn"=>$localconstraintresults[3]['ConstraintSynonymous']);

	$variantresultsline = array_merge($inputvariantdetails,$generesults,$localresults);
	array_push($variantresults,$variantresultsline);
	}

/*
Gene results can be retrieved from $variantresults
*/

//For troubleshooting for downsteam processing print these to check output
//print_r($variantresults);
?>
