<?php
//Containers to store raw and normalised results in
$rawlocal = array();
$normalisedlocal = array();

//Calculate the local constraint for all the ranges specified in the checkranges array and populate the results array
foreach ($localconstraintranges as $constraintoutput)
	{
	//Calculate normalisation value
	$rangesize = $constraintoutput['End']-$constraintoutput['Start']+1;
	$normalisationfactor = $genelength/$rangesize;

	//Information about the row
	$rawrow = array();
	$rawrow['Name'] = $constraintoutput['Name'];
	$rawrow['Start'] = $constraintoutput['Start'];
	$rawrow['End'] = $constraintoutput['End'];
	$normalisedrow = array();
	$normalisedrow['Name'] = $constraintoutput['Name'];
	$normalisedrow['Start'] = $constraintoutput['Start'];
	$normalisedrow['End'] = $constraintoutput['End'];

	$subsetvariantsandscores = subsetuscoreandvariant($sequencenucleotides,$constraintoutput['Start'],$constraintoutput['End']);

	//Observed variant counts
	$rawrow['VariantsMissense'] = $subsetvariantsandscores['VariantsMissense'];
	$rawrow['VariantsSynonymous'] = $subsetvariantsandscores['VariantsSynonymous'];
	$normalisedrow['VariantsMissense'] = $subsetvariantsandscores['VariantsMissense']*$normalisationfactor;
	$normalisedrow['VariantsSynonymous'] = $subsetvariantsandscores['VariantsSynonymous']*$normalisationfactor;

	//Find the percentage of the U score contributed by the given region
	$rawrow['MissenseUScorePercent'] = ($subsetvariantsandscores['UScoreMissense']/$totalvariants['UScoreMissense'])*100;
	$rawrow['SynonymousUScorePercent'] = ($subsetvariantsandscores['UScoreSynonymous']/$totalvariants['UScoreSynonymous'])*100;
	$normalisedrow['MissenseUScorePercent'] = $rawrow['MissenseUScorePercent'];
	$normalisedrow['SynonymousUScorePercent'] = $rawrow['SynonymousUScorePercent'];

	//Calculate local expected frequencies from the percentage of U score and the overall expected frequency
	$rawrow['MissenseExpected'] = ($globalresults['AdjustedExpectedMissense']/100)*$rawrow['MissenseUScorePercent'];
	$rawrow['SynonymousExpected'] = ($globalresults['AdjustedExpectedSynonymous']/100)*$rawrow['SynonymousUScorePercent'];
	$normalisedrow['MissenseExpected'] = $rawrow['MissenseExpected']*$normalisationfactor;
	$normalisedrow['SynonymousExpected'] = $rawrow['SynonymousExpected']*$normalisationfactor;

	//Calculate local Z scores
	$rawrow['LocalZMissense'] = ($rawrow['MissenseExpected']-$rawrow['VariantsMissense'])/sqrt($rawrow['MissenseExpected']);
	$rawrow['LocalZSynonymous'] = ($rawrow['SynonymousExpected']-$rawrow['VariantsSynonymous'])/sqrt($rawrow['SynonymousExpected']);
	$normalisedrow['LocalZMissense'] = ($normalisedrow['MissenseExpected']-$normalisedrow['VariantsMissense'])/sqrt($normalisedrow['MissenseExpected']);
	$normalisedrow['LocalZSynonymous'] = ($normalisedrow['SynonymousExpected']-$normalisedrow['VariantsSynonymous'])/sqrt($normalisedrow['SynonymousExpected']);

	//Calculate adjusted constraint
	$rawrow['ConstraintMissense'] = $rawrow['LocalZMissense']*$globalresults['AdjustMissense'];
	$rawrow['ConstraintSynonymous'] = $rawrow['LocalZSynonymous']*$globalresults['AdjustSynonymous'];
	$normalisedrow['ConstraintMissense'] = $normalisedrow['LocalZMissense']*$globalresults['AdjustMissense'];
	$normalisedrow['ConstraintSynonymous'] = $normalisedrow['LocalZSynonymous']*$globalresults['AdjustSynonymous'];

	//Return output to containers
	array_push($rawlocal,$rawrow);
	array_push($normalisedlocal,$normalisedrow);
	}
?>
