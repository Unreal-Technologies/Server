<?php
include 'Php2core/Init.php';

$version = new Php2Core\Version('Server Control', 1,0,0,0);
$version -> Add(VERSION);

echo $version;

var_dump(new Php2Core\Meuk());
?>