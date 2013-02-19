<?php

	/**
	 * Argument parser.
	 */
	class ArgumentParser {

		private $optionSpecs;
		private $argumentOptionSpec;
		private $errorMessage;
		private $options;
		private $arguments;

		/**
		 * Construct.
		 */
		public function ArgumentParser() {
			$this->optionSpec=array();
			$this->argumentOptionSpec=array();
			$this->options=array();
			$this->arguments=array();
		}

		/**
		 * Add option.
		 */
		public function addOption($option) {
			$this->optionSpec[]=$option;
		}

		/**
		 * Add argument option.
		 */
		public function addArgumentOption($option) {
			$this->argumentOptionSpec[]=$option;
		}

		/**
		 * Get error message.
		 */
		public function getErrorMessage() {
			return $this->errorMessage;
		}

		/**
		 * Get option.
		 */
		public function getOption($opt) {
			if (!array_key_exists($opt,$this->options))
				return FALSE;

			return $this->options[$opt];
		}

		/**
		 * Get arguments.
		 */
		public function getArguments() {
			return $this->arguments;
		}

		/**
		 * Parse.
		 */
		public function parse($arguments) {
			array_shift($arguments);
			$out = array();

			for ($i=0; $i<sizeof($arguments); $i++) {
				$arg=$arguments[$i];

				if (substr($arg,0,1) == '-') {
					$o=substr($arg,1,1);

					if (in_array($o,$this->optionSpec)) {
						if (strlen($arg)>2) {
							$this->errorMessage=$o." does not take arguments.";
							return FALSE;
						}

						$this->options[$o]=TRUE;
					}

					else if (in_array($o,$this->argumentOptionSpec)) {
						if (strlen($arg)>2)
							$this->options[$o]=substr($arg,2);

						else {
							if ($i>=sizeof($arguments)-1) {
								$this->errorMessage=$o." requires an argument.";
								return FALSE;
							}

							$this->options[$o]=$arguments[$i+1];
							$i++;
						}
					}

					else {
						$this->errorMessage="Unknown option ".$o;
						return FALSE;
					}
				}

				else {
					$this->arguments[]=$arg;
				}
			}

			return TRUE;
		}
	}