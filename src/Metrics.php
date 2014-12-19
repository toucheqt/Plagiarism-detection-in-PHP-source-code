<?php

/* todo: 1. nactu soubor s jsonem
 * 2. dekoduju to do struktury - done
 * 3. zkontrolovat pocet funkci/trid/metod - done
 * 4. vhodne je ulozit - done
 * 5. kontrolovat goto a eval - done
 * 6. haelstedova technika - nebylo by spatne to udelat tak, ze se to automaticky zkontroluje pro vsechny funkce
 * a pokud by to pak nekde naslo urcenou podobnost, tak by se to pak dalo projet i pro pripadne vetveni/cykly NEBO by se to
 * jeste dalo rovnou zapnout parametrem.
 * 
 * 
 * TODO 
 * nejprve potrebuju spocitat vsechny operandy a operatory
 * potom potrebuju spocitat vsechny unikatni operandy a operatory
 * potom to muzu pridat do pole
 * po projiti celeho kodu napisu do halstead.php funkci eval, ktera spocita vsechny ty kravinky okolo
 * ??? profit
 * 
 */
 
	class Metrics {
		
		// default number of functions in every program is one, becouse all program must contain main method
		const DEF_FUNCTION_COUNT = 1;
		
		// default order of tokens; 0 - token name; 1 - original token; 2 - line number
		const TOKEN_NAME = 0;
		const ORIGINAL_TOKEN = 1;
		const LINE_NUMBER = 2;
		
		// variables with file attributes
		private $fileName;
		private $filePath;
		private $content;
		
		// variable containing file metrics
		private $functionCount;
		private $globalVarCount;
		private $globalVarArray;
		private $atUsageCount;
		private $evalCount;
		private $gotoCount;
		
		// array with Halstead objects containing halstead metrics of given code, one element in array equals to one function in given code
		private $halsteadMetrics;
		
		/**
		 * Constructor with no argument, that will only initialize class variables.
		 */
		public function __construct() {
		
			$this->setFunctionCount(self::DEF_FUNCTION_COUNT);
			$this->setGlobalVarCount(0);
			$this->setAtUsageCount(0);
			$this->setGlobalVarArray();
			$this->setEvalCount(0);
			$this->setGotoCount(0);
			$this->halsteadMetrics = array();
			 
		}
		 
		/**
		 * Method will get all useful metrics of code like count of function or methods, global variables or usage
		 * of @ token. All metrics will be stored in class variables.
		 */
		public function getMetrics() {
			
			$isGlobal = false; // variable determines if currently loaded tokens might be global variable - keyword global
			$isGlobalArray = false; // variable determines if currently loaded tokens might be global variable - array $GLOBALS
			$isFunction = false; // variable determines if currently loaded tokens are within function or method
			$bracketCount = 0; // variable to determine the end of function or method by counting left and right brackets
			 
			foreach ($this->getContent() as $value) {
				
				switch ($value[self::TOKEN_NAME]) {
					
					case 'T_FUNCTION':
						$isFunction = true;
						break;
						
					case 'T_VARIABLE':
						if ($value[self::ORIGINAL_TOKEN] === '$GLOBALS') {
							$isGlobalArray = true;
						}
						break;
						
					case ';':
						if ($isFunction && ($bracketCount === 0)) {
							$isFunction = false; // we encounterd only declaration of function
						}
						break;
						
					case '{':
						if ($isFunction) {
							if ($bracketCount === 0) { // new definition of function
								$this->setFunctionCount($this->getFunctionCount() + 1);
							}
							$bracketCount++;
						}
						break;
						
					case 'T_GLOBAL':
						$isGlobal = true;
						break;
						
					case 'T_EVAL':
						$this->setEvalCount($this->getEvalCount() + 1);
						break;
						
					case 'T_GOTO':
						$this->setGotoCount($this->getGotoCount() + 1);
						break;
						
					case '@': 
						$this->setAtUsageCount($this->getAtUsageCount() + 1);
						break;
						
				}
				
				// check global variables if any
				if ($isGlobal || $isGlobalArray) {
					if ($value[self::TOKEN_NAME] === 'T_VARIABLE' && !in_array($value[self::ORIGINAL_TOKEN], $this->getGlobalVarArray())
							&& $value[self::ORIGINAL_TOKEN] !== '$GLOBALS') {
						$this->insertGlobalVarArray($value[self::ORIGINAL_TOKEN]);
						$this->setGlobalVarCount($this->getGlobalVarCount() + 1);
					}
					
					else if ($isGlobalArray && $value[self::TOKEN_NAME] === 'T_CONSTANT_ENCAPSED_STRING') {
								
						$tmpToken = str_replace('\'', '', $value[self::ORIGINAL_TOKEN]);
						$tmpToken = str_replace('"', '', $tmpToken);
						
						if (!in_array($tmpToken, $this->getGlobalVarArray())) {			
							$this->insertGlobalVarArray($tmpToken);
							$this->setGlobalVarCount($this->getGlobalVarCount() + 1);
						}
						
					}
					
				}
				
				// end searching for globals
				if ($isGlobal && $value[self::TOKEN_NAME] == ';') $isGlobal = false;
				if ($isGlobalArray && $value[self::TOKEN_NAME] == ']') $isGlobalArray = false;
				
				// check if is needed to load functions / methods
				if ($isFunction && ($bracketCount > 0)) {
					
					if ($value[self::TOKEN_NAME] == '}') $bracketCount--;
					if ($bracketCount === 0) $isFunction = false;					
				
				}
						
			} // end foreach
			 
		}
		 
		
		/**
		 * Setter for file. This could include even filepath to file. Not just name of the file.
		 * Selected file will be decoded from .json format into class array variable content.
		 * @param string Filename of with optional file path to file.
		 * @return Returns success of operation.
		 */
		public function setFile($file) {
			 
			for ($i = strlen(&$file) - 1; $i >= 0; $i--) {
				if (!strcmp($file[$i], '/')) {
					$this->setFileName(substr($file, $i + 1, strlen($file) - $i));
					$this->setFilePath(substr($file, 0, $i + 1));
					break;
				}		
			}
			 
			if (!$this->decode()) return false;
			 
			return true;
			 	 
		}
		 
		/**
		 * Getter for file stored in this object. File consists of filepath and filename.
		 * @return string Filepath combined with filename.
		 */
		public function getFile() {
			return $this->filePath . $this->fileName;
		}
		 
		/**
		 * Load content of json file, decode the content from json and saves it into class variable content.
		 * @return Returns success of operation.
		 */
		private function decode() {
			 
			// load file
			if (($this->content = file_get_contents($this->getFile())) === false) return false;
			 
			// decode
			$this->content = json_decode(&$this->content);
			return true;
		}
		 
		/**
		 * Setter for class variable filename.
		 * @param string Name of the file. This is optional argument.
		 */
		private function setFileName($fileName = NULL) {
			$this->fileName = $fileName;
		}
		 
		/**
		 * Getter for filename stored in this object. 
		 * @return string Filepath combined with filename.
		 */
		public function getFileName() {
			return $this->fileName;
		}
		 
		/**
		 * Setter for class variable filepath.
		 * @param string Path to the file. This is optional argument.
		 */
		private function setFilePath($filePath = NULL) {
			$this->filePath = $filePath;
		}
		 
		/**
		 * Getter for filepath to file stored in this object. 
		 * @return string Returns file path to the file stored in this object.
		 */
		public function getFilePath() {
			return $this->filePath;
		}
		 
		/**
		 * Setter for class variable content.
		 * @param string Content of the selected file.
		 */
		private function setContent($content) {
			$this->content = $content;
		}
		 
		/**
		 * Getter for the content of the file stored in this object.
		 * @return string Returns content of the file that is stored in this object.
		 */
		public function getContent() {
			return $this->content;
		}
		 
		/**
		 * Setter for class variable function count. Sets the number of function tokens in selected json file.
		 * @param int Number of function tokens in selected file.
		 */
		private function setFunctionCount($count) {
			$this->functionCount = $count;
		}
		 
		/**
		 * Getter for class variable function count. 
		 * @return int Returns count of the appearance of the function tokens in selected json file.
		 */
		public function getFunctionCount() {
			return $this->functionCount;
		}
		 
		/**
		 * Setter for class variable globalVarCount. Sets the number of globalVariable tokens in selected json file.
		 * Known problems: Problem will count following code as two global variables, not just one.
		 * // GLOBAL VAIRABLE DECLARED HERE
		 * $global_variable = 1;
		 * 
		 * // INSIDE FUNCTION
		 * $variable = 'global_variable';
		 * $result = $GLOBALS[$variable] + $GLOBALS['global_variable']; // this will be counted as two due to usage of $variable
		 * @param int Number of globalVariable tokens in selected file.
		 */
		private function setGlobalVarCount($count) {
			$this->globalVarCount = $count;
		}
		 
		/**
		 * Getter for class variable globalVarCount. 
		 * @return int Returns count of the appearance of the globalVariable tokens in selected json file.
		 */
		public function getGlobalVarCount() {
			return $this->globalVarCount;
		}
		 
		/**
		 * Setter for class variable globalVarArray. Variable contains all unique global variables used in class variable content.
		 * @param array Contains unique global variables used in class variable content. If the parameter is NULL, method will create empty array.
		 */
		private function setGlobalVarArray($array = NULL) {
			if (is_null($array)) {
				$this->globalVarArray = array();
				return;
			}
			$this->globalVarArray = $array;
		}
		
		/**
		 * Method will insert given element into class variable globalVarArray at the end of array.
		 * @param string Element (expected original token variable) that will be inserted as the last element of globalVarArray.
		 */
		private function insertGlobalVarArray($element) {
			array_push($this->globalVarArray, $element);
		}
		 
		/**
		 * Getter for class variable globalVarArray. Variable contains all unique global variables used in class variable content.
		 * @return array Returns array with unique global variables used in class variable content.
		 */
		public function getGlobalVarArray() {
			return $this->globalVarArray;
		}
		 
		/**
		 * Setter for class variable atUsageCount. Sets the number of @ tokens in selected json file.
		 * @param int Number of @ tokens in selected file.
		 */
		private function setAtUsageCount($count) {
			$this->atUsageCount = $count;
		}
		 
		/**
		 * Getter for class variable atUsageCount. 
		 * @return int Returns count of the appearance of the @ tokens in selected json file.
		 */
		public function getAtUsageCount() {
			return $this->atUsageCount;
		}
		
		/**
		 * Setter for class variable evalCount. Sets the number of T_EVAL tokens in selected json file.
		 * @param int Number of T_EVAL tokens in selected file.
		 */
		private function setEvalCount($count) {
			$this->evalCount = $count;
		}
		
		/**
		 * Getter for class variable evalCount.
		 * @return int Returns count of the appearance of the T_EVAL tokens in selected json file.
		 */
		public function getEvalCount() {
			return $this->evalCount;
		}
		
		/**
		 * Setter for class variable gotoCount. Sets the number of T_GOTO tokens in selected json file.
		 * @param int Number of T_GOTO tokens in selected file.
		 */
		private function setGotoCount($count) {
			$this->gotoCount = $count;
		}
		
		/**
		 * Getter for class variable gotoCount.
		 * @return int Returns count of the appearance of the T_GOTO tokens in selected json file.
		 */
		public function getGotoCount() {
			return $this->gotoCount;
		}
		
		/**
		 * Method will add given Halstead object to the class list with halstead metrics.
		 * @param Halstead Halstead metrics of specified function.
		 */
		public function addHalsteadMetric($halstead) {
			array_push($this->halsteadMetrics, $halstead);
		}
		
		/**
		 * Getter for class variable halsteadMetrics.
		 * @return array Returns array with Halstead objects containing halstead metrics of specified file.
		 */
		public function getHalsteadMetrics() {
			return $this->halsteadMetrics;
		}
		  
	}
	
?>
