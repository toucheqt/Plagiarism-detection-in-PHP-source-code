<?php
	
	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Enviroment {
		
		// JSON objects
		private $template = null;
		private $project = null;
		
		// CSV file
		private $matchedPairs = null;
		
		public function getTemplate() {
			return $this->template;
		}
		
		public function setTemplate($template) {
			$this->template = $template;
		}
		
		public function getProject() {
			return $this->project;
		}
		
		public function setProject($project) {
			$this->project = $project;
		}
		
		public function getMatchedPairs() {
			return $this->matchedPairs;
		}
		
		public function setMatchedPairs($matchedPairs) {
			$this->matchedPairs = $matchedPairs;
		}
		
	}

?>