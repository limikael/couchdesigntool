<?php

	require_once "phar://couchdesigntool.phar/src/CouchDesignTool.php";
	require_once "phar://couchdesigntool.phar/src/ArgumentParser.php";

	/**
	 * Print usage and exit.
	 */
	function usage() {
		echo "Process javascript snippets into a CouchDB design document based on a template.\n\n";
		echo "Usage: couchdesigntool [options] <template> [snippets...]\n\n";
		echo "Options:\n";
		echo "    -c <url>   Create CouchDB document.\n";
		echo "    -u <url>   Update CouchDB document.\n";
		echo "    -w <url>   -u if the document exists, otherwise -w.\n";
		exit(1);
	}

	$parser=new ArgumentParser();
	$parser->addArgumentOption("c");
	$parser->addArgumentOption("u");
	$parser->addArgumentOption("w");
	$res=$parser->parse($_SERVER["argv"]);

	if (!$res)
		usage();

	if (sizeof($parser->getArguments())<1)
		usage();

	$arguments=$parser->getArguments();

	$c=new CouchDesignTool();
	$c->setTemplateFile($arguments[0]);

	for ($i=1; $i<sizeof($arguments); $i++)
		$c->loadSnippets($arguments[$i]);

	if ($parser->getOption("c"))
		$c->setUrl($parser->getOption("c"),"c");

	if ($parser->getOption("u"))
		$c->setUrl($parser->getOption("u"),"u");

	if ($parser->getOption("w"))
		$c->setUrl($parser->getOption("w"),"w");

	$c->run();