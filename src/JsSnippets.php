<?php

	/**
	 * Snippets of javascript.
	 */
	class JsSnippets {

		private $snippets;

		/**
		 * Construct.
		 */
		public function JsSnippets() {
			$this->snippets=array();
		}

		/**
		 * Get assoc array of snippets.
		 */
		public function getSnippets() {
			return $this->snippets;
		}

		/**
		 * Load snippets
		 */
		public function load($fn) {
			$functionsource=file_get_contents($fn);

			$res=preg_match_all('/\/\*[ ]*@([A-Za-z_]+)[ ]*\*\//',$functionsource,$matches);

			$offsets=array();
			foreach ($matches[0] as $match)
				$offsets[]=strpos($functionsource,$match);

			for ($i=0; $i<sizeof($offsets); $i++) {
				$start=$offsets[$i]+strlen($matches[0][$i]);

				if ($i>=sizeof($offsets)-1)
					$end=strlen($functionsource);

				else
					$end=$offsets[$i+1];

				$func=substr($functionsource,$start,$end-$start);
				$this->snippets[$matches[1][$i]]=$func;
			}
		}
	}