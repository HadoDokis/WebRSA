<?php
	/**
	 * Code source de la classe Decisiondossierpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisiondossierpcg66 ...
	 *
	 * @package app.Model
	 */
	class Decisiondossierpcg66 extends AppModel
	{
		public $name = 'Decisiondossierpcg66';

		public $recursive = -1;

		public $actsAs = array(
			'Pgsqlcake.PgsqlAutovalidate',
			'Formattable' => array(
                'suffix' => array(
                    'orgtransmisdossierpcg66_id'
                )
            ),
			'Enumerable' => array(
				'fields' => array(
					'avistechnique',
					'validationproposition',
					'etatop',
					'typersa',
					'recidive',
					'phase',
					'defautinsertion',
                    'haspiecejointe',
                    'instrencours'
				)
			),
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				66 => array(
					'PCG66/propositiondecision.odt',
				)
			)
		);

		public $belongsTo = array(
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
				'foreignKey' => 'dossierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Compofoyerpcg66' => array(
				'className' => 'Compofoyerpcg66',
				'foreignKey' => 'compofoyerpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisionpcg66' => array(
				'className' => 'Decisionpcg66',
				'foreignKey' => 'decisionpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orgdecisiondossierpcg66' => array(
				'className' => 'Orgtransmisdossierpcg66',
				'foreignKey' => 'orgtransmisdossierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Decisiontraitementpcg66' => array(
				'className' => 'Decisiontraitementpcg66',
				'joinTable' => 'decisionsdossierspcgs66_decisionstraitementspcgs66',
				'foreignKey' => 'decisiondossierpcg66_id',
				'associationForeignKey' => 'decisiontraitementpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Decisiondossierpcg66Decisiontraitementpcg66'
			),
			'Decisionpersonnepcg66' => array(
				'className' => 'Decisionpersonnepcg66',
				'joinTable' => 'decisionsdossierspcgs66_decisionspersonnespcgs66',
				'foreignKey' => 'decisiondossierpcg66_id',
				'associationForeignKey' => 'decisionpersonnepcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Decisiondossierpcg66Decisionpersonnepcg66'
			),
			'Typersapcg66' => array(
				'className' => 'Typersapcg66',
				'joinTable' => 'decisionsdossierspcgs66_typesrsapcgs66',
				'foreignKey' => 'decisiondossierpcg66_id',
				'associationForeignKey' => 'typersapcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Decisiondossierpcg66Typersapcg66'
			),
			'Orgtransmisdossierpcg66' => array(
				'className' => 'Orgtransmisdossierpcg66',
				'joinTable' => 'decisionsdossierspcgs66_orgstransmisdossierspcgs66',
				'foreignKey' => 'decisiondossierpcg66_id',
				'associationForeignKey' => 'orgtransmisdossierpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Decdospcg66Orgdospcg66'
			)
		);


        public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Decisiondossierpcg66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
        );

 		public $validate = array(
 			'etatop' => array(
 				'notEmpty' => array(
 					'rule' => 'notEmpty',
 					'message' => 'Champ obligatoire'
 				)
 			)
 		);

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			if( Configure::read( 'nom_form_pdo_cg' ) == 'cg66' ) {
				$validationdecision = Set::extract( $this->data, 'Decisionpropopdo.validationdecision' );

				$etat = 'attinstr';

				if ( !is_numeric( $validationdecision ) )
					$etat = 'attval';
				elseif ( is_numeric( $validationdecision ) && $validationdecision == 1 )
					$etat = 'dossiertraite';

				$this->data['Decisionpropopdo']['etatdossierpdo'] = $etat;
			}

			return $return;
		}


		/**
		* Retourne l'id technique du dossier RSA auquel ce traitement est lié.
		*/

		public function dossierId( $decisiondossierpcg66_id ){
			$result = $this->find(
				'first',
				array(
					'fields' => array( 'Foyer.dossier_id' ),
					'conditions' => array(
						'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
					),
					'contain' => false,
					'joins' => array(
						array(
							'table'      => 'dossierspcgs66',
							'alias'      => 'Dossierpcg66',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossierpcg66.id = Decisiondossierpcg66.dossierpcg66_id' )
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossierpcg66.foyer_id = Foyer.id' )
						)
					)
				)
			);

			if( !empty( $result ) ) {
				return $result['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		* Récupère les données pour le PDf
		*/

		public function getPdfDecision( $id ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
			$qual = $optionModel->qual();
			$typevoie = $optionModel->typevoie();
			$services = $this->Dossierpcg66->Serviceinstructeur->find( 'list' );
// 			$decisionspdos = $this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->Decisionpersonnepcg66->Decisionpdo->find( 'list' );
			$situationspdos = $this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->Situationpdo->find( 'list' );
			$conditions = array( 'Decisiondossierpcg66.id' => $id );

			$joins = array(
				array(
					'table'      => 'dossierspcgs66',
					'alias'      => 'Dossierpcg66',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Dossierpcg66.id = Decisiondossierpcg66.dossierpcg66_id' )
				),
				array(
					'table'      => 'decisionspdos',
					'alias'      => 'Decisionpdo',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Decisionpdo.id = Decisiondossierpcg66.decisionpdo_id' )
				),
				array(
					'table'      => 'originespdos',
					'alias'      => 'Originepdo',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Originepdo.id = Dossierpcg66.originepdo_id' )
				),
				array(
					'table'      => 'polesdossierspcgs66',
					'alias'      => 'Poledossierpcg66',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Poledossierpcg66.id = Dossierpcg66.poledossierpcg66_id' )
				),
				array(
					'table'      => 'personnespcgs66',
					'alias'      => 'Personnepcg66',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Dossierpcg66.id = Personnepcg66.dossierpcg66_id' )
				),
				array(
					'table'      => 'traitementspcgs66',
					'alias'      => 'Traitementpcg66',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Personnepcg66.id = Traitementpcg66.personnepcg66_id' )
				),
				array(
					'table'      => 'descriptionspdos',
					'alias'      => 'Descriptionpdo',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Descriptionpdo.id = Traitementpcg66.descriptionpdo_id' )
				),
				array(
					'table'      => 'personnespcgs66_situationspdos',
					'alias'      => 'Personnepcg66Situationpdo',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Personnepcg66.id = Personnepcg66Situationpdo.personnepcg66_id' )
				),
				array(
					'table'      => 'decisionspersonnespcgs66',
					'alias'      => 'Decisionpersonnepcg66',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Decisionpersonnepcg66.personnepcg66_situationpdo_id = Personnepcg66Situationpdo.id' )
				),
// 				array(
// 					'table'      => 'situationspdos',
// 					'alias'      => 'Situationpdo',
// 					'type'       => 'INNER',
// 					'foreignKey' => false,
// 					'conditions' => array( 'Situationpdo.id = Personnepcg66Situationpdo.situationpdo_id' )
// 				),
				array(
					'table'      => 'personnespcgs66_statutspdos',
					'alias'      => 'Personnepcg66Statutpdo',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Personnepcg66.id = Personnepcg66Statutpdo.personnepcg66_id' )
				),
				array(
					'table'      => 'statutspdos',
					'alias'      => 'Statutpdo',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Statutpdo.id = Personnepcg66Statutpdo.statutpdo_id' )
				),
				array(
					'table'      => 'users',
					'alias'      => 'User',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'User.id = Dossierpcg66.user_id' )
				),
				array(
					'table'      => 'foyers',
					'alias'      => 'Foyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Foyer.id = Dossierpcg66.foyer_id' )
				),
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.foyer_id = Foyer.id' )
				),
				array(
					'table'      => 'prestations',
					'alias'      => 'Prestation',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'Prestation.rolepers IN ( \'DEM\', \'CJT\')'
						)
				),
				array(
					'table'      => 'dossiers',
					'alias'      => 'Dossier',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
				),
				array(
					'table'      => 'adressesfoyers',
					'alias'      => 'Adressefoyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						'Foyer.id = Adressefoyer.foyer_id',
						'Adressefoyer.rgadr' => '01'
					)
				),
				array(
					'table'      => 'adresses',
					'alias'      => 'Adresse',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
				),
				array(
					'table'      => 'pdfs',
					'alias'      => 'Pdf',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						'Pdf.modele' => $this->alias,
						'Pdf.fk_value = Decisiondossierpcg66.id'
					)
				),
			);

			$queryData = array(
				'fields' => array(
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.complideadr',
					'Adresse.compladr',
					'Adresse.lieudist',
					'Adresse.numcomrat',
					'Adresse.numcomptt',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Adresse.pays',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					//
					'Dossierpcg66.orgpayeur',
					'Dossierpcg66.id',
					'Dossierpcg66.etatdossierpcg',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.serviceinstructeur_id',
					'User.nom',
					'User.prenom',
					'User.numtel',
					'Decisiondossierpcg66.id',
					'Decisiondossierpcg66.commentaire',
					'Decisiondossierpcg66.avistechnique',
					'Decisiondossierpcg66.dateavistechnique',
					'Decisiondossierpcg66.commentaireavistechnique',
					'Poledossierpcg66.name',
					'Decisiondossierpcg66.validationproposition',
					'Decisiondossierpcg66.datevalidation',
					'Decisiondossierpcg66.commentairevalidation',
					'Decisiondossierpcg66.commentairetechnicien',
					'Originepdo.libelle',
					'Decisionpdo.libelle'/*,
					'Typersapcg66.name'*/

				),
				'joins' => $joins,
				'conditions' => $conditions,
				'contain' => false
			);

			$data = $this->find( 'first', $queryData );


			$data['Personne']['qual'] = Set::enum( Hash::get( $data, 'Personne.qual'), $qual );
			$data['Adresse']['typevoie'] = Set::enum( $data['Adresse']['typevoie'], $typevoie );
			$data['Dossierpcg66']['serviceinstructeur_id'] = Set::enum( Hash::get( $data, 'Dossierpcg66.serviceinstructeur_id' ), $services );

			$sections = array();
			$personnesfoyerpcg = $this->Dossierpcg66->Personnepcg66->find(
				'all',
				array(
					'conditions' => array(
						'Personnepcg66.dossierpcg66_id' => $data['Dossierpcg66']['id']
					),
					'contain' => array(
						'Personne' => array(
							'Prestation'
						)
					)
				)
			);

			$data['Presence'] = array();
			$data['Presence']['dem'] = $data['Presence']['cjt'] = $data['Presence']['enf'] = 0;

			foreach( $personnesfoyerpcg as $personnefoyerpcg ) {
				$personnefoyerpcg['Prestation'] = $personnefoyerpcg['Personne']['Prestation'];
				unset( $personnefoyerpcg['Personne']['Prestation'] );

				$data['Presence'][strtolower( $personnefoyerpcg['Prestation']['rolepers'] )] = 1;

				$data[$personnefoyerpcg['Prestation']['rolepers']] = $personnefoyerpcg;

				$personnespcgs66_situationspdos = $this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->find(
					'all',
					array(
						'conditions' => array(
							'Personnepcg66Situationpdo.personnepcg66_id' => $personnefoyerpcg['Personnepcg66']['id']
						),
						'joins' => array(
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->join( 'Situationpdo' )
						),
						'fields' => array_merge(
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->fields(),
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->Situationpdo->fields()
						)

					)
				);
				$data[$personnefoyerpcg['Prestation']['rolepers']]['Situationpdo']['libelles'] = implode( "\n", Set::extract( '/Situationpdo/libelle', $personnespcgs66_situationspdos ) );

				$personnespcgs66_statutspdos = $this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->find(
					'all',
					array(
						'conditions' => array(
							'Personnepcg66Statutpdo.personnepcg66_id' => $personnefoyerpcg['Personnepcg66']['id']
						),
						'joins' => array(
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->join( 'Statutpdo' )
						),
						'fields' => array_merge(
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->fields(),
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->Statutpdo->fields()
						)

					)
				);
				$data[$personnefoyerpcg['Prestation']['rolepers']]['Statutpdo']['libelles'] = implode( "\n", Set::extract( '/Statutpdo/libelle', $personnespcgs66_statutspdos ) );


				// Calcul des revenus à afficher dans la décision si on décide de répercuter la fiche de calcul dans la décision
				$traitementsAvecFicheCalcul = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->find(
					'all',
					array(
						'conditions' => array(
							'Traitementpcg66.personnepcg66_id' => $personnefoyerpcg['Personnepcg66']['id'],
							'Traitementpcg66.typetraitement' => 'revenu',
							'Traitementpcg66.reversedo' => '1',
                            'Traitementpcg66.annule' => 'N'
						),
						'contain' => false
					)
				);
				$data[$personnefoyerpcg['Prestation']['rolepers']]['Personnepcg66']['fichecalculreversee'] = '';
				foreach( $traitementsAvecFicheCalcul as $i => $traitementFicheCalcul ){
					$data[$personnefoyerpcg['Prestation']['rolepers']]['Personnepcg66']['fichecalculreversee'] += $traitementFicheCalcul['Traitementpcg66']['revenus'];
				}
			}



			// Recherche des pièces nécessaires pour cette aide, et qui ne sont pas présentes
			$querydata = array(
				'joins' => array(
					$this->Typersapcg66->join( 'Decisiondossierpcg66Typersapcg66' )
				),
				'conditions' => array(
					'Decisiondossierpcg66Typersapcg66.decisiondossierpcg66_id' => $id
				),
				'contain' => false
			);


			$data['Decisiondossierpcg66']['Typersapcg66'] = null;

			$typesrsa = $this->Typersapcg66->find( 'list', $querydata );

			if( !empty( $typesrsa ) ) {
				$data['Decisiondossierpcg66']['Typersapcg66'] .= "\n" .'- '.implode( "\n- ", $typesrsa ).',';
			}

			$options = array();
			$options = Set::merge(
				$this->enums(),
				$this->Dossierpcg66->enums()
			);
//debug($data);
//die();
			return $this->ged(
				$data,
				$this->modeleOdt( $data ),
				false,
				$options
			);
		}

		/**
		* Retourne le chemin relatif du modèle de document à utiliser pour l'enregistrement du PDF.
		*/

		public function modeleOdt( $data ) {
			return "PCG66/propositiondecision.odt";
		}


		/**
		* Fonction permettant de récupérer les décisions qui ont été uniquement
        * transmises à l'OP
		*/

		public function sqDatetransmissionOp( $dossierpcg66Id = 'Dossierpcg66.id' ) {
			return $this->sq(
				array(
					'alias' => 'decisionsdossierspcgs66',
					'fields' => array( 'decisionsdossierspcgs66.id' ),
					'joins' => array(
						array_words_replace(
							$this->join( 'Dossierpcg66' ),
							array(
								'Decisiondossierpcg66' => 'decisionsdossierspcgs66',
								'Dossierpcg66' => 'dossierspcgs66'
							)
						)
					),
					'conditions' => array(
						'decisionsdossierspcgs66.dossierpcg66_id = '.$dossierpcg66Id,
						'decisionsdossierspcgs66.etatop' => 'transmis',
						'decisionsdossierspcgs66.datetransmissionop IS NOT NULL'
					),
					'order' => array( 'decisionsdossierspcgs66.datetransmissionop DESC' ),
					'contain' => false,
					'limit' => 1
				)
			);
			/*return "SELECT decisionsdossierspcgs66.id
						FROM decisionsdossierspcgs66
							INNER JOIN dossierspcgs66 ON ( dossierspcgs66.id = decisionsdossierspcgs66.dossierpcg66_id )
						WHERE
							decisionsdossierspcgs66.dossierpcg66_id = dossierspcgs66.id
							AND decisionsdossierspcgs66.etatop = 'transmis'
						LIMIT 1";*/
		}

		/**
		*
		*/
		public function updateDossierpcg66Dateimpression( $ids ) {
			return $this->Dossierpcg66->updateAllUnBound(
				array(
					'Dossierpcg66.dateimpression' => "'".date( 'Y-m-d' )."'",
					'Dossierpcg66.etatdossierpcg' => '\'atttransmisop\''
				),
				array(
					'Dossierpcg66.id IN ('
						.$this->sq(
							array(
								'alias' => 'decisionsdossierspcgs66',
								'fields' => array( 'decisionsdossierspcgs66.dossierpcg66_id' ),
								'conditions' => array(
									'decisionsdossierspcgs66.id' => $ids
								),
								'contain' => false
							)
						)
					.')'
				)
			);
		}


        /**
		 * Retourne une sous-requête permettant de connaître la dernière décision
		 * pour un dossier PCG donné.
		 *
		 * @param string $field Le champ Dossierpcg66.id sur lequel faire la sous-requête
		 * @return string
		 */
		public function sqDernier( $field ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false, false );
			return "SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.dossierpcg66_id = ".$field."
					ORDER BY {$table}.created DESC
					LIMIT 1";
		}

	}
?>