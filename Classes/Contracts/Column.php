<?php

	namespace ChefSections\Contracts;


	interface Column{

		public function beforeTemplate();

		public function afterTemplate();

		public function saveProperties();

		public function sanitizeProperties( $props );

		public function build();

		public function buildPreview();

		public function getFields();

		public function getField( $name, $default = null );

		public function theField( $name, $default = null );

		public function getTitle( $name = 'title', $class = 'column-title' );

		public function theTitle( $name = 'title', $class = 'column-title' );

	}