<?php

	include __DIR__ . '/../entity/Arguments.php';
	include __DIR__ . '/../utils/SearchUtils.php';

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class ArgParser {
		
		private $argc;
		private $argv;
		
		public function __construct($argc, $argv) {
			$this->argc = $argc;
			$this->argv = $argv;
		}
		
		// ======== Methods ============
		
		/**
		 * Parse and validates arguments from command line into Arguments entity.
		 * @throws InvalidArgumentException
		 */
		public function parseArguments() {
			
			$arguments = new Arguments();
			
			foreach ($this->argv as $arg) {
				// ignore scripts first parameter
				if ($arg == $this->argv[0])
					continue;
					
				if (!strcmp($arg, Constant::ARG_FIRST_PHASE)) {
					$arguments->setIsStepOne(true);
				} else if (!strcmp($arg, Constant::ARG_SECOND_PHASE)) {
					$arguments->setIsStepTwo(true);
				} else if (!strcmp($arg, Constant::ARG_THIRD_PHASE)) {
					$arguments->setIsStepThree(true);
				} else if (!strcmp($arg, Constant::ARG_FOURTH_PHASE)) {
					$arguments->setIsStepFour(true);
				} else if (!strcmp($arg, Constant::ARG_EVAL_PHASE)) {
					$arguments->setIsEval(true);
				} else if (!strcmp($arg, Constant::ARG_GEN_PHASE)) {
					$arguments->setIsGenerateFiles(true);
				} else if (!strcmp($arg, Constant::ARG_COMMENTS)) {
					$arguments->setIsRemoveComments(true);
				} else if (!strcmp($arg, Constant::ARG_HELP) || !strcmp($arg, Constant::ARG_HELP_SHORT)) {
					$arguments->setIsHelp(true);
				} else if (!strcmp($arg, Constant::ARG_FORCE)) {
					$arguments->setIsForce(true);
				} else if (strpos($arg, Constant::ARG_INPUT_PATH) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setInputPath($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_OUTPUT_PATH) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setOutputPath($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_INPUT_TEMPLATE_PATH) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setInputTemplatePath($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_INPUT_JSON) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setInputJSON($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_TEMPLATE_JSON) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setTemplateJSON($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_INPUT_CSV) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setInputCSV($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_JSON_NAME) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setJSONOutputFilename($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_CSV_NAME) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setCSVOutputFilename($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_START_INDEX) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setStartIndex($tmpArray[1]);
				} else if (strpos($arg, Constant::ARG_COUNT) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setCount($tmpArray[1]);
				} else {
					Logger::warning('Script was started with unknown parameter: ' . $arg);
				}
			} // end foreach
		
			self::validateArguments($arguments);
			return $arguments;
		}
		
		/**
		 * Validates arguments
		 * @param unknown_type $arguments
		 * @throws InvalidArgumentException
		 */
		private function validateArguments($arguments) {
			$arguments->validateSteps();
			$errorMessage = null;
			
			if ($arguments->getIsHelp() && $this->argc != 2) {
				$errorMessage .= 'Parameter --help can not be combined with other arguments. ';
			}
			
			// if specified, only one phase can be processed
			else if ($arguments->getIsStepOne() && ($arguments->getIsStepTwo() || $arguments->getIsStepThree()
					|| $arguments->getIsStepFour())) {
				$errorMessage .= 'Invalid combination of the script phases. ';			
			} else if ($arguments->getIsStepTwo() && ($arguments->getIsStepOne() || $arguments->getIsStepThree()
					|| $arguments->getIsStepFour())) {
				$errorMessage .= 'Invalid combination of the script phases. ';		
			} else if ($arguments->getIsStepThree() && ($arguments->getIsStepOne() || $arguments->getIsStepTwo()
					|| $arguments->getIsStepFour())) {
				$errorMessage .= 'Invalid combination of the script phases. ';			
			} else if ($arguments->getIsStepFour() && ($arguments->getIsStepOne() || $arguments->getIsStepTwo()
					|| $arguments->getIsStepThree())) {
				$errorMessage .= 'Invalid combination of the script phases. ';
			} else if (($arguments->getIsEval() || $arguments->getIsGenerateFiles()) && self::isSinglePhase($arguments)) {
				$errorMessage .= 'Invalid combination of the script phases. ';
			}
			
			// remove comments is possible only in first phase
			else if ($arguments->getIsRemoveComments() && ($arguments->getIsStepTwo() || $arguments->getIsStepThree()
					|| $arguments->getIsStepFour() || $arguments->getIsEval() || !is_null($arguments->getInputJSON()))) {
				$errorMessage .= 'Comment can be removed only in first phase. ';			
			}
			
			// validates if JSON or CSV file is supplied in later phases	
			else if ($arguments->getIsStepTwo() && is_null($arguments->getInputJSON())) {
				$errorMessage .= 'Missing JSON file. ';
			} else if (($arguments->getIsStepThree() || $arguments->getIsStepFour() || $arguments->getIsEval()) 
					&& (is_null($arguments->getInputJSON()) || is_null($arguments->getInputCSV()))) {
				$errorMessage .= 'Missing JSON or CSV file.';			
			}
			
			// validates paths and files
			if (!is_dir($arguments->getInputPath()))
				$errorMessage .= 'Input path is not valid. ';
			else if (!is_dir($arguments->getOutputPath()))
				$errorMessage .= 'Output path is not valid. ';
			else if (!is_null($arguments->getInputJSON()) && !is_file($arguments->getInputJSON())) 
				$errorMessage .= 'Input JSON file is not valid. ';
			else if (!is_null($arguments->getTemplateJSON()) && !is_file($arguments->getTemplateJSON()))
				$errorMessage .= 'Template JSON file is not valid. ';
			else if (!is_null($arguments->getInputCSV()) && !is_file($arguments->getInputCSV()))
				$errorMessage .= 'Input CSV file is not valid. ';
				
			// validates paging
			if ($arguments->getStartIndex() < 0)
				$errorMessage .= 'Start index must be greater than zero. ';
			if ($arguments->getCount() < 0)
				$errorMessage .= 'Max count per page must be greater than zero. ';
			if ($arguments->getIsForce() && !$arguments->getIsGlobalFlow()) 
				$errorMessage .= 'Can not force evaluating all assignments without executing all phases. ';
			
			// throw exception if any error occurred
			if (!is_null($errorMessage)) {
				$errorMessage .= "\nFor more informations start scrit with parameters '--help' or '-h'";
				Logger::errorFatal($errorMessage);
				throw new InvalidArgumentException($errorMessage);
			}
		}
		
		/**
		 * Returns boolean value whether one of the four phases is active
		 */
		private function isSinglePhase($arguments) {
			return $arguments->getIsStepOne() || $arguments->getIsStepTwo() || $arguments->getIsStepThree() || $arguments->getIsStepFour();
		}
		
		/**
		 * Prints program help to stdin. 
		 */
		// TODO Update --help
		public static function printHelp() {
			$msg = "**************************************** HELP ****************************************\n";
			$msg .= "Author: Ondrej Krpec, xkrpecqt@gmail.com\n";
			$msg .= "Plagiarism detection tool for PHP written as bachelor thesis at FIT VUT Brno, 2015.\n";
			$msg .= "--projects={path} > Path to directory with current projects. Can not be combined with parameter --projectJSON={path}\n";
			$msg .= "--projectJSON={path} > Path to file with current projects in JSON format. Can not be combined with parameter --projects={path}\n";
			$msg .= "--templates={path} > Path to directory with templates projects. Can not be combined with parameter --templateJSON={path}\n";
			$msg .= "--templateJSON={path} > Path to file with template projects in JSON format. Can not be combined with parameter --templates={path}\n";
			$msg .= "--help > Prints out help. Can not be combined with other arguments.\n";
			$msg .= "-c > Force remove comments from projects. Can not be combined with paramters --projectJSON={path} and --templateJSON={path}\n"; 
			
			echo $msg;
		}
				
	}

?>