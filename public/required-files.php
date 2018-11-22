<?php
//Directory where the required files are held
$directory = "../functions/";

//Include files containing shared functions which can also be used by other scripts
include_once $directory . 'api-functions.php';
include_once $directory . 'dna-functions.php';
include_once $directory . 'uniprot-functions.php';
include_once $directory . 'ensembl-functions.php';
include_once $directory . 'mutationrate-functions.php';
include_once $directory . 'exac-functions.php';
include_once $directory . 'bruteforce-functions.php';
include_once $directory . 'cycleloop-functions.php';
?>
