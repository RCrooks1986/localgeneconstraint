<?php
//---FunctionBreak---
/*Convert a codon to an amino acid

$codon is the codon
$output is the type of output - default is single letter

Return is either a single letter amino acid code (default), a 3 letter code, or the full amino acid name.*/
//---DocumentationBreak---
function translatecodon($codon,$outputtype="1")
	{
	//Default output to 1
	if (($outputtype != "1") AND ($outputtype != "3") AND ($outputtype != "Name"))
		$outputtype = 1;
	
	//Genetic code
	$geneticcode = array();
	$geneticcode['AAA'] = array("1"=>"K","3"=>"Lys","Name"=>"Lysine");
	$geneticcode['AAC'] = array("1"=>"N","3"=>"Asn","Name"=>"Asparagine");
	$geneticcode['AAG'] = array("1"=>"K","3"=>"Lys","Name"=>"Lysine");
	$geneticcode['AAT'] = array("1"=>"N","3"=>"Asn","Name"=>"Asparagine");
	$geneticcode['ACA'] = array("1"=>"T","3"=>"Thr","Name"=>"Threonine");
	$geneticcode['ACC'] = array("1"=>"T","3"=>"Thr","Name"=>"Threonine");
	$geneticcode['ACG'] = array("1"=>"T","3"=>"Thr","Name"=>"Threonine");
	$geneticcode['ACT'] = array("1"=>"T","3"=>"Thr","Name"=>"Threonine");
	$geneticcode['AGA'] = array("1"=>"R","3"=>"Arg","Name"=>"Arginine");
	$geneticcode['AGC'] = array("1"=>"S","3"=>"Ser","Name"=>"Serine");
	$geneticcode['AGG'] = array("1"=>"R","3"=>"Arg","Name"=>"Arginine");
	$geneticcode['AGT'] = array("1"=>"S","3"=>"Ser","Name"=>"Serine");
	$geneticcode['ATA'] = array("1"=>"I","3"=>"Ile","Name"=>"Isoleucine");
	$geneticcode['ATC'] = array("1"=>"I","3"=>"Ile","Name"=>"Isoleucine");
	$geneticcode['ATG'] = array("1"=>"M","3"=>"Met","Name"=>"Methionine");
	$geneticcode['ATT'] = array("1"=>"I","3"=>"Ile","Name"=>"Isoleucine");
	$geneticcode['CAA'] = array("1"=>"Q","3"=>"Gln","Name"=>"Glutamine");
	$geneticcode['CAC'] = array("1"=>"H","3"=>"His","Name"=>"Histidine");
	$geneticcode['CAG'] = array("1"=>"Q","3"=>"Gln","Name"=>"Glutamine");
	$geneticcode['CAT'] = array("1"=>"H","3"=>"His","Name"=>"Histidine");
	$geneticcode['CCA'] = array("1"=>"P","3"=>"Pro","Name"=>"Proline");
	$geneticcode['CCC'] = array("1"=>"P","3"=>"Pro","Name"=>"Proline");
	$geneticcode['CCG'] = array("1"=>"P","3"=>"Pro","Name"=>"Proline");
	$geneticcode['CCT'] = array("1"=>"P","3"=>"Pro","Name"=>"Proline");
	$geneticcode['CGA'] = array("1"=>"R","3"=>"Arg","Name"=>"Arginine");
	$geneticcode['CGC'] = array("1"=>"R","3"=>"Arg","Name"=>"Arginine");
	$geneticcode['CGG'] = array("1"=>"R","3"=>"Arg","Name"=>"Arginine");
	$geneticcode['CGT'] = array("1"=>"R","3"=>"Arg","Name"=>"Arginine");
	$geneticcode['CTA'] = array("1"=>"L","3"=>"Leu","Name"=>"Leucine");
	$geneticcode['CTC'] = array("1"=>"L","3"=>"Leu","Name"=>"Leucine");
	$geneticcode['CTG'] = array("1"=>"L","3"=>"Leu","Name"=>"Leucine");
	$geneticcode['CTT'] = array("1"=>"L","3"=>"Leu","Name"=>"Leucine");
	$geneticcode['GAA'] = array("1"=>"E","3"=>"Glu","Name"=>"Glutamic Acid");
	$geneticcode['GAC'] = array("1"=>"D","3"=>"Asp","Name"=>"Aspartic Acid");
	$geneticcode['GAG'] = array("1"=>"E","3"=>"Glu","Name"=>"Glutamic Acid");
	$geneticcode['GAT'] = array("1"=>"D","3"=>"Asp","Name"=>"Aspartic Acid");
	$geneticcode['GCA'] = array("1"=>"A","3"=>"Ala","Name"=>"Alanine");
	$geneticcode['GCC'] = array("1"=>"A","3"=>"Ala","Name"=>"Alanine");
	$geneticcode['GCG'] = array("1"=>"A","3"=>"Ala","Name"=>"Alanine");
	$geneticcode['GCT'] = array("1"=>"A","3"=>"Ala","Name"=>"Alanine");
	$geneticcode['GGA'] = array("1"=>"G","3"=>"Gly","Name"=>"Glycine");
	$geneticcode['GGC'] = array("1"=>"G","3"=>"Gly","Name"=>"Glycine");
	$geneticcode['GGG'] = array("1"=>"G","3"=>"Gly","Name"=>"Glycine");
	$geneticcode['GGT'] = array("1"=>"G","3"=>"Gly","Name"=>"Glycine");
	$geneticcode['GTA'] = array("1"=>"V","3"=>"Val","Name"=>"Valine");
	$geneticcode['GTC'] = array("1"=>"V","3"=>"Val","Name"=>"Valine");
	$geneticcode['GTG'] = array("1"=>"V","3"=>"Val","Name"=>"Valine");
	$geneticcode['GTT'] = array("1"=>"V","3"=>"Val","Name"=>"Valine");
	$geneticcode['TAA'] = array("1"=>"*","3"=>"***","Name"=>"Stop");
	$geneticcode['TAC'] = array("1"=>"Y","3"=>"Tyr","Name"=>"Tyrosine");
	$geneticcode['TAG'] = array("1"=>"*","3"=>"***","Name"=>"Stop");
	$geneticcode['TAT'] = array("1"=>"Y","3"=>"Tyr","Name"=>"Tyrosine");
	$geneticcode['TCA'] = array("1"=>"S","3"=>"Ser","Name"=>"Serine");
	$geneticcode['TCC'] = array("1"=>"S","3"=>"Ser","Name"=>"Serine");
	$geneticcode['TCG'] = array("1"=>"S","3"=>"Ser","Name"=>"Serine");
	$geneticcode['TCT'] = array("1"=>"S","3"=>"Ser","Name"=>"Serine");
	$geneticcode['TGA'] = array("1"=>"*","3"=>"***","Name"=>"Stop");
	$geneticcode['TGC'] = array("1"=>"C","3"=>"Cys","Name"=>"Cysteine");
	$geneticcode['TGG'] = array("1"=>"W","3"=>"Trp","Name"=>"Tryptophan");
	$geneticcode['TGT'] = array("1"=>"C","3"=>"Cys","Name"=>"Cysteine");
	$geneticcode['TTA'] = array("1"=>"L","3"=>"Leu","Name"=>"Leucine");
	$geneticcode['TTC'] = array("1"=>"F","3"=>"Phe","Name"=>"Phenylalanine");
	$geneticcode['TTG'] = array("1"=>"L","3"=>"Leu","Name"=>"Leucine");
	$geneticcode['TTT'] = array("1"=>"F","3"=>"Phe","Name"=>"Phenylalanine");
	
	$aminoacid = $geneticcode[$codon];
	$aminoacid = $aminoacid[$outputtype];
	
	Return $aminoacid;
	}
//---FunctionBreak---
?>