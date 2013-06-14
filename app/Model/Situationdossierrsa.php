<?php
	/**
	 * Code source de la classe Situationdossierrsa.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Situationdossierrsa ...
	 *
	 * @package app.Model
	 */
	class Situationdossierrsa extends AppModel
	{

		public $name = 'Situationdossierrsa';
		public $useTable = 'situationsdossiersrsa';
		public $validate = array(
			'etatdosrsa' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dtrefursa' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'moticlorsa' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);
		public $belongsTo = array(
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'dossier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		public $hasMany = array(
			'Suspensiondroit' => array(
				'className' => 'Suspensiondroit',
				'foreignKey' => 'situationdossierrsa_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Suspensionversement' => array(
				'className' => 'Suspensionversement',
				'foreignKey' => 'situationdossierrsa_id',
				'dependent' => false,
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
		 *
		 */
		public function etatOuvert() {
			return array( 'Z', '2', '3', '4' ); // Z => dossier ajouté avec le formulaire "Préconisation ..."
		}

		/**
		 *
		 */
		public function etatAttente() {
			return array( '0', 'Z' );
		}

		/**
		 *
		 */
		public function droitsOuverts( $dossier_id ) {
			if( valid_int( $dossier_id ) ) {

				$qd_situation = array(
					'conditions' => array(
						'Situationdossierrsa.dossier_id' => $dossier_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$situation = $this->find( 'first', $qd_situation );

				return in_array( Set::extract( $situation, 'Situationdossierrsa.etatdosrsa' ), $this->etatOuvert() );
			}
			else {
				return false;
			}
		}

		/**
		 *
		 */
		public function droitsEnAttente( $dossier_id ) {
			if( valid_int( $dossier_id ) ) {

				$qd_situation = array(
					'conditions' => array(
						'Situationdossierrsa.dossier_id' => $dossier_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$situation = $this->find( 'first', $qd_situation );

				return in_array( Set::extract( $situation, 'Situationdossierrsa.etatdosrsa' ), $this->etatAttente() );
			}
			else {
				return false;
			}
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
