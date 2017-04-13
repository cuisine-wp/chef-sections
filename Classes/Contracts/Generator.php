<?php

	namespace ChefSections\Contracts;


	interface Generator{

		public function getAttributes();
		public function generatePost( Array $attributes );

	}