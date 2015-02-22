<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class ArgParser {
		
		private $argc;
		private $argv;
		
		private $workingDirectory;
		private $templateDirectory;
		
		private $isRemoveComments;
		
		public function __construct($argc, $argv) {
			$this->argc = $argc;
			$this->argv = $argv;
			$this->workingDirectory = null;
			$this->templateDirectory = null;
			$this->isRemoveComments = false;
		}
		
		public function parseArguments() {
			self::validateArgumentsPresence();
			
			$lastArgument = null;
			foreach ($this->argv as $argument) {
				
				if (!is_null($lastArgument)) {
					switch ($lastArgument) {
						case '--workingDir':
							$this->workingDirectory = $argument;
							break;
							
						case '--templateDir':
							$this->templateDirectory = $argument;
							break;
					}
					$lastArgument = null;
				}
				
				if (strcmp('--workingDir', $argument) || strcmp('templateDir', $argument)) {
					$lastArgument = $argument;
				}
				
				else if (strcmp('-c', $argument)) {
					$this->isRemoveComments = true;
				}
			} // end foreach
			
			// todo self::validateArguments();
		}
		
		/**
		 * Validates if mandatory arguments are filled
		 * @throws InvalidArgumentException
		 */
		private function validateArgumentsPresence() {
			$errorMessage = null;
			
			if (!in_array("--workingDir", $this->argv)) {
				$errorMessage = "Missing argument: --workingDir. ";
			}
			else if (!in_array("--templateDir", $this->argv)) {
				$errorMessage = "Missing argument: --templateDir. ";
			}
			
			if (!is_null($errorMessage)) {
				$errorMessage .= "For more information start script with argument --help.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
		}
		
		// =========== Getters/Setters =========
		
		public function getWorkingDirectory() {
			return $this->workingDirectory;
		}
		
		public function setWorkingDirectory($workingDirectory) {
			$this->workingDirectory = $workingDirectory;
		}
		
		public function getTemplateDirectory() {
			return $this->templateDirectory;
		}
		
		public function setTemplateDirectory($templateDirectory) {
			$this->templateDirectory = templateDirectory;
		}
		
		public function getIsRemoveComments() {
			return $this->isRemoveComments;
		}
		
		public function setIsRemoveComments($isRemoveComments) {
			$this->isRemoveComments = $isRemoveComments;
		}
		
	}

?>