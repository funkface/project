#!/usr/bin/env php
<?php

$_SERVER['argv'][] = 'generate-migrations-diff';
//$_SERVER['argv'][] = 'generate-models-yaml';
$_SERVER['argc'] = count($_SERVER['argv']);

chdir(dirname(__FILE__));
include('doctrine.php');