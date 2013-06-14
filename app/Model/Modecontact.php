<?php
	/**
	 * Code source de la classe Modecontact.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Modecontact ...
	 *
	 * @package app.Model
	 */
	class Modecontact extends AppModel
	{

		public $name = 'Modecontact';
		public $validate = array(
			// Role personne
			'numtel' => array(
				array(
					'rule' => array( 'between', 10, 14 ),
					'message' => 'Le numéro de téléphone est composé de 10 chiffres',
					'allowEmpty' => true
				)
			),
			'numposte' => array(
				array(
					'rule' => 'alphaNumeric',
					'allowEmpty' => true
				),
				array(
					'rule' => array( 'between', 4, 4 ),
					'message' => 'Le numéro de poste est composé de 4 chiffres'
				)
			)
		);
		public $belongsTo = array(
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'foyer_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 *
		 */
		public function dossierId( $modecontact_id ) {
			$qd_modecontact = array(
				'conditions'=> array(
					'Modecontact.id' => $modecontact_id
				),
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1
			);
			$modecontact = $this->find('first', $qd_modecontact);

			if( !empty( $modecontact ) ) {
				return $modecontact['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		 *
		 */
		public function sqDerniere( $field ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false, false );
			return "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.foyer_id = ".$field."
					ORDER BY {$table}.foyer_id DESC
					LIMIT 1
			";
		}

	}
?>
