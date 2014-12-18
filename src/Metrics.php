<?php

/* todo: 1. nactu soubor s jsonem
 * 2. dekoduju to do struktury - done
 * 3. zkontrolovat pocet funkci/trid/metod - done
 * 4. vhodne je ulozit - done
 * 5. kontrolovat goto a eval
 * 6. haelstedova technika - nebylo by spatne to udelat tak, ze se to automaticky zkontroluje pro vsechny funkce
 * a pokud by to pak nekde naslo urcenou podobnost, tak by se to pak dalo projet i pro pripadne vetveni/cykly NEBO by se to
 * jeste dalo rovnou zapnout parametrem.
 * 
 * 
 * 
 * 
 */
 
	class Metrics {
		
		// default number of functions in every program is one, becouse all program must contain main method
		const DEF_FUNCTION_COUNT = 1;
		
		// variables with file attributes
		private $fileName;
		private $filePath;
		private $content;
		
		private $functionCount;
		private $globalVarCount;
		private $atUsageCount;
		
		/**
		 * Constructor with no argument, that will only initialize class variables.
		 */
		 public function __construct() {
		
			 $this->setFunctionCount(self::DEF_FUNCTION_COUNT);
			 $this->setGlobalVarCount(0);
			 $this->setAtUsageCount(0);
			 
		 }
		 
		 /**
		  * Method will get all useful metrics of code like count of function or methods, global variables or usage
		  * of @ token. All metrics will be stored in class variables.
		  */
		 public function getMetrics() {
			 
			 foreach ($this->getContent() as $value) {
				 if ($value[0] == 'T_FUNCTION') $this->setFunctionCount($this->getFunctionCount() + 1);
				 else if ($value[0] == 'T_GLOBAL') $this->setGlobalVarCount($this->getGlobalVarCount() + 1);
				 else if ($value[0] == '@') $this->setAtUsageCount($this->getAtUsageCount() + 1);
			 }
			 
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
		  
	}
	
?>
