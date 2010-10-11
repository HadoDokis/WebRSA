<?php
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
			return array( 'Z', 2, 3, 4 ); // Z => dossier ajouté avec le formulaire "Préconisation ..."
		}

		/**
		*
		*/

		public function etatAttente() {
			return array( 0, 'Z' );
		}

		/**
		*
		*/

		public function droitsOuverts( $dossier_id ) {
			if( valid_int( $dossier_id ) ) {
				$situation = $this->findByDossierId( $dossier_id, null, null, -1 );
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
				$situation = $this->findByDossierId( $dossier_id, null, null, -1 );
				return in_array( Set::extract( $situation, 'Situationdossierrsa.etatdosrsa' ), $this->etatAttente() );
			}
			else {
				return false;
			}
		}
	}
?>
