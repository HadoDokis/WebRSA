<?php
	/**
	 * Fichier source de la classe PersonneReferent.
	 *
	 * PHP 5.3
	 *
	 * @package app.models
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PersonneReferent ...
	 *
	 * @package app.models
	 */
	class PersonneReferent extends AppModel
	{
		public $name = 'PersonneReferent';

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array( 'referent_id' )
			),
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe'
				)
			)
		);

		public $validate = array(
			'referent_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dddesignation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				)
			),
			'dfdesignation' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.',
					'allowEmpty' => true,
				),
				array(
					'rule' => array( 'compareDates', 'dddesignation', '>=' ),
					'message' => 'La date de fin de désignation doit être au moins la même que la date de début de désignation'
				)
			)
		);

		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'PersonneReferent\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);


		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Retourne l'id technique du dossier auquel appartient ce référent de parcours.
		 *
		 * @param integer $pers_ref_id L'id technique du référent de parcours
		 * @return integer
		 */
		public function dossierId( $pers_ref_id ){
			$qd_personnereferent = array(
				'conditions'=> array(
					'PersonneReferent.id' => $pers_ref_id
				),
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1
			);
			$persreferent = $this->find('first', $qd_personnereferent);

			if( !empty( $persreferent ) ) {
				return $persreferent['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Retourne une sous-requête permettant de connaître le dernier référent de parcours pour un
		 * allocataire donné.
		 *
		 * @param string $field Le champ Personne.id sur lequel faire la sous-requête
		 * @return string
		 */
		public function sqDerniere( $field ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false );
			return "SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.personne_id = ".$field."
					ORDER BY {$table}.dddesignation DESC
					LIMIT 1";
		}

		/**
		 * Lors de l'ajout d'une orientation, on ajoute un nouveau référent de parcours si celui-ci a été précisé
		 * lors de la création de l'orientation.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function referentParOrientstruct( $data ) {
			$saved = true;

			$last_referent = $this->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id'=> $data['Orientstruct']['personne_id']
					),
					'order' => array(
						'PersonneReferent.dddesignation DESC',
						'PersonneReferent.id DESC'
					),
					'contain' => false
				)
			);

			list( $structurereferente_id, $referent_id ) = explode( '_', $data['Orientstruct']['referent_id'] );

			if ( !empty( $referent_id ) && ( empty( $last_referent ) || ( isset( $last_referent['PersonneReferent']['referent_id'] ) && !empty( $last_referent['PersonneReferent']['referent_id'] ) && $last_referent['PersonneReferent']['referent_id'] != $referent_id ) ) ) {
				if ( !empty( $last_referent ) && empty( $last_referent['PersonneReferent']['dfdesignation'] ) ) {
					$last_referent['PersonneReferent']['dfdesignation'] = $data['Orientstruct']['date_valid'];
					$this->create( $last_referent );
					$saved = $this->save( $last_referent ) && $saved;
				}

				$personnereferent['PersonneReferent'] = array(
					'personne_id' => $data['Orientstruct']['personne_id'],
					'referent_id' => $referent_id,
					'structurereferente_id' => $structurereferente_id,
					'dddesignation' => $data['Orientstruct']['date_valid']
				);
				$this->create( $personnereferent );
				$saved = $this->save( $personnereferent ) && $saved;
			}

			return $saved;
		}
	}
?>