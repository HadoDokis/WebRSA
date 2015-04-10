<?php
	/**
	 * Code source de la classe Regressionorientationcov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once( ABSTRACTMODELS.'AbstractThematiquecov58.php' );

	/**
	 * La classe Regressionorientationcov58 ...
	 *
	 * @package app.Model
	 */
	class Regressionorientationcov58 extends AbstractThematiquecov58
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Regressionorientationcov58';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Dependencies',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Règles de validation par défaut du modèle.
		 *
		 * @var array
		 */
		public $validate = array(
			'referent_id' => array(
				'dependentForeignKeys' => array(
					'rule' => array( 'dependentForeignKeys', 'Referent', 'Structurereferente' ),
					'message' => 'La référent n\'appartient pas à la structure référente',
				)
			),
			'structurereferente_id' => array(
				'dependentForeignKeys' => array(
					'rule' => array( 'dependentForeignKeys', 'Structurereferente', 'Typeorient' ),
					'message' => 'La structure référente n\'appartient pas au type d\'orientation',
				)
			)
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'dossiercov58_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Nvorientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'nvorientstruct_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
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
			),
		);


		/**
		 * Retourne le querydata utilisé dans la partie décisions d'une COV.
		 *
		 * Surchargé afin d'ajouter le modèle de la thématique et le modèle de
		 * décision dans le contain.
		 *
		 * @param integer $cov58_id
		 * @return array
		 */
		public function qdDossiersParListe( $cov58_id ) {
			$result = parent::qdDossiersParListe( $cov58_id );
			$modeleDecision = 'Decision'.Inflector::underscore( $this->alias );

			$result['contain'][$this->alias] = array(
				'Orientstruct' => array(
					'Typeorient',
					'Structurereferente',
					'Referent'
				),
				'Typeorient',
				'Structurereferente',
				'Referent'
			);

			$result['contain']['Passagecov58'][$modeleDecision] = array(
				'Typeorient',
				'Structurereferente',
				'Referent',
				'order' => array( 'etapecov DESC' )
			);

			return $result;
		}

		/**
		 * Tentative de sauvegarde des décisions de la thématique.
		 *
		 * Une nouvelle orientation sera crée lorsque la décision est
		 * "Accepte" ou "Maintien référent actuel"; le référent du parcours
		 * sera clôturé s'il existe et un nouveau référent du parcours sera désigné
		 * s'il a été choisi.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveDecisions( $data ) {
			$modelDecisionName = 'Decision'.Inflector::underscore( $this->alias );

			$success = true;
			if ( isset( $data[$modelDecisionName] ) && !empty( $data[$modelDecisionName] ) ) {
				foreach( $data[$modelDecisionName] as $key => $values ) {
					$passagecov58 = $this->Dossiercov58->Passagecov58->find(
						'first',
						array(
							'fields' => array_merge(
								$this->Dossiercov58->Passagecov58->fields(),
								$this->Dossiercov58->Passagecov58->Cov58->fields(),
								$this->Dossiercov58->fields(),
								$this->fields()
							),
							'conditions' => array(
								'Passagecov58.id' => $values['passagecov58_id']
							),
							'joins' => array(
								$this->Dossiercov58->Passagecov58->join( 'Dossiercov58' ),
								$this->Dossiercov58->Passagecov58->join( 'Cov58' ),
								$this->Dossiercov58->join( $this->alias )
							)
						)
					);

					if( in_array( $values['decisioncov'], array( 'accepte', 'refuse' ) ) ) {
						$date_propo = Hash::get( $passagecov58, "{$this->alias}.datedemande" );
						list( $date_valid, $heure_valid ) = explode( ' ', Hash::get( $passagecov58, 'Cov58.datecommission' ) );

						$rgorient = $this->Dossiercov58->Personne->Orientstruct->rgorientMax( $passagecov58['Dossiercov58']['personne_id'] ) + 1;
						$origine = ( $rgorient > 1 ? 'reorientation' : 'manuelle' );

						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => Hash::get( $passagecov58, 'Dossiercov58.personne_id' ),
								'typeorient_id' => Hash::get( $values, 'typeorient_id' ),
								'structurereferente_id' => suffix( Hash::get( $values, 'structurereferente_id' ) ),
								'referent_id' => suffix( Hash::get( $values, 'referent_id' ) ),
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $rgorient,
								'origine' => $origine,
								'etatorient' => 'decision',
								'user_id' => Hash::get( $passagecov58, "{$this->alias}.user_id" )
							)
						);

						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;

						// Mise à jour de l'enregistrement de la thématique avec l'id de la nouvelle orientation
						$success = $success && $this->updateAllUnBound(
							array( "\"{$this->alias}\".\"nvorientstruct_id\"" => $this->Orientstruct->id ),
							array( "\"{$this->alias}\".\"id\"" => Hash::get( $passagecov58, "{$this->alias}.id" ) )
						);

						$success = $this->Orientstruct->Personne->PersonneReferent->changeReferentParcours(
							Hash::get( $passagecov58, 'Dossiercov58.personne_id' ),
							suffix( Hash::get( $values, 'referent_id' ) ),
							array(
								'PersonneReferent' => array(
									'personne_id' => Hash::get( $passagecov58, 'Dossiercov58.personne_id' ),
									'referent_id' => suffix( Hash::get( $values, 'referent_id' ) ),
									'dddesignation' => $date_valid,
									'structurereferente_id' => suffix( Hash::get( $values, 'structurereferente_id' ) ),
									'user_id' => Hash::get( $passagecov58, "{$this->alias}.user_id" )
								)
							)
						) && $success;
					}
					else{
						//Sauvegarde des décisions
						$this->Dossiercov58->Passagecov58->{$modelDecisionName}->create( array( $modelDecisionName => $data[$modelDecisionName][$key] ) );
						$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->save() && $success;
					}

					// Modification etat du dossier passé dans la COV
					if( in_array( $values['decisioncov'], array( 'accepte', 'refuse' ) ) ) {
						$this->Dossiercov58->Passagecov58->updateAllUnBound(
							array( 'Passagecov58.etatdossiercov' => '\'traite\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'annule' ) {
						$this->Dossiercov58->Passagecov58->updateAllUnBound(
							array( 'Passagecov58.etatdossiercov' => '\'annule\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'reporte' ) {
						$this->Dossiercov58->Passagecov58->updateAllUnBound(
							array( 'Passagecov58.etatdossiercov' => '\'reporte\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
				}
			}

			$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->saveAll( Set::extract( $data, '/'.$modelDecisionName ), array( 'atomic' => false, 'validate' => 'only' ) ) && $success;

			return $success;
		}
	}
?>