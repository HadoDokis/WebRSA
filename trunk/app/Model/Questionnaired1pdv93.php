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
		 * TODO: changer la signature, on ne modifie plus
		 *
		 * @param integer $id
		 * @param integer $personne_id
		 * @return array
		 */
		public function prepareFormDataAddEdit( $id, $personne_id ) {
			$formData = array();

			if( !is_null( $personne_id ) ) {
				$data = $this->Situationallocataire->getSituation( $personne_id );

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

				$formData[$this->alias]['date_validation'] = date( 'Y-m-d' );
				$formData[$this->alias]['nivetu'] = $this->nivetu( $personne_id );
				$formData[$this->alias]['autre_caracteristique'] = 'beneficiaire_minimas';
			}

			if( !is_null( $id ) ) {
				// TODO
			}

			return $formData;
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "{$this->alias}.personne_id" ),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result[$this->alias]['personne_id'];
			}
			else {
				return null;
			}
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
		 * Messages à envoyer à l'utilisateur.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function messages( $personne_id ) {
			$messages = array();

			$nivetu = $this->nivetu( $personne_id );
			if( empty( $nivetu ) ) {
				$messages['Dsp.nivetu_obligatoire'] = 'error';
			}

			$toppersdrodevorsa = $this->toppersdrodevorsa( $personne_id );
			if( empty( $toppersdrodevorsa ) ) {
				$messages['Calculdroitrsa.toppersdrodevorsa_notice'] = 'notice';
			}

			$droitsouverts = $this->droitsouverts( $personne_id );
			if( empty( $droitsouverts ) ) {
				$messages['Situationdossierrsa.etatdosrsa_ouverts'] = 'notice';
			}

			return $messages;
		}
	}
?>