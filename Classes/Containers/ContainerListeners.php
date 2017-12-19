<?php

	namespace CuisineSections\Containers;

	use CuisineSections\Wrappers\StaticInstance;


	class ContainerListeners extends StaticInstance{


		public function __construct()
		{
			$this->listen();
		}


		/**
		 * Listen for container events
		 * 
		 * @return void
		 */
		public function listen()
		{
			if( apply_filters( 'cuisine_sections_enable_group_container', true ) ){

				add_filter( 'cuisine_sections_containers', function( $data ){

					$data[ 'group' ] = [
						'label' => 'Section Group',
						'view' => 'grouped',
						'class' => '\CuisineSections\Containers\GroupContainer'
					];

					return $data;
				});

			}
		}

	}

	\CuisineSections\Containers\ContainerListeners::getInstance();