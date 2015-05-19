<?php
	/**
	 * Fichier source de la classe Decisioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisioncui66 est la classe contenant les avis techniques du CUI pour le CG 66.
	 *
	 * @package app.Model
	 */
	class Decisioncui66 extends AppModel
	{
		public $name = 'Decisioncui66';
		
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
		
		public function getPropositions( $id, $action ){
			$query = array(
				'fields' => array_merge(
					$this->Cui66->Propositioncui66->fields()
				),
				'joins' => array(
					$this->Cui66->join( 'Propositioncui66', array( 'type' => 'INNER' ) ),
					$this->Cui66->join( 'Decisioncui66', array( 'type' => 'LEFT OUTER' ) ),
				),
				'order' => array( 'Propositioncui66.created DESC' )
			);
			
			if ( $action === 'add' ){
				$query['conditions']['Cui66.cui_id'] = $id;
			}
			else{
				$query['conditions']['Decisioncui66.id'] = $id;
			}
			
			return $this->Cui66->find( 'all', $query );
		}
		
		/**
		 * 
		 * @param integer $cui66_id
		 * @param integer $id
		 * @return array
		 */
		public function prepareAddEditFormData( $cui66_id, $id = null ) {
			// Ajout
			if( empty( $id ) ) {
				$decision['Decisioncui66']['cui66_id'] = $cui66_id;
			}
			// Mise à jour
			else {
				$query['conditions'] = array(
					'Decisioncui66.id' => $id,
					'Decisioncui66.cui66_id' => $cui66_id,
				);
				$decision = $this->find( 'first', $query );
			}

			return $decision;
		}
		
		/**
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEditFormData( array $data, $user_id = null ) {
			$data['Decisioncui66']['user_id'] = $user_id;
			
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

			$options = Hash::merge(
				$options,
				$this->enums(),
				$this->Cui66->Propositioncui66->enums()
			);

			return $options;
		}
	}
?>