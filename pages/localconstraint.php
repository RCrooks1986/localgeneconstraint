<?php
//Get the engines directory
$currentdirectory = getcwd();
$removedirs = array("/pages","/engines");
$currentdirectory = str_replace($removedirs,"",$currentdirectory);
$enginesdirectory = $currentdirectory . "/engines/";

//The HTML form action address
if ($currentdirectory == "/srv/http/development/public/GeneConstraint")
	$formlink = "localconstraint.php";
else
	$formlink = "?page=LocalConstraint";

//Get data from form
if (isset($_POST['submit']) == true)
  {
	//Get user inputs
	$genesymbol = $_POST['GeneSymbol'];
	$inputtype = $_POST['Type'];
	if ($inputtype == "Position")
		{
		$checknucleotide = $_POST['Position'];
	  $startrange = 0;
	  $endrange = 0;
		}
  elseif ($inputtype == "Range")
		{
		$checknucleotide = 0;
		$startrange = $_POST['Start'];
		$endrange = $_POST['End'];
		}

	$userenst = $_POST['ENST'];
	$userensg = $_POST['ENSG'];
	$useruniprot = $_POST['UniProt'];

  include $enginesdirectory . 'single-position.php';

	if ($ids['ENSG'] == '')
		$displayensg = "Not Found";
	else
		$displayensg = $ids['ENSG'];

	if ($ids['ENST'] == '')
		$displayenst = "Not Found";
	else
		$displayenst = $ids['ENST'];

	if ($ids['UniProt'] == '')
		$displayuniprot = "Not Found";
	else
		$displayuniprot = $ids['UniProt'];

	//Display results
	include $enginesdirectory . 'localconstraint-make-results.php';
	echo $resultshtml;
	}
?>
<div class="item">
<form action="<?php echo $formlink; ?>" method="post">
<p class="blockheading">Variant Local Constraint</p>
<p>Calculate local constraint scores of the region around a variant. This can be
	 used to test if a variant is at a position in a region of a gene that is subject
	 to constrained selection, even if the entire gene is not constrained.</p>
<p><b>NOTE: This tool has not been validated for clinical use. Use only for
	research use, or in consultation with a clinical bioinformatician or clinical
	geneticist in the context of other clinical evidence.</b></p>
<p>Gene: <input type="text" name="GeneSymbol" size="5"></p>
<p><input type="radio" name="Type" value="Position" checked> Position: <input type="text" name="Position" size="3"> or <input type="radio" name="Type" value="Range"> Range: <input type="text" name="Start" size="3"> - <input type="text" name="End" size="3"></p>
<p>You do not need to provide an exact nucleotide change, only the position</p>
<p>Optional:</p>
<p>ENST: <input type="text" size="10" name="ENST"></p>
<p>ENSG: <input type="text" size="10" name="ENSG"></p>
<p>UniProt: <input type="text" size="5" name="UniProt"></p>
<p>The script has defaults (below) of these stored or attempts to look them up if they are not specified.</p>
<p><input type="submit" name="submit" value="Go"> <input type="reset" name="reset" value="Reset"></p>
</form>
</div>

<div>
<p class="blockheading">Default Gene IDs</p>

<p>Default gene IDs used in the local constraint tool</p>
<table class="scientific">
<tr>
  <th>Gene Symbol</th>
  <th>ENST</th>
  <th>ENSG</th>
  <th>UniProt</th>
</tr>
<?php
include '../engines/gene-ids.php';

foreach ($genelist as $gene)
  {
  echo '<tr>';
  echo '<td>' . $gene['GeneSymbol'] . '</td>';
  echo '<td>' . $gene['ENST'] . '</td>';
  echo '<td>' . $gene['ENSG'] . '</td>';
  echo '<td>' . $gene['UniProt'] . '</td>';
  echo '</tr>';
  }
?>
</title>

</div>
