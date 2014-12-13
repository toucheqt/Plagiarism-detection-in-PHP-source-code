<?php

	use Tester\Assert;
	
	# load tester libraries and tested class
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/../src/Tokens.php';
	
	Tester\Environment::setup();
	
	class TokensTest extends Tester\TestCase {
		
		private $phpFile;
		private $jsonFile;
		
		public function TokensTest() {
			$this->phpFile = './test-files/HelloWorld.php';
			$this->jsonFile = './../tokens/HelloWorld.json';
		}
	
		public function testTokens() {
			
			$object = new Tokenizer();
			
			// set filepath and filename to file HelloWorld.php located at ./test-files/
			$object->setFile($this->phpFile);
			
			Assert::same('HelloWorld.php', $object->getFileName());
			Assert::same('./test-files/', $object->getFilePath());
			
			// get tokens from file and check if whitespaces has been removed
			Assert::true($object->getTokens());
			Assert::notContains(T_WHITESPACE, $object->getContent());
			
			Assert::true(file_exists($this->jsonFile));
			
		}
	
	}
	
	# run tests
	$testCase = new TokensTest();
	$testCase->run();
	
?>

