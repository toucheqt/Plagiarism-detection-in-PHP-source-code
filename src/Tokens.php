<?php

	/**
	 * Class used for transforming source code from one file to stream of tokens and saving them into serialized format .json.
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 */
	 
	class Tokenizer {
		 
	private $fileName;
	private $filePath;
	private $content;	  
		  
		/**
		 * Method will open selected file and load its content into string. After that it will transform source code
		 * from that string to stream of tokens, removing T_WHITESPACEs from it and save it to the .JSON file for further use.
		 * @param filePath Determines path where should be saved tokens loaded from file in class variable. At the end of the path will
		 * 		always be created directory /tokens.
		 * 		If the parameter is NULL, then directory /tokens will be created in current directory.
		 * @return bool Returns success of operation.
		 */
		 public function getTokens($filePath = NULL) {
			  
			if (!isset($filePath)) $filePath = '.';

			// check if filename is not null
			if (!$this->getFileName()) return false;

			// load file
			if (($this->setContent(file_get_contents($this->getFilePath() . $this->getFileName()))) === false) return false;
			  
			// get tokens
			$this->setContent(token_get_all($this->getContent()));
			  
			// format tokens and remove whitespaces
			$tmpArray = array();
			for ($i = 0; $i < count($this->getContent()); $i++) {  
				if ($this->getContentElement($i, 0) != T_WHITESPACE) { // dont add whitespaces
					if (is_numeric($this->getContentElement($i, 0))) {
						$this->setContentElement(token_name($this->getContentElement($i, 0)), $i, 0);
					}
					array_push($tmpArray, $this->getContentElement($i));
				}
			}

			$this->setContent(json_encode($tmpArray));

			// create /tokens directory at certain path
			if (!file_exists($filePath . '/tokens')) {
				if (!mkdir($filePath . '/tokens')) return false;
			}

			// saves tokens into .json file
			if (!file_put_contents($filePath . '/tokens/' . str_replace('.php', '.json', $this->getFileName()), $this->getContent(), FILE_USE_INCLUDE_PATH)) {
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
		 
		/**
		 * Setter for class variable content that will return element at specified position.
		 * @param string Element that will be stored into specified position in content.
		 * @param int X coordinate of element.
		 * @param int Y coordinate of element. If the variable is NULL will set whole element into X position.
		 */
		public function setContentElement($element, $posX, $posY = NULL) {
			if (is_null($posY)) {
				$this->content[$posX] = $element;
				return;
			}
			$this->content[$posX][$posY] = $element;
		}
		
		/**
		 * Getter for class variable content that will return element at specified position.
		 * @param int X coordinate of element.
		 * @param int Y coordinate of element. If the variable is NULL then method will return whole element at position X.
		 * @return string Element from class variable content at specified position.
		 */
		public function getContentElement($posX, $posY = NULL) {
			if (is_null($posY)) return $this->content[$posX];
			return $this->content[$posX][$posY];
		}
	 }

?>
