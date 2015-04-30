<?php
	/**
	 * Fichier source de la classe Accompagnementcui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Accompagnementcui66 est la classe contenant les accompagnements du CUI pour le CG 66.
	 *
	 * @package app.Model
	 */
	class Accompagnementcui66 extends AppModel
	{
		public $name = 'Accompagnementcui66';
		
		public $recursive = -1;
				
		public $belongsTo = array(
			'Cui66' => array(
				'className' => 'Cui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
			'Immersioncui66' => array(
				'className' => 'Immersioncui66',
				'foreignKey' => 'immersioncui66_id',
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
					'Accompagnementcui66' => array(
						'cui66_id' => $cui66_id,
					)
				);
			}
			// Mise à jour
			else {
				$query = $this->queryView( $id, false );
				$result = $this->find( 'first', $query );
				$result = $this->Immersioncui66->Entreeromev3->prepareFormDataAddEdit( $result );
			}

			return $result;
		}

		// TODO: doc
		public function queryView( $id, $joinEntreeromev3 = true ) {
			$query = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Immersioncui66->fields(),
					$this->Immersioncui66->Entreeromev3->fields()
				),
				'conditions' => array(
					'Accompagnementcui66.id' => $id
				),
				'joins' => array(
					$this->join( 'Immersioncui66', array( 'type' => 'LEFT OUTER' ) ),
					$this->Immersioncui66->join( 'Entreeromev3', array( 'type' => 'LEFT OUTER' ) ),
				)
			);
			
			if( $joinEntreeromev3 ) {
				$query = $this->Immersioncui66->Entreeromev3->getCompletedRomev3Joins( $query );
			}

			return $query;
		}
		
		/**
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEditFormData( array $data, $user_id = null ) {
			$data['Accompagnementcui66']['user_id'] = $user_id;
			$success = true;
			
			// Si le genre d'accompagnement est immersion
			if ( isset($data['Immersioncui66']) && $data['Accompagnementcui66']['genre'] === 'immersion' ){$data['Immersioncui66']['user_id'] = $user_id;//FIXME
				unset( $this->Immersioncui66->Entreeromev3->validate['familleromev3_id']['notEmpty'] );
				// Si un code famille (rome v3) est vide, on ne sauvegarde pas le code rome
				if ( !isset($data['Entreeromev3']['familleromev3_id']) || $data['Entreeromev3']['familleromev3_id'] === '' ){ 
					$data['Immersioncui66']['entreeromev3_id'] = null;

					// Si le code rome avait un id, on supprime l'entreeromev3 correspondant
					if ( isset($data['Entreeromev3']['id']) && $data['Entreeromev3']['id'] !== '' ){
						$this->Immersioncui66->Entreeromev3->id = $data['Entreeromev3']['id'];
						$success = $this->Immersioncui66->Entreeromev3->delete() && $success;
					}
				}
				// Dans le cas contraire, on enregistre le tout
				else{
					$this->Immersioncui66->Entreeromev3->create($data);
					$success = $this->Immersioncui66->Entreeromev3->save() && $success;
					$data['Immersioncui66']['entreeromev3_id'] = $this->Immersioncui66->Entreeromev3->id;
				}
				
				$this->Immersioncui66->create($data);
				$success = $this->Immersioncui66->save() && $success;
				$data['Accompagnementcui66']['immersioncui66_id'] = $this->Immersioncui66->id;
			}
			
			$this->create($data);
			$success = $this->save() && $success;
			
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
			$params = $params + array( 'allocataire' => true, 'find' => false, 'autre' => false, 'pdf' => false );

			if( Hash::get( $params, 'allocataire' ) ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );

				$options = $Allocataire->options();
			}
			
			if( $params['find'] ) {
				$options = Hash::merge(
					$options,
					$this->Cui66->Cui->Entreeromev3->options()
				);
			}

			$options = Hash::merge(
				$options,
				$this->enums(),
				$this->Immersioncui66->enums()
			);

			return $options;
		}
	}
?>