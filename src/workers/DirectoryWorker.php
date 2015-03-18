<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class DirectoryWorker {
		
		/**
		 * Loads directories from given path
		 * @param $path
		 */
		public static function getSubDirectories($path) {
			$subDirectories = array();
			$dir = new DirectoryIterator($path);
			foreach ($dir as $fileInfo) {
				if ($fileInfo->isDir() && !$fileInfo->isDot()) {
					$item = array(
						"path" => $fileInfo->getPath(),
						"dir" => $fileInfo->getFilename(),
						"files" => self::getFiles($fileInfo->getPath() . '/' . $fileInfo->getFilename()),
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
					$tokensWorker = new TokensWorker($fileInfo->getPathname());
					$tokenBlock = new TokenBlock($tokensWorker->getTokens());
					$item = array(
						"filename" => $fileInfo->getFilename(),
						"content" => $tokenBlock->to_json(),
					);
					$files[] = $item;
				}
			}
			return $files;
		}
		
	}

?>