<?php
	// FIXME: possible de faire plus "proprement" qu'avec des define ?
	define( 'ANNOBTNIVDIPMAX_MIN_YEAR', ( date( 'Y' ) - 100 ) );
	define( 'ANNOBTNIVDIPMAX_MAX_YEAR', date( 'Y' ) );
	define( 'ANNOBTNIVDIPMAX_MESSAGE', 'Veuillez entrer une année comprise entre '.ANNOBTNIVDIPMAX_MIN_YEAR.' et '.ANNOBTNIVDIPMAX_MAX_YEAR.' .' );

	class Dsp extends AppModel
	{
		public $name = 'Dsp';

		protected $_modules = array( 'caf' );

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Libderact66Metier' => array(
				'className' => 'Coderomemetierdsp66',
				'foreignKey' => 'libderact66_metier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Libsecactderact66Secteur' => array(
				'className' => 'Coderomesecteurdsp66',
				'foreignKey' => 'libsecactderact66_secteur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Libactdomi66Metier' => array(
				'className' => 'Coderomemetierdsp66',
				'foreignKey' => 'libactdomi66_metier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Libsecactdomi66Secteur' => array(
				'className' => 'Coderomesecteurdsp66',
				'foreignKey' => 'libsecactdomi66_secteur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Libemploirech66Metier' => array(
				'className' => 'Coderomemetierdsp66',
				'foreignKey' => 'libemploirech66_metier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Libsecactrech66Secteur' => array(
				'className' => 'Coderomesecteurdsp66',
				'foreignKey' => 'libsecactrech66_secteur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Detaildifsoc' => array(
				'className' => 'Detaildifsoc',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailaccosocfam' => array(
				'className' => 'Detailaccosocfam',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailaccosocindi' => array(
				'className' => 'Detailaccosocindi',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detaildifdisp' => array(
				'className' => 'Detaildifdisp',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailnatmob' => array(
				'className' => 'Detailnatmob',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detaildiflog' => array(
				'className' => 'Detaildiflog',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailmoytrans' => array(
				'className' => 'Detailmoytrans',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detaildifsocpro' => array(
				'className' => 'Detaildifsocpro',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailprojpro' => array(
				'className' => 'Detailprojpro',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailfreinform' => array(
				'className' => 'Detailfreinform',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Detailconfort' => array(
				'className' => 'Detailconfort',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'DspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

		public $validate = array(
			'annobtnivdipmax' => array(
				'rule' => array( 'inclusiveRange', ANNOBTNIVDIPMAX_MIN_YEAR, ANNOBTNIVDIPMAX_MAX_YEAR ),
				'message' => ANNOBTNIVDIPMAX_MESSAGE,
				'allowEmpty' => true
			),
			'personne_id' => array( // FIXME: Autovalidate ne le fait pas ? -> contratsinsertion/edit/10630
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $actsAs = array(
			'Conditionnable',
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'sitpersdemrsa' => array(
						'values' => array( '0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109' )
					),
					'nivetu' => array(
						array( '1201', '1202', '1203', '1204', '1205', '1206', '1207' )
					),
					'nivdipmaxobt' => array(
						'values' => array( '2601', '2602', '2603', '2604', '2605', '2606' )
					),
					'hispro' => array(
						'values' => array( '1901', '1902', '1903', '1904' )
					),
					'cessderact' => array(
						'values' => array( '2701', '2702' )
					),
					'duractdomi' => array(
						'values' => array( '2104', '2105', '2106', '2107' )
					),
					'inscdememploi' => array(
						'values' => array( '4301', '4302', '4303', '4304'  )
					),
					'accoemploi' => array(
						'values' => array( '1801', '1802', '1803'  )
					),
					'natlog' => array(
						'values' => array( '0901', '0902', '0903', '0904', '0905', '0906', '0907', '0908', '0909', '0910', '0911', '0912', '0913' )
					),
					'demarlog' => array(
						'values' => array( '1101', '1102', '1103' )
					),
					'topisogroouenf' => array( 'type' => 'booleannumber', 'domain' => 'default', ),
					'topdrorsarmiant' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcouvsoc' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topqualipro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcompeextrapro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topengdemarechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topdomideract' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topisogrorechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topprojpro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcreareprientre' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topmoyloco' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'toppermicondub' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topautrpermicondu' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'accosocfam' => array( 'type' => 'nov', 'domain' => 'default' ),
					'accosocindi' => array( 'type' => 'nov', 'domain' => 'default' ),
					'soutdemarsoc' => array( 'type' => 'nov', 'domain' => 'default' ),
					'concoformqualiemploi' => array( 'type' => 'nos', 'domain' => 'default' ),
					'drorsarmianta2' => array( 'type' => 'nos', 'domain' => 'default' ),
					'statutoccupation' => array( 'values' => array('proprietaire', 'locataire') ),
					'suivimedical'
				)
			),
			'Revision' => array('auto' => false),
			'Formattable' => array(
				'suffix' => array( 'libderact66_metier_id', 'libactdomi66_metier_id', 'libemploirech66_metier_id' )
			)
		);

		/*
		* FIXME: le Set::remove est plus propre, non ?
		*/

		public function filterOptions( $cg, $options ) {
			if( $cg == 'cg58' ) {
				$valuesDeleted = array( '0502', '0503', '0504' );
				foreach( $valuesDeleted as $valueDeleted ){
					unset( $options['Detaildifdisp']['difdisp'][$valueDeleted] );
				}
				return $options;
			}

			// Detaildifdisp.difdisp
			$values = array( '0507', '0508', '0509', '0510', '0511', '0512', '0513', '0514' );
			foreach( $values as $value ) {
				unset( $options['Detaildifdisp']['difdisp'][$value] );
			}

			return $options;
		}

		/**
		* Permet de récupérer les dernières DSP d'une personne, en attendant l'index unique sur personne_id
		*/

		public function sqDerniereDsp( $personneIdFied = 'Personne.id' ) {
			return $this->sq(
				array(
					'alias' => 'dsps',
					'fields' => array( 'dsps.id' ),
					'conditions' => array(
						"dsps.personne_id = {$personneIdFied}"
					),
					'contain' => false,
					'order' => array( 'dsps.id DESC' ),
					'limit' => 1
				)
			);
		}


		public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
			$conditions[] = array(
				'OR' => array(
					'Dsp.id IS NOT NULL',
					'DspRev.id IS NOT NULL'
				)
			);

			$conditions = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );

			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );

			// Secteur d'activité et code métier, texte libre
			foreach( array( 'libsecactderact', 'libderact', 'libsecactdomi', 'libactdomi', 'libsecactrech', 'libemploirech' ) as $fieldName ) {
				if( !empty( $params['Dsp'][$fieldName] ) ) {
					$conditions[]['OR'] = array(
						array(
							"Dsp.{$fieldName} ILIKE" => $this->wildcard( $params['Dsp'][$fieldName] )
						),
						array(
							"DspRev.{$fieldName} ILIKE" => $this->wildcard( $params['Dsp'][$fieldName] )
						)
					);
				}
			}

			$champs = array( 'nivetu', 'hispro' );
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$champs = array_merge( $champs, array( 'libderact66_metier_id', 'libsecactderact66_secteur_id', 'libsecactdomi66_secteur_id', 'libactdomi66_metier_id', 'libsecactrech66_secteur_id', 'libemploirech66_metier_id' ) );
			}

			foreach( $champs as $fieldName ) {
				if( !empty( $params['Dsp'][$fieldName] ) ) {
					$conditions[]['OR'] = array(
						array(
							"Dsp.{$fieldName}" => suffix( $params['Dsp'][$fieldName] )
						),
						array(
							"DspRev.{$fieldName}" => suffix( $params['Dsp'][$fieldName] )
						)
					);
				}
			}


			$query = array(
				'fields' => array(
					'"DspRev"."id"',
					//
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libderact" ELSE "DspRev"."libderact" END ) AS "Donnees__libderact"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactderact" ELSE "DspRev"."libsecactderact" END ) AS "Donnees__libsecactderact"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libderact66_metier_id" ELSE "DspRev"."libderact66_metier_id" END ) AS "Donnees__libderact66_metier_id"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactderact66_secteur_id" ELSE "DspRev"."libsecactderact66_secteur_id"
					END ) AS "Donnees__libsecactderact66_secteur_id"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactdomi" ELSE "DspRev"."libsecactdomi" END ) AS "Donnees__libsecactdomi"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libactdomi" ELSE "DspRev"."libactdomi" END ) AS "Donnees__libactdomi"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libactdomi66_metier_id" ELSE "DspRev"."libactdomi66_metier_id" END ) AS "Donnees__libactdomi66_metier_id"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactdomi66_secteur_id" ELSE "DspRev"."libsecactdomi66_secteur_id" END ) AS "Donnees__libsecactdomi66_secteur_id"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."nivetu" ELSE "DspRev"."nivetu" END ) AS "Donnees__nivetu"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."hispro" ELSE "DspRev"."hispro" END ) AS "Donnees__hispro"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactrech" ELSE "DspRev"."libsecactrech" END ) AS "Donnees__libsecactrech"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libemploirech" ELSE "DspRev"."libemploirech" END ) AS "Donnees__libemploirech"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactrech66_secteur_id" ELSE "DspRev"."libsecactrech66_secteur_id" END ) AS "Donnees__libsecactrech66_secteur_id"',
					'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libemploirech66_metier_id" ELSE "DspRev"."libemploirech66_metier_id" END ) AS "Donnees__libemploirech66_metier_id"',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nir',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Situationdossierrsa.etatdosrsa',
					'Personne.foyer_id',
				),
				'joins' => array(
// 					array(
// 						'table'      => 'personnes',
// 						'alias'      => 'Personne',
// 						'type'       => 'INNER',
// 						'foreignKey' => false,
// 						'conditions' => array( 'Dsp.personne_id = Personne.id' )
// 					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Personne.foyer_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
							'Prestation.natprest = \'RSA\''
						)
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' ) //FIXME sqDerniereRgadr01( 'Foyer.id' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					//FIXME
					array(
						'table'      => 'dsps_revs',
						'alias'      => 'DspRev',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'DspRev.personne_id = Personne.id',
							'DspRev.id IN ( '.$this->Personne->DspRev->sq(
								array(
									'alias' => 'tmp_dsps_revs',
									'fields' => array(
										'tmp_dsps_revs.id'
									),
									'conditions' => array(
										'tmp_dsps_revs.personne_id = Personne.id'
									),
									'order' => 'tmp_dsps_revs.modified DESC',
									'limit' => 1
								)
							).' )'
						)
					),
					array(
						'table'      => 'dsps',
						'alias'      => 'Dsp',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Dsp.personne_id = Personne.id',
							'Dsp.personne_id NOT IN ( '.$this->Personne->DspRev->sq(
								array(
									'alias' => 'tmp_dsps_revs2',
									'fields' => array(
										'tmp_dsps_revs2.personne_id'
									),
									'conditions' => array(
										'tmp_dsps_revs2.personne_id = Dsp.personne_id'
									)
								)
							).' )'
						)
					)
				),
				'recursive' => -1,
// 				'limit' => 10,
				'conditions' => $conditions
			);


			return $query;
		}
	}
?>