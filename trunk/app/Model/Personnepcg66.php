<?php
	/**
	 * Code source de la classe Personnepcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Personnepcg66 ...
	 *
	 * @package app.Model
	 */
	class Personnepcg66 extends AppModel
	{
		public $name = 'Personnepcg66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable' => array( 'suffix' => array( 'categoriedetail' ) )
		);

		public $virtualFields = array(
			'nbtraitements' => array(
				'type'      => 'integer',
				'postgres'  => '(
					SELECT COUNT(*)
						FROM traitementspcgs66
						WHERE
							traitementspcgs66.personnepcg66_id = "%s"."id"
				)',
			),
		);

		public $validate = array(
			'categoriegeneral' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'categoriedetail' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
				'foreignKey' => 'dossierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Traitementpcg66' => array(
				'className' => 'Traitementpcg66',
				'foreignKey' => 'personnepcg66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
/*			'Personnepcg66Situationpdo' => array(
				'className' => 'Personnepcg66Situationpdo',
				'foreignKey' => 'personnepcg66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),*/// INFO: à éventuellement décommenter si besoin pour faire appel à certains modèles plus aisément mais normalement inutile
			/*'Personnepcg66Statutpdo' => array(
				'className' => 'Personnepcg66Statutpdo',
				'foreignKey' => 'personnepcg66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),*/
		);

		public $hasAndBelongsToMany = array(
			'Situationpdo' => array(
				'className' => 'Situationpdo',
				'joinTable' => 'personnespcgs66_situationspdos',
				'foreignKey' => 'personnepcg66_id',
				'associationForeignKey' => 'situationpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Personnepcg66Situationpdo'
			),
			'Statutpdo' => array(
				'className' => 'Statutpdo',
				'joinTable' => 'personnespcgs66_statutspdos',
				'foreignKey' => 'personnepcg66_id',
				'associationForeignKey' => 'statutpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Personnepcg66Statutpdo'
			)
		);

		public function updateEtatDossierPcg66( $decisionpersonnepcg66_id ) {
			$decisionpersonnepcg66 = $this->Personnepcg66Situationpdo->Decisionpersonnepcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisionpersonnepcg66.id' => $decisionpersonnepcg66_id
					),
					'contain' => array(
						'Personnepcg66Situationpdo' => array(
							'Personnepcg66' => array(
								'Dossierpcg66'
							)
						)
					)
				)
			);
			$decisionpdo_id = Set::classicExtract( $decisionpersonnepcg66, 'Decisionpersonnepcg66.decisionpdo_id' );
			$typepdo_id = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.Personnepcg66.Dossierpcg66.typepdo_id' );
			$user_id = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.Personnepcg66.Dossierpcg66.user_id' );
			$avistechnique = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.Personnepcg66.Dossierpcg66.avistechnique' );
			$validationavis = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.Personnepcg66.Dossierpcg66.validationproposition' );
			$iscomplet = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.Personnepcg66.Dossierpcg66.iscomplet' );
			$dossierpcg66_id = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.Personnepcg66.Dossierpcg66.id' );

			$etat = $this->Dossierpcg66->etatDossierPcg66( $typepdo_id, $user_id, $decisionpdo_id, $avistechnique, $validationavis, $iscomplet, $dossierpcg66_id );

			$return = $this->saveField( 'etatdossierpcg', $etat );

			return $return;
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "{$this->alias}.personne_id" ),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result[$this->alias]['personne_id'];
			}
			else {
				return null;
			}
		}
	}
?>