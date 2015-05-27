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
		/**
		 * Alias de la table et du model
		 * @var string
		 */
		public $name = 'Rupturecui66';
		
		/**
		 * Recurcivité du model 
		 * @var integer
		 */
		public $recursive = -1;
		
		/**
		 * Possède des clefs étrangères vers d'autres models
		 * @var array
		 */
        public $belongsTo = array(
			'Cui66' => array(
				'className' => 'Cui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
        );
		
		/**
		 * Ces models possèdent une clef étrangère vers ce model
		 * @var array
		 */
		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Rupturecui66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
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
		 * Récupère les donnés par defaut dans le cas d'un ajout, ou récupère les données stocké en base dans le cas d'une modification
		 * 
		 * @param integer $cui66_id
		 * @param integer $id
		 * @return array
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
			
			if ( empty($result) ){
				throw new HttpException(404, "HTTP/1.1 404 Not Found");
			}

			return $result;
		}
		
		/**
		 * Query utilisé pour la visualisation
		 * 
		 * @param integer $id
		 * @return array
		 */
		public function queryView( $id ) {
			$query = array(
				'conditions' => array(
					'Rupturecui66.id' => $id,
				)
			);

			return $query;
		}
				
		/**
		 * Sauvegarde du formulaire
		 * 
		 * @param array $data
		 * @param integer $user_id
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