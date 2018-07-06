<?php
include '../functions/api-functions.php';
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

//Calculate the liklihood of missense and nonsense variants are each nucleotide
$changescores = array();
foreach($sequence as $currentkey=>$currentnucleotide)
	{
	//Check that the sequence position is not near a splice site using the checksplice function and the $exonboundaries['Boundaries'] array
	$splicecheck = checksplice($sequenceposition,$exonboundaries['Boundaries']);
	if (($splicecheck == false) AND ($sequenceposition > 3) AND ($sequenceposition < $endcodon))
		{
		//Variables to store missense and synonymous mutation rate scores at this sequence position
		$missense = 0;
		$synonymous = 0;
		
		//Calculate U values for each possible change
		$trinucleotide = $sequence[$currentkey-1] . $sequence[$currentkey] . $sequence[$currentkey+1];
		$uvalues = uvalue($trinucleotide);
		}
	
	
	/*
	elseif ($codonposition == 3)
		{
		
		}
	*/
	//Increment the codon position counter and the sequence position counter
	$codonposition = frameinc($codonposition,"123");	
	$sequenceposition++;
	}

//$uniprot = getuniprotdetails($genesymbol);

//print_r($uniprot);
?>