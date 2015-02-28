<?php

	include '../entity/Arguments.php';
	include '../utils/SearchUtils.php';

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
				
				if (!strcmp('--workingDir', $argument) || !strcmp('templateDir', $argument)) {
					$lastArgument = $argument;
				}
				else if (!strcmp("--help", $argument) == 0) {
					return new Arguments(NULL, true);
				}
				
				else if (strcmp('-c', $argument)) {
					$this->isRemoveComments = true;
				}
			} // end foreach
			
			self::validateArgumentsCorectness();
			
			$arguments = new Arguments($this->workingDirectory);
			$arguments->setTemplateDirectory($this->templateDirectory);
			$arguments->setIsRemoveComments($this->isRemoveComments);
			
			return $arguments;
			
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
		 * Validates if used arguments are ok. 
		 */
		public function validateArgumentsCorectness() {
			$errorMessage = null;
			
			if (!is_dir($this->workingDirectory))
				$errorMessage = "Working directory is not valid. ";
				
			if (!is_dir($this->templateDirectory))
				$errorMessage = "Template directory is not valid. ";
				
			if (!is_null($errorMessage)) {
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
			$msg .= "--help > Prints help. Can not be combined with other arguments.\n";
			$msg .= "-c > Remove comments from source projects.\n";
			echo $msg;
		}
				
	}

?>