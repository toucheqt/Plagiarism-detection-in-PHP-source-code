<?php

	include __DIR__ . '/../constants/Constant.php';

	/**
	 * Entity for storing program arguments
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Arguments {
		
		/** If set on true, script will proceed throught all five steps if mandatory arguments are correctly filled. */
		private $isGlobalFlow = true;
		
		private $isStepOne = false;
		private $isStepTwo = false;
		private $isStepThree = false;
		private $isStepFour = false;
		private $isStepFive = false;
		
		/** If set on true, script will proceed through step three, four and five. */
		private $isEval = false;
		
		/** If set on true, script will proceed through step one and two. */
		private $isGenerateFiles;
		
		private $inputPath = Constant::DEFAULT_PATH;
		private $outputPath = Constant::DEFAULT_PATH;
		private $inputJSON = null;
		private $templateJSON = null;
		private $inputCSV = null;
		
		private $csvOutputFilename = Constant::DEFAULT_FILENAME;
		private $jsonOutputFilename = Constant::DEFAULT_FILENAME;
		
		private $isRemoveComments = false;
		
		private $isHelp = false;
		
		private $startIndex = Constant::START_INDEX;
		private $count = Constant::COUNT;
		
		private $force = false;
		
		
		// ======= Constructors =======
		public function __construct() {}
		
		// ====== Methods ======
		
		/**
		 * 
		 * Validates script workflow and fixes it, if its incorrect.
		 */
		private function validateSteps() {
			if ($this->isStepOne || $this->isStepTwo || $this->isStepThree || $this->isStepFour || $this->isStepFive 
					|| $this->isEval || $this->isGenerateFiles)
				$this->isGlobalFlow = false;
		}
		
		
		// ======= Getters/Setters =======
		
		public function getIsGlobalFlow() {
			return $this->isGlobalFlow;
		}
		
		public function setIsGlobalFlow($isGlobalFlow) {
			$this->isGlobalFlow = $isGlobalFlow;
		}
		
		public function getIsStepOne() {
			return $this->isStepOne;
		}
		
		public function setIsStepOne($isStepOne) {
			$this->isStepOne = $isStepOne;
			$this->validateSteps();
		}
		
		public function getIsStepTwo() {
			return $this->isStepTwo;
		}
		
		public function setIsStepTwo($isStepTwo) {
			$this->isStepTwo = $isStepTwo;
			$this->validateSteps();
		}
		
		public function getIsStepThree() {
			return $this->isStepThree;
		}
		
		public function setIsStepThree($isStepThree) {
			$this->isStepThree = $isStepThree;
			$this->validateSteps();
		}
		
		public function getIsStepFour() {
			return $this->isStepFour;
		}
		
		public function setIsStepFour($isStepFour) {
			$this->isStepFour = $isStepFour;
			$this->validateSteps();
		}
		
		public function getIsStepFive() {
			return $this->isStepFive;
		}
		
		public function setIsStepFive($isStepFive) {
			$this->isStepFive = $isStepFive;
			$this->validateSteps();
		}
		
		public function getIsEval() {
			return $this->isEval;
		}
		
		public function setIsEval($isEval) {
			$this->isEval = $isEval;
		}
		
		public function getIsGenerateFiles() {
			return $this->isGenerateFiles;
		}
		
		public function setIsGenerateFiles($isGenerateFiles) {
			$this->isGenerateFiles = $isGenerateFiles;
		}
		
		public function getInputPath() {
			return $this->inputPath;
		}
		
		public function setInputPath($inputPath) {
			$this->inputPath = $inputPath;
		}
		
		public function getOutputPath() {
			return $this->outputPath;
		}
		
		public function setOutputPath($outputPath) {
			$this->outputPath = $outputPath;
		}
		
		public function getJsonOutputFilename() {
			return $this->jsonOutputFilename;
		}
		
		public function setJsonOutputFilename($filename) {
			$this->jsonOutputFilename = $filename;
		}
		
		public function getIsRemoveComments() {
			return $this->isRemoveComments;
		}
		
		public function setIsRemoveComments($isRemoveComments) {
			$this->isRemoveComments = $isRemoveComments;
		}
		
		public function getInputJSON() {
			return $this->inputJSON;
		}
		
		public function setInputJSON($inputJSON) {
			$this->inputJSON = $inputJSON;
		}
		
		public function getTemplateJSON() {
			return $this->templateJSON;
		}
		
		public function setTemplateJSON($templateJSON) {
			$this->templateJSON = $templateJSON;
		}
		
		public function getInputCSV() {
			return $this->inputCSV;
		}
		
		public function setInputCSV($inputCSV) {
			$this->inputCSV = $inputCSV;
		}
		
		public function getCsvOutputFilename() {
			return $this->csvOutputFilename;
		}
		
		public function setCsvOutputFilename($csvOutputFilename) {
			$this->csvOutputFilename = $csvOutputFilename;
		}
		
		public function getIsHelp() {
			return $this->isHelp;
		}
		
		public function setIsHelp($isHelp) {
			$this->isHelp = $isHelp;
		}
		
		public function getStartIndex() {
			return $this->startIndex;
		}
		
		public function setStartIndex($startIndex) {
			$this->startIndex = $startIndex;
		}
		
		public function getCount() {
			return $this->count;
		}
		
		public function setCount($count) {
			$this->count = $count;
		}
		
		public function getIsForce() {
			return $this->force;
		}
		
		public function setIsForce($force) {
			$this->force = $force;
		}
	}

?>