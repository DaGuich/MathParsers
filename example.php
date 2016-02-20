<?php
require_once("MathParser.php");


$statement = "1*(2*(3+4))+5";

$parser = new MathParser( $statement );

echo $statement ."\n";
echo $parser->result() . "\n";
