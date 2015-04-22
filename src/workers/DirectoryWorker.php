<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class DirectoryWorker {
		
		/**
		 * Loads directories from given path and proccess them into JSON serializable array
		 * @param $path
		 * @param $isRemoveComments
		 */
		public static function getSubDirectories($path, $isRemoveComments) {
			$subDirectories = array();
			$dir = new DirectoryIterator($path);
			foreach ($dir as $fileInfo) {
				if ($fileInfo->isDir() && !$fileInfo->isDot()) {
					$item = array(
						"path" => $fileInfo->getPath(),
						"dir" => $fileInfo->getFilename(),
						"files" => self::getFiles($fileInfo->getPath() . '/' . $fileInfo->getFilename(),
								$isRemoveComments),
					);
					$subDirectories[$fileInfo->getFilename()] = $item;
				}
			}
			return $subDirectories;
		}
		
		/**
		 * Gets all files in selected directory and proccess them into JSON serializable array
		 */
		private static function getFiles($path, $isRemoveComments) {
			$files = array();
			$dir = new DirectoryIterator($path);
			foreach ($dir as $fileInfo) {
				if ($fileInfo->isFile() && $fileInfo->isReadable()) {
					$tokensWorker = new TokensWorker($fileInfo->getPathname());
					if ($isRemoveComments) // remove comments if needed
						$tokensWorker->removeCommentsFromTokens();
					
					$tokenBlock = new TokenBlock($tokensWorker->getTokens());
					$item = array(
						"filename" => $fileInfo->getFilename(),
						"content" => $tokenBlock->toJSON(),
					);
					$files[] = $item;
				}
			}
			return $files;
		}
		
	}

?>