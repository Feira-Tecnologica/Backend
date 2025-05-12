<?php
require("../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['../Routes']);

header('Content-Type: application/json');
echo $openapi->toJSON();