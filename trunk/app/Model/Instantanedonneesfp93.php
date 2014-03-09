<?php
	/**
	 * Code source de la classe Instantanedonneesfp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Instantanedonneesfp93 ...
	 *
	 * @package app.Model
	 */
	class Instantanedonneesfp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Instantanedonneesfp93';

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
			'Formattable' => array(
				'null' => false,
				'trim' => false,
				'phone' => array( 'benef_tel_fixe', 'benef_tel_port' ),
				'suffix' => false,
				'amount' => false,
			),
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Règles de validation
		 *
		 * @var array
		 */
		public $validate = array(
			'benef_tel_fixe' => array(
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				)
			),
			'benef_tel_port' => array(
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				)
			),
			'benef_email' => array(
				'email' => array(
					'rule' => array( 'email' ),
					'allowEmpty' => true
				)
			),
		);

		/**
		 *
		 * @see Situationallocataire::natpfD1()
		 *
		 * @var array
		 */
		public $virtualFields = array(
			'benef_natpf' => array(
				'type'      => 'string',
				'postgres'  => '( CASE
					WHEN ( "%s"."benef_natpf_socle" = \'1\' AND "%s"."benef_natpf_activite" = \'1\' AND "%s"."benef_natpf_majore" = \'1\' ) THEN \'socle_majore_activite\'
					WHEN ( "%s"."benef_natpf_socle" = \'1\' AND "%s"."benef_natpf_activite" = \'1\' AND "%s"."benef_natpf_majore" = \'0\' ) THEN \'socle_activite\'
					WHEN ( "%s"."benef_natpf_socle" = \'1\' AND "%s"."benef_natpf_activite" = \'0\' AND "%s"."benef_natpf_majore" = \'1\' ) THEN \'socle_majore\'
					WHEN ( "%s"."benef_natpf_socle" = \'1\' AND "%s"."benef_natpf_activite" = \'0\' AND "%s"."benef_natpf_majore" = \'0\' ) THEN \'socle\'
					WHEN ( "%s"."benef_natpf_socle" = \'0\' AND "%s"."benef_natpf_activite" = \'1\' AND "%s"."benef_natpf_majore" = \'1\' ) THEN \'NC\'
					WHEN ( "%s"."benef_natpf_socle" = \'0\' AND "%s"."benef_natpf_activite" = \'1\' AND "%s"."benef_natpf_majore" = \'0\' ) THEN \'NC\'
					WHEN ( "%s"."benef_natpf_socle" = \'0\' AND "%s"."benef_natpf_activite" = \'0\' AND "%s"."benef_natpf_majore" = \'1\' ) THEN \'NC\'
					WHEN ( "%s"."benef_natpf_socle" = \'0\' AND "%s"."benef_natpf_activite" = \'0\' AND "%s"."benef_natpf_majore" = \'0\' ) THEN \'NC\'
					ELSE \'NC\'
				END )'
			),
		);

		/**
		 * Les valeurs possibles pour la nature de la prestation (voir le champ
		 * virtuel benef_natpf.
		 *
		 * @var array
		 */
		public $benef_natpf = array(
			'socle_majore_activite',
			'socle_activite',
			'socle_majore',
			'socle',
			'NC',
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Ficheprescription93' => array(
				'className' => 'Ficheprescription93',
				'foreignKey' => 'ficheprescription93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function getInstantane( $personne_id ) {
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			$querydata = array(
				'fields' => array_merge(
					array(
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'"Personne"."numfixe" AS "Personne__tel_fixe"',
						'"Personne"."numport" AS "Personne__tel_port"',
						'Personne.email',
						'Dsp.nivetu',
						'DspRev.nivetu',
						'Cer93.positioncer',
						'Historiqueetatpe.identifiantpe',
						'Historiqueetatpe.etat',
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.complideadr',
						'Adresse.compladr',
						'Adresse.numcomptt',
						'Adresse.numcomrat',
						'Adresse.codepos',
						'Adresse.locaadr',
						'Dossier.matricule',
						'Situationdossierrsa.etatdosrsa',
						'Calculdroitrsa.toppersdrodevorsa',
					),
					$this->Ficheprescription93->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->vfsSummary()
				),
				'contain' => false,
				'joins' => array(
					$this->Ficheprescription93->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$this->Ficheprescription93->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$this->Ficheprescription93->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Ficheprescription93->Personne->join(
						'Dsp',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Dsp.id IN ( '.$this->Ficheprescription93->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
							)
						)
					),
					$this->Ficheprescription93->Personne->join(
						'DspRev',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'DspRev.id IN ( '.$this->Ficheprescription93->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
							)
						)
					),
					$this->Ficheprescription93->Personne->join(
						'Contratinsertion',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Contratinsertion.id IN ( '.$this->Ficheprescription93->Personne->Contratinsertion->sqDernierContrat().' )'
							)
						)
					),
					$this->Ficheprescription93->Personne->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
					$this->Ficheprescription93->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Ficheprescription93->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$this->Ficheprescription93->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Ficheprescription93->Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$this->Ficheprescription93->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'LEFT OUTER' ) ),
					$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
					$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
				),
				'conditions' => array(
					'Personne.id' => $personne_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Ficheprescription93->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Informationpe.id IS NULL',
							'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
						)
					),
					array(
						'OR' => array(
							'Historiqueetatpe.id IS NULL',
							'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
						)
					)
				)
			);

			$result = $this->Ficheprescription93->Personne->find( 'first', $querydata );

			$return = array();
			if( !empty( $result ) ) {
				foreach( array( 'Personne', 'Adresse', 'Dossier', 'Detailcalculdroitrsa', 'Situationdossierrsa', 'Calculdroitrsa' ) as $modelName ) {
					foreach( $result[$modelName] as $field => $value ) {
						$return[$this->alias]["benef_{$field}"] = $value;
					}
				}

				$return[$this->alias]['benef_identifiantpe'] = $result['Historiqueetatpe']['identifiantpe'];
				$return[$this->alias]['benef_inscritpe'] = ( $result['Historiqueetatpe']['etat'] === 'inscription' ? '1' : '0' );

				// Niveau d'étude
				$nivetu = Hash::get( $result, 'DspRev.nivetu' );
				if( $nivetu === null ) {
					$nivetu = Hash::get( $result, 'Dsp.nivetu' );
				}
				$return[$this->alias]['benef_nivetu'] = $nivetu;

				// FIXME: que dans le cas d'un ajout ?
				// FIXME: le dernier non annulé ?
				// Position du dernier CER
				$positioncer = Hash::get( $return, 'Cer93.positioncer' );
				if( !empty( $positioncer ) ) {
					switch( $positioncer ) {
						case '99valide':
							$positioncer = 'valide';
							break;
						case '04premierelecture':
						case '05secondelecture':
						case '07attavisep':
							$positioncer = 'validationcg';
							break;
						case '00enregistre':
						case '01signe':
						case '02attdecisioncpdv':
						case '03attdecisioncg':
							$positioncer = 'validationpdv';
							break;
					}
				}
				$return[$this->alias]['benef_positioncer'] = $positioncer;

				// Nature de prestation
				foreach( array( 'benef_natpf_activite', 'benef_natpf_majore', 'benef_natpf_socle' ) as $field ) {
					$return[$this->alias][$field] = ( $return[$this->alias][$field] ? '1' : '0' );
				}

				$activite = Hash::get( $return, "{$this->alias}.benef_natpf_activite" );
				$majore = Hash::get( $return, "{$this->alias}.benef_natpf_majore" );
				$socle = Hash::get( $return, "{$this->alias}.benef_natpf_socle" );

				if( $socle && !$activite && !$majore ) {
					$return[$this->alias]['benef_natpf'] = 'socle';
				}
				else if( $socle && !$activite && $majore ) {
					$return[$this->alias]['benef_natpf'] = 'socle_majore';
				}
				else if( $socle && $activite && !$majore ) {
					$return[$this->alias]['benef_natpf'] = 'socle_activite';
				}
				else if( $socle && $activite && $majore ) {
					$return[$this->alias]['benef_natpf'] = 'socle_majore_activite';
				}
			}

			return $return;
		}

		/**
		 * Complète les enums avec le champ virtuel benef_natpf.
		 *
		 * @return array
		 */
		public function enums() {
			$enums = parent::enums();

			$enums[$this->alias]['benef_natpf'] = array();
			foreach( $this->benef_natpf as $natpf ) {
				$enums[$this->alias]['benef_natpf'][$natpf] = __d( 'instantanedonneesfp93', "ENUM::BENEF_NATPF::{$natpf}" );
			}

			return $enums;
		}
	}
?>