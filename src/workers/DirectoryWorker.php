<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class DirectoryWorker {
		
		public static function getSubDirectories($path) {
			$subDirectories = array();
			$dir = new DirectoryIterator($path);
			foreach ($dir as $subDir) {
				if ($subDir->isDir && !$subDir->isDot()) {
					$subDirectories[] = $subDir;
				}
			}
		}
		
	}

?>