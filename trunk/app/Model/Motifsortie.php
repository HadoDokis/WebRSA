<?php
	class Motifsortie extends AppModel
	{
		public $name = 'Motifsortie';

		public $actsAs = array(
			'Autovalidate'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				)
			)
		);

        public $hasAndBelongsToMany = array(
         'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'joinTable' => 'actionscandidats_motifssortie',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'motifsortie_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatMotifsortie'
			)
        );
        
		public $hasMany = array(
			'ActioncandidatPersonne' => array(
				'className' => 'ActioncandidatPersonne',
				'foreignKey' => 'motifsortie_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
        
		/**
		 * Renvoit une liste clé / valeur avec clé qui est l'id du motif de sortie
		 * et la valeur qui est le name du motif de sortie.
		 * Utilisé pour les valeurs des input select.
		 *
		 * @return array
		 */
		public function listOptions() {
			$cacheKey = 'motifssortie_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find(
					'list',
					array (
						'contain' => false,
						'order' => 'Motifsortie.name ASC',
					)
				);

				Cache::write( $cacheKey, $results );
			}

			return $results;
		}
        
		/**
		 * Suppression et regénération du cache.
		 *
		 * @return boolean
		 */
		protected function _regenerateCache() {
			$keys = array(
				'motifssortie_list_options',
			);

			foreach( $keys as $key ) {
				Cache::delete( $key );
			}

			// Regénération des éléments du cache.
			$success = true;

			$tmp  = $this->listOptions();
			$success = !empty( $tmp ) && $success;

			return $success;
		}

		/**
		 * On s'assure de nettoyer le cache en cas de modification.
		 *
		 * @param type $created
		 * @return type
		 */
		public function afterSave( $created ) {
			parent::afterSave( $created );
			$this->_regenerateCache();
		}

		/**
		 * On s'assure de nettoyer le cache en cas de suppression.
		 *
		 * @return type
		 */
		public function afterDelete() {
			parent::afterDelete();
			$this->_regenerateCache();
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$success = $this->_regenerateCache();
			return $success;
		}
	}
?>
