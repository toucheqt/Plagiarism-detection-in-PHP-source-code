<?php

/* todo: 1. nactu soubor s jsonem
 * 2. dekoduju to do struktury
 * 3. zkontrolovat pocet funkci/trid/metod
 * 4. vhodne je ulozit
 * 5. haelstedova technika
 */
 
	class Metrics {
		
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
				 $this->fileName = NULL;
				 $this->filePath = NULL;
			 }
			 $this->content = NULL;
		 }
		 
		 public function getContent() { // dbg private
			 
			 // load file
			 if (($this->content = file_get_contents($this->filePath . $this->fileName)) === false) return false;
			 
			 // decode
			 $this->content = json_decode(&$this->content);
			 
			 print_r( $this->content);
		 }
		
		 /**
		  * Setter for file. This could include even filepath to file. Not just name of the file.
		  * @param string Filename of with optional file path to file.
		  */
		 public function setFile($file) {
			 
			 for ($i = strlen(&$file) - 1; $i >= 0; $i--) {
				 if (!strcmp($file[$i], '/')) {
					 $this->fileName = substr($file, $i + 1, strlen(&$file) - $i);
					 $this->filePath = substr($file, 0, $i + 1);
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
		 
		// public function getCount
	}
	
	$metrics = new Metrics("./../Tokens/HelloWorld.json");
	$metrics->getContent();


?>
