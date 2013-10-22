<?php
	/**
	 * Code source de la classe Questionnaired1pdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Questionnaired1pdv93 ...
	 *
	 * @package app.Model
	 */
	class Questionnaired1pdv93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Questionnaired1pdv93';

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
		 * Par défaut, on met la récursivité au minimum.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'rendezvous_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Situationallocataire' => array(
				'className' => 'Situationallocataire',
				'foreignKey' => 'situationallocataire_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
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
		 * Règles de validation en plus de celles en base.
		 *
		 * @var array
		 */
		public $validate = array(
			'inscritpe' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'marche_travail' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'vulnerable' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'diplomes_etrangers' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'categorie_sociopro' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'nivetu' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'autre_caracteristique' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'autre_caracteristique_autre' => array(
				'notNullIf' => array(
					'rule' => array( 'notNullIf', 'autre_caracteristique', true, array( 'autres' ) )
				)
			),
			'conditions_logement' => array(
				'notNullIf' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'conditions_logement_autre' => array(
				'notNullIf' => array(
					'rule' => array( 'notNullIf', 'conditions_logement', true, array( 'autre' ) )
				)
			),
			'date_validation' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				),
				'checkDateOnceAYear' => array(
					'rule' => array( 'checkDateOnceAYear', 'personne_id' )
				),
			),
		);

		/**
		 * FIXME: sur la date de RDV + traduction
		 *
		 * @param array $check
		 * @return boolean
		 */
		public function checkDateOnceAYear( $check, $group_column ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( Set::normalize( $check ) as $key => $value ) {
				list( $year, ) = explode( '-', $value );

				if( !empty( $year ) ) {
					$personne_id = Hash::get( $this->data, "{$this->alias}.{$group_column}" );
					$conditions = array(
						"{$this->alias}.{$group_column}" => $personne_id,
						"{$this->alias}.{$key} BETWEEN '{$year}-01-01' AND '{$year}-12-31'"
					);

					$id = Hash::get( $this->data, "{$this->alias}.{$this->primaryKey}" );
					if( !empty( $id ) ) {
						$conditions["{$this->alias}.{$this->primaryKey} <>"] = $id;
					}

					$count = $this->find(
						'count',
						array(
							'contain' => false,
							'conditions' => $conditions
						)
					);

					$result = ( $count == 0 ) && $result;
				}
			}
			return $result;
		}

		/**
		 *
		 * @param array $data
		 * @return array
		 */
		public function completeDataForView( $data ) {
			// Calcul de la situation familiale suivant les catégories du tableau D1
			$sitfam_view = null;
			$isole = in_array( $data['Situationallocataire']['sitfam'], array( 'CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU' ) );
			if( $isole ) {
				$sitfam_view = ( empty( $data['Situationallocataire']['nbenfants'] ) ? 'isole_sans_enfant' : 'isole_avec_enfant' );
			}
			else {
				$sitfam_view = ( empty( $data['Situationallocataire']['nbenfants'] ) ? 'en_couple_sans_enfant' : 'en_couple_avec_enfant' );
			}
			$data['Situationallocataire']['sitfam_view'] = $sitfam_view;

			// Calcul de la nature de la prestation suivant les catégories du tableau D1
			$natpf_view = null;
			if( $data['Situationallocataire']['natpf_socle'] && $data['Situationallocataire']['natpf_activite'] ) {
				$natpf_view = 'socle_activite';
			}
			else if( $data['Situationallocataire']['natpf_socle'] ) {
				$natpf_view = 'socle';
			}
			else if( $data['Situationallocataire']['natpf_majore'] ) {
				$natpf_view = 'majore';
			}
			$data['Situationallocataire']['natpf_view'] = $natpf_view;

			$date_validation = Hash::get( $data, 'Questionnaired1pdv93.date_validation' );

			// Calcul des tranches d'âge suivant les catégories du tableau D1
			$Tableausuivipdv93 = ClassRegistry::init( 'Tableausuivipdv93' );
			$tranches = array_keys( $Tableausuivipdv93->tranches_ages );
			$tranche_age_view = null;
			$age = age( $data['Situationallocataire']['dtnai'], $date_validation );
			foreach( $tranches as $tranche ) {
				list( $min, $max ) = explode( '_', $tranche );
				if( $min <= $age && $age <= $max ) {
					$tranche_age_view = $tranche;
				}
			}
			$data['Situationallocataire']['tranche_age_view'] = $tranche_age_view;

			// Calcul de l'ancienneté dans le dispositif suivant les catégories du tableau D1
			$Tableausuivipdv93 = ClassRegistry::init( 'Tableausuivipdv93' );
			$tranches = array_keys( $Tableausuivipdv93->anciennetes_dispositif );
			$anciennete_dispositif_view = null;
			$age = age( $data['Situationallocataire']['dtdemrsa'], $date_validation );
			foreach( $tranches as $tranche ) {
				list( $min, $max ) = explode( '_', $tranche );
				if( $min <= $age && $age <= $max ) {
					$anciennete_dispositif_view = $tranche;
				}
			}
			$data['Situationallocataire']['anciennete_dispositif_view'] = $anciennete_dispositif_view;

			return $data;
		}

		/**
		 *
		 *
		 * @param integer $personne_id
		 * @return array
		 * @throws NotFoundException
		 */
		public function prepareFormData( $personne_id ) {
			$formData = array();

			$data = $this->Situationallocataire->getSituation( $personne_id );
			if( empty( $data ) ) {
				throw new NotFoundException();
			}

			// On complète les données du formulaire
			$formData[$this->alias]['personne_id'] = $personne_id;

			$formData['Situationallocataire']['personne_id'] = $personne_id;
			$modelNames = array(
				'Personne',
				'Prestation',
				'Calculdroitrsa',
				'Historiqueetatpe',
				'Adresse',
				'Dossier',
				'Situationdossierrsa',
				'Foyer',
				'Suiviinstruction',
				'Detailcalculdroitrsa',
			);
			foreach( $modelNames as $modelName ) {
				foreach( $data[$modelName] as $field => $value ) {
					$formData['Situationallocataire'][$field] = $value;
				}
			}
			$formData['Situationallocataire']['identifiantpe'] = $data['Historiqueetatpe']['identifiantpe'];
			$formData['Situationallocataire']['datepe'] = $data['Historiqueetatpe']['date'];
			$formData['Situationallocataire']['etatpe'] = $data['Historiqueetatpe']['etat'];
			$formData['Situationallocataire']['codepe'] = $data['Historiqueetatpe']['code'];
			$formData['Situationallocataire']['motifpe'] = $data['Historiqueetatpe']['motif'];

			foreach( array( 'socle', 'majore', 'activite' ) as $type ) {
				$formData['Situationallocataire']["natpf_{$type}"] = ( $formData['Situationallocataire']["natpf_{$type}"] ? '1' : '0' );
			}

			// Inscrit à Pôle Emploi
			$inscritpe = Hash::get( $data, 'Historiqueetatpe.etat' );
			if( !is_null( $inscritpe ) ) {
				$inscritpe = ( ( $inscritpe == 'inscription' ) ? '1' : '0' );
			}
			$formData[$this->alias]['inscritpe'] = $inscritpe;

			$formData[$this->alias]['date_validation'] = date( 'Y-m-d' );
			$formData[$this->alias]['nivetu'] = $this->nivetu( $personne_id );
			$formData[$this->alias]['autre_caracteristique'] = 'beneficiaire_minimas';
			$formData[$this->alias]['rendezvous_id'] = $this->rendezvous( $personne_id );

			// Champs en visualisation uniquement
			$formData = $this->completeDataForView( $formData );

			return $formData;
		}

		/**
		 * Filtrage des options pour le formulaire: pour les groupes vulnérables,
		 * on ne garde que "Personnes handicapées (reconnues par la MDPH)" et
		 * "Autres personnes défavorisées"
		 *
		 * @param array $options
		 * @return array
		 */
		public function filterOptions( array $options ) {
			$options = Hash::remove( $options, "{$this->alias}.vulnerable.migrant" );
			$options = Hash::remove( $options, "{$this->alias}.vulnerable.minorite" );

			return $options;
		}

		/**
		 * Retourne le niveau d'étude d'un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @return string
		 */
		public function nivetu( $personne_id ) {
			$querydata = array(
				'fields' => array( 'nivetu' ),
				'contain' => false,
				'conditions' => array( 'personne_id' => $personne_id ),
				'order' => array( 'id DESC' ),
			);

			$nivetu = $this->Personne->Dsp->DspRev->find( 'first', $querydata );
			$nivetu = Hash::get( $nivetu, 'DspRev.nivetu' );

			if( empty( $nivetu ) ) {
				$nivetu = $this->Personne->Dsp->find( 'first', $querydata );
				$nivetu = Hash::get( $nivetu, 'Dsp.nivetu' );
			}

			return $nivetu;
		}

		/**
		 * Retourne la soumission à droits et devoirs d'un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function toppersdrodevorsa( $personne_id ) {
			$querydata = array(
				'fields' => array( 'toppersdrodevorsa' ),
				'contain' => false,
				'conditions' => array( 'personne_id' => $personne_id )
			);
			$calculdroitrsa = $this->Personne->Calculdroitrsa->find( 'first', $querydata );
			$toppersdrodevorsa = Hash::get( $calculdroitrsa, 'Calculdroitrsa.toppersdrodevorsa' );

			return $toppersdrodevorsa;

		}

		/**
		 * Retourne la soumission à droits et devoirs d'un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function droitsouverts( $personne_id ) {
			$querydata = array(
				'fields' => array( 'Situationdossierrsa.etatdosrsa' ),
				'contain' => false,
				'conditions' => array( 'Personne.id' => $personne_id ),
				'joins' => array(
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
				)
			);

			$situationdossierrsa = $this->Personne->find( 'first', $querydata );
			$situationdossierrsa = Hash::get( $situationdossierrsa, 'Situationdossierrsa.etatdosrsa' );
			$situationdossierrsa = in_array( $situationdossierrsa, (array)Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' ), true );

			return $situationdossierrsa;
		}

		/**
		 * Retourne l'id du RDV à utiliser dans le questionnaire.
		 *
		 * @param integer $personne_id
		 * @return integer
		 */
		public function rendezvous( $personne_id ) {
			$querydata = array(
				'conditions' => array(
					'Thematiquerdv.linkedmodel' => $this->alias
				)
			);
			$thematiquesrdvs = $this->Rendezvous->Thematiquerdv->find( 'all', $querydata ); // FIXME: une boucle ?

			$with = $this->Rendezvous->hasAndBelongsToMany['Thematiquerdv']['with'];
			$foo = date( 'Y-m-d' );
			$querydata = array(
				'fields' => array(
					'Rendezvous.id'
				),
				'contain' => false,
				'conditions' => array(
					'Rendezvous.personne_id' => $personne_id,
					"DATE_TRUNC( 'YEAR', Rendezvous.daterdv ) = DATE_TRUNC( 'YEAR', TIMESTAMP '{$foo}' )",
					'Rendezvous.typerdv_id' => Hash::extract( $thematiquesrdvs, '{n}.Thematiquerdv.typerdv_id' ),
					'Thematiquerdv.linkedmodel' => $this->alias
				),
				'joins' => array(
					$this->Rendezvous->join( $with, array( 'type' => 'INNER' ) ),
					$this->Rendezvous->{$with}->join( 'Thematiquerdv', array( 'type' => 'INNER' ) ),
				),
				'order' => array( 'Rendezvous.daterdv ASC' )
			);
			$rendezvous = $this->Rendezvous->find( 'first', $querydata );

			return Hash::get( $rendezvous, 'Rendezvous.id' );
		}

		/**
		 * Messages à envoyer à l'utilisateur.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function messages( $personne_id ) {
			$messages = array();

			// Qui possède un RDV ...
			$rendezvous = $this->rendezvous( $personne_id );
			if( empty( $rendezvous ) ) {
				$messages['Rendezvous.premierrdv'] = 'error';
			}

			$nivetu = $this->nivetu( $personne_id );
			if( empty( $nivetu ) ) {
				$messages['Dsp.nivetu_obligatoire'] = 'error';
			}

			$droitsouverts = $this->droitsouverts( $personne_id );
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
				$messages['Questionnaired1pdv93.exists'] = 'notice';
			}

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
			return !in_array( 'error', $messages ) && !array_key_exists( 'Questionnaired1pdv93.exists', $messages );
		}
	}
?>