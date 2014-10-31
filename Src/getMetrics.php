<?php

/* todo: 1. nactu soubor s jsonem
 * 2. dekoduju to do struktury
 * 3. zkontrolovat pocet funkci/trid/metod
 * 4. vhodne je ulozit
 * 5. haelstedova technika
 */
 
	class Metrics {
		
		// default number of functions in every program is one, becouse all program must contain main method
		const DEF_FUNCTION_COUNT = 1;
		
		// variables with file attributes
		private $fileName;
		private $filePath;
		private $content;
		
		private $functionCount;
		
		/**
		 * Constructor with one argument - filename. This argument will be set to class variable fileName.
		 * @param string Filename of file that will be processed. This argument is optional.
		 */
		 public function Metrics($file = NULL) {
			 
			 if (isset($file)) $this->setFile($file);
			 else {
				 $this->setFileName();
				 $this->setFilePath();
			 }
			 
			 $this->setFunctionCount('DEF_FUNCTION_COUNT');
			 
		 }
		
		 /**
		  * Setter for file. This could include even filepath to file. Not just name of the file.
		  * @param string Filename of with optional file path to file.
		  */
		 public function setFile($file) {
			 
			 for ($i = strlen(&$file) - 1; $i >= 0; $i--) {
				 if (!strcmp($file[$i], '/')) {
					 $this->setFileName(substr($file, $i + 1, strlen($file) - $i));
					 $this->setFilePath(substr($file, 0, $i + 1));
					 break;
				 }		
			 }	
			 	 
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
		  */
		 private function decode() {
			 
			 // load file
			 if (($this->content = file_get_contents($this->filePath . $this->fileName)) === false) return false;
			 
			 // decode
			 $this->content = json_decode(&$this->content);
			 
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
		 private function getFileName() {
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
		 private function getFilePath() {
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
		 private function getContent() {
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
		 private function getFunctionCount() {
			 return $this->functionCount;
		 }	 
	}
	
	$metrics = new Metrics("./../Tokens/Function.json");
	//$metrics->decode();


?>
