<?php
	/**
	 * Code source de la classe Questionnaired2pdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Questionnaired2pdv93 ...
	 *
	 * @package app.Model
	 */
	class Questionnaired2pdv93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Questionnaired2pdv93';

		/**
		 * Récursivité par défaut de ce modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Allocatairelie',
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate',
		);

		/**
		 * Les règles de validation qui seront ajoutées aux règles de validation
		 * déduites de la base de données.
		 *
		 * @var array
		 */
		public $validate = array(
			// FIXME: nouvelle règle de validation: c'est obligatoire pour l'un OU l'autre
			'sortieemploid2pdv93_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'situationaccompagnement', true, array( 'sortie_obligation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'sortieautred2pdv93_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'situationaccompagnement', true, array( 'sortie_obligation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
		);

		/**
		 * Associations "Has one".
		 *
		 * @var array
		 */
		public $hasOne = array(
			'Populationd1d2pdv93' => array(
				'className' => '',
				'foreignKey' => 'questionnaired2pdv93_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Personne' => array(
				'className' => '',
				'foreignKey' => 'personne_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Sortieautred2pdv93' => array(
				'className' => 'Sortieautred2pdv93',
				'foreignKey' => 'sortieautred2pdv93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Sortieemploid2pdv93' => array(
				'className' => 'Sortieemploid2pdv93',
				'foreignKey' => 'sortieemploid2pdv93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Retourne la structure référente dans laquelle l'allocataire doit encore
		 * remplir un questionnaire D2 pour l'année en cours.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function structurereferenteId( $personne_id ) {
			$sq = $this->sq(
				array(
					'alias' => 'questionnairesd2pdvs93',
					'fields' => 'questionnairesd2pdvs93.id',
					'contain' => false,
					'conditions' => array(
						'questionnairesd2pdvs93.personne_id' => $personne_id,
						'EXTRACT( \'YEAR\' FROM questionnairesd2pdvs93.created )' => date( 'Y' ),
						'questionnairesd2pdvs93.structurereferente_id = Rendezvous.structurereferente_id'
					)
				)
			);

			$querydata = array(
				'fields' => array( 'Rendezvous.structurereferente_id' ),
				'contain' => false,
				'joins' => array(
					$this->Personne->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Questionnaired1pdv93.personne_id' => $personne_id,
					'EXTRACT( \'YEAR\' FROM Questionnaired1pdv93.date_validation )' => date( 'Y' ),
					"NOT EXISTS( {$sq} )",
				),
			);
			$questionnaired1pdv93 = $this->Personne->Questionnaired1pdv93->find( 'first', $querydata );

			return Hash::get( $questionnaired1pdv93, 'Rendezvous.structurereferente_id' );

		}

		/**
		 * Messages à envoyer à l'utilisateur.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function messages( $personne_id ) {
			$messages = array();

			// Qui possède un questionnaire D1 sans questionnaire D2 pour l'année en cours
			$structurereferente_id = $this->structurereferenteId( $personne_id );
			if( empty( $structurereferente_id ) ) {
				$messages['Questionnaired1pdv93.exists'] = 'error';
			}

			// FIXME: mettre dans un behavior pour grouper le code avec le questionnaire D1
			/*$droitsouverts = $this->droitsouverts( $personne_id );
			if( empty( $droitsouverts ) ) {
				$messages['Situationdossierrsa.etatdosrsa_ouverts'] = 'notice';
			}

			$toppersdrodevorsa = $this->toppersdrodevorsa( $personne_id );
			if( empty( $toppersdrodevorsa ) ) {
				$messages['Calculdroitrsa.toppersdrodevorsa_notice'] = 'notice';
			}

			$this->create( array( 'personne_id' => $personne_id ) );
			$exists = !$this->checkDateOnceAYear( array( 'date_validation' => date( 'Y-m-d' ) ), 'personne_id' );
			if( $exists ) {
				$messages['Questionnaired2pdv93.exists'] = 'notice';
			}*/

			return $messages;
		}

		/**
		 * Permet de savoir si un ajout est possible à partir des messages
		 * renvoyés par la méthode messages.
		 *
		 * @param array $messages
		 * @return boolean
		 */
		public function addEnabled( array $messages ) {
			return !in_array( 'error', $messages ) && !array_key_exists( 'Questionnaired2pdv93.exists', $messages );
		}

		/**
		 *
		 *
		 * @param integer $personne_id
		 * @param integer $id
		 * @return array
		 * @throws NotFoundException
		 */
		public function prepareFormData( $personne_id, $id = null ) {
			$formData = array();

			if( !empty( $id ) ) {
				$querydata = array(
					'conditions' => array(
						"{$this->alias}.id" => $id
					),
					'contain' => false
				);

				$formData = $this->find( 'first', $querydata );
			}
			else {
				$formData[$this->alias]['personne_id'] = $personne_id;
				$formData[$this->alias]['structurereferente_id'] = $this->structurereferenteId( $personne_id );
			}

			return $formData;
		}

	}
?>