<?php

	namespace ChefSections\Contracts;


	interface Generator{

		public function set( $data );
		public function sanitizeData( $data );
		
		public function ran();
		public function check();
		public function generate();
		public function maybeGenerate();


	}