<?php
//---FunctionBreak---
/*Cycles through elements in a repeating set of values.

$option is the current option in the */
//---DocumentationBreak---
function cycle($option,$elements)
	{
	//Split options into array
	if (is_array($elements) == false)
		$elements = str_split($elements);
	
	//Current position and position to cycle to zero
	$currentcycle = array_search($option,$elements);
	
	if ($currentcycle !== false)
		{
		$elementsize = count($elements);
		
		//Increment
		$currentcycle++;
		if ($currentcycle == $elementsize)
			$currentcycle = 0;
		
		Return $elements[$currentcycle];
		}
	else
		Return false;
	}
//---FunctionBreak---
?>