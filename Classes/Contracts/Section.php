<?php

	namespace CuisineSections\Contracts;


	interface Section{

		public function sanitizeArgs( $args );
		public function setAttributes( $args );
		public function getAttributes();

		public function beforeTemplate();
		public function afterTemplate();

		public function getClass();

		public function getProperty( $name, $default = false );
		public function getColumns( $columns );
		public function getName( $args );


	}