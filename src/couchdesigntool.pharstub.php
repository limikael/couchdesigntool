#!/usr/bin/env php
<?php

	Phar::mapPhar("couchdesigntool.phar");
	require "phar://couchdesigntool.phar/src/couchdesigntool.main.php";

	__HALT_COMPILER();
?>