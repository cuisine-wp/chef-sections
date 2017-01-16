<?php

	namespace ChefSections\Contracts;


	interface Column{

		public function beforeTemplate();

		public function afterTemplate();

		public function saveProperties();

		public function sanitizeProperties( Array $props );

		public function build();

		public function buildPreview();

		public function getFields();

		public function getField( String $name, $default = null );

		public function theField( String $name, $default = null );

	}