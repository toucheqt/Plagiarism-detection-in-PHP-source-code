<?php
	
	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Enviroment {
		
		private $template;
		private $project;
		
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
		
	}

?>