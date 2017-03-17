<?php

	namespace ChefSections\Contracts;


	interface Generator{

		public function set( Array $data );
		public function sanitizeData( Array $data );
		
		public function ran();
		public function check();
		public function generate();
		public function maybeGenerate();


	}