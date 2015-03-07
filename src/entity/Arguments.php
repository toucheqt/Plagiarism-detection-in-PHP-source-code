<?php

	/**
	 * Entity for storing program arguments
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Arguments {
		
		private $workingDirectory;
		private $templateDirectory;
		
		private $isRemoveComments;
		private $isHelp;
		
		public function __construct($workingDirectory, $isHelp = NULL) {
			$this->workingDirectory = $workingDirectory;
			$this->templateDirectory = null;
			$this->isRemoveComments = false;
			$this->isHelp = false;
			if (!is_null($isHelp))
				$this->isHelp = $isHelp;
		}
		
		// ======= Getters/Setters ========
		
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
			$this->templateDirectory = $templateDirectory;
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