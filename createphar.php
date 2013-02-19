<?php

	$version="0.5";

	$phar=new Phar("couchdesigntool-$version.phar");
	$phar->addFile("src/couchdesigntool.main.php");
	$phar->addFile("src/CouchDesignTool.php");
	$phar->addFile("src/ArgumentParser.php");
	$phar->addFile("src/JsSnippets.php");
	$phar->setStub(file_get_contents("src/couchdesigntool.pharstub.php"));

	chmod("couchdesigntool-$version.phar",0755);