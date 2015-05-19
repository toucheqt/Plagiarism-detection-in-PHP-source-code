<?php

	/**
	 * 
	 * Class for easy manipulation with JSON and CSV files.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class FileUtils {
		
		/**
		 * 
		 * Converts given array to JSON and saves it into file.
		 * @param $path Path specifying where the file should be saved.
		 * @param $filename String specifying the file name.
		 * @param $data JSON structure that should be saved into file.
		 * @throws InvalidArgumentException Throws an exception if filename was not specified.
		 * @throws RuntimeException Throws an exception if an error occurred during saving.
		 */
		public static function saveToJSON($path, $filename, $data) {
			if (is_null($filename)) {
				Logger::error("Filename can not be null.");
				throw new InvalidArgumentException();
			}
			
			if (!file_put_contents($path . $filename . Constant::JSON_FILE_EXTENSION, @json_encode($data), FILE_USE_INCLUDE_PATH)) {
				Logger::error('File ' . $filename . ' can not be created.');
				throw new RuntimeException();
			}			
		}
		
		/**
		 * 
		 * Returns content of given JSON file
		 * @param $path Path with the name of the file that should be opened.
		 * @throws InvalidArgumentException Throws an exception if the path is not provided.
		 * @throws RuntimeException Throws an exception if an error occurred during opening the file.
		 * @return Returns decoded JSON structure from the input file.
		 */
		public static function getJSONFromFile($path) {
			if (is_null($path)) {
				Logger::errorFatal('Path can not be null.');
				throw new InvalidArgumentException();
			}
			
			$content = file_get_contents($path);
			if (!$content) {
				Logger::errorFatal('File ' . $path . ' can not be opened.');
				throw new RuntimeException();
			}
			
			return json_decode($content);
		}
		
		/**
		 * 
		 * Converts given array and saves it as a CSV file.
		 * @param $path Path specifying where the file should be saved.
		 * @param $filename String specifying the file name.
		 * @param $matchedPairs Data that should be saved into CSV file.
		 * @throws InvalidArgumentException Throws an exception if filename was not specified.
		 * @throws RuntimeException Throws an exception if an error occurred during saving the file.
		 */
		public static function saveToCSV($path, $filename, $matchedPairs) {
			if (is_null($filename)) {
				Logger::error('Can not save CSV file. Filename can not be null. ');
				throw new InvalidArgumentException();
			}
			
			$fd = fopen($path . $filename . Constant::CSV_FILE_EXTENSION, 'w');
			if (!$fd) {
				Logger::error('Can not save CSV file. File can not be created. ');
				throw new RuntimeException();
			}

			foreach ($matchedPairs as $pair) {
				fputcsv($fd, $pair);
			}
			
			fclose($fd);
		}
		
		/**
		 * 
		 * Returns specific page of matched pairs from given CSV file.
		 * @param $path Path to the CSV file. Can not be null.
		 * @param $startIndex Number of the page that should be read.
		 * @param $count Number of the pairs per page.
		 * @throws InvalidArgumentException Throws an exception if path is not provided.
		 * @throws RuntimeException Throws an exception if an error occured during opening the file.
		 * @return Return specific page with matched pairs.
		 */
		public static function getFromCSV($path, $startIndex, $count) {
			if (is_null($path)) {
				Logger::errorFatal('Path to CSV file can not be null. ');
				throw new InvalidArgumentException();
			}
			
			$fd = fopen($path, 'r');
			if (!$fd) {
				Logger::errorFatal('CSV file can not be opened. ');
				throw new RuntimeException();
			}
			
			// implements paging
			$skippedFiles = ($startIndex - 1) * $count;
			try {
				$tmpPairs = array();
				for ($i = 0; $i < $skippedFiles; $i++) {
					$tmp = fgetcsv($fd);
					if (!is_array($tmp)) {
						fclose($fd);
						Logger::info('CSV file was successfuly loaded. ');
						return $tmpPairs;
					} else {
						$tmpPairs[] = $tmp;
					}
				}
			} catch (Exception $ex) {
				Logger::errorFatal('Error during creating matched pairs page. ');
				throw new RuntimeException();
			}
			
			$matchedPairs = array();
			for ($i = 0; $i < $count; $i++) {
				$matchedPairs[] = fgetcsv($fd);
			}
			
			fclose($fd);
			Logger::info('CSV file was successfuly loaded. ');
			return $matchedPairs;
		}
		
		/**
		 * 
		 * Returns all evaluated pairs.
		 * @param $path Path to the CSV file. Can not be null.
		 * @throws InvalidArgumentException Throws an exception if path is not provided.
		 * @throws RuntimeException Throws an exception if an error occured during opening the file.
		 * @return Return all evaluated pairs.
		 */
		public static function getResultsFromCSV($path) {
			if (is_null($path)) {
				Logger::errorFatal('Path to CSV file can not be null. ');
				throw new InvalidArgumentException();
			}
			
			$fd = fopen($path, 'r');
			if (!$fd) {
				Logger::errorFatal('CSV file can not be opened. ');
				throw new RuntimeException();
			}
			
			$evaluatedPairs = array();
			while (($line = fgetcsv($fd)) !== false) {
				$evaluatedPairs[] = $line;
			}
			
			fclose($fd);
			Logger::info('CSV file was successfuly loaded. ');
			return $evaluatedPairs;
		}
		
	}

?>