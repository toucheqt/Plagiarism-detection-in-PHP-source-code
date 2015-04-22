<?php
	
	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Enviroment {
		
		// JSON objects
		private $templates = null;
		private $projects = null;
		
		// CSV file
		private $matchedPairs = null;
		
		// ========= functions ================
		
		/**
		 * Creates single page of matched pairs for comparison. This is due to comparison being time consuming and comparing all
		 * assignments in a row would take very long time. 
		 */
		public function createPage($startIndex, $count) {
			$page = array();
			for ($i = $startIndex; $i < $startIndex * $count; $i++) {
				$page[] = $this->matchedPairs[$i];
			}
			$this->matchedPairs = $page;
		}
		
		// ======== getters/setters ============
		
		public function getTemplates() {
			return $this->templates;
		}
		
		public function setTemplates($templates) {
			$this->templates = $templates;
		}
		
		public function getProjects() {
			return $this->projects;
		}
		
		public function setProjects($projects) {
			$this->projects = $projects;
		}
		
		public function getMatchedPairs() {
			return $this->matchedPairs;
		}
		
		public function setMatchedPairs($matchedPairs) {
			$this->matchedPairs = $matchedPairs;
		}
		
	}

?>