<?php
//---FunctionBreak---
/*Determine if a position is close to a splice site

$position is the position to check
$exons is an array of positions of splice sites

Returns true or false whether the position is near a splice site or not*/
//---DocumentationBreak---
function checksplice($position,$exons)
	{
	$splice = false;
	
	//Specify the size of the exon region, account for the region being between 
	$region = 1;
	$region = $region-0.5;
	
	foreach ($exons as $exon)
		{
		//Define boundaries for this splice site
		$exon = $exon+0.5;
		$high = $exon+$region;
		$low = $exon-$region;
		
		//Set to true if within splice site boundary
		if (($position >= $low) AND ($position <= $high))	
			$splice = true;
		}
	
	Return $splice;
	}
//---FunctionBreak---
/*Splits a DNA sequence into arry of trinucleotides centred around each nucleotide

$dna is the DNA sequence to get the chunks for

Note, this will get trinucleotides from position 5'+1 to 3'-1 to incorporate flanking residues*/
//---DocumentationBreak---
function sequencetoblocks($dna)
	{
	$dna = str_split($dna);
	
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
/*Look up u scores for a changes from one nucleotide to another 

$trinucleotide is data for the liklihood of each change*/
//---DocumentationBreak---
function uvalue($trinucleotide)
	{
	//Make upper case
	$trinucleotide = strtoupper($trinucleotide);
	
	//uscores for each change
	$uvalueslist = array();
	$uvalueslist['AAAC'] = array("U"=>0.129,"SD"=>0.049);
	$uvalueslist['AAAG'] = array("U"=>0.58,"SD"=>0.137);
	$uvalueslist['AAAT'] = array("U"=>0.122,"SD"=>0.03);
	$uvalueslist['AACC'] = array("U"=>0.236,"SD"=>0.079);
	$uvalueslist['AACG'] = array("U"=>0.846,"SD"=>0.184);
	$uvalueslist['AACT'] = array("U"=>0.1,"SD"=>0.041);
	$uvalueslist['AAGC'] = array("U"=>0.122,"SD"=>0.043);
	$uvalueslist['AAGG'] = array("U"=>0.351,"SD"=>0.098);
	$uvalueslist['AAGT'] = array("U"=>0.093,"SD"=>0.031);
	$uvalueslist['AATC'] = array("U"=>0.159,"SD"=>0.072);
	$uvalueslist['AATG'] = array("U"=>2.282,"SD"=>0.33);
	$uvalueslist['AATT'] = array("U"=>0.169,"SD"=>0.06);
	$uvalueslist['ACAA'] = array("U"=>0.362,"SD"=>0.067);
	$uvalueslist['ACAG'] = array("U"=>0.299,"SD"=>0.06);
	$uvalueslist['ACAT'] = array("U"=>1.559,"SD"=>0.16);
	$uvalueslist['ACCA'] = array("U"=>0.515,"SD"=>0.082);
	$uvalueslist['ACCG'] = array("U"=>0.173,"SD"=>0.048);
	$uvalueslist['ACCT'] = array("U"=>0.679,"SD"=>0.136);
	$uvalueslist['ACGA'] = array("U"=>0.759,"SD"=>0.146);
	$uvalueslist['ACGG'] = array("U"=>0.994,"SD"=>0.162);
	$uvalueslist['ACGT'] = array("U"=>8.687,"SD"=>0.683);
	$uvalueslist['ACTA'] = array("U"=>0.246,"SD"=>0.066);
	$uvalueslist['ACTG'] = array("U"=>0.447,"SD"=>0.091);
	$uvalueslist['ACTT'] = array("U"=>0.758,"SD"=>0.196);
	$uvalueslist['AGAA'] = array("U"=>1.16,"SD"=>0.197);
	$uvalueslist['AGAC'] = array("U"=>0.408,"SD"=>0.077);
	$uvalueslist['AGAT'] = array("U"=>0.303,"SD"=>0.044);
	$uvalueslist['AGCA'] = array("U"=>0.814,"SD"=>0.173);
	$uvalueslist['AGCC'] = array("U"=>0.401,"SD"=>0.087);
	$uvalueslist['AGCT'] = array("U"=>0.161,"SD"=>0.045);
	$uvalueslist['AGGA'] = array("U"=>1.642,"SD"=>0.242);
	$uvalueslist['AGGC'] = array("U"=>0.517,"SD"=>0.083);
	$uvalueslist['AGGT'] = array("U"=>0.321,"SD"=>0.055);
	$uvalueslist['AGTA'] = array("U"=>1.505,"SD"=>0.297);
	$uvalueslist['AGTC'] = array("U"=>0.375,"SD"=>0.109);
	$uvalueslist['AGTT'] = array("U"=>0.397,"SD"=>0.097);
	$uvalueslist['ATAA'] = array("U"=>0.279,"SD"=>0.079);
	$uvalueslist['ATAC'] = array("U"=>1.397,"SD"=>0.328);
	$uvalueslist['ATAG'] = array("U"=>0.184,"SD"=>0.071);
	$uvalueslist['ATCA'] = array("U"=>0.416,"SD"=>0.078);
	$uvalueslist['ATCC'] = array("U"=>0.793,"SD"=>0.149);
	$uvalueslist['ATCG'] = array("U"=>0.159,"SD"=>0.046);
	$uvalueslist['ATGA'] = array("U"=>0.454,"SD"=>0.068);
	$uvalueslist['ATGC'] = array("U"=>2.09,"SD"=>0.235);
	$uvalueslist['ATGG'] = array("U"=>0.565,"SD"=>0.077);
	$uvalueslist['ATTA'] = array("U"=>0.168,"SD"=>0.057);
	$uvalueslist['ATTC'] = array("U"=>1.372,"SD"=>0.239);
	$uvalueslist['ATTG'] = array("U"=>0.203,"SD"=>0.062);
	$uvalueslist['CAAC'] = array("U"=>0.309,"SD"=>0.087);
	$uvalueslist['CAAG'] = array("U"=>0.984,"SD"=>0.163);
	$uvalueslist['CAAT'] = array("U"=>0.086,"SD"=>0.022);
	$uvalueslist['CACC'] = array("U"=>0.776,"SD"=>0.158);
	$uvalueslist['CACG'] = array("U"=>0.92,"SD"=>0.172);
	$uvalueslist['CACT'] = array("U"=>0.176,"SD"=>0.066);
	$uvalueslist['CAGC'] = array("U"=>0.416,"SD"=>0.083);
	$uvalueslist['CAGG'] = array("U"=>0.808,"SD"=>0.126);
	$uvalueslist['CAGT'] = array("U"=>0.032,"SD"=>0.016);
	$uvalueslist['CATC'] = array("U"=>0.2,"SD"=>0.086);
	$uvalueslist['CATG'] = array("U"=>2.459,"SD"=>0.3);
	$uvalueslist['CATT'] = array("U"=>0.525,"SD"=>0.124);
	$uvalueslist['CCAA'] = array("U"=>0.418,"SD"=>0.109);
	$uvalueslist['CCAG'] = array("U"=>0.247,"SD"=>0.068);
	$uvalueslist['CCAT'] = array("U"=>0.995,"SD"=>0.093);
	$uvalueslist['CCCA'] = array("U"=>0.36,"SD"=>0.104);
	$uvalueslist['CCCG'] = array("U"=>0.317,"SD"=>0.078);
	$uvalueslist['CCCT'] = array("U"=>1.322,"SD"=>0.178);
	$uvalueslist['CCGA'] = array("U"=>0.603,"SD"=>0.175);
	$uvalueslist['CCGG'] = array("U"=>0.701,"SD"=>0.144);
	$uvalueslist['CCGT'] = array("U"=>8.34,"SD"=>0.475);
	$uvalueslist['CCTA'] = array("U"=>0.278,"SD"=>0.093);
	$uvalueslist['CCTG'] = array("U"=>0.299,"SD"=>0.075);
	$uvalueslist['CCTT'] = array("U"=>1.43,"SD"=>0.222);
	$uvalueslist['CGAA'] = array("U"=>11.527,"SD"=>0.871);
	$uvalueslist['CGAC'] = array("U"=>1.155,"SD"=>0.213);
	$uvalueslist['CGAT'] = array("U"=>0.851,"SD"=>0.115);
	$uvalueslist['CGCA'] = array("U"=>9.735,"SD"=>0.787);
	$uvalueslist['CGCC'] = array("U"=>1.006,"SD"=>0.187);
	$uvalueslist['CGCT'] = array("U"=>0.645,"SD"=>0.139);
	$uvalueslist['CGGA'] = array("U"=>13.023,"SD"=>0.88);
	$uvalueslist['CGGC'] = array("U"=>1.218,"SD"=>0.184);
	$uvalueslist['CGGT'] = array("U"=>0.822,"SD"=>0.134);
	$uvalueslist['CGTA'] = array("U"=>10.255,"SD"=>0.973);
	$uvalueslist['CGTC'] = array("U"=>1.046,"SD"=>0.247);
	$uvalueslist['CGTT'] = array("U"=>1.193,"SD"=>0.262);
	$uvalueslist['CTAA'] = array("U"=>0.241,"SD"=>0.081);
	$uvalueslist['CTAC'] = array("U"=>1.057,"SD"=>0.203);
	$uvalueslist['CTAG'] = array("U"=>0.265,"SD"=>0.09);
	$uvalueslist['CTCA'] = array("U"=>0.222,"SD"=>0.073);
	$uvalueslist['CTCC'] = array("U"=>1.198,"SD"=>0.17);
	$uvalueslist['CTCG'] = array("U"=>0.227,"SD"=>0.061);
	$uvalueslist['CTGA'] = array("U"=>0.219,"SD"=>0.045);
	$uvalueslist['CTGC'] = array("U"=>1.871,"SD"=>0.151);
	$uvalueslist['CTGG'] = array("U"=>0.488,"SD"=>0.066);
	$uvalueslist['CTTA'] = array("U"=>0.383,"SD"=>0.112);
	$uvalueslist['CTTC'] = array("U"=>1.136,"SD"=>0.208);
	$uvalueslist['CTTG'] = array("U"=>0.472,"SD"=>0.109);
	$uvalueslist['GAAC'] = array("U"=>0.166,"SD"=>0.045);
	$uvalueslist['GAAG'] = array("U"=>0.598,"SD"=>0.101);
	$uvalueslist['GAAT'] = array("U"=>0.146,"SD"=>0.028);
	$uvalueslist['GACC'] = array("U"=>0.172,"SD"=>0.053);
	$uvalueslist['GACG'] = array("U"=>0.615,"SD"=>0.11);
	$uvalueslist['GACT'] = array("U"=>0.354,"SD"=>0.073);
	$uvalueslist['GAGC'] = array("U"=>0.098,"SD"=>0.031);
	$uvalueslist['GAGG'] = array("U"=>0.321,"SD"=>0.068);
	$uvalueslist['GAGT'] = array("U"=>0.12,"SD"=>0.033);
	$uvalueslist['GATC'] = array("U"=>0.195,"SD"=>0.062);
	$uvalueslist['GATG'] = array("U"=>1.025,"SD"=>0.159);
	$uvalueslist['GATT'] = array("U"=>0.518,"SD"=>0.101);
	$uvalueslist['GCAA'] = array("U"=>0.328,"SD"=>0.063);
	$uvalueslist['GCAG'] = array("U"=>0.192,"SD"=>0.058);
	$uvalueslist['GCAT'] = array("U"=>0.999,"SD"=>0.103);
	$uvalueslist['GCCA'] = array("U"=>0.474,"SD"=>0.07);
	$uvalueslist['GCCG'] = array("U"=>0.239,"SD"=>0.062);
	$uvalueslist['GCCT'] = array("U"=>1.011,"SD"=>0.166);
	$uvalueslist['GCGA'] = array("U"=>1.013,"SD"=>0.163);
	$uvalueslist['GCGG'] = array("U"=>0.578,"SD"=>0.129);
	$uvalueslist['GCGT'] = array("U"=>6.762,"SD"=>0.491);
	$uvalueslist['GCTA'] = array("U"=>0.39,"SD"=>0.073);
	$uvalueslist['GCTG'] = array("U"=>0.26,"SD"=>0.071);
	$uvalueslist['GCTT'] = array("U"=>1.209,"SD"=>0.23);
	$uvalueslist['GGAA'] = array("U"=>1.431,"SD"=>0.134);
	$uvalueslist['GGAC'] = array("U"=>0.437,"SD"=>0.077);
	$uvalueslist['GGAT'] = array("U"=>0.376,"SD"=>0.043);
	$uvalueslist['GGCA'] = array("U"=>1.661,"SD"=>0.153);
	$uvalueslist['GGCC'] = array("U"=>0.497,"SD"=>0.096);
	$uvalueslist['GGCT'] = array("U"=>0.491,"SD"=>0.072);
	$uvalueslist['GGGA'] = array("U"=>1.805,"SD"=>0.165);
	$uvalueslist['GGGC'] = array("U"=>0.559,"SD"=>0.094);
	$uvalueslist['GGGT'] = array("U"=>0.348,"SD"=>0.059);
	$uvalueslist['GGTA'] = array("U"=>1.789,"SD"=>0.206);
	$uvalueslist['GGTC'] = array("U"=>0.53,"SD"=>0.124);
	$uvalueslist['GGTT'] = array("U"=>1.166,"SD"=>0.161);
	$uvalueslist['GTAA'] = array("U"=>0.296,"SD"=>0.09);
	$uvalueslist['GTAC'] = array("U"=>0.922,"SD"=>0.245);
	$uvalueslist['GTAG'] = array("U"=>0.38,"SD"=>0.113);
	$uvalueslist['GTCA'] = array("U"=>0.371,"SD"=>0.083);
	$uvalueslist['GTCC'] = array("U"=>0.765,"SD"=>0.181);
	$uvalueslist['GTCG'] = array("U"=>0.242,"SD"=>0.067);
	$uvalueslist['GTGA'] = array("U"=>0.37,"SD"=>0.06);
	$uvalueslist['GTGC'] = array("U"=>1.377,"SD"=>0.184);
	$uvalueslist['GTGG'] = array("U"=>0.323,"SD"=>0.057);
	$uvalueslist['GTTA'] = array("U"=>0.339,"SD"=>0.095);
	$uvalueslist['GTTC'] = array("U"=>1.077,"SD"=>0.264);
	$uvalueslist['GTTG'] = array("U"=>0.223,"SD"=>0.08);
	$uvalueslist['TAAC'] = array("U"=>0.317,"SD"=>0.159);
	$uvalueslist['TAAG'] = array("U"=>1.036,"SD"=>0.299);
	$uvalueslist['TAAT'] = array("U"=>0.154,"SD"=>0.051);
	$uvalueslist['TACC'] = array("U"=>0.221,"SD"=>0.075);
	$uvalueslist['TACG'] = array("U"=>0.975,"SD"=>0.153);
	$uvalueslist['TACT'] = array("U"=>0.049,"SD"=>0.049);
	$uvalueslist['TAGC'] = array("U"=>0.287,"SD"=>0.203);
	$uvalueslist['TAGG'] = array("U"=>0.95,"SD"=>0.361);
	$uvalueslist['TAGT'] = array("U"=>0.187,"SD"=>0.107);
	$uvalueslist['TATC'] = array("U"=>0.161,"SD"=>0.072);
	$uvalueslist['TATG'] = array("U"=>1.817,"SD"=>0.237);
	$uvalueslist['TATT'] = array("U"=>0.295,"SD"=>0.132);
	$uvalueslist['TCAA'] = array("U"=>0.412,"SD"=>0.074);
	$uvalueslist['TCAG'] = array("U"=>0.633,"SD"=>0.089);
	$uvalueslist['TCAT'] = array("U"=>1.063,"SD"=>0.125);
	$uvalueslist['TCCA'] = array("U"=>0.228,"SD"=>0.069);
	$uvalueslist['TCCG'] = array("U"=>0.426,"SD"=>0.09);
	$uvalueslist['TCCT'] = array("U"=>1.027,"SD"=>0.161);
	$uvalueslist['TCGA'] = array("U"=>0.55,"SD"=>0.135);
	$uvalueslist['TCGG'] = array("U"=>0.748,"SD"=>0.196);
	$uvalueslist['TCGT'] = array("U"=>8.276,"SD"=>0.641);
	$uvalueslist['TCTA'] = array("U"=>0.156,"SD"=>0.065);
	$uvalueslist['TCTG'] = array("U"=>0.414,"SD"=>0.098);
	$uvalueslist['TCTT'] = array("U"=>0.504,"SD"=>0.135);
	$uvalueslist['TGAA'] = array("U"=>1.43,"SD"=>0.214);
	$uvalueslist['TGAC'] = array("U"=>0.511,"SD"=>0.114);
	$uvalueslist['TGAT'] = array("U"=>0.288,"SD"=>0.046);
	$uvalueslist['TGCA'] = array("U"=>1.612,"SD"=>0.188);
	$uvalueslist['TGCC'] = array("U"=>0.756,"SD"=>0.128);
	$uvalueslist['TGCT'] = array("U"=>0.53,"SD"=>0.087);
	$uvalueslist['TGGA'] = array("U"=>1.648,"SD"=>0.128);
	$uvalueslist['TGGC'] = array("U"=>0.423,"SD"=>0.077);
	$uvalueslist['TGGT'] = array("U"=>0.366,"SD"=>0.061);
	$uvalueslist['TGTA'] = array("U"=>2.166,"SD"=>0.242);
	$uvalueslist['TGTC'] = array("U"=>0.683,"SD"=>0.136);
	$uvalueslist['TGTT'] = array("U"=>0.526,"SD"=>0.115);
	$uvalueslist['TTAA'] = array("U"=>0.259,"SD"=>0.075);
	$uvalueslist['TTAC'] = array("U"=>0.774,"SD"=>0.21);
	$uvalueslist['TTAG'] = array("U"=>0.364,"SD"=>0.09);
	$uvalueslist['TTCA'] = array("U"=>0.072,"SD"=>0.051);
	$uvalueslist['TTCC'] = array("U"=>0.587,"SD"=>0.112);
	$uvalueslist['TTCG'] = array("U"=>0.109,"SD"=>0.038);
	$uvalueslist['TTGA'] = array("U"=>0.181,"SD"=>0.047);
	$uvalueslist['TTGC'] = array("U"=>0.74,"SD"=>0.158);
	$uvalueslist['TTGG'] = array("U"=>0.326,"SD"=>0.103);
	$uvalueslist['TTTA'] = array("U"=>0.179,"SD"=>0.09);
	$uvalueslist['TTTC'] = array("U"=>0.641,"SD"=>0.14);
	$uvalueslist['TTTG'] = array("U"=>0.375,"SD"=>0.087);
	
	//Create list of variants to cycle through
	$variants = array();
	$listtrinucleotides = str_split($trinucleotide);
	if ($listtrinucleotides[1] != "A")
		array_push($variants,"A");
	if ($listtrinucleotides[1] != "C")
		array_push($variants,"C");
	if ($listtrinucleotides[1] != "G")
		array_push($variants,"G");
	if ($listtrinucleotides[1] != "T")
		array_push($variants,"T");
	
	//Make list of uscores
	$uscores = array();
	foreach ($variants as $variant)
		{
		$arraylookup = $trinucleotide . $variant;
		
		$uscores[$variant] = $uvalueslist[$arraylookup];
		}
	
	Return $uscores;
	}
//---FunctionBreak---
/*Calculates the total U Score and number of variants in a subset of a gene

$sequence is an array containing U scores and variant counts at each nucleotide position in a gene and for both missense and synonymous variants
$min and $max are the minimum and maximum of the range to lookup*/
//---DocumentationBreak---
function subsetuscoreandvariant($sequence,$min="",$max="")
	{
	//Define $min and $max constraints as - infinity and infinity to account for any size of gene
	if (is_numeric($min) == false)
		$min = 0-INF;
	if (is_numeric($max) == false)
		$max = INF;
	
	//Create array to hold U Scores and variant counts
	$output = array();
	$output['UScoreMissense'] = 0;
	$output['UScoreSynonymous'] = 0;
	$output['VariantsMissense'] = 0;
	$output['VariantsSynonymous'] = 0;
		
	foreach ($sequence as $position=>$variantdata)
		{
		//Only include if within the range specified by max and min
		if (($position >= $min) AND ($position <= $max))
			{			
			//Add missense and synonymous UScores to UScores in outpu array
			if (isset($variantdata['UScoreMissense']) == true)
				$output['UScoreMissense'] = $output['UScoreMissense']+$variantdata['UScoreMissense'];
			if (isset($variantdata['UScoreSynonymous']) == true)
				$output['UScoreSynonymous'] = $output['UScoreSynonymous']+$variantdata['UScoreSynonymous'];
			
			//Add missense and synonymous variants to variant counts in output array
			if (isset($variantdata['VariantsMissense']) == true)
				$output['VariantsMissense'] = $output['VariantsMissense']+$variantdata['VariantsMissense'];
			if (isset($variantdata['VariantsSynonymous']) == true)
				$output['VariantsSynonymous'] = $output['VariantsSynonymous']+$variantdata['VariantsSynonymous'];
			}
		}
	
	Return $output;
	}
//---FunctionBreak---
?>