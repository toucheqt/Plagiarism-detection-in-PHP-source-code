<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class JsonConverter {
		
		// TODO on unix, implement relative and default path
		
		/**
		 * 
		 * Converts given array to json and saves it into file.
		 * @param string $path
		 * @param string $filename
		 * @param array $data
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public function saveToJson($path, $filename, $data) {
			if (is_null($filename)) {
				$errorMessage = "Filename can not be null.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);				
			}
			
			// create directory if does not exists
			if (!file_exists($path)) {
				if (!mkdir($filename)) {
					$errorMessage = 'Can not create directory ' . $filename . "\n";
					echo $errorMessage;
					throw new RuntimeException($errorMessage);
				}
			}

			$jsonNode = json_encode($data);
			if (!file_put_contents($path . $filename, $jsonNode, FILE_USE_INCLUDE_PATH)) {
				$errorMessage = 'File ' . $filename . " can not be created.\n";
				echo $errorMessage;
				throw new RuntimeException($errorMessage);
			}			
		}
		
		/**
		 * 
		 * Returns content of given json file
		 * @param string $path
		 * @param string $filename
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public function getJsonFromFile($path, $filename) {
			if (is_null($filename)) {
				$errorMessage = "Filename can not be null.\n";
				echo $errorMessage;
				throw new InvalidArgumentException($errorMessage);
			}
			
			$content = file_get_contents($path . $filename);
			if (!$content) {
				$errorMessage = 'File ' . $path . $filename . " can not be opened.\n";
				echo $errorMessage;
				throw new RuntimeException($errorMessage);
			}
			
			$content = json_decode($content);
			return $content;
		}
		
	}

?>