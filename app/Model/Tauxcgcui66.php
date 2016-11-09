<?php
	/**
	 * Code source de la classe Tauxcgcui66.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Tauxcgcui66 ...
	 *
	 * @package app.Model
	 */
	class Tauxcgcui66 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Tauxcgcui66';

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Postgres.PostgresAutovalidate',
			'Formattable'
		);

		public function options(){
			$optionsCui66 = ClassRegistry::init( 'Cui66' )->options();

			$options['Tauxcgcui66'] = array_merge( $optionsCui66['Cui'], $optionsCui66['Cui66'] );

			return $options;
		}

		public function prepareFormDataAddEdit( $id ){
			// Cas ajout
			if ( $id === null ){
				$result = array(
					'Tauxcgcui66' => array(
						'tauxfixeregion' => '0',
						'priseenchargeeffectif' => '0',
						'tauxcg' => '0'
					)
				);
			}
			// Cas modification
			else{
				$result = $this->find('first',
					array(
						'conditions' => array(
							'id' => $id
						)
					)
				);
			}

			if ( empty($result) ){
				throw new HttpException(404, "HTTP/1.1 404 Not Found");
			}

			return $result;
		}

		public function saveAddEdit( $data ){
			return $this->save( $data );
		}
	}
?>