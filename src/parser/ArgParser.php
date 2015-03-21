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
		
		const PROJECT_DIRECTORY = '--projects=';
		const PROJECT_JSON_DIRECTORY = '--projectJSON=';
		const TEMPLATE_DIRECTORY = '--templates=';
		const TEMPLATE_JSON_DIRECTORY = '--templateJSON=';
		const HELP = '--help';
		const REMOVE_COMMENTS = '-c';
		
		private $argc;
		private $argv;
		
		private $isRemoveComments;
		
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
				if ($arg == $this->argv[0]) {
					continue;					
				}

				if (strpos($arg, self::PROJECT_DIRECTORY) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setProjectsPath($tmpArray[1]);
				}
				
				else if (strpos($arg, self::PROJECT_JSON_DIRECTORY) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setProjectJSONPath($tmpArray[1]);
				}
				
				else if (strpos($arg, self::TEMPLATE_DIRECTORY) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setTemplatesPath($tmpArray[1]);
				}
				
				else if (strpos($arg, self::TEMPLATE_JSON_DIRECTORY) !== false) {
					$tmpArray = explode('=', $arg, 2);
					$arguments->setTemplateJSONPath($tmpArray[1]);
				}
				
				else if (!strcmp($arg, self::HELP)) {
					$arguments->setIsHelp(true);
				}
				
				else if (!strcmp($arg, self::REMOVE_COMMENTS)) {
					$arguments->setIsRemoveComments(true);
				}
				
				else {
					Logger::warning('Script was started with unknown parameter: ' . $arg);
				}
			}
			
			self::validateArguments($arguments);
			
			return $arguments;
		}
		
		/**
		 * Validates arguments
		 * @param unknown_type $arguments
		 * @throws InvalidArgumentException
		 */
		private function validateArguments($arguments) {
			$errorMessage = null;
			
			if ($arguments->getIsHelp() && $this->argc != 2) {
				$errorMessage .= 'Can not combine \'--help\' with other arguments. ';
			}
			
			// validates project path
			if (is_null($arguments->getProjectsPath()) && is_null($arguments->getProjectJSONPath())) {
				$errorMessage .= 'Projects path was not specified. ';
			}

			else if (!is_null($arguments->getProjectsPath()) && !is_null($arguments->getProjectJSONPath())) {
				$errorMessage .= 'Expected only one project path. ';
			}
			
			else if (!is_null($arguments->getProjectsPath()) && is_null($arguments->getProjectJSONPath())) {
				if (!is_dir($arguments->getProjectsPath())) {
					$errorMessage .= 'Specified projects path is not valid. ';
				}
			}
			
			else if (is_null($arguments->getProjectsPath()) && !is_null($arguments->getProjectJSONPath())) {
				if (!is_file($arguments->getProjectJSONPath())) {
					$errorMessage .= 'Specified project file is not valid. ';
				}
			}
			
			// validates templates path
			if (!is_null($arguments->getTemplatesPath()) && !is_null($arguments->getTemplateJSONPath())) {
				$errorMessage .= 'Expected only one template path. ';
			}
			
			else if (!is_null($arguments->getTemplatesPath()) && is_null($arguments->getTemplateJSONPath())) {
				if (!is_dir($arguments->getTemplatesPath())) {
					$errorMessage .= 'Specified templates path is not valid. ';
				}
			} 
			
			else if (is_null($arguments->getTemplatesPath()) && !is_null($arguments->getTemplateJSONPath())) {
				if (!is_file($arguments->getTemplateJSONPath())) {
					$errorMessage .= 'Specified template file is not valid. ';
				}
			}
			
			// validate isRemoveComments
			if (!is_null($arguments->getProjectJSONPath()) && $arguments->getIsRemoveComments()) {
				$errorMessage .= 'Can not remove comments from projects JSON file. ';
			}
			
			if (!is_null($arguments->getTemplateJSONPath()) && $arguments->getIsRemoveComments()) {
				$errorMessage .= 'Can not remove comments from templates JSON file. ';
			}
			
			
			// throw exception if any error occurred
			if (!is_null($errorMessage)) {
				$errorMessage .= "\nFor more informations start script with argument '--help'";
				Logger::errorFatal($errorMessage);
				throw new InvalidArgumentException($errorMessage);
			}
		}
		
		/**
		 * Prints program help to stdin. 
		 */
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