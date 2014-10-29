<?php

	/* todo: jako parametr se preda slozka obsahujici soubory ke kontrole
	 * potom s kazdym souborem provedu nasledujici:
	 * 1. nacist
	 * 2. prevest na tokeny
	 * 3. ulozit do vhodne struktury
	 * 4. zavrit soubor a pokracovat s dalsim
	 */
	 
	 class Tokenizer {
		 
		 private $fileName;
		 private $content;
		 	 
		 /**
		  * Constructor with one argument - filename. This argument will be set to class variable fileName
		  * @param string Filename of file that will be processed. This argument is optional.
		  */
		  public function Tokenizer($fileName = NULL) {
			  $this->fileName = $fileName;
			  $this->content = NULL;
		  }
		  
		  public function getTokens() {
			  
			  // check if filename is not null
			  if (!isset($this->fileName)) return false;
			  
			  // load file
			  if (($this->content = file_get_contents($this->fileName)) === false) return false;
			  
			  // get tokens
			  $this->content = token_get_all(&$this->content);
			  
			  foreach($this->content as &$value) {
				  echo $value[0]."\n";
				  $value = token_name($value[0]);
			  }
			  print_r( $this->content);
			  
			  //$this->content = json_encode(&$this->content);

			  return true;			  
		  }
		 
		 /**
		  * Setter for variable fileName. Sets name of file that will be converted to stream of tokens.
		  * @param string Filename of file that will be processed.
		  */
		 public function setFileName($fileName) {
			 $this->fileName = $fileName;
		 }
		 
		 /**
		  * Getter for variable fileName. Returns name of the file that will be converted to stream of tokens.
		  * @return string Filename of file that will be processed.
		  */
		 public function getFileName() {
			 return $this->fileName;
		 }
	 }
	 
	 $tokenizer = new Tokenizer("./Tests/EnWhitespace2.php");
	 //for ($i = 0; $i < 700; $i++) {
	 $tokenizer->getTokens();
	//}
	

?>
