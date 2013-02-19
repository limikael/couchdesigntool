<?php

	require_once "phar://couchdesigntool.phar/src/JsSnippets.php";

	/**
	 * Couch design tool.
	 */
	class CouchDesignTool {

		private $templateFileName;
		private $snippets;
		private $url;
		private $urlMode;

		/**
		 * Constructor.
		 */
		public function CouchDesignTool() {
			$this->snippets=array();
		}

		/**
		 * Get template file.
		 */
		public function setTemplateFile($f) {
			$this->templateFileName=$f;
		}

		/**
		 * Set url and mode.
		 */
		public function setUrl($url, $mode) {
			$this->url=$url;
			$this->urlMode=$mode;
		}

		/**
		 * Load snippet.
		 */
		public function loadSnippets($fn) {
			$snippets=new JsSnippets();
			$snippets->load($fn);

			foreach ($snippets->getSnippets() as $name=>$snippet)
				$this->snippets[$name]=$snippet;
		}

		/**
		 * Process node.
		 */
		private function processNode(&$node) {
			foreach ($node as $k=>&$child) {
				if (is_array($child))
					$this->processNode($child);

				else if (substr($child,0,1)=="@") {
					$snippetName=substr($child,1);

					if (!array_key_exists($snippetName,$this->snippets)) {
						echo "Undefined snippet: ".$snippetName."\n";
						exit(1);
					}

					$child=$this->snippets[$snippetName];
				}
			}
		}

		/**
		 * Get couch doc.
		 */
		private function getCouchDocument($url) {
			$curl=curl_init($url);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
			$doc=curl_exec($curl);

			$status=curl_getinfo($curl,CURLINFO_HTTP_CODE);
			if ($status!=200)
				return NULL;

			return json_decode($doc,TRUE);
		}

		/**
		 * Put couch document.
		 */
		private function putCouchDocument($url, $doc) {
			$encoded=json_encode($doc,JSON_PRETTY_PRINT)."\n";

			$curl=curl_init($url);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
 			curl_setopt($curl,CURLOPT_CUSTOMREQUEST,"PUT");
 			curl_setopt($curl,CURLOPT_POSTFIELDS,$encoded);
			$rawres=curl_exec($curl);
			$res=json_decode($rawres,TRUE);

			$status=curl_getinfo($curl,CURLINFO_HTTP_CODE);
			if ($status!=201 || !$res["ok"]) {
				echo "Failed: ".$res["error"].": ".$res["reason"]."\n";
				exit(1);
			}

			return $res["rev"];
 		}

		/**
		 * Run.
		 */
		public function run() {
			$template=json_decode(file_get_contents($this->templateFileName),TRUE);

			$this->processNode($template);

			if (!$this->url) {
				echo json_encode($template,JSON_PRETTY_PRINT)."\n";
				return;
			}

			$current=$this->getCouchDocument($this->url);

			if ($current)
				echo "Old revision: ".$current["_rev"]."\n";

			else
				echo "Old revision: none.\n";

			if ($this->urlMode=="u" && !$current) {
				echo "Update (-u) requested but no document exists, no action taken.\n";
				exit(1);
			}

			if ($this->urlMode=="c" && $current) {
				echo "Create (-c) requested but document already exists, no action taken.\n";
				exit(1);
			}

			if ($current)
				$template["_rev"]=$current["_rev"];

			$rev=$this->putCouchDocument($this->url,$template);
			echo "New revision: ".$rev."\n";
		}
	}
