<?php

	/**
	 * Entity for storing program arguments
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Arguments {
		
		private $projectsPath;
		private $templatesPath;
		private $projectJSONPath;
		private $templateJSONPath;
		
		private $isHelp = false;
		private $isRemoveComments = false;
		
		// ======= Getters/Setters ======
		
		public function getProjectsPath() {
			return $this->projectsPath;
		}
		
		public function setProjectsPath($projectsPath) {
			$this->projectsPath = $projectsPath;
		} 
		
		public function getTemplatesPath() {
			return $this->templatesPath;
		}
		
		public function setTemplatesPath($templatesPath) {
			$this->templatesPath = $templatesPath;
		}
		
		public function getProjectJSONPath() {
			return $this->projectJSONPath;
		}
		
		public function setProjectJSONPath($projectJSONPath) {
			$this->projectJSONPath = $projectJSONPath;
		}
		
		public function getTemplateJSONPath() {
			return $this->templateJSONPath;
		}
		
		public function setTemplateJSONPath($templateJSONPath) {
			$this->templateJSONPath = $templateJSONPath;
		}
		
		public function getIsHelp() {
			return $this->isHelp;
		}
		
		public function setIsHelp($isHelp) {
			$this->isHelp = $isHelp;
		}
		
		public function getIsRemoveComments() {
			return $this->isRemoveComments;
		}
		
		public function setIsRemoveComments($isRemoveComments) {
			$this->isRemoveComments = $isRemoveComments;
		}
		 
	}

?>