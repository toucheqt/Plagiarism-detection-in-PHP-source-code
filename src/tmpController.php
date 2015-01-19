<?php

	include 'Tokens.php';
	include 'Metrics.php';
	
	
	// ************************************************** FILE ONE ************************************************************ //
	$file = 'Halstead';
	
	$tokenizer = new Tokenizer();
	$tokenizer->setFile('./tests/test-files/' . $file . '.php');
	if ($tokenizer->getTokens()) echo 'Successfuly loaded tokens into file ' . $file . ".json\n";
	else {
		echo 'Couldnt save tokens into file ' . $file . ".json\nEnding now...\n";
		exit();
	}
	
	$metrics = new Metrics();
	if ($metrics->setFile('./tokens/' . $file . '.json')) echo 'Successfuly decoded ' . $file . ".json\n";
	else {
		echo 'Couldnt decode file ' . $file . ".json\nEnding now...\n";
		exit();
	}
	
	// print functions count
	$metrics->getMetrics();
	$tmp = $metrics->getHalsteadMetrics();
	
	/*
	echo 'Number of functions in ' . $file . '.php = ' . $metrics->getFunctionCount() . "\n";
	echo 'Number of global variables in ' . $file . '.php = ' . $metrics->getGlobalVarCount() . "\n";
	echo 'Number of @ in ' . $file . '.php = ' . $metrics->getAtUsageCount() . "\n";
	echo 'Number of eval() in ' . $file . '.php = ' . $metrics->getEvalCount() . "\n";	
	echo 'Number of goto in ' . $file . '.php = ' . $metrics->getGotoCount() . "\n";	
	echo 'Number of operands in first function in ' . $file . '.php = ' . $tmp[0]->getOperandsCount() . "\n";
	echo 'Number of unique operands in first function in ' . $file . '.php = ' . count($tmp[0]->getUniqueOperands()) . "\n";
	echo 'Number of operators in first function in ' . $file . '.php = ' . $tmp[0]->getOperatorsCount() . "\n";
	echo 'Number of unique operators in first function in ' . $file . '.php = ' . count($tmp[0]->getUniqueOperators()) . "\n";
	echo 'Calculated function length = ' . $tmp[0]->getProgramLength() . "\n";
	echo 'Volume = ' . $tmp[0]->getVolume() . "\n";
	echo 'Difficulty = ' . $tmp[0]->getDifficulty() . "\n";
	*/
	
	
	// ************************************************** FILE TWO ************************************************************ //
	$file2 = 'Function-copy';
	
	$tokenizer2 = new Tokenizer();
	$tokenizer2->setFile('./tests/test-files/' . $file2 . '.php');
	if ($tokenizer2->getTokens()) echo 'Successfuly loaded tokens into file ' . $file2 . ".json\n";
	else {
		echo 'Couldnt save tokens into file ' . $file2 . ".json\nEnding now...\n";
		exit();
	}
	
	$metrics2 = new Metrics();
	if ($metrics2->setFile('./tokens/' . $file2 . '.json')) echo 'Successfuly decoded ' . $file2 . ".json\n";
	else {
		echo 'Couldnt decode file ' . $file2 . ".json\nEnding now...\n";
		exit();
	}
	
	$metrics2->getMetrics();
	print_r($metrics2->getContent());
	$tmp2 = $metrics2->getHalsteadMetrics();
	
	$functionIndex = 1;
	$plagiarism = array();
	foreach ($tmp as $value) {
		
		$length = $value->getProgramLength();
		$volume = $value->getVolume();
		$difficulty = $value->getDifficulty();
		
		for ($i = 0; $i < count($tmp2); $i++) {
			$gravity = 0; // zavaznost prestupku, kazda promenna length, volume, atd v rozmezi 10% od puvodni hodnoty inkrementuje gravity
			// checking if its within 10% radius
			if (($tmp2[$i]->getProgramLength() >= $length*0.8) && ($tmp2[$i]->getProgramLength() <= $length*1.8)) $gravity++;
			if (($tmp2[$i]->getVolume() >= $volume*0.8) && ($tmp2[$i]->getVolume() <= $volume*1.8)) $gravity++;
			if (($tmp2[$i]->getDifficulty() >= $difficulty*0.8) && ($tmp2[$i]->getDifficulty() <= $difficulty*1.8)) $gravity++;
			
			if ($gravity > 0) {
				$copied = array();
				array_push($copied, $functionIndex);
				array_push($copied, $i);
				if ($gravity == 1) array_push($copied, "maybe");
				else if ($gravity == 2) array_push($copied, "this really might be it");
				else if ($gravity == 3) array_push($copied, "yep, this is it");
				array_push($plagiarism, $copied);
				
				echo "Original - plagiarism\nProgram length: " . $length . " - " . $tmp2[$i]->getProgramLength() . "\n";
				echo "Volume: " . $volume . " - " . $tmp2[$i]->getVolume() . "\n";
				echo "Difficulty: " . $difficulty . " - " . $tmp2[$i]->getDifficulty() . "\n";
			}
		}
		
		$functionIndex++;
	}
	
	print_r($plagiarism);
?>			
