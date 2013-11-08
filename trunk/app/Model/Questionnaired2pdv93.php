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
			'Questionnairepdv93',
		);

		/**
		 * Les règles de validation qui seront ajoutées aux règles de validation
		 * déduites de la base de données.
		 *
		 * @var array
		 */
		public $validate = array(
			'sortieaccompagnementd2pdv93_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'situationaccompagnement', true, array( 'sortie_obligation' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'chgmentsituationadmin' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'situationaccompagnement', true, array( 'changement_situation' ) ),
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
				'className' => 'Populationd1d2pdv93',
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
			'Pdv' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Questionnaired1pdv93' => array(
				'className' => 'Questionnaired1pdv93',
				'foreignKey' => 'questionnaired1pdv93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Sortieaccompagnementd2pdv93' => array(
				'className' => 'Sortieaccompagnementd2pdv93',
				'foreignKey' => 'sortieaccompagnementd2pdv93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Retourne la structure référente pour laquelle l'allocataire doit encore
		 * remplir un questionnaire D2 pour l'année en cours.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function structurereferenteId( $personne_id ) {
			$sq = $this->sq(
				array(
					'alias' => 'questionnairesd2pdvs93',
					'fields' => 'questionnairesd2pdvs93.questionnaired1pdv93_id',
					'contain' => false,
					'conditions' => array(
						'questionnairesd2pdvs93.personne_id = Questionnaired1pdv93.personne_id',
						'EXTRACT( \'YEAR\' FROM questionnairesd2pdvs93.created ) = EXTRACT( \'YEAR\' FROM Questionnaired1pdv93.date_validation )',
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
					"Questionnaired1pdv93.id NOT IN ( {$sq} )",
				),
				'order' => array(
					'Questionnaired1pdv93.date_validation DESC'
				)
			);

			$questionnaired1pdv93 = $this->Personne->Questionnaired1pdv93->find( 'first', $querydata );

			return Hash::get( $questionnaired1pdv93, 'Rendezvous.structurereferente_id' );
		}

		/**
		 * Retourne l'id du questionnaire D1 pour lequel l'allocataire doit encore
		 * remplir un questionnaire D2 pour l'année en cours.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function questionnairesd1pdv93Id( $personne_id ) {
			$sq = $this->sq(
				array(
					'alias' => 'questionnairesd2pdvs93',
					'fields' => 'questionnairesd2pdvs93.questionnaired1pdv93_id',
					'contain' => false,
					'conditions' => array(
						'questionnairesd2pdvs93.personne_id' => $personne_id,
						'EXTRACT( \'YEAR\' FROM questionnairesd2pdvs93.created )' => date( 'Y' ),
						'questionnairesd2pdvs93.structurereferente_id = Rendezvous.structurereferente_id'
					)
				)
			);

			$querydata = array(
				'fields' => array( 'Questionnaired1pdv93.id' ),
				'contain' => false,
				'joins' => array(
					$this->Personne->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Questionnaired1pdv93.personne_id' => $personne_id,
					'EXTRACT( \'YEAR\' FROM Questionnaired1pdv93.date_validation )' => date( 'Y' ),
					"Questionnaired1pdv93.id NOT IN ( {$sq} )",
				),
				'order' => array(
					'Questionnaired1pdv93.date_validation DESC'
				)
			);
			$questionnaired1pdv93 = $this->Personne->Questionnaired1pdv93->find( 'first', $querydata );

			return Hash::get( $questionnaired1pdv93, 'Questionnaired1pdv93.id' );
		}

		/**
		 * Retourne une sous-requête permettant de trouver les clés primaires des
		 * allocataires ayant un questionnaire D1 pour l'année en cours qui n'a
		 * pas encore de questionnaire D2 associé.
		 *
		 * @param string $personneIdAlias
		 * @param integer $year
		 * @return string
		 */
		public function sqQuestionnaired2Necessaire( $personneIdAlias = 'Personne.id', $year = null ) {
			$sqQ2Q1Id = $this->sq(
				array(
					'alias' => 'questionnairesd2pdvs93',
					'fields' => 'questionnairesd2pdvs93.questionnaired1pdv93_id',
					'contain' => false,
					'conditions' => array(
						"questionnairesd2pdvs93.personne_id = questionnairesd1pdvs93.personne_id",
						'EXTRACT( \'YEAR\' FROM questionnairesd2pdvs93.created ) = EXTRACT( \'YEAR\' FROM rendezvous.daterdv )',
					)
				)
			);

			$querydata = array(
				'alias' => 'questionnairesd1pdvs93',
				'fields' => 'questionnairesd1pdvs93.personne_id',
				'contain' => false,
				'joins' => array(
					$this->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"questionnairesd1pdvs93.personne_id = {$personneIdAlias}",
					"questionnairesd1pdvs93.id NOT IN ( {$sqQ2Q1Id} )"
				)
			);

			if( !is_null( $year ) ) {
				$querydata['conditions']['EXTRACT( \'YEAR\' FROM rendezvous.daterdv )'] = $year;
			}

			$querydata = array_words_replace(
				$querydata,
				array(
					'Rendezvous' => 'rendezvous',
					'Questionnaired1pdv93' => 'questionnairesd1pdvs93',
					'Questionnaired2pdv93' => 'questionnairesd2pdvs93',
				)
			);

			return $this->Questionnaired1pdv93->sq( $querydata );
		}

		/**
		 * Messages à envoyer à l'utilisateur.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function messages( $personne_id ) {
			$messages = array();

			$this->create( array( 'personne_id' => $personne_id ) );
			$exists = !$this->checkDateOnceAYear( array( 'created' => date( 'Y-m-d' ) ), 'personne_id' );
			if( $exists ) {
				$messages['Questionnaired2pdv93.exists'] = 'error';
			}
			else {
				// Qui possède un questionnaire D1 sans questionnaire D2 pour l'année en cours
				$structurereferente_id = $this->structurereferenteId( $personne_id );
				if( empty( $structurereferente_id ) ) {
					$messages['Questionnaired1pdv93.missing'] = 'error';
				}
			}

			$droitsouverts = $this->droitsouverts( $personne_id );
			if( empty( $droitsouverts ) ) {
				$messages['Situationdossierrsa.etatdosrsa_ouverts'] = 'notice';
			}

			$toppersdrodevorsa = $this->toppersdrodevorsa( $personne_id );
			if( empty( $toppersdrodevorsa ) ) {
				$messages['Calculdroitrsa.toppersdrodevorsa_notice'] = 'notice';
			}

			return $messages;
		}

		/**
		 * @param array $check
		 * @return boolean
		 */
		public function checkDateOnceAYear( $check, $group_column ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( Hash::normalize( $check ) as $key => $value ) {
				list( $year, ) = explode( '-', $value );

				if( !empty( $year ) ) {
					// Pas encore de questionnaire D1 pour l'année en question
					$querydata = array( 'contain' => false );

					$personne_id = Hash::get( $this->data, "{$this->alias}.{$group_column}" );
					$querydata['conditions'] = array(
						"{$this->alias}.{$group_column}" => $personne_id,
						"{$this->alias}.{$key} BETWEEN '{$year}-01-01' AND '{$year}-12-31'"
					);

					$id = Hash::get( $this->data, "{$this->alias}.{$this->primaryKey}" );
					if( !empty( $id ) ) {
						$querydata['conditions']["{$this->alias}.{$this->primaryKey} <>"] = $id;
					}

					$count = $this->find( 'count', $querydata );

					if( $count == 0 ) {
						$result = ( $count == 0 ) && $result;
					}
					else {
						// Tous les D1 ont déjà un D2 correspondant ?
						$sq = $this->sq(
							array(
								'alias' => 'questionnairesd2pdvs93',
								'fields' => array( 'questionnairesd2pdvs93.questionnaired1pdv93_id' ),
								'conditions' => array(
									'questionnairesd2pdvs93.questionnaired1pdv93_id = Questionnaired1pdv93.id',
									'questionnairesd2pdvs93.personne_id = Questionnaired1pdv93.personne_id',
								),
								'contain' => false
							)
						);

						$querydata = array(
							'contain' => false,
							'conditions' => array(
								"Questionnaired1pdv93.id NOT IN ( {$sq} )",
								'Questionnaired1pdv93.personne_id' => $personne_id,
							),
						);

						$count = $this->Personne->Questionnaired1pdv93->find( 'count', $querydata );

						$result = ( $count > 0 ) && $result;
					}
				}
			}

			return $result;
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
				$formData[$this->alias]['questionnaired1pdv93_id'] = $this->questionnairesd1pdv93Id( $personne_id );

				// Lorsque l'allocataire ne possède pas encore de D2 et est soumis à droits et devoirs, on préremplit en maintien
				$querydata = array(
					'fields' => array( 'Calculdroitrsa.toppersdrodevorsa' ),
					'contain' => false,
					'conditions' => array(
						'Calculdroitrsa.personne_id' => $personne_id
					)
				);
				$calculdroitrsa = $this->Personne->Calculdroitrsa->find( 'first', $querydata );
				if( Hash::get( $calculdroitrsa, 'Calculdroitrsa.toppersdrodevorsa' ) ) {
					$formData[$this->alias]['situationaccompagnement'] = 'maintien';
				}
			}

			return $formData;
		}

		/**
		 * Enregistrement d'un questionnaire D2 pour une situation d'accompagnement
		 * et un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @param string $situationaccompagnement
		 * @param string $chgmentsituationadmin
		 * @return boolean
		 */
		public function saveAuto( $personne_id, $situationaccompagnement, $chgmentsituationadmin = null ) {
			$success = true;

			$questionnaired1pdv93_id = $this->questionnairesd1pdv93Id( $personne_id );
			$structurereferente_id = $this->structurereferenteId( $personne_id );

			if( !empty( $structurereferente_id ) && !empty( $questionnaired1pdv93_id ) ) {
				$questionnaired2pdv93 = array(
					'Questionnaired2pdv93' => array(
						'personne_id' => $personne_id,
						'questionnaired1pdv93_id' => $questionnaired1pdv93_id,
						'structurereferente_id' => $structurereferente_id,
						'situationaccompagnement' => $situationaccompagnement,
						'sortieaccompagnementd2pdv93_id' => null,
						'chgmentsituationadmin' => $chgmentsituationadmin,
					)
				);

				$this->create( $questionnaired2pdv93 );
				$success = $this->save() && $success;
			}

			return $success;
		}
	}
?>