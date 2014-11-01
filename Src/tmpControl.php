<?php

require 'getTokens.php';

$tokenizer = new Tokenizer("./../Tests/IPP.php");
if (!$tokenizer->getTokens()) echo "fail\n";

?>


