<?php
//Get the engines directory
$currentdirectory = getcwd();
$removedirs = array("/pages","/engines");
$currentdirectory = str_replace($removedirs,"",$currentdirectory);
$enginesdirectory = $currentdirectory . "/engines/";

//Get data from form
if (1 == 1)
  {
  //$genesymbol = "COL1A1";
  //$checknucleotide = 1679;

  $genesymbol = "PAX6";
  $checknucleotide = 0;
  $startrange = (273*3)-2;
  $endrange = 422*3;

  include $enginesdirectory . 'single-position.php';

  echo '<div class="item">';
  if ((isset($globalresults) == true) AND (isset($rawlocal) == true))
    {
    if ($checknucleotide == 0)
      echo "<p>The region of " . $genesymbol . " " . $startrange . "-" . $endrange . " has been analysed!</p>";
    else
      echo "<p>The variant at position " . $genesymbol . ": c." . $checknucleotide . " has been analysed!</p>";

    echo '<p>Gene Identifiers:<br>';
    echo 'Gene Symbol: ' . $genesymbol . '<br>';
    echo 'ENST: ' . $checknucleotide . '<br>';
    echo 'ENSG: ' . $startrange . '<br>';
    echo 'UniProt: ' . $endrange . '<br>';

    echo '<p>Table 1: Details about the gene and its constraint parameters. These are used to calculate the local constraint.<br>';
    echo '<table class="scientific">';
    echo '<tr>';
    echo '<th>Parameter</th>';
    echo '<th>Gene Wide Score</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Expected Missense</td>';
    echo '<td>' . round($globalresults['ExpectedMissense'],2) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Adjusted Expected Missense</td>';
    echo '<td>' . round($globalresults['AdjustedExpectedMissense'],2) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Observed Missense</td>';
    echo '<td>' . round($globalresults['ObservedMissense'],2) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Z-Score Missense</td>';
    echo '<td>' . round($globalresults['ZMissense'],2) . '</td>';
    echo '</tr>';
    echo '</table></p>';

    if ($globalresults['ZMissense'] >= 3.09)
      echo "<p>This gene is subject to constrained selection. The Missense Z-Score is " . round($globalresults['ZMissense'],2) . "</p>";

    echo '<p>Table 2: Details about the gene region and its constraint, raw details.<br>';
    echo '<table class="scientific">';
    echo '<tr>';
    echo '<th>Search Window</th>';
    echo '<th>Expected</th>';
    echo '<th>Observed</th>';
    echo '<th>Z-Score</th>';
    echo '</tr>';
    foreach($rawlocal as $localresult)
      {
      echo '<tr>';
      echo '<td>' . $localresult['Name'] . '</td>';
      echo '<td>' . round($localresult['MissenseExpected'],2) . '</td>';
      echo '<td>' . $localresult['VariantsMissense'] . '</td>';
      echo '<td>' . round($localresult['ConstraintMissense'],2) . '</td>';
      echo '</tr>';
      }
    echo '</table>';

    echo '<p>Table 3: Details about the gene region and its constraint, with data normalised.<br>';
    echo '<table class="scientific">';
    echo '<tr>';
    echo '<th>Search Window</th>';
    echo '<th>Expected</th>';
    echo '<th>Observed</th>';
    echo '<th>Z-Score</th>';
    echo '</tr>';
    foreach($normalisedlocal as $localresult)
      {
      echo '<tr>';
      echo '<td>' . $localresult['Name'] . '</td>';
      echo '<td>' . round($localresult['MissenseExpected'],2) . '</td>';
      echo '<td>' . round($localresult['VariantsMissense'],2) . '</td>';
      echo '<td>' . round($localresult['ConstraintMissense'],2) . '</td>';
      echo '</tr>';
      }
    echo '</table>';
    }
  else
    {
    echo "<p>D'oh! Something appears to have gone wrong!</p>";
    }
  echo '</div>';
  }
?>
<div class="item">
<p class="blockheading">Variant Local Constraint</p>
<p>Calculate local constraint scores of the region around a variant.</p>
<p>Gene: <input type="text" name="GeneSymbol" size="5"> Position: <input type="text" name="GeneSymbol" size="3"></p>
<p>You do not need to provide an exact nucleotide change, only the position</p>
<p>Optional:</p>
<p>ENST: <input type="text" size="10" name="ENST"></p>
<p>ENSG: <input type="text" size="10" name="ENSG"></p>
<p>UniProt: <input type="text" size="5" name="UniProt"></p>
<p>The script has defaults of these stored or looks them up if they are not specified.</p>
</div>

<div class="item">
<p class="blockheading">Region Local Constraint</p>
<p>Calculate the local constraint score for a specified region of a gene.</p>
</div>
