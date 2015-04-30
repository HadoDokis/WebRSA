<?php
	/**
	 * Fichier source de la classe Rupturecui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Rupturecui66 est la classe contenant les avis techniques du CUI pour le CG 66.
	 *
	 * @package app.Model
	 */
	class Rupturecui66 extends AppModel
	{
		public $name = 'Rupturecui66';
		
		public $recursive = -1;
		
        public $belongsTo = array(
			'Cui66' => array(
				'className' => 'Cui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
        );
		
		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Formattable',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);
		
		/**
		 * 
		 * @param integer $cui66_id
		 * @param integer $id
		 * @return array
		 * @fixme Envoyer une exception si on ne trouve pas l'enregistrement
		 */
		public function prepareAddEditFormData( $cui66_id, $id = null ) {
			// Ajout
			if( empty( $id ) ) {
				$result = array(
					'Rupturecui66' => array(
						'cui66_id' => $cui66_id,
						'dateenregistrement' => date_format(new DateTime(), 'Y-m-d'),
					)
				);
			}
			// Mise à jour
			else {
				$query = $this->queryView($id);
				$result = $this->find( 'first', $query );
			}

			return $result;
		}
		
		public function queryView( $id ) {
			$query = array(
				'conditions' => array(
					'Rupturecui66.id' => $id,
				)
			);

			return $query;
		}
				
		/**
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEditFormData( array $data, $user_id = null ) {
			$data['Rupturecui66']['user_id'] = $user_id;
			
			$this->create($data);
			$success = $this->save();
			
			return $success;
		}
				
		/**
		 * Retourne les options nécessaires au formulaire de recherche, au formulaire,
		 * aux impressions, ...
		 *
		 * @param array $params <=> array( 'allocataire' => true, 'find' => false, 'autre' => false, 'pdf' => false )
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = array();
			
			$optionRupture = $this->enums();
			$optionRupture['Rupturecui66']['motif'] = ClassRegistry::init( 'motifrupturecui66' )->find( 'list' );

			$options = Hash::merge(
				$options,
				$optionRupture
			);
			
			return $options;
		}
	}
?>