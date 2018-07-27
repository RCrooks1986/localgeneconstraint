<?php
//---FunctionBreak---
/*Brute force calculates the expected value based on Z = E-O/sqrt(E)

$z is the Z score (not constraint) to optimise E to
$o is the observed value to optimise E to
$min is the minimum starting value of E
$max is the maximum starting value of E

This brute force algorithm tries 50 cycles on brute forcing*/
//---DocumentationBreak---
function brutecalculatee($z,$o,$min,$max)
	{
	//Define the cycle
	$cycles = 25;
	$count = 0;
	while ($count < $cycles)
		{
		//Range size
		$rangesize = $max-$min;
	
		//Generate random 3 decimal place value
		$random = rand(0,10000);
		$random = $random/10000;
		
		//Generate a value to test
		$random = $rangesize*$random;
		$value = $min+$random;
		
		//Calculate the Z value using the generated E value
		$test = ($value-$o)/sqrt($value);
		
		//Set new maximum or minimum value for the random number generator range
		if ($test < $z)
			$min = $value;
		elseif ($test > $z)
			$max = $value;
		else
			{
			$min = $value;
			$max = $value;
			}
			
		$count++;
		}
	
	//Calculate the value and round it to nearest decimal place
	$value = ($max+$min)/2;
	$value = round($value,1);
	Return $value;
	}
//---FunctionBreak---
?>