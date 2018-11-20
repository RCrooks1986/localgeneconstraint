<?php
//Include files containing shared functions which can also be used by other scripts
include_once '../functions/api-functions.php';
include_once '../functions/dna-functions.php';
include_once '../functions/uniprot-functions.php';
include_once '../functions/ensembl-functions.php';
include_once '../functions/mutationrate-functions.php';
include_once '../functions/exac-functions.php';
include_once '../functions/bruteforce-functions.php';
include_once '../functions/cycleloop-functions.php';

//Automatically define gene symbol and nucleotide around which to search if not already specified
if (isset($genesymbol) == false)
	$genesymbol = "BRCA1";
if (isset($checknucleotide) == false)
	$checknucleotide = 350;

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

$globalresults = array();
$globalresults['ExpectedMissense'] = $constaintscores['hits'][0]['exac']['all']['exp_mis'];
$globalresults['ExpectedSynonymous'] = $constaintscores['hits'][0]['exac']['all']['exp_syn'];
$globalresults['ObservedMissense'] = $constaintscores['hits'][0]['exac']['all']['n_mis'];
$globalresults['ObservedSynonymous'] = $constaintscores['hits'][0]['exac']['all']['n_syn'];
$globalresults['ZMissense'] = ($globalresults['ExpectedMissense']-$globalresults['ObservedMissense'])/sqrt($globalresults['ExpectedMissense']);
$globalresults['ZSynonymous'] = ($globalresults['ExpectedSynonymous']-$globalresults['ObservedSynonymous'])/sqrt($globalresults['ExpectedSynonymous']);
$globalresults['AdjustMissense'] = $constaintscores['hits'][0]['exac']['all']['mis_z']/$globalresults['ZMissense'];
$globalresults['AdjustSynonymous'] = $constaintscores['hits'][0]['exac']['all']['syn_z']/$globalresults['ZSynonymous'];

//Get the gene sequence from the CDS identified by the ENST ID
$sequence = getensemblsequencefromenst($ids);

//Turn sequence into array in order to process it as a loop
$sequence = str_split($sequence);

//Define codon and sequence position for Uscore loop
$codonposition = 1;
$sequenceposition = 1;
//Variable to denote where the stop codon is, do not analyse stop codons
$endcodon = count($sequence)-2;

//Variables to store the total u scores for missense and synonymous variation in gene
$totalmissense = 0;
$totalsynonymous = 0;

//Calculate the liklihood of missense and nonsense variants at each nucleotide
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

//This section can possibly be removed
//Rescale expected number of variants for whole sequence based on observed values in the variant location data from ExAC, the Z scores, and the brute force algorithm.
//Parameters for deviation range to brute force, current is 25% either way, but may be changed in future
$deviationrange = 0.25;
$topdeviation = 1+$deviationrange;
$bottomdeviation = 1-$deviationrange;

//Range parameters to use in the brute force algorithm
$minsyn = $globalresults['ExpectedSynonymous']*$bottomdeviation;
$maxsyn = $globalresults['ExpectedSynonymous']*$topdeviation;
$minmis = $globalresults['ExpectedMissense']*$bottomdeviation;
$maxmis = $globalresults['ExpectedMissense']*$topdeviation;

//Run brute force algorithm to recalculate expected scores
$globalresults['AdjustedExpectedMissense'] = brutecalculatee($globalresults['ZMissense'],$totalvariants['VariantsMissense'],$minmis,$maxmis);
$globalresults['AdjustedExpectedSynonymous'] = brutecalculatee($globalresults['ZSynonymous'],$totalvariants['VariantsSynonymous'],$minsyn,$maxsyn);

//Retrieve U Scores and Variant frequenies for a given range
//$subsetvariantsandscores = subsetuscoreandvariant($sequencenucleotides);
$protstart = 165;
$protfinish = 193;
$nucstart = ($protstart*3)-2;
$nucfinish = ($protfinish*3);

//Define nucleotide ranges to check
$localconstraintresults = array();
$localconstraintresults[0] = array("Name"=>"+/-10","Start"=>$checknucleotide-10,"End"=>$checknucleotide+10);
$localconstraintresults[1] = array("Name"=>"+/-20","Start"=>$checknucleotide-20,"End"=>$checknucleotide+20);
$localconstraintresults[2] = array("Name"=>"+/-30","Start"=>$checknucleotide-20,"End"=>$checknucleotide+30);

//Calculate the local constraint for all the ranges specified in the checkranges array and populate the results array
foreach ($localconstraintresults as $constraintoutput)
	{
	$subsetvariantsandscores = subsetuscoreandvariant($sequencenucleotides,$constraintoutput['Start'],$constraintoutput['End']);
	$constraintoutput['MissenseObserved'] = $subsetvariantsandscores['VariantsMissense'];
	$constraintoutput['SynonymouseObserved'] = $subsetvariantsandscores['VariantsSynonymous'];

	//Find the percentage of the U score contributed by the given region
	$constraintoutput['MissenseUScorePercent'] = ($subsetvariantsandscores['UScoreMissense']/$totalvariants['UScoreMissense'])*100;
	$constraintoutput['SynonymousUScorePercent'] = ($subsetvariantsandscores['UScoreSynonymous']/$totalvariants['UScoreSynonymous'])*100;

	//Calculate local expected frequencies from the percentage of U score and the overall expected frequency
	$constraintoutput['MissenseExpected'] = ($globalresults['AdjustedExpectedMissense']/100)*$constraintoutput['MissenseUScorePercent'];
	$constraintoutput['SynonymousExpected'] = ($globalresults['AdjustedExpectedSynonymous']/100)*$constraintoutput['SynonymousUScorePercent'];

	//Calculate local Z scores
	$constraintoutput['LocalZMissense'] = ($constraintoutput['MissenseExpected']-$subsetvariantsandscores['VariantsMissense'])/sqrt($constraintoutput['MissenseExpected']);
	$constraintoutput['LocalZSynonymous'] = ($constraintoutput['SynonymousExpected']-$subsetvariantsandscores['VariantsSynonymous'])/sqrt($constraintoutput['SynonymousExpected']);

	$constraintoutput['ConstraintMissense'] = $constraintoutput['LocalZMissense']*$globalresults['AdjustMissense'];
	$constraintoutput['ConstraintSynonymous'] = $constraintoutput['LocalZSynonymous']*$globalresults['AdjustSynonymous'];

	array_push($localconstraintresults,$constraintoutput);
	}

/*
Global (gene wide) result can be retrieved by calling $globalresults
Local (by range specified) results can be retrieved by calling $localconstraintresults
*/
?>
