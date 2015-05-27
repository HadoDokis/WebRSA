<?php
	/**
	 * Fichier source de la classe Propositioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Propositioncui66 est la classe contenant les avis techniques du CUI pour le CG 66.
	 *
	 * @package app.Model
	 */
	class Propositioncui66 extends AppModel
	{
		/**
		 * Alias de la table et du model
		 * @var string
		 */
		public $name = 'Propositioncui66';
		
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
					'Fichiermodule.modele = \'Propositioncui66\'',
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
			'Gedooo.Gedooo',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);
		
		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'default' => 'CUI/%s/impression.odt',
			'aviselu' => 'CUI/%s/aviselu.odt',
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
					'Propositioncui66' => array(
						'cui66_id' => $cui66_id,
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
				'fields' => array_merge(
					$this->fields()
				),
				'conditions' => array(
					'Propositioncui66.id' => $id,
				)
			);

			return $query;
		}
		
		/**
		 * Requète d'impression
		 * 
		 * @param integer $id
		 * @param string $modeleOdt
		 * @return type
		 */
		public function queryImpression( $id, $modeleOdt = null ){
			$queryView = $this->queryView( $id );
			$queryImpressionCui66 = $this->Cui66->queryImpression( 'Cui66.cui_id' ); 
			
			$query['fields'] = array_merge( $queryView['fields'], $queryImpressionCui66['fields'] );
			
			$query['joins'] = array_merge( 
				array( 
					$this->join( 'Cui66' ),
				),
				$queryImpressionCui66['joins']
			);
			$query['conditions'] = $queryView['conditions'];
			
			return $query;
		}
		
		/**
		 * Sauvegarde du formulaire
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEditFormData( array $data, $user_id = null ) {
			$data['Propositioncui66']['user_id'] = $user_id;
			
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
			
			$optionRefus = $this->enums();
			$optionRefus['Propositioncui66']['motif'] = ClassRegistry::init( 'motifrefuscui66' )->find( 'list' );

			$options = Hash::merge(
				$options,
				$optionRefus
			);

			return $options;
		}
	}
?>