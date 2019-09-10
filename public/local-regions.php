<?php
//Containers to store raw and normalised results in
$rowlocal = array()
$normalisedlocal = array()

//Calculate the local constraint for all the ranges specified in the checkranges array and populate the results array
foreach ($localconstraintresults as $localconstraintresultskey=>$constraintoutput)
	{
	$subsetvariantsandscores = subsetuscoreandvariant($sequencenucleotides,$constraintoutput['Start'],$constraintoutput['End']);
	$constraintoutput['MissenseObserved'] = $subsetvariantsandscores['VariantsMissense'];
	$constraintoutput['SynonymousObserved'] = $subsetvariantsandscores['VariantsSynonymous'];

	//Find the percentage of the U score contributed by the given region
	$constraintoutput['MissenseUScorePercent'] = ($subsetvariantsandscores['UScoreMissense']/$totalvariants['UScoreMissense'])*100;
	$constraintoutput['SynonymousUScorePercent'] = ($subsetvariantsandscores['UScoreSynonymous']/$totalvariants['UScoreSynonymous'])*100;

	//Calculate local expected frequencies from the percentage of U score and the overall expected frequency
	$constraintoutput['MissenseExpected'] = ($globalresults['AdjustedExpectedMissense']/100)*$constraintoutput['MissenseUScorePercent'];
	$constraintoutput['SynonymousExpected'] = ($globalresults['AdjustedExpectedSynonymous']/100)*$constraintoutput['SynonymousUScorePercent'];

	//Calculate local Z scores
	$constraintoutput['LocalZMissense'] = ($constraintoutput['MissenseExpected']-$subsetvariantsandscores['VariantsMissense'])/sqrt($constraintoutput['MissenseExpected']);
	$constraintoutput['LocalZSynonymous'] = ($constraintoutput['SynonymousExpected']-$subsetvariantsandscores['VariantsSynonymous'])/sqrt($constraintoutput['SynonymousExpected']);

	$constraintoutput['ConstraintMissense'] = $constraintoutput['LocalZMissense']*$globalresults['AdjustMissense'];
	$constraintoutput['ConstraintSynonymous'] = $constraintoutput['LocalZSynonymous']*$globalresults['AdjustSynonymous'];

	$localconstraintresults[$localconstraintresultskey] = $constraintoutput;
	}
?>
