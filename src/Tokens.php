<?php

	 /**
	  * Class used for transforming source code from one file to stream of tokens and saving them into serialized format .json.
	  * All files will be saved into directory ./../Tokens
	  * @author Ondrej Krpec, xkrpecqt@gmail.com
	  */
	 
	 class Tokenizer {
		 
		 private $fileName;
		 private $filePath;
		 private $content;	  
		  
		  /**
		   * Method will open selected file and load its content in the string. After that it will transform source code
		   * from file to stream of tokens, remove T_WHITESPACEs from it and save it to the .JSON file for further use.
		   * @return bool Returns success of operation.
		   */
		  public function getTokens() {

			  // check if filename is not null
			  if (!$this->getFileName()) return false;

			  // load file
			  if (($this->setContent(file_get_contents($this->getFilePath() . $this->getFileName()))) === false) return false;
			  
			  // get tokens
			  $this->setContent(token_get_all($this->getContent()));
			  
			  // format tokens and remove whitespaces
			  $tmpArray = array();
			  $arrayLen = count($this->getContent());
			  $srcArray = $this->getContent();
			  for ($i = 0; $i < $arrayLen; $i++) {
				  
				  // dont add whitespaces
				  if ($srcArray[$i][0] != T_WHITESPACE) {
					  if (is_numeric($srcArray[$i][0])) array_push($tmpArray, token_name($srcArray[$i][0]));
					  else $srcArray[$i] = array_push($tmpArray, $srcArray[$i]);
				  }
				 
			  }
			  $this->setContent(json_encode($tmpArray));

			  // writes tokens to file
			  if (!file_exists('./../tokens')) { // TODO: tady by to chtelo tu cestu delat mozna jinak
				  if (!mkdir('./../tokens')) return false; // TODO chtelo by to vyhazovat vyjimky a ne vracet false
			  }

			  if (!file_put_contents('./../tokens/' . str_replace('.php', '.json', $this->getFileName()), $this->getContent(), FILE_USE_INCLUDE_PATH)) {
				  return false;
			  }

			  return true;	
			  		  
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
		  * Setter for class variable filename.
		  * @param string Name of the file. This is optional argument.
		  */
		 public function setFileName($fileName = NULL) {
			 $this->fileName = $fileName;
		 }
		 
		 /**
		  * Getter for class variable filename.
		  * @return string Name of the file.
		  */
		 public function getFileName() {
			 return $this->fileName;
		 }
		 
		 /**
		  * Setter for class variable filepath.
		  * @param string Path to the file. This is optional argument.
		  */
		 public function setFilePath($filePath = NULL) {
			 $this->filePath = $filePath;
		 }
		 
		 /**
		  * Getter for class variable filepath.
		  * @return string Path to the file.
		  */
		 public function getFilePath() {
			 return $this->filePath;
		 }
		 
		 /**
		  * Setter for class variable content.
		  * @param string Sets content of the file. This is optional argument.
		  */
		 public function setContent($content = NULL) {
			 $this->content = $content;
		 }
		 
		 /**
		  * Getter for class variable content.
		  * @return string Returns content of the file.
		  */
		 public function getContent() {
			 return $this->content;
		 }
	 }

?>
