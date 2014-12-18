<?php

	use Tester\Assert;
	
	# load tester libraries and tested class
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/../src/Metrics.php';
	
	Tester\Environment::setup();
	
	class MetricsTest extends Tester\TestCase {
							
		public function testBasicMetrics() {
			
			$srcFilePath = './../tokens/';
			$srcFileName = 'HelloWorld.json';
			
			$object = new Metrics();
			
			// set filepath and filename to file HelloWorld.json located at ./../tokens/
			Assert::true($object->setFile($srcFilePath . $srcFileName));
			
			Assert::same($srcFileName, $object->getFileName());
			Assert::same($srcFilePath, $object->getFilePath());
			
			// get and test metrics
			$object->getMetrics();
			
			Assert::same(1, $object->getFunctionCount());
			Assert::same(0, $object->getGlobalVarCount());
			Assert::same(0, $object->getAtUsageCount());
			
			// test complicated file
			$srcFileName = 'Function.json';
			
			Assert::true($object->setFile($srcFilePath . $srcFileName));
			
			Assert::same($srcFileName, $object->getFileName());
			Assert::same($srcFilePath, $object->getFilePath());
			
			// get and test metrics
			$object->getMetrics();
			
			Assert::same(2, $object->getFunctionCount());
			Assert::same(1, $object->getGlobalVarCount());
			Assert::same(1, $object->getAtUsageCount());
			
		}
	
	}
	
	# run tests
	$testCase = new MetricsTest();
	$testCase->run();
	
?>
