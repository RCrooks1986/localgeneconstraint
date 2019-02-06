<?php
//Get variant name
if (isset($_GET['position']) == true)
  $checknucleotide = $_GET['position'];
else
  $checknucleotide = 4;

//Get variant name
if (isset($_GET['gene']) == true)
  $genesymbol = $_GET['gene'];
else
  $genesymbol = "BRCA1";

$t1 = microtime(true);

//Run the check on a single position
include '../public/single-position.php';

$scanstop = $genelength-3;

//The uniform section that all screens of this type have
$uniform = $genesymbol . " c." . $checknucleotide . "\t" . $genesymbol . "\t" . $checknucleotide;

foreach ($localconstraintresults as $result)
  {
  //The type of stuff
  if ($result['Name'] == "+/-15")
    $type = "V15bp";
  elseif ($result['Name'] == "+/-30")
    $type = "V30bp";
  elseif ($result['Name'] == "+/-60")
    $type = "V60bp";
  elseif ($result['Name'] == "+/-90")
    $type = "V90bp";
  elseif ($result['Name'] == "Exon")
    $type = "Exon";
  elseif (substr($result['Name'],0,6) == "Domain")
    $type = "Domain";

  //Start and end to get the size of window
  if ($result['Start'] < 1)
    $result['Start'] = 1;
  if ($result['End'] > $genelength)
    $result['End'] = $genelength;
  $regionsize = $result['End']-$result['Start']+1;

  //Create output line
  $line = "\n" . $uniform . "\t" . $type . "\t" . $regionsize . "\t" . $result['MissenseObserved'] . "\t" . $result['MissenseExpected'];
  file_put_contents("Variants.txt",$line,FILE_APPEND);
  }
$t2 = microtime(true);
$time = $t2-$t1;

if (($genesymbol != "NF1") OR ($checknucleotide < $scanstop))
  {
  $body = '<p>Completed gene ' . $genesymbol . ' position ' . $checknucleotide . '.</p>';
  if ($checknucleotide == $scanstop)
    {
    $genesymbol = "NF1";
    $checknucleotide = 4;
    }
  else
    $checknucleotide++;

  $body = $body . '<script>';
  $body = $body . 'location.replace("https://development.v.je/GeneConstraint/analysis/gene-scan.php?gene=' . $genesymbol . '&position=' . $checknucleotide . '")';
  $body = $body . '</script>';
  }
else
  {
  $body = '<p>Finished!</p>';
  }
?>
<html>
<head>
  <title></title>
</head>
<body>

<?php echo $body; ?>

</body>
</html>
