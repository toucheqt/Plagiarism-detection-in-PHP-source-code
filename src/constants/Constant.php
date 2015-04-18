<?php

	/**
	 * 
	 * Class containing all script constants, allowing a simple modification if needed.
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Constant {
		
		// arg parameters
		const ARG_FIRST_PHASE = '--first';
		const ARG_SECOND_PHASE = '--second';
		const ARG_THIRD_PHASE = '--third';
		const ARG_FOURTH_PHASE = '--fourth';
		
		// arg parameters shortcuts
		const ARG_EVAL_PHASE = '-e';
		const ARG_GEN_PHASE = '-g';
		const ARG_COMMENTS = '-c';
		
		const ARG_HELP = '--help';
		const ARG_HELP_SHORT = '-h';
		
		// arg paths
		// TODO pridal bych moznost, ze se da vygenerovat i template 
		const ARG_INPUT_PATH = '--input=';
		const ARG_OUTPUT_PATH = '--output=';
		const ARG_INPUT_JSON = '--projectsJSON=';
		const ARG_TEMPLATE_JSON = '--templatesJSON=';
		const ARG_INPUT_CSV = '--inputCSV=';
		
		const ARG_JSON_NAME = '--nameJSON=';
		const ARG_CSV_NAME = '--nameCSV';
		
		// paging
		const ARG_START_INDEX = '--index=';
		const ARG_COUNT = '--count=';
		const ARG_FORCE = '-f';
		
		const START_INDEX = 0;
		const COUNT = 20; // TODO find best count
		
		// file paths and file names
		const DEFAULT_PATH = './';
		const DEFAULT_FILENAME = 'generatedOutput';
		
		// file extensions
		const JSON_FILE_EXTENSION = '.json';
		const CSV_FILE_EXTENSION = '.csv';
		
		private function __construct() {}
		
	}

?>