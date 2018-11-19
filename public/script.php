<?php
//Include files containing shared functions which can also be used by other scripts
include '../functions/api-functions.php';
include '../functions/dna-functions.php';
include '../functions/ensembl-functions.php';
include '../functions/uniprot-functions.php';
include '../functions/exac-functions.php';
include '../functions/mutationrate-functions.php';
include '../functions/bruteforce-functions.php';
include '../functions/cycleloop-functions.php';

//Get gene symbol as user input
$genesymbol = "BRCA1";

//Get constraint range as user input
//There will be a variety of different constraint ranges available in this section
$nucrangestart = 200;
$nucrangeend = 500;

echo "<p>Input Parameters:<br>";
echo "Gene Symbol: " . $genesymbol . "<br>";
echo "Start: " . $nucrangestart . "<br>";
echo "End: " . $nucrangeend . "</p>";

//Get the ENSG and LRG IDs from the gene symbol input which can be used for querying other functions
$ids = getsequenceids($genesymbol);

//Get the exons from ExAc by querying with the ENSG sequence using the getexacexons function
//ExAc is used as the source of the exons as this makes it compatible with the missense variants data
$exons = getexacexons($ids['ENSG']);

//Get the exon boundaries and ENST ID from the exons array
$exonboundaries = exonsandcds($exons);
$ids['ENST'] = $exonboundaries['CDS']['ENST'];

//Get constaint scores for this gene from the API
//Reform this function so that it gets a constraint table, z scores and adjustment factors and exports them as an array
$constaintscores = exacconstraint($ids);

$totalexpectedmissense = $constaintscores['hits'][0]['exac']['all']['exp_mis'];
$totalexpectedsynonymous = $constaintscores['hits'][0]['exac']['all']['exp_syn'];
$totalobservedmissense = $constaintscores['hits'][0]['exac']['all']['n_mis'];
$totalobservedsynonymous = $constaintscores['hits'][0]['exac']['all']['n_syn'];
$zscoremissense = ($totalexpectedmissense-$totalobservedmissense)/sqrt($totalexpectedmissense);
$zscoresynonymous = ($totalexpectedsynonymous-$totalobservedsynonymous)/sqrt($totalexpectedsynonymous);
$adjustmissense = $constaintscores['hits'][0]['exac']['all']['mis_z']/$zscoremissense;
$adjustsynonymous = $constaintscores['hits'][0]['exac']['all']['syn_z']/$zscoresynonymous;

//Get the gene sequence from the CDS identified by the ENST ID
$sequence = getensemblsequencefromenst($ids);

//Turn sequence into array in order to process it as a loop
$sequence = str_split($sequence);

//Define which position in the codon, starts at 1, will loop back to 1 once past 3
$codonposition = 1;
//Define sequence position, starts at 1
$sequenceposition = 1;

//Variable to denote where the stop codon is, do not change stop codons
$endcodon = count($sequence)-2;

//Variables to store the total u scores for missense and synonymous variation in gene
$totalmissense = 0;
$totalsynonymous = 0;

//Calculate the liklihood of missense and nonsense variants are each nucleotide
$sequencenucleotides = array();
foreach($sequence as $currentkey=>$currentnucleotide)
	{
	//Check that the sequence position is not near a splice site using the checksplice function and the $exonboundaries['Boundaries'] array
	//Also for if statement check that the sequence is not at the start or stop codon, as these would not be missense variants
	$splicecheck = checksplice($sequenceposition,$exonboundaries['Boundaries']);
	if (($splicecheck == false) AND ($sequenceposition > 3) AND ($sequenceposition < $endcodon))
		{
		//Variables to store missense and synonymous mutation rate scores at this sequence position
		$missense = 0;
		$synonymous = 0;

		//Calculate U values for each possible change using the U scores function and the adjacent residues
		$trinucleotide = $sequence[$currentkey-1] . $sequence[$currentkey] . $sequence[$currentkey+1];
		$uvalues = uvalue($trinucleotide);

		//Generate current codon and new codon template through refence to the current positions
		if ($codonposition == 1)
			{
			$currentcodon = $currentnucleotide . $sequence[$currentkey+1] . $sequence[$currentkey+2];
			$codontemplate = "X" . $sequence[$currentkey+1] . $sequence[$currentkey+2];
			}
		elseif ($codonposition == 2)
			{
			$currentcodon = $sequence[$currentkey-1] . $currentnucleotide . $sequence[$currentkey+1];
			$codontemplate = $sequence[$currentkey-1] . "X" . $sequence[$currentkey+1];
			}
		elseif ($codonposition == 3)
			{
			$currentcodon = $sequence[$currentkey-2] . $sequence[$currentkey-1] . $currentnucleotide;
			$codontemplate = $sequence[$currentkey-2] . $sequence[$currentkey-1] . "X";
			}

		//Identify the current amino acid for identifying whether the variant is a missense or a synonymous variant
		$currentaminoacid = translatecodon($currentcodon);

		foreach ($uvalues as $variant=>$uvalue)
			{
			//Produce variant and identify the new amino acid produced
			$variantcodon = str_replace("X",$variant,$codontemplate);
			$variantaminoacid = translatecodon($variantcodon);

			//echo $variantcodon . " = " . $variantaminoacid . " ";

			//Add U score to missense or synonymous nucleotide position score
			if (($currentaminoacid != $variantaminoacid) AND ($variantaminoacid != "*"))
				$missense = $missense+$uvalue['U'];
			elseif (($currentaminoacid == $variantaminoacid) AND ($variantaminoacid != "*"))
				$synonymous = $synonymous+$uvalue['U'];
			}

		//Record U scores in by position array and in the total scores array
		$positionuscores = array("UScoreMissense"=>$missense,"UScoreSynonymous"=>$synonymous);
		$sequencenucleotides[$sequenceposition] = $positionuscores;
		$totalmissense = $totalmissense+$missense;
		$totalsynonymous = $totalsynonymous+$synonymous;
		}

	//Increment the codon position counter and the sequence position counter
	$codonposition = frameinc($codonposition,"123");
	$sequenceposition++;
	}

//This should be earlier and called if looking at protein domain
//$uniprot = getuniprotdetails($genesymbol);

echo "<p>ExAC Parameters (Summary Table)<br>";
echo "Expected Missense: " . $totalexpectedmissense . "<br>";
echo "Expected Synonymous: " . $totalexpectedsynonymous . "<br>";
echo "Total Missense: " . $totalmissense . "<br>";
echo "Total Synonymous: " . $totalsynonymous . "<br>";
echo "Z Score Missense: " . $zscoremissense . "<br>";
echo "Z Score Synonymous : " . $zscoresynonymous . "<br>";
echo "Adjustment Factor Missense: " . $adjustmissense . "<br>";
echo "Adjustment Factor Synonymous: " . $adjustsynonymous . "</p>";

//Get list of variants and the positions they're in from ExAC
$variantlist = exacvariants($ids);

//Filter variants to only include variants that are missense or synonymous
//May also try filtering this list for quality to see if this can be used to remove brute force step later
foreach ($variantlist as $variant)
	{
	//Variants are only included if they are missense variants or synonymous variants
	if ((($variant['major_consequence'] == 'missense_variant') OR ($variant['major_consequence'] == 'synonymous_variant')) AND ($variant['allele_freq'] < 0.001))
		{
		//Extract position from the HGVSc array element in order to identify where a unique variant occurs
		$hgvsc = $variant['HGVSc'];

		//Define where to start and stop retrieving integers to identify a position from a HGVSc identifier
		if ((strpos($variant['HGVSc'],"del") !== false) AND (strpos($variant['HGVSc'],"ins") !== false) AND (strpos($variant['HGVSc'],"_") !== false))
			{
			//Start getting numbers between "_" and "d" if this is an del ins variant
			$opens = array("_");
			$closes = array("d");
			}
		else
			{
			//Start getting numbers between "." and a nucleotide if this is an del ins variant
			$opens = array(".");
			$closes = array("A","C","G","T");
			}

		//Parameters to begin extracting integer position
		$position = str_split($variant['HGVSc']);
		$extracting = false;
		//Loop to find each character and extract only the integer that refers to the position of the variant
		foreach ($position as $positionkey=>$character)
			{
			if ($extracting == false)
				unset($position[$positionkey]);

			if (in_array($character,$opens) == true)
				$extracting = true;
			elseif (in_array($character,$closes) == true)
				{
				$extracting = false;
				unset($position[$positionkey]);
				}
			}

		//Turn the position into a numerical value
		$position = implode("",$position);

		//Define variant type to record in sequence array
		if ($variant['major_consequence'] == 'missense_variant')
			$varianttype = "VariantsMissense";
		elseif ($variant['major_consequence'] == 'synonymous_variant')
			$varianttype = "VariantsSynonymous";

		if (isset($sequencenucleotides[$position]) == true)
			{
			//Assign number of variants to the position based on the variant location retrieved from ExAC
			if (isset($sequencenucleotides[$position][$varianttype]) == true)
				$sequencenucleotides[$position][$varianttype] = $sequencenucleotides[$position][$varianttype]+1;
			else
				$sequencenucleotides[$position][$varianttype] = 1;
			}
		}
	}

//Get the total count of all variants and U scores
$totalvariants = subsetuscoreandvariant($sequencenucleotides);

echo "<p>Variant counts and U scores<br>";
echo "U Score Missense: " . $totalvariants['UScoreMissense'] . "<br>";
echo "U Score Synonymous: " . $totalvariants['UScoreSynonymous'] . "<br>";
echo "Variants Missense: " . $totalvariants['VariantsMissense'] . "<br>";
echo "Variants Synonymous: " . $totalvariants['VariantsSynonymous'] . "<br>";

//This section can possibly be removed
//Rescale expected number of variants for whole sequence based on observed values in the variant location data from ExAC, the Z scores, and the brute force algorithm.
//Parameters for deviation range to brute force, current is 25% either way, but may be changed in future
$deviationrange = 0.25;
$topdeviation = 1+$deviationrange;
$bottomdeviation = 1-$deviationrange;

//Range parameters to use in the brute force algorithm
$minsyn = $totalexpectedsynonymous*$bottomdeviation;
$maxsyn = $totalexpectedsynonymous*$topdeviation;
$minmis = $totalexpectedmissense*$bottomdeviation;
$maxmis = $totalexpectedmissense*$topdeviation;

//Run brute force algorithm to recalculate expected scores
$estimatedexpectedmissense = brutecalculatee($zscoremissense,$totalvariants['VariantsMissense'],$minmis,$maxmis);
$estimatedexpectedsynonymous = brutecalculatee($zscoresynonymous,$totalvariants['VariantsSynonymous'],$minsyn,$maxsyn);

//Retrieve U Scores and Variant frequenies for a given range
//$subsetvariantsandscores = subsetuscoreandvariant($sequencenucleotides);
$protstart = 165;
$protfinish = 193;
$nucstart = ($protstart*3)-2;
$nucfinish = ($protfinish*3);
$subsetvariantsandscores = subsetuscoreandvariant($sequencenucleotides,$nucrangestart,$nucrangeend);

echo "<p>Expected variant counts: <br>";
echo "Missense: " . $estimatedexpectedmissense . "<br>";
echo "Synonymous: " . $estimatedexpectedsynonymous . "</p>";

//Find the percentage of the U score contributed by the given region
$subsetmissenseuscorespercent = ($subsetvariantsandscores['UScoreMissense']/$totalvariants['UScoreMissense'])*100;
$subsetsynonymoususcorespercent = ($subsetvariantsandscores['UScoreSynonymous']/$totalvariants['UScoreSynonymous'])*100;

echo "<p>Percentage of U score in subset: <br>";
echo "Missense: " . $subsetmissenseuscorespercent . "<br>";
echo "Synonymous: " . $subsetsynonymoususcorespercent . "</p>";

//Calculate local expected frequencies from the percentage of U score and the overall expected frequency
$localmissenseexpected = ($estimatedexpectedmissense/100)*$subsetmissenseuscorespercent;
$localsynonymousexpected = ($estimatedexpectedsynonymous/100)*$subsetsynonymoususcorespercent;

echo "<p>Expected and Observed Variants in subset: <br>";
echo "Missense Expected: " . $localmissenseexpected . "<br>";
echo "Synonymous Expected: " . $localsynonymousexpected . "<br>";
echo "Missense Observed: " . $subsetvariantsandscores['VariantsMissense'] . "<br>";
echo "Synonymous Observed: " . $subsetvariantsandscores['VariantsSynonymous'] . "</p>";

//Calculate local Z scores
$localzscoremissense = ($localmissenseexpected-$subsetvariantsandscores['VariantsMissense'])/sqrt($localmissenseexpected);
$localzscoresynonymous = ($localsynonymousexpected-$subsetvariantsandscores['VariantsSynonymous'])/sqrt($localsynonymousexpected);

echo "<p>Z Scores and Constraint: <br>";
echo "Z Score Missense: " . $localzscoremissense . "<br>";
echo "Z Score Synonymous: " . $localzscoresynonymous . "<br>";

$localconstraintmissense = $localzscoremissense*$adjustmissense;
$localconstraintsynonymous = $localzscoresynonymous*$adjustsynonymous;

echo "Constraint Missense: " . $localconstraintmissense . "<br>";
echo "Constraint Synonymous: " . $localconstraintsynonymous . "</p>";
?>
