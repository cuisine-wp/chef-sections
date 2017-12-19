<?php

	namespace CuisineSections\Contracts;


	interface Generator{

		public function getAttributes();
		public function generatePost( Array $attributes );

	}