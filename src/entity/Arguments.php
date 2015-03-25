<?php

	include_once './../constants/Constant.php';

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
		
		/** If set on true, script will proceed throught step three, four and five. */
		private $isEval = false;
		
		
		// Step 1 variables
		private $inputPath = Constant::DEFAULT_PATH;
		private $outputPath = Constant::DEFAULT_PATH;
		
		private $jsonOutputFilename = Constant::DEFAULT_FILENAME;
		
		private $isRemoveComments = false;
		
		
		// ======= Constructors =======
		public function __construct() {}
		
		// ====== Methods ======
		
		/**
		 * 
		 * Validates script workflow and fixes it, if its incorrect.
		 */
		private function validateSteps() {
			if ($this->isStepOne || $this->isStepTwo || $this->isStepThree || $this->isStepFour || $this->isStepFive || $this->isEval)
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
			
		 
	}

?>