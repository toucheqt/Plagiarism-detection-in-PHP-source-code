<?php

	/**
	 * 
	 * Worker for jobs with directories.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class DirectoryWorker {
		
		####################################  METHODS  ##################################
		
		/**
		 * 
		 * Loads directories from given path and proccess them into JSON serializable array
		 * @param $path Path to the directories that should be loaded into JSON serializable array.
		 * @param $isRemoveComments Argument from command line that specifies whether script should ignore source code comments
		 * 		when processing data.
		 * @return Returns array with processed subdirectories.
		 */
		public static function getSubDirectories($path, $isRemoveComments) {
			$subDirectories = array();
			$dir = new DirectoryIterator($path);
			foreach ($dir as $fileInfo) {
				if ($fileInfo->isDir() && !$fileInfo->isDot()) {
					$item = array(
						Constant::PATTERN_PATH => $fileInfo->getPath(),
						Constant::PATTERN_DIR => $fileInfo->getFilename(),
						Constant::PATTERN_FILES => self::getFiles($fileInfo->getPath() . '/' . $fileInfo->getFilename(),
								$isRemoveComments),
					);
					$subDirectories[$fileInfo->getFilename()] = $item;
				}
			}
			return $subDirectories;
		}
		
		/**
		 * 
		 * Method for loading all files in the selected directory and processing them into JSON serializable array.
		 * @param $path Path to the directory with files that should be proccessed.
		 * @param $isRemoveComments Argument from command line that specifies whether script should ignore source code comments
		 * 		when processing data.
		 * @return Returns an array with processed files from the specified directory.
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
						Constant::PATTERN_FILENAME => $fileInfo->getFilename(),
						Constant::PATTERN_CONTENT => $tokenBlock->toJSON(),
					);
					$files[] = $item;
				}
			}
			return $files;
		}
		
	}

?>