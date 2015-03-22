<?php

	#SYN:xkrpec01

	// definice konstant
	define ("LINE_FEED", 10);
	define ("HORIZONTAL_TAB", 9);
	define ("ASCII_32", 32);

	// promenne pro praci se soubory
	$inputStr = NULL;
	$formatStr = NULL;
	$outputStr = NULL;
	$inputHandle = STDIN;
	$outputHandle = STDOUT;
	$formatHandle = NULL;
	
	$addS = "";

	// nacteni a zpracovani parametru, pokud nactu 1 byly zadany spatne
	if (($flagArray = getParams($argv)) == 1) {
		fwrite(STDERR, "ERROR: Spatne zadane vstupni parametry.\n");
		exit(1);
	}

	// tisk napovedy
	if ($flagArray['flagHelp'] != NULL) {
		printHelp();
		exit(0);
	}

	// otevreni vstupniho souboru, pokud byl zadan
	if ($flagArray['flagInput']) {
		if (($inputHandle = openFile($flagArray['flagInput'], strlen("--input="))) == -1) {
			fwrite(STDERR, "ERROR: Chyba pri prace se vstupnim soubourem.\n");
			exit(2);
		}
	}

	$inputStr = getResources($inputHandle);
	$outputStr = $inputStr;
	fclose($inputHandle);

	if (!checkUtf($inputStr)) {
		fwrite(STDERR, "ERROR: Spatne kodovani vstupniho souboru.\n");
		exit(2); // TO DO
	}

	// je zadan formatovaci soubor ? Pokud neni, nedelam nic
	if ($flagArray['flagFormat']) {
		// pokud se mi ho nepodari nacist, nedelam nic
		if (($formatHandle = openFile($flagArray['flagFormat'], strlen("--format="))) == -1) {
			fwrite(STDERR, "ERROR: Chyba pri praci s formatovacim souborem. Pokracuji bez nej.\n");
		}
		
		else {
			$formatStr = getResources($formatHandle);
			fclose($formatHandle);
			$formatArray = getFormat($formatStr);
			
			if ($formatArray == -1) {
				fwrite(STDERR, "ERROR: Chyba ve formatovacim souboru1.\n");
				exit(4);
			}
			elseif ($formatArray != NULL) {
				$parity = true;
				$i = 0;
				foreach ($formatArray as $key1 => $value1) {
					foreach ($value1 as $key2 => $value2) {
							if ($parity) {
								$formatArray[$i]['RV'] = checkRv($value2);
								if ($formatArray[$i]['RV'] == -1) {
									fwrite(STDERR, "ERROR: Chybne zadany RV.\n");
									exit(4);
								}
								
							}
							else {
								
								if (!checkTag($value2) == -1) {
									fwrite(STDERR, "ERROR: Chyba ve formatovacim souboru.\n");
									exit(4);
								}
							}
							$parity = !$parity;
					}
					$i++;
				}

				$outputStr = applyRv($formatArray, $inputStr);

			} // end elseif, pokud jsem neskocil do zadne vetve soubor byl pradny -> nic nedelam
		}

	}

	// pokud je zadan parametr --br, upravim
	if ($flagArray['flagBr']) {
		$outputStr = nl2br($outputStr);
	}

	// je zadan parametr output ?
	if ($flagArray['flagOutput']) {
		
		$outputHandle = substr($flagArray['flagOutput'], strlen("--output="), strlen($flagArray['flagOutput']) - strlen("--output="));
		// osetreni vlnovky
			if ($outputHandle[0] == "~") {
			$outputHandle = $_SERVER["HOME"]."/".substr($outputHandle, 1);
		}

		if (!($outputHandle = fopen($outputHandle, 'w+'))) {
			exit(3);
		}
		
		fwrite($outputHandle, $outputStr);
		fclose($outputHandle);
	}

	else {
		echo $outputStr;
	}

	return 0;




	
	/* Funkce aplikuje regularni vyrazy na vstupni soubor
	 * @param - pole s RV a tagy
	 * @param - vstupni soubor
	 * @return - vraci upraveny string
	 */
	function applyRv($formatArray, $inputStr) {
		// TO DO dalo by se napravit prehazovani tak, ze budu mit dve pole, jedno pro zacatky tagu a druhy pro konce tagu
		// jedno seradim stabilnim sortem a to druhe seradim nestabilnim sortem, ve vysledku to pak bude ok
		$applyArray[] = array(
			'POS' => $posStart = NULL,
			'TAG' => $tag = NULL,
			'END' => $end = NULL,
			'INDEX' => $index = NULL
		);
		
		
		
		$pattern = NULL;
		$tag = NULL;
		$parity = true;
		$i = 0;
		$outputStr = $inputStr;
		$offset = 0;
		$pom = NULL;
		
		foreach ($formatArray as $key1 => $value1) {
			$parity = true;
			foreach ($value1 as $key2 => $value2) {
				if ($parity) { // RV
					$pattern = $value2;
				}
				else { // TAG
					$tag = $value2;
				}
				$parity = !$parity;
			}
			global $addS;
			preg_match_all("/".$pattern."/".$addS, $inputStr, $matches, PREG_OFFSET_CAPTURE);
			
			// nahraju pozice znaku k do applyArray
			foreach ($matches[0] as $key1 => $value1) {
				$parity = false;
				$jump = false;
				foreach ($value1 as $key2 => $value2) {
					if (($parity) && (!$jump)) {									
						$applyArray[$i]['POS'] = $value2;
						$applyArray[$i]['TAG'] = $tag;
						$applyArray[$i]['END'] = false;
						$applyArray[$i]['INDEX'] = NULL;
						$i++;
						$applyArray[$i]['POS'] = $value2 + $pom;
						$applyArray[$i]['TAG'] = $tag;
						$applyArray[$i]['END'] = true;
						$applyArray[$i]['INDEX'] = NULL;
						$i++;
					}
					else {
						$pom = strlen($value2);
						if ($pom == 0) $jump = true;
					}
					
					$parity = !$parity;
				}
			}
		}
		
		// sort array
		$count = count($applyArray);
		for ($i = 0; $i < $count - 1; $i++) {
			for ($j = 0; $j < $count - $i - 1; $j++) {
				if ($applyArray[$j+1]['POS'] < $applyArray[$j]['POS']) {
					$tmpPos = $applyArray[$j+1]['POS'];
					$tmpTag = $applyArray[$j+1]['TAG'];
					$tmpEnd = $applyArray[$j+1]['END'];
					$applyArray[$j+1]['POS'] = $applyArray[$j]['POS'];
					$applyArray[$j+1]['TAG'] = $applyArray[$j]['TAG'] ;
					$applyArray[$j+1]['END'] = $applyArray[$j]['END'] ;
					$applyArray[$j]['POS']  = $tmpPos;
					$applyArray[$j]['TAG']  = $tmpTag;
					$applyArray[$j]['END']  = $tmpEnd;
				}
			}
		}
		for ($i = 0; $i < $count - 1; $i++) {
			for ($j = 0; $j < $count - $i - 1; $j++) {
				if ($applyArray[$j+1]['POS'] == $applyArray[$j]['POS']) {
					if ((!$applyArray[$j]['END']) && ($applyArray[$j+1]['END'])) {
						$tmpPos = $applyArray[$j+1]['POS'];
						$tmpTag = $applyArray[$j+1]['TAG'];
						$tmpEnd = $applyArray[$j+1]['END'];
						$applyArray[$j+1]['POS'] = $applyArray[$j]['POS'];
						$applyArray[$j+1]['TAG'] = $applyArray[$j]['TAG'] ;
						$applyArray[$j+1]['END'] = $applyArray[$j]['END'] ;
						$applyArray[$j]['POS']  = $tmpPos;
						$applyArray[$j]['TAG']  = $tmpTag;
						$applyArray[$j]['END']  = $tmpEnd;
					}
					
				}
			}
		}
		
		$index = 1;
		for ($i = 0; $i < $count - 1; $i++) {
			for ($j = 0; $j < $count - $i - 1; $j++) {
				if (($applyArray[$j+1]['POS'] == $applyArray[$j]['POS']) && ($applyArray[$j]['END']) && ($applyArray[$j+1]['END'])) {
					$applyArray[$j]['INDEX'] = $index;
					$index++;
					$applyArray[$j+1]['INDEX'] = $index;
				}
				else $index = 1;
			}
		}
		
		for ($i = 0; $i < $count - 1; $i++) {
			for ($j = 0; $j < $count - $i - 1; $j++) {
				if (($applyArray[$j+1]['POS'] == $applyArray[$j]['POS']) && ($applyArray[$j]['END']) && ($applyArray[$j+1]['END'])) {
					if ($applyArray[$j]['INDEX'] < $applyArray[$j+1]['INDEX']) {
						$tmpPos = $applyArray[$j+1]['POS'];
						$tmpTag = $applyArray[$j+1]['TAG'];
						$tmpEnd = $applyArray[$j+1]['END'];
						$tmpIn = $applyArray[$j+1]['INDEX'];
						$applyArray[$j+1]['POS'] = $applyArray[$j]['POS'];
						$applyArray[$j+1]['TAG'] = $applyArray[$j]['TAG'] ;
						$applyArray[$j+1]['END'] = $applyArray[$j]['END'] ;
						$applyArray[$j+1]['INDEX'] = $applyArray[$j]['INDEX'] ;
						$applyArray[$j]['POS']  = $tmpPos;
						$applyArray[$j]['TAG']  = $tmpTag;
						$applyArray[$j]['END']  = $tmpEnd;
						$applyArray[$j]['INDEX']  = $tmpIn;
					}
				}
			}
		}
				
		// zacne postupne aplikovat prikazy
		$i = 0;
		$insert = NULL;
		$offset = 0;
		
		foreach ($applyArray as $key1 => $value1) {
	
			if ($applyArray[$i]['END'] == false) {

				if ($applyArray[$i]['TAG'] == "bold") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."<b>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("<b>");
				}
				
				elseif ($applyArray[$i]['TAG'] == "italic") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."<i>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("<i>");
				}
				
				elseif ($applyArray[$i]['TAG'] == "underline") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."<u>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("<u>");
				}
				
				elseif ($applyArray[$i]['TAG'] == "teletype") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."<tt>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("<tt>");
				}
				
				elseif ((strncmp($applyArray[$i]['TAG'], "size:", strlen("size:"))) == 0) {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."<font size="
					.substr($applyArray[$i]['TAG'], strlen("size:"), 1).">"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("<font size=x>");
				}
				
				elseif ((strncmp($applyArray[$i]['TAG'], "color:", strlen("color:"))) == 0) {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."<font color=#"
					.substr($applyArray[$i]['TAG'], strlen("color:"), 6).">"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("<font color=#XXXXXX>");
				}
			}
			else {
				if ($applyArray[$i]['TAG'] == "bold") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."</b>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("</b>");
				}
				
				elseif ($applyArray[$i]['TAG'] == "italic") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."</i>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("</i>");
				}
				
				elseif ($applyArray[$i]['TAG'] == "underline") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."</u>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("</u>");
				}
				
				elseif ($applyArray[$i]['TAG'] == "teletype") {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."</tt>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("</tt>");
				}
				
				elseif (((strncmp($applyArray[$i]['TAG'], "size:", strlen("size:"))) == 0) ||
						((strncmp($applyArray[$i]['TAG'], "color:", strlen("color:"))) == 0)) {
					$outputStr = substr($outputStr, 0, $applyArray[$i]['POS'] + $offset)."</font>"
					.substr($outputStr, $applyArray[$i]['POS'] + $offset, strlen($outputStr) - $applyArray[$i]['POS']);
					$offset += strlen("</font>");
				}
			}

			$i++;

		}
		
		return $outputStr;
	}





	/* Funkce zkontroluje zda je zadany RV platny a prevedej jej na RV odpovidajici jazyku php
	 * @param - string obsahujici RV
	 * @return - -1 v pripade spatne zadaneho RV
	 * @return - RV odpovidajici jazyku php
	 */
	function checkRv($rvStr) {
		
		// pomocne promenne
		$leftBracket = 0;
		$rightBracket = 0;
		$firstChar = true;
		$lastChar = NULL;
		$convertRv = NULL;
		$exMark = "";

		// v cyklu projdu cely string
		for ($i = 0; $i < strlen($rvStr); $i++) {
			// na zacatku se nesmi vyskytovat znaky .|*+
			if (($firstChar) && (($rvStr[$i] == '.') || ($rvStr[$i] == '|') || ($rvStr[$i] == '*') || ($rvStr[$i] == '+'))) {
				return -1;
			}
			
			// za procentem mohou byt pouze nektere znaky
			if ($lastChar == '%') {
				if ($rvStr[$i] == 's') {
					$convertRv .= "[".$exMark." \t\n\r\f\v]";
				}
				
				elseif (($rvStr[$i] == 'a') && ($exMark == "")) {
					$convertRv .= ".";
					global $addS;
					$addS = "s";	
				}
				
				elseif ($rvStr[$i] == 'd') {
					$convertRv .= "[".$exMark."0-9]";
				}
				
				elseif ($rvStr[$i] == 'l') {
					$convertRv .= "[".$exMark."a-z]";
				}
				
				elseif ($rvStr[$i] == 'L') {
					$convertRv .= "[".$exMark."A-Z]";
				}
				
				elseif ($rvStr[$i] == 'w') {
					$convertRv .= "[".$exMark."a-zA-Z]";
				}
				
				elseif ($rvStr[$i] == 'W') {
					$convertRv .= "[".$exMark."a-zA-Z0-9]";
				}
				
				elseif ($rvStr[$i] == 't') {
					$convertRv .= "[".$exMark."\t]";
				}
				
				elseif ($rvStr[$i] == 'n') {
					$convertRv .= "[".$exMark."\n]";
				}
				
				elseif (($rvStr[$i] == '.') || ($rvStr[$i] == '|') || ($rvStr[$i] == '*') || ($rvStr[$i] == '+') || 
						($rvStr[$i] == '!') || ($rvStr[$i] == '(') || ($rvStr[$i] == ')')) {
					$convertRv .= "\\".$rvStr[$i];
				}
				
				elseif ($rvStr[$i] == '%') {
					$convertRv .= "\%";
				}
				
				else return -1;
				
				$lastChar = NULL;
				$exMark = "";				
			}
			
			// posledni znak nebylo procento
			else {
				// nactu procento
				if ($rvStr[$i] == '%') {
					$lastChar = '%';
				}
				
				// nactu vykricnik
				elseif ($rvStr[$i] == '!') {
					$exMark = '^';
					// nesmim mit dva vykricniky po sobe
					if ($lastChar == '!') return -1;
					else $lastChar = '!';
				}
				
				// nactu tecku
				elseif ($rvStr[$i] == '.') {
					if (($lastChar == '.') || ($lastChar == '|')) return -1;
					else $lastChar = '.';
					$exMark = "";
				}
				
				else {
					// nactu +*!|
					if (($rvStr[$i] == '+') || ($rvStr[$i] == '*') || ($rvStr[$i] == '|') || ($rvStr[$i] == '!')) {
						// dva specialni znaky po sobe nesmi byt
						if ($rvStr[$i] == $lastChar) return -1;
						
						// negace specialniho znaku nesmi byt
						elseif ($lastChar == '!') return -1;
						
						// osetreni .+ atd
						elseif (($lastChar == '.') && (($rvStr[$i] == '+') || ($rvStr[$i] == '*') || ($rvStr[$i] == '|'))) return -1;
						
						// osetreni ((
						elseif ($lastChar == '(') return -1;
						
						// osetreni +* atd
						elseif ((($lastChar == '+') || ($lastChar == '*') || ($lastChar == '|')) && 
								(($rvStr[$i] == '+') || ($rvStr[$i] == '*'))) return -1;
								
						else {
							$lastChar = $rvStr[$i];
						}
					}
					
					// nactu )
					elseif ($rvStr[$i] == ')') {
						if (($lastChar == '!') || ($lastChar == '|') || ($lastChar == '.') || ($lastChar == '(')) return -1;
						$rightBracket++;
					}
					
					// nactu (
					if ($rvStr[$i] == '(') {
						if ($lastChar == '!') return -1;
						else $lastChar = '(';	
						$leftBracket++;
					}
					
					else $lastChar = "";
					
					// pridani znaku do vysledneho RV
					if ($exMark == '^') {
						$convertRv .= "[^".$rvStr[$i]."]";
					}
					
					else {
						if (($rvStr[$i] == '\\') || ($rvStr[$i] == '^') || ($rvStr[$i] == '?') || ($rvStr[$i] == '$') ||
							($rvStr[$i] == '}') || ($rvStr[$i] == '{') || ($rvStr[$i] == '[') || ($rvStr[$i] == ']') || ($rvStr[$i] == '/')) {
							$convertRv .= "\\".$rvStr[$i];
						}
						
						else {			
							$convertRv .= $rvStr[$i];
							
							if (($rvStr[$i] == '+') && ($rvStr[$i] == '*')) {
								global $addS;
								$addS = "s";
							}
						}
					}
					
					// pokud posledni znak nebyl specialni, tak vynuluju promennou lastChar
					if (($rvStr[$i] != '+') && ($rvStr[$i] != '*') && ($rvStr[$i] != '|') && ($rvStr[$i] != '!') && ($rvStr[$i] != '.')) {
						$lastChar = "";
					}
					
					$exMark = "";
				}
								
			}

			$firstChar = false;
		} // konec for
		
		// na konci nesmi byt .|! atd
		if (($lastChar == '.') || ($lastChar == '!') || ($lastChar == '|')) return -1;
		
		// musim mit stejny pocet levych i pravych zavorek
		if ($leftBracket != $rightBracket) return -1;
		return $convertRv;		
	}





	/* Funkce zkontroluje zda je vlozeny tag v poradku
	 * @param - string obsahujici tag ke kontrole
	 * @return - true/false v zavislosti na tom, zda je tag v poradku
	 */
	function checkTag($tagStr) {
		
		// check bold
		if ((strcmp($tagStr, "bold")) == 0) {
			return true;
		}
		
		// check italic
		elseif ((strcmp($tagStr, "italic")) == 0) {
			return true;
		}
		
		// check underline
		elseif ((strcmp($tagStr, "underline")) == 0) {
			return true;
		}
		
		// check teletype
		elseif ((strcmp($tagStr, "teletype")) == 0) {
			return true;
		}
		
		// check size
		elseif (((strncmp($tagStr, "size:", strlen("size:"))) == 0) && ((strlen($tagStr)) == (strlen("size:x"))))  {
			if (((intval($tagStr[5])) >= 1) && ((intval($tagStr[5])) <= 7)) {
				return true;
			}
		}
		
		// check color
		if (((strncmp($tagStr, "color:", strlen("color:"))) == 0) && ((strlen($tagStr)) == (strlen("color:XXXXXX")))) {
			for ($i = 0 + strlen("color:"); $i < strlen("color:") + 6 ; $i++) {
				if ((preg_match("/[0-9a-fA-F]/", $tagStr[$i])) == 0) { // TO DO - kdyztak prepsat pouze na velka pismena
					return false;
				}
			}
			return true;
		}
		return false;
	}






	/* Funkce zkontroluje, zda byl spravne zadan formatovaci soubor a vrati dvojice formatovacich
	 * pravidel ve tvaru: regularni vyraz : pravidlo
	 * @param - string, ve kterem je ulozen obsah formatovaciho souboru
	 * @return - vraci dvourozmerne pole ve tvaru VYRAZ : PRAVIDLO
	 * @return - vraci NULL v pripade, ze byl formatovaci soubor prazny
	 * @return - vraci -1 v pripade chyby v formatovacim souboru.
	 */
	function getFormat($formatStr) {

		$arrayLines = 0;
		$rvStarted = false;
		$rvDone = false;
		$tagStarted = false;
		$firstTag = true;
		$formatArray[] = array(
			'RV' => $RV = NULL,
			'TAG' => $TAG = NULL
		);
		
		// prvni zkontroluju, jestli neni formatovaci soubor prazdny
		if ((trim($formatStr) == "") || ($formatStr == NULL)) return NULL; 

		// projdu cely soubor a vytahnu si z nej regularni vyrazy + pravidla
		for ($i = 0; $i < strlen($formatStr); $i++) {
			if (($i == strlen($formatStr)-1)) {
				if ((ord($formatStr[$i])) == LINE_FEED) break;
			}
			
			// zkontroluju jestli nenacitam konec radku
			if (ord($formatStr[$i]) == LINE_FEED) { 
				$rvStarted = false;
				$rvDone = false;
				$tagStarted = false;
				$firstTag = true;
				$arrayLines++;
				$formatArray[$arrayLines]['RV'] = NULL;
				$formatArray[$arrayLines]['TAG'] = NULL;
			}
			
			else {
				// jeste jsem nezacal nacitat RV
				if (!$rvStarted) {
					if ((ord($formatStr[$i])) < ASCII_32) {
						return -1;						
					}
					else {
						$rvStarted = true;
						$formatArray[$arrayLines]['RV'] = $formatStr[$i];
					}
				}
				
				// zacal jsem nacitat RV
				else {
					if (!$rvDone) {
						if (ord($formatStr[$i]) >= ASCII_32) {
							$formatArray[$arrayLines]['RV'] .= $formatStr[$i];
						}
						else {
							if (ord($formatStr[$i]) == HORIZONTAL_TAB) { 
								$rvDone = true;
							}
							else {
								return -1;
							}
						}
					}
					
					// nacitani tagu
					else {
						if (!$tagStarted) {
							if (!$firstTag) {
								if ((ord($formatStr[$i]) != HORIZONTAL_TAB) && ($formatStr[$i] != " ")) {
									$tagStarted = true;
									$formatArray[$arrayLines]['TAG'] = $formatStr[$i];
								}	
							}
							else {
								if (ord($formatStr[$i]) != HORIZONTAL_TAB) {
									$tagStarted = true;
									$formatArray[$arrayLines]['TAG'] .= $formatStr[$i];	
								}
							}
						}
						else {
							if ($formatStr[$i] != ",") {
								$formatArray[$arrayLines]['TAG'] .= $formatStr[$i];
							}
							else {
								$tagStarted = false;
								$firstTag = false;
								$arrayLines++;
								$formatArray[$arrayLines]['RV'] = $formatArray[$arrayLines-1]['RV'];
							}
						}
					} // end nacitani tagu
				} // end nacitani rv
			} // end nacitani radku	
		} // end for

		return $formatArray;
	}





	/* Funkce zkontroluje, zda je vstupni string ulozen v kodovani utf8.
	 * @param - Vstupni string
	 * @return - Funkce vraci true/false zavisle na tom, zda vstupni string je ci neni v kodovani UTF-8
	 */
	function checkUtf($string) {
		$string = str_replace("\xEF\xBB\xBF", '', $string);
		return @iconv('UTF-16', 'UTF-8' . '//IGNORE', iconv('UTF-8', 'UTF-16//IGNORE', $string)) == $string;
	}
	





	/* Funkce zkontroluje a otevre soubor (pokud bylo vse v poradku) a vrati popisovac souboru
	 * @param - Prebira parametr s nazvem souboru
	 * @param - Delka prefixu zadaneho parametru
	 * @return - ukazatel na soubor
	 * @return - vraci -1 pokud se nepodari otevrit zadany soubor
	 */
	function openFile($filename, $prefixLen) {
		$filename = substr($filename, $prefixLen, strlen($filename)-$prefixLen);

		// osetreni vlnovky
		if ($filename[0] == "~") {
			$filename = $_SERVER["HOME"]."/".substr($filename, 1);
		}

		// kontrola zda soubor existuje
		if (!file_exists($filename)) {
			return -1;
		}

		if (!($handle = fopen($filename, 'r'))) {
			return -1;
		}		
		
		return $handle;
	}





	/* Funkce nacte obsah zadaneho souboru a ulozi jej do stringu, ktery vrati
	 * @param - ukazatel na zadany soubor
	 * @return - string obsahujici obsah zadaneho souboru
	 */
	function getResources($filename) {
		$resources = NULL;

		do {
			$resources .= fgetc($filename);
		} while (!feof($filename));

		return $resources;
	}





	/* Funkce vytiskne napovedu k projektu na stdout
	 * @param - funkce neprebira zadne parametry
	 * @return - funkce je typu void
	 */
	function printHelp() {
		echo "Napoveda k projektu 1, SYN: Zvyrazneni syntaxe\n";
		echo "Predmet: IPP 2013/2014\n";
		echo "Autor: Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz\n";
		echo "Popis: Tento skript slouzi k automatickemu zvyraznovani syntaxe ruznych casti textu.\n";
		echo "Skript pracuje s tabulkou regularnich vyrazu, ke kterym bude prirazeno pozadovane vystupni formatovani.\n";
		echo "Skript prijima tyto parametry:\n";
		echo "--help : Vytiskne napovedu k projektu. Nesmi byt kombinovano s jinym parametrem.\n";
		echo "--format=filename : Urceni formatovaciho souboru.\n";
		echo "--input=filename : Urceni vstupniho souboru v kodovani UTF-8.\n";
		echo "--output=filename : Urceni vystupniho souboru v kodovani UTF-8 s naformatovanym vstupnim textem.\n";
		echo "--br : prida element <br /> na konec kazdeho radku vstupniho textu\n";
		return;
	}




	/* Funkce zpracuje a zkontroluje zadane parametry prikazove radky
	 * @param - Jedinym argumentem je pole parametru prikazove radky
	 * @return - Vraci pole obsahujici informace o tom, ktery z parametru byl zadany
	 * @return - Vraci 1 v pripade zadani spatne kombinace parametru.
	 */
	function getParams($argv) {
	
		$foundError = false;

		// flagy pro kontrolu parametru
		$flagArray = array (
			'flagHelp' => NULL, 
			'flagFormat' => NULL, 
			'flagInput' => NULL, 
			'flagOutput' => NULL, 
			'flagBr' => NULL
		);

		$controlCount = 1; // kontrolni pocet parametru

		// zjisteni ktere parametry byly zadany
		for ($i = 1; $i < count($argv); $i++) { 

			switch ($argv[$i]) {

				case "--help":
					if ($flagArray['flagHelp'] == NULL) {
						$flagArray['flagHelp'] = $argv[$i];
						$controlCount++;
					}

					else {
						$flagArray['flagHelp'] = NULL;
						$foundError = true;
					}
					break;

				case strncmp($argv[$i], "--format=", strlen("--format=")):
					if ($flagArray['flagFormat'] == NULL) {
						$flagArray['flagFormat'] = $argv[$i];
						$controlCount++;
					}
				
					else {
						$flagArray['flagFormat'] = NULL;
						$foundError = true;
					}
					break;

				case strncmp($argv[$i], "--input=", strlen("--input="));
					if ($flagArray['flagInput'] == NULL) {
						$flagArray['flagInput'] = $argv[$i];
						$controlCount++;
					}
				
					else {
						$flagArray['flagInput'] = NULL;
						$foundError = true;
					}
					break;

				case strncmp($argv[$i], "--output=", strlen("--output="));
					if ($flagArray['flagOutput'] == NULL) {
						$flagArray['flagOutput'] = $argv[$i];
						$controlCount++;
					}
				
					else {
						$flagArray['flagOutput'] = NULL;
						$foundError = true;
					}
					break;

				case strncmp($argv[$i], "--br", strlen("--br"));
					if ($flagArray['flagBr'] == NULL) {
						$flagArray['flagBr'] = $argv[$i];
						$controlCount++;
					}
				
					else {
						$flagArray['flagBr'] = NULL;
						$foundError = true;
					}
					break;

				default:
					return 1; // pokud se sem vubec dostanu, muzu vratit chybu
		
			}

			if ($foundError) break;			

		}

		if ((($flagArray['flagHelp'] != NULL) && (count($argv) != 2)) || ($controlCount != count($argv))) {
			return 1;
		}
		
		else return $flagArray;
	}

?>


