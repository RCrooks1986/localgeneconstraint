<?php
//---FunctionBreak---
/*Splits a DNA sequence into arry of trinucleotides centred around each nucleotide

$dna is the DNA sequence to get the chunks for

Note, this will get trinucleotides from position 5'+1 to 3'-1 to incorporate flanking residues*/
//---DocumentationBreak---
function sequencetoblocks($dna)
	{
	$dna = strsplit($dna);
	
	//Start and stop 1 position away from ends
	$stop = count($dna)-1;
	$key = 1;
	
	$chunks = array();
	
	while ($key < $stop)
		{
		$chunk = $dna[$key-1] . $dna[$key] . $dna[$key+1];
		
		array_push($chunks,$chunk);
		$key++;
		}
	
	Return $chunks;
	}
//---FunctionBreak---
/*Convert an array of chunks of trinucleotides into likelihood of mutations into every other nucleotide

$chunks is an array of trinucleotide chunks produced by the sequencetoblocks() function
$trinucleotidechangesdata is data for the liklihood of each change
*/
//---DocumentationBreak---
function chunkstomutation($chunks)
	{
	//Trinucleotide changes array
	$trinucleotidechangesdata = array();
	
	
	//Array to store rate at which each mutation happens
	$mutationrates = array();
	
	foreach ($chucks as $trinucleotide)
		{
		//Lookup the context dependent rate of change to each nucleotide
		$mutationrateslookup = $trinucleotidechangesdata[$trinucleotide];
		
		//Middle nucleotide
		array_push($mutationrates,$mutationrateslookup);
		}
	}
//---FunctionBreak---
?>