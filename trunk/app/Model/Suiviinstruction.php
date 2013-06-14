<?php
	/**
	 * Code source de la classe Suiviinstruction.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Suiviinstruction ...
	 *
	 * @package app.Model
	 */
	class Suiviinstruction extends AppModel
	{
		public $name = 'Suiviinstruction';

		public $displayField = 'typeserins';

		public $belongsTo = array(
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'dossier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => false,
				'conditions' => array(
					'Suiviinstruction.numdepins = Serviceinstructeur.numdepins',
					'Suiviinstruction.typeserins = Serviceinstructeur.typeserins',
					'Suiviinstruction.numcomins = Serviceinstructeur.numcomins',
					'Suiviinstruction.numagrins = Serviceinstructeur.numagrins'
				),
				'fields' => '',
				'order' => ''
			),
		);

		public $validate = array(
			'suiirsa' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'date_etat_instruction' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nomins' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numdepins' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typeserins' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numcomins' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numagrins' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		/**
		 *
		 * @param type $field
		 * @return type
		 */
		public function sqDerniere($field) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false, false );
			return "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.dossier_id = ".$field."
					ORDER BY {$table}.id DESC
					LIMIT 1
			";
		}

		/**
		 * Retourne l'id du dossier auquel est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function dossierId( $id ) {
			$querydata = array(
				'fields' => array( "{$this->alias}.dossier_id" ),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result[$this->alias]['dossier_id'];
			}
			else {
				return null;
			}
		}
	}
?>