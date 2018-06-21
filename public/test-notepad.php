<?php
include '../functions/mutationrate-functions.php';
include '../functions/cycle-functions.php';

//Example DNA sequence
$nucleotidesequencefull = "AATGTCTACGTATCTATCGCGTATCTCGCTCGCGCGTATATATCGTACGTAGCTAGCTGATCGCG";
$nucleotidesequencetrimmed = substr($nucleotidesequencefull,1,-1);
$nucleotidesequencetrimmedarray = str_split($nucleotidesequencetrimmed);

//Split the sequence into blocks
$blocks = sequencetoblocks($nucleotidesequencefull);

echo "<table>";
echo "<tr>";
echo "<td>Nucleotide</td>";
echo "<td>->A</td>";
echo "<td>->C</td>";
echo "<td>->G</td>";
echo "<td>->T</td>";
echo "<td></td>";
echo "</tr>";

//Details for adding codons to codon array
$codonposition = 1;
$allcodons = array();
$singlecodon = array();
$singlecodonvariants = array();

//Get per nucleotide change U scores
foreach ($blocks as $blockkey=>$block)
	{
	$nucleotide = $nucleotidesequencetrimmedarray[$blockkey];
	
	//Get U scores for block
	$uvalues = uvalue($block);
	echo "<tr>";
	echo "<td>" . $nucleotide . "</td>";
	
	$options = array("A","C","G","T");
	
	//Display U values
	foreach ($options as $option)
		{
		if (isset($uvalues[$option]) == true)
			{
			echo "<td>" . $uvalues[$option]['U'] . "</td>";
			
			$codonvariants = array("Variant"=>$option,"U"=>$uvalues[$option]['U'],"SD"=>$uvalues[$option]['SD']);
			array_push($singlecodonvariants,$codonvariants);
			}
		else
			echo "<td>---</td>";
		}
	
	if ($codonposition == 3)
		{
		echo "<td>Add to codons array</td>";
		
		$singlecodon['Variants'] = $singlecodonvariants;
		
		//Add codon details to codon array
		array_push($allcodons,$singlecodon);
		$singlecodonvariants = array();
		}
	elseif ($codonposition == 2)
		{
		$singlecodon = array("Codon"=>$block);
		
		echo "<td></td>";
		}
	else
		echo "<td></td>";
	 
	$codonposition = cycle($codonposition,"123");
	}

//Assign per change type 
foreach ($allcodons as $codonposition)
	{
	$originalcodon = $codonposition['Codon'];
	
	echo $originalcodon . "<br>";
	
	foreach ($codonposition['Variants'] as $location=>$variants)
		{
		foreach ($variants as $variant)
			{
			print_r($variant);
			
			$newcodon = substr_replace($originalcodon,"P",$location,1);
			
			echo $newcodon . "<br>";
			}
		}
	
	echo "<hr>";
	}
?>