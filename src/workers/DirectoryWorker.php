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
			foreach ($dir as $fileInfo) {
				if ($fileInfo->isDir() && !$fileInfo->isDot()) {
					$item = array(
						"path" => $fileInfo->getPath(),
						"dir" => $fileInfo->getFilename(),
						"files" => self::getFiles($fileInfo->getPath() . '\\' . $fileInfo->getFilename()),
					);
					$subDirectories[] = $item;
				}
			}
			return $subDirectories;
		}
		
		/**
		 * Gets all files in selected directory
		 */
		private static function getFiles($path) {
			$files = array();
			$dir = new DirectoryIterator($path);
			foreach ($dir as $fileInfo) {
				if ($fileInfo->isFile() && $fileInfo->isReadable()) {
					$item = array(
						"filename" => $fileInfo->getFilename(),
						"content" => new TokensWorker($fileInfo->getPathname()),
					);
					$files[] = $item;
				}
			}
			return $files;
		}
		
	}

?>