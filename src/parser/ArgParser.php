<?php

	include '../units/SearchUtils.php';

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
		private $isHelp;
		
		public function __construct($argc, $argv) {
			$this->argc = $argc;
			$this->argv = $argv;
			$this->workingDirectory = null;
			$this->templateDirectory = null;
			$this->isRemoveComments = false;
			$this->isHelp = false;
		}
		
		public function parseArguments() {
			self::validateArgumentsPresence();
			if ($this->isHelp)
				return;
			
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
				
				else if (strcmp('--help', $argument)) {
					$this->isHelp = true;
				}
				
				else if (strcmp('-c', $argument)) {
					$this->isRemoveComments = true;
				}
			} // end foreach
		}
		
		/**
		 * Validates if mandatory arguments are filled
		 * @throws InvalidArgumentException
		 */
		private function validateArgumentsPresence() {
			$errorMessage = null;
			
			if (SearchUtils::inArray('--help', $this->argv) && $this->argc != 2) {
				$errorMessage = "Wrong arguments. ";
			}
			
			else if (!SearchUtils::inArray('--workingDir', $this->argv)) {
				$errorMessage = "Missing argument: --workingDir. ";
			}
			else if (!SearchUtils::inArray('--templateDir', $this->argv)) {
				$errorMessage = "Missing argument: --templateDir. ";
			}
			
			if (!is_null($errorMessage)) {
				$errorMessage .= "For more information start script with argument --help.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
		}
		
		/**
		 * Prints program help to stdin. 
		 */
		public function printHelp() {
			$msg = "**************************************** HELP ****************************************\n";
			$msg .= "Author: Ondrej Krpec, xkrpecqt@gmail.com\n";
			$msg .= "Plagiarism detection tool for PHP written as bachelor thesis at FIT VUT Brno, 2015.\n";
			$msg .= "Mandatory arguments:\n";
			$msg .= "--workingDir path > Path to directory with current projects.\n";
			$msg .= "--templateDir path > Path to directory with stored projects.\n";
			$msg .= "Optional arguments:\n";
			$msg .= "--help > Prints help. Can not be combined with other arguments.";
			$msg .= "-c > Remove comments from source projects.\n";
			echo $msg;
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
		
		public function getIsHelp() {
			return $this->isHelp;
		}
		
		public function setIsHelp($isHelp) {
			$this->isHelp = $isHelp;
		}
		
	}

?>