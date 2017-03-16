<?php

	namespace ChefSections\Contracts;


	interface Generator{

		public function check();
		public function generate();
		public function maybeGenerate();

	}