<?php
	class Historiqueemploi extends AppModel
	{
		public $name = 'Historiqueemploi';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'secteuractivite',
					'emploi',
					'dureehebdomadaire',
					'naturecontrat',
					'dureecdd'
				)
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
		);

		public $validate = array(
			'datefin' => array(
				array(
					'rule' => array( 'compareDates', 'datedebut', '>' ),
					'message' => 'La date de fin être supérieure à la date de début'
				)
			)
		);

		/**
		* Mise à vide de la durée du CDD si la nature du contrat de travail n'est
		* pas un CDD.
		*/
		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			if( isset( $this->data['Historiqueemploi']['naturecontrat'] ) && $this->data['Historiqueemploi']['naturecontrat'] != 'TCT3' ) {
				$this->data['Historiqueemploi']['dureecdd'] = null;
			}

			return $return;
		}
	}
?>