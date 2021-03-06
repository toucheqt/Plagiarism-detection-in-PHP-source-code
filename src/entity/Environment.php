<?php
	
	/**
	 * 
	 * Entity that saves script's workflow data.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class Environment {
		
		############################  VARIABLES AND CONSTANT  ###########################
		
		// JSON objects
		private $templates = null;
		private $projects = null;
		
		// CSV file
		private $matchedPairs = null;
		
		// CSV file after third phase
		private $shallowOutput = null;
		
		// Pairs for deeper analysis
		private $depthAnalysisPairs = null;
		
		####################################  METHODS  ##################################
		
		/**
		 * Creates single page of matched pairs for comparison. This is due to comparison being time consuming and comparing all
		 * assignments in a row would take very long time. 
		 * @param $startIndex Number of page to start with.
		 * @param $count Number of pairs per page.
		 */
		public function createPage($startIndex, $count) {
			$finalCount = 0;
			$isEmpty = true;
			foreach ($this->matchedPairs as $iterable) {
				$finalCount++;
			}
			$page = array();
			if ($startIndex < 0) $startIndex = 0;
			($finalCount < $count) ? $finalCount = count($this->matchedPairs) : $finalCount = $count;
			
			for ($i = $startIndex * $count; $i < ($startIndex * $count) + $count; $i++) {
				if (@!is_null($this->matchedPairs[$i])) {
					$page[] = $this->matchedPairs[$i];
					$isEmpty = false;
				}
			}
			
			if ($isEmpty) {
				Logger::warning('There are no projects on selected page. ');
			}
			
			$this->matchedPairs = $page;
		}
		
		##############################  GETTERS AND SETTERS  ############################
		
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
		
		public function getShallowOutput() {
			return $this->shallowOutput;
		}
		
		public function setShallowOutput($shallowOutput) {
			$this->shallowOutput = $shallowOutput;
		}
		
		public function getDepthAnalysisPairs() {
			return $this->depthAnalysisPairs;
		}
		
		public function setDepthAnalysisPairs($depthAnalysisPairs) {
			$this->depthAnalysisPairs = $depthAnalysisPairs;
		}
		
	}

?>