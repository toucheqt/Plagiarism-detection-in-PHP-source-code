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
		  * Constructor with one argument - filename. This argument will be set to class variable fileName
		  * @param string Filename of file that will be processed. This argument is optional.
		  */
		  public function Tokenizer($file = NULL) {
			  if (!isset($file)) $this->setFile($file);
			  else {
				  $this->fileName = NULL;
				  $this->filePath = NULL;
			  }
			  $this->content = NULL;
		  }
		  
		  /**
		   * Method will open selected file and load its content in the string. After that it will transform source code
		   * from file to stream of tokens, remove T_WHITESPACEs from it and save it to the .JSON file for further use.
		   * @return bool Returns success of operation.
		   */
		  public function getTokens() {
			  
			  // check if filename is not null
			  if (!isset($this->fileName)) return false;
			  
			  // load file
			  if (($this->content = file_get_contents($this->filePath . $this->fileName)) === false) return false;
			  
			  // get tokens
			  $this->content = token_get_all(&$this->content);
			  
			  // format tokens and remove whitespaces
			  $tmpArray = array();
			  $arrayLen = count(&$this->content);
			  for ($i = 0; $i < $arrayLen; $i++) {
				  
				  // dont add whitespaces
				  if ($this->content[$i][0] != T_WHITESPACE) { 
					  if (is_numeric($this->content[$i][0])) array_push($tmpArray, token_name($this->content[$i][0]));
					  else $this->content[$i] = array_push($tmpArray, $this->content[$i]);
				  }
				 
			  }
			  $this->content = $tmpArray;

			  // writes tokens to file
			  if (!file_exists('./../Tokens')) {
				  if (!mkdir('./../Tokens')) return false;
			  }
			  
			  if (!file_put_contents('./../Tokens/' . str_replace('.php', '.json', &$this->fileName), &$this->content, FILE_USE_INCLUDE_PATH)) {
				  return false;
			  }

			  $this->content = json_encode(&$this->content);

			  return true;			  
		  }
		 
		 /**
		  * Setter for file. This could include even filepath to file. Not just name of the file.
		  * @param string Filename of with optional file path to file.
		  */
		 public function setFile($file) {
			 
			 for ($i = strlen(&$file) - 1; $i >= 0; $i--) {
				 if (!strcmp($file[$i], "/")) {
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
	 }

?>
