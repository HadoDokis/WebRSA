<?php
	/**
	 * Fichier source du modèle Histochoixcer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Histochoixcer93.
	 *
	 * @package app.Model
	 */
	class Histochoixcer93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Histochoixcer93';

		/**
		 * Récursivité.
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
// 			'Validation.Autovalidate',
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate',
		);

		/**
		 * FIXME: doc - Histochoixcer93.prevalide est obligatoire lorsque l'on est à l'étape
		 * 04premierelecture.
		 *
		 * @var array
		 */
		public $validate = array(
			'prevalide' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'decisioncs' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'decisioncadre' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Cer93' => array(
				'className' => 'Cer93',
				'foreignKey' => 'cer93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			)
		);

		/**
		 *
		 */
		public function prepareFormData( $contratinsertion, $etape, $user_id ) {
			$formData = array();

			// Ajout ou modification
			$action = 'add';
			$nbHistochoixcer93 = count( $contratinsertion['Cer93']['Histochoixcer93'] );
			if( $nbHistochoixcer93 > 0 ) {
				$etapeHistochoixcer93 = $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1]['etape'];

				$intEtapeHistochoixcer93 = preg_replace( '/^([0-9]{2}).*$/', '\1', $etapeHistochoixcer93 );
				$intEtape = preg_replace( '/^([0-9]{2}).*$/', '\1', $etape );

				if( !( ( $intEtapeHistochoixcer93 == $intEtape ) || ( $intEtapeHistochoixcer93 == ( $intEtape - 1 ) ) ) ) {
					throw new error500Exception( 'Incohérence des étapes' );
				}

				if( $etapeHistochoixcer93 == $etape ) {
					$action = 'edit';
				}
			}

			if( $action == 'add' ) {
				$formData = array(
					'Histochoixcer93' => array(
						'cer93_id' => $contratinsertion['Cer93']['id'],
						'user_id' => $user_id,
						'etape' => $etape,
					)
				);

				// FIXME: pas toujours
				if( ( $nbHistochoixcer93 > 0 ) && $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1] ) {
					foreach( array( 'formeci', 'commentaire' ) as $field ) {
						$formData['Histochoixcer93'][$field] = $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1][$field];
					}
				}
			}
			else {
				$formData = array( 'Histochoixcer93' => $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1] );
			}

			return $formData;
		}

		/**
		 * Sauvegarde des différentes étapes de décisions du CER au cours du workflow.
		 *	Lorsque le CPDV refuse, le contrat est en état final 99rejete
		 *
		 * FIXME: plus les états finaux pour Chargé de suivi et avis cadre
		 */
		public function saveDecision( $data ) {
			$success = $this->save( $data );

			// Parfois le champ datechoix vient du formulaire parfois c'est un champ caché
			$datechoix = ( is_array( $data['Histochoixcer93']['datechoix'] ) ? date_cakephp_to_sql( $data['Histochoixcer93']['datechoix'] ) : $data['Histochoixcer93']['datechoix'] );

			if( $data['Histochoixcer93']['etape'] == '03attdecisioncg' && $data['Histochoixcer93']['isrejet'] ) {
				$success = $this->Cer93->updateAll(
					array(
						'Cer93.positioncer' => '\'99rejete\'',
						'Cer93.formeci' => '\''.$data['Histochoixcer93']['formeci'].'\'',
					),
					array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
				) && $success;

				$this->Cer93->id = $data['Histochoixcer93']['cer93_id'];
				$contratinsertion_id = $this->Cer93->field( 'contratinsertion_id' );

				$success = $this->Cer93->Contratinsertion->updateAll(
					array(
						'Contratinsertion.decision_ci' => '\'R\'',
						'Contratinsertion.datedecision' => '\''.$datechoix.'\'',
						'Contratinsertion.forme_ci' => '\''.$data['Histochoixcer93']['formeci'].'\''
					),
					array( '"Contratinsertion"."id"' => $contratinsertion_id )
				) && $success;
			}
			else if( $data['Histochoixcer93']['etape'] == '05secondelecture' ) {
				// Validation du contrat en seconde lecture
				if( $data['Histochoixcer93']['decisioncs'] == 'valide' ) {
					$success = $this->Cer93->updateAll(
						array(
							'Cer93.positioncer' => '\'99valide\'',
							'Cer93.formeci' => '\''.$data['Histochoixcer93']['formeci'].'\'',
						),
						array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
					) && $success;

					$cer93 = $this->Cer93->find(
						'first',
						array(
							'fields' => array(
								'Cer93.contratinsertion_id'
							),
							'conditions' => array(
								'Cer93.id' => $data['Histochoixcer93']['cer93_id']
							)
						)
					);

					$success = $this->Cer93->Contratinsertion->updateAll(
						array(
							'Contratinsertion.decision_ci' => '\'V\'',
							'Contratinsertion.datevalidation_ci' => '\''.$datechoix.'\'',
							'Contratinsertion.datedecision' => '\''.$datechoix.'\'',
							'Contratinsertion.forme_ci' => '\''.$data['Histochoixcer93']['formeci'].'\''
						),
						array( '"Contratinsertion"."id"' => $cer93['Cer93']['contratinsertion_id'] )
					) && $success;
				}
				// Passage en EP du contrat en seconde lecture
				else if( $data['Histochoixcer93']['decisioncs'] == 'passageep' ) {
					$cer93 = $this->Cer93->find(
						'first',
						array(
							'conditions' => array(
								'Cer93.id' => $data['Histochoixcer93']['cer93_id']
							),
							'contain' => array(
								'Contratinsertion'
							)
						)
					);

					$dossierep = array(
						'Dossierep' => array(
							'themeep' => 'contratscomplexeseps93',
							'personne_id' => $cer93['Contratinsertion']['personne_id']
						)
					);

					$this->Cer93->Contratinsertion->Personne->Dossierep->create( $dossierep );
					$success = ( $this->Cer93->Contratinsertion->Personne->Dossierep->save() !== false ) && $success;

					// Sauvegarde des données de la thématique
					$contratcomplexeep93 = array(
						'Contratcomplexeep93' => array(
							'dossierep_id' => $this->Cer93->Contratinsertion->Personne->Dossierep->id,
							'contratinsertion_id' => $cer93['Contratinsertion']['id']
						)
					);

					$this->Cer93->Contratinsertion->Personne->Dossierep->Contratcomplexeep93->create( $contratcomplexeep93 );
					$success = ( $this->Cer93->Contratinsertion->Personne->Dossierep->Contratcomplexeep93->save() !== false ) && $success;

					$success = $this->Cer93->updateAll(
						array(
							'Cer93.positioncer' => '\'07attavisep\'',
							'Cer93.formeci' => '\''.$data['Histochoixcer93']['formeci'].'\''
						),
						array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
					) && $success;

					$success = $this->Cer93->Contratinsertion->updateAll(
						array(
							'Contratinsertion.forme_ci' => '\''.$data['Histochoixcer93']['formeci'].'\'',
						),
						array( '"Contratinsertion"."id"' => $cer93['Cer93']['contratinsertion_id'] )
					) && $success;
				}
				// Avis cadre
				else {
					$success = $this->Cer93->updateAll(
						array( 'Cer93.positioncer' => '\'05secondelecture\'' ),
						array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
					) && $success;
				}
			}
			else if( $data['Histochoixcer93']['etape'] == '06attaviscadre' ) {
				// Validation du contrat en seconde lecture
				if( in_array( $data['Histochoixcer93']['decisioncadre'], array( 'valide', 'rejete' ) ) ) {
					$success = $this->Cer93->updateAll(
						array(
							'Cer93.positioncer' => '\''.( ( $data['Histochoixcer93']['decisioncadre'] == 'valide' ) ? '99valide' : '99rejete' ).'\'',
							'Cer93.formeci' => '\''.$data['Histochoixcer93']['formeci'].'\''
						),
						array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
					) && $success;

					$cer93 = $this->Cer93->find(
						'first',
						array(
							'fields' => array(
								'Cer93.contratinsertion_id'
							),
							'conditions' => array(
								'Cer93.id' => $data['Histochoixcer93']['cer93_id']
							)
						)
					);

					$fields = array(
						'Contratinsertion.decision_ci' => '\''.( ( $data['Histochoixcer93']['decisioncadre'] == 'valide' ) ? 'V' : 'R' ).'\'',
						'Contratinsertion.datedecision' => '\''.$datechoix.'\'',
						'Contratinsertion.forme_ci' => '\''.$data['Histochoixcer93']['formeci'].'\'',
					);

					if( $data['Histochoixcer93']['decisioncadre'] == 'valide' ) {
						$fields['Contratinsertion.datevalidation_ci'] = '\''.$datechoix.'\'';
					}

					$success = $this->Cer93->Contratinsertion->updateAll(
						$fields,
						array( '"Contratinsertion"."id"' => $cer93['Cer93']['contratinsertion_id'] )
					) && $success;
				}
				// Passage en EP du contrat en seconde lecture
				else if( $data['Histochoixcer93']['decisioncadre'] == 'passageep' ) {
					$cer93 = $this->Cer93->find(
						'first',
						array(
							'conditions' => array(
								'Cer93.id' => $data['Histochoixcer93']['cer93_id']
							),
							'contain' => array(
								'Contratinsertion'
							)
						)
					);

					$dossierep = array(
						'Dossierep' => array(
							'themeep' => 'contratscomplexeseps93',
							'personne_id' => $cer93['Contratinsertion']['personne_id']
						)
					);

					$this->Cer93->Contratinsertion->Personne->Dossierep->create( $dossierep );
					$success = ( $this->Cer93->Contratinsertion->Personne->Dossierep->save() !== false ) && $success;

					// Sauvegarde des données de la thématique
					$contratcomplexeep93 = array(
						'Contratcomplexeep93' => array(
							'dossierep_id' => $this->Cer93->Contratinsertion->Personne->Dossierep->id,
							'contratinsertion_id' => $cer93['Contratinsertion']['id']
						)
					);

					$this->Cer93->Contratinsertion->Personne->Dossierep->Contratcomplexeep93->create( $contratcomplexeep93 );
					$success = ( $this->Cer93->Contratinsertion->Personne->Dossierep->Contratcomplexeep93->save() !== false ) && $success;

					$success = $this->Cer93->updateAll(
						array(
							'Cer93.positioncer' => '\'07attavisep\'',
							'Cer93.formeci' => '\''.$data['Histochoixcer93']['formeci'].'\''
						),
						array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
					) && $success;

					$success = $this->Cer93->Contratinsertion->updateAll(
						array(
							'Contratinsertion.forme_ci' => '\''.$data['Histochoixcer93']['formeci'].'\''
						),
						array( '"Contratinsertion"."id"' => $cer93['Cer93']['contratinsertion_id'] )
					) && $success;
				}
			}
			else {
				$success = $this->Cer93->updateAll(
					array( 'Cer93.positioncer' => '\''.$data['Histochoixcer93']['etape'].'\'' ),
					array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
				) && $success;
			}

			return $success;
		}

		/**
		 * Retourne une sous-requête permettant de connaître le dernier historique pour un
		 * CER93 donné
		 *
		 * @param string $field Le champ Cer93.id sur lequel faire la sous-requête
		 * @return string
		 */
		public function sqDernier( $field ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false );
			return "SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.cer93_id = ".$field."
					ORDER BY {$table}.modified DESC
					LIMIT 1";
		}
	}
?>