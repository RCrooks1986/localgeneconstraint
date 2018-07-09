<?php
include '../functions/api-functions.php';
include '../functions/dna-functions.php';
include '../functions/ensembl-functions.php';
include '../functions/uniprot-functions.php';
include '../functions/exac-functions.php';
include '../functions/mutationrate-functions.php';
include '../../../functions/cycleloop-functions.php';

$genesymbol = "BRCA1";

echo "<p>Gene Symbol: " . $genesymbol . "</p>";

//Get the ENSG and LRG IDs from the gene symbol input
$ids = getsequenceids($genesymbol);

print_r($ids);

echo "<br>";

//Get the exons from ExAc by querying with the ENSG sequence using the getexacexons function
//ExAc is used as the source of the exons as this makes it compatible with the missense variants data
$exons = getexacexons($ids['ENSG']);

echo "Exons:<br>";

print_r($exons);

echo "<br>";

$exonboundaries = exonsandcds($exons);
$ids['ENST'] = $exonboundaries['CDS']['ENST'];

echo "Exon Boundaries:<br>";

print_r($exonboundaries);

echo "<br>";

//Get constaint scores for this gene from the API
$constaintscores = exacconstraint($ids);

print_r($constaintscores);

$totalexpectedmissense = $constaintscores['hits'][0]['exac']['all']['exp_mis'];
$totalexpectedsynonymous = $constaintscores['hits'][0]['exac']['all']['exp_syn'];

echo "<br>";

//Get the gene sequence from the CDS identified by the ENST ID
$sequence = getensemblsequencefromenst($ids);

echo $sequence . "<br>";

//Turn sequence into array in order to process it as a loop
$sequence = str_split($sequence);

//Define which position in the codon, starts at 1
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
		
		//echo $currentcodon . " = " . $currentaminoacid . " ";
		
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
		
		//echo $currentnucleotide . " Miss: " . $missense . " Syn: " . $synonymous . "<br>";
		
		//Record U scores in by position array and in the total scores array
		$positionuscores = array("Miss"=>$missense,"Syn"=>$synonymous);
		$sequencenucleotides[$sequenceposition] = $positionuscores;
		$totalmissense = $totalmissense+$missense;
		$totalsynonymous = $totalsynonymous+$synonymous;
		}
	
	//Increment the codon position counter and the sequence position counter
	$codonposition = frameinc($codonposition,"123");	
	$sequenceposition++;
	}

//$uniprot = getuniprotdetails($genesymbol);

print_r($sequencenucleotides);

echo $totalexpectedmissense . "<br>";
echo $totalexpectedsynonymous . "<br>";
?>