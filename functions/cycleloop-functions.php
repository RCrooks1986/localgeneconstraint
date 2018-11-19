<?php
//---FunctionBreak---
/*Converts numbers on a cycle array to a number based on the maximum values of each element

$cycle is the array of cycle elements
$maxs is the maximum value at each element

Output is a number that the value equals. Error will be returned if there there are a different number of elements in the cycle than in maxs, or if any numbers exceed the maximum for that element*/
//---DocumentationBreak---
function cyclearraytonumber($cycle,$maxs)
	{
	$error = false;
	
	//Error checking
	if (count($cycle) == count($maxs))
		{
		foreach ($cycle as $cyclekey=>$cyclevalue)
			{
			//If cycle value is greater than maximum allowed
			if ($cyclevalue > $maxs[$cyclekey])
				{
				echo "<p>Error! A value in the cycle array is larger than the maximum permitted!</p>";
				$error = true;
				}
			}
		}
	//If cycle has fewer values than maxs
	else
		{
		echo "<p>Error! There are a different number of elements in the cycle array to the maxs array!</p>";
		$error = true;
		
		Return 0;
		}
	
	if ($error == false)
		{
		$cyclevalues = cyclevalues($maxs);
		
		$number = 0;
		foreach ($cycle as $element=>$value)
			{
			$elementvalue = $cyclevalues[$element]*$value;
			
			$number = $number+$elementvalue;
			}
		
		Return $number;
		}
	}
//---FunctionBreak---
/**/
//---DocumentationBreak---
function numbertocyclearray($number,$maxs)
	{
	$number = floor($number);
		
	//Element values
	$elementvalues = cyclevalues($maxs);
	$elementvalues = array_reverse($elementvalues);
	
	//Array to store the values
	$output = array();
	foreach ($elementvalues as $value)
		{
		//Number to add to this element and number to migrate to next element
		$fill = $number/$value;
		$fill = floor($fill);
		$number = $number%$value;
		
		//Add to this column
		array_push($output,$fill);
		}
			
	$output = array_reverse($output);
	
	Return $output;
	}
//---FunctionBreak---
/**/
//---DocumentationBreak---
function cyclevalues($maxs)
	{
	//What 1 value in that element is worth, default to 1 for the first element
	$singlevalue = 1;
	$cyclevalues = array($singlevalue);
	$maxelement = 0;
	
	//Generate values for each element in array
	while (count($cyclevalues) < count($maxs))
		{
		$cyclemax = $maxs[$maxelement]+1;
		$singlevalue = $singlevalue*$cyclemax;
		array_push($cyclevalues,$singlevalue);
		$maxelement++;
		}
	
	//Return array of values for each element in the cycle loop
	Return $cyclevalues;
	}
//---FunctionBreak---
/**/
//---DocumentationBreak---
function maxcycle($maxs)
	{
	
	//Get each cycle value and multiple it by cycle maximum
	foreach ($maxs as $cyclevalue)
		{
		$cyclevalue = $cyclevalue+1;
		
		if (isset($maximumvalue) == true)
			$maximumvalue = $maximumvalue*$cyclevalue;
		else
			$maximumvalue = $cyclevalue;
		}
	
	$maximumvalue = $maximumvalue-1;
	Return $maximumvalue;
	}
//---FunctionBreak---
/*Increments a cycle through a frame scheme

$position is the current frame position
$cycle is the cycle of frame positions that are cycled through during incrementing

The new frame in the cycle scheme is returned*/
//---DocumentationBreak---
function frameinc($position,$cycle)
	{
	if (is_array($cycle) == false)
		$cycle = str_split($cycle);
	$current = array_search($position,$cycle);
	
	//Increment value and cycle back to 0 if at end of loop
	$current++;
	if ($current == count($cycle))
		$current = 0;
	
	//Get and return new position
	$position = $cycle[$current];
	Return $position;
	}
//---FunctionBreak---
?>