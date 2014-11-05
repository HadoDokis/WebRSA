<?php
	/**
	 * Code source de la classe Dsp.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// FIXME: possible de faire plus "proprement" qu'avec des define ?
	define( 'ANNOBTNIVDIPMAX_MIN_YEAR', ( date( 'Y' ) - 100 ) );
	define( 'ANNOBTNIVDIPMAX_MAX_YEAR', date( 'Y' ) );
	define( 'ANNOBTNIVDIPMAX_MESSAGE', 'Veuillez entrer une année comprise entre '.ANNOBTNIVDIPMAX_MIN_YEAR.' et '.ANNOBTNIVDIPMAX_MAX_YEAR.' .' );
	/**
	 * La classe Dsp ...
	 *
	 * @package app.Model
	 */
	class Dsp extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
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
			),
			// Début ROME V3
			// 1. Dernière activité
			'Deractfamilleromev3' => array( // TODO: dans l'autre sens
				'className' => 'Familleromev3',
				'foreignKey' => 'deractfamilleromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Deractdomaineromev3' => array( // TODO: dans l'autre sens
				'className' => 'Domaineromev3',
				'foreignKey' => 'deractdomaineromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Deractmetierromev3' => array( // TODO: dans l'autre sens
				'className' => 'Metierromev3',
				'foreignKey' => 'deractmetierromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Deractappellationromev3' => array( // TODO: dans l'autre sens
				'className' => 'Appellationromev3',
				'foreignKey' => 'deractappellationromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			// 2. Dernière activité dominante
			'Deractdomifamilleromev3' => array( // TODO: dans l'autre sens
				'className' => 'Familleromev3',
				'foreignKey' => 'deractdomifamilleromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Deractdomidomaineromev3' => array( // TODO: dans l'autre sens
				'className' => 'Domaineromev3',
				'foreignKey' => 'deractdomidomaineromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Deractdomimetierromev3' => array( // TODO: dans l'autre sens
				'className' => 'Metierromev3',
				'foreignKey' => 'deractdomimetierromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Deractdomiappellationromev3' => array( // TODO: dans l'autre sens
				'className' => 'Appellationromev3',
				'foreignKey' => 'deractdomiappellationromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			// 3. Activité recherchée
			'Actrechfamilleromev3' => array( // TODO: dans l'autre sens
				'className' => 'Familleromev3',
				'foreignKey' => 'actrechfamilleromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actrechdomaineromev3' => array( // TODO: dans l'autre sens
				'className' => 'Domaineromev3',
				'foreignKey' => 'actrechdomaineromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actrechmetierromev3' => array( // TODO: dans l'autre sens
				'className' => 'Metierromev3',
				'foreignKey' => 'actrechmetierromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actrechappellationromev3' => array( // TODO: dans l'autre sens
				'className' => 'Appellationromev3',
				'foreignKey' => 'actrechappellationromev3_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
				// Fin ROME V3
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
			'personne_id' => array( // FIXME: Autovalidate2 ne le fait pas ? -> contratsinsertion/edit/10630
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Conditionnable',
			'Autovalidate2',
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
						'values' => array( '4301', '4302', '4303', '4304' )
					),
					'accoemploi' => array(
						'values' => array( '1801', '1802', '1803' )
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
					'statutoccupation' => array( 'values' => array( 'proprietaire', 'locataire' ) ),
					'suivimedical'
				)
			),
			'Formattable' => array(
				'suffix' => array(
					'libderact66_metier_id',
					'libactdomi66_metier_id',
					'libemploirech66_metier_id',
					// Début ROME V3
					'deractdomaineromev3_id',
					'deractmetierromev3_id',
					'deractappellationromev3_id',
					'deractdomidomaineromev3_id',
					'deractdomimetierromev3_id',
					'deractdomiappellationromev3_id',
					'actrechdomaineromev3_id',
					'actrechmetierromev3_id',
					'actrechappellationromev3_id'
				// Fin ROME V3
				)
			)
		);

		/**
		 * Préfixes des champs liés au catalogue ROME v3.
		 *
		 * @var array
		 */
		public $prefixesRomev3 = array( 'deract', 'deractdomi', 'actrech' );

		/**
		 * Suffixes des champs liés au catalogue ROME v3.
		 *
		 * @var array
		 */
		public $suffixesRomev3 = array( 'famille', 'domaine', 'metier', 'appellation' );

		/*
		 * FIXME: le Hash::remove est plus propre, non ?
		 */

		public function filterOptions( $cg, $options ) {
			if( $cg == 'cg58' ) {
				$valuesDeleted = array( '0502', '0503', '0504' );
				foreach( $valuesDeleted as $valueDeleted ) {
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

		/**
		 * Retourne un array de conditions permettant de s'assurer cibler à la fois
		 * le modèle Dsp et le modèle DspRev.
		 *
		 * @param array $condition
		 * @return array
		 */
		protected function _searchConditionDspDspRev( array $condition ) {
			$return = array(
				'OR' => array(
					$condition,
					array_words_replace( $condition, array( 'Dsp' => 'DspRev' ) )
				)
			);

			return $return;
		}

		/**
		 * Retourne une condition permettant d'obtenir un champ dans un modèle
		 * principal (si la Dsp existe) ou un modèle secondaire (si la DspRev
		 * existe).
		 *
		 * @param string $fieldName Nom du champ
		 * @param string $modelNamePrimary Nom du modèle principal
		 * @param string $modelNameSecondary Nom du modèle secondaire
		 * @param string $modelNameResult Nom du modèle de résultat
		 * @return string
		 */
		protected function _searchCaseFieldDspDspRev( $fieldName, $modelNamePrimary, $modelNameSecondary, $modelNameResult = 'Donnees' ) {
			return "( CASE WHEN \"Dsp\".\"id\" IS NOT NULL THEN \"{$modelNamePrimary}\".\"{$fieldName}\" ELSE \"{$modelNameSecondary}\".\"{$fieldName}\" END ) AS \"{$modelNameResult}__{$fieldName}\"";
		}

		/**
		 *
		 * @see Allocataire::searchQuery()
		 *
		 * @param array $types
		 * @return array
		 */
		public function searchQuery( array $types = array() ) { // TODO: cache
			// TODO: types -> $types['Prestation']
			/*$types += array(
				'Ficheprescription93' => 'LEFT OUTER'
			);*/

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = array(
					'fields' => array(
						'"DspRev"."id"',
						// TODO: $this->_searchCaseFieldDspDspRev()
						'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libderact" ELSE "DspRev"."libderact" END ) AS "Donnees__libderact"',
						'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactderact" ELSE "DspRev"."libsecactderact" END ) AS "Donnees__libsecactderact"',
						'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libderact66_metier_id" ELSE "DspRev"."libderact66_metier_id" END ) AS "Donnees__libderact66_metier_id"',
						'( CASE WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."libsecactderact66_secteur_id" ELSE "DspRev"."libsecactderact66_secteur_id" END ) AS "Donnees__libsecactderact66_secteur_id"',
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
						'Adresse.numvoie',
						'Adresse.libtypevoie',
						'Adresse.nomvoie',
						'Adresse.compladr',
						'Adresse.complideadr',
						'Adresse.codepos',
						'Adresse.nomcom',
						'Adresse.numcom',
						'Situationdossierrsa.etatdosrsa',
						'Personne.foyer_id',
					),
					'joins' => array(
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->join(
							'Prestation',
							array(
								'type' => 'INNER',
								'conditions' => array(
									'Prestation.rolepers' => array( 'DEM', 'CJT' )
								)
							)
						),
						$this->Personne->Foyer->join(
							'Adressefoyer',
							array(
								'type' => 'INNER',
								'conditions' => array(
									'Adressefoyer.id IN( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
								)
							)
						),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
						$this->Personne->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
						array(
							'table' => 'dsps_revs',
							'alias' => 'DspRev',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'DspRev.personne_id = Personne.id',
								'DspRev.id IN ( '.$this->DspRev->sqDerniere( 'Personne.id' ).' )'
							)
						),
						array(
							'table' => 'dsps',
							'alias' => 'Dsp',
							'type' => 'LEFT OUTER',
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
					'conditions' => array(
						'OR' => array(
							'Dsp.id IS NOT NULL',
							'DspRev.id IS NOT NULL'
						)
					)
				);

				// Champs et jointures ROME V3
				if( Configure::read( 'Romev3.enabled' ) ) {
					foreach( $this->prefixesRomev3 as $prefix ) {
						foreach( $this->suffixesRomev3 as $suffix ) {
							$modelAlias = Inflector::camelize( "{$prefix}{$suffix}romev3" );
							$modelAliasRev = "{$modelAlias}Rev";

							// Ajout du champ
							$modelAliasDonnees = $modelAlias;
							$query['fields'][] = $this->_searchCaseFieldDspDspRev( 'name', $modelAlias, $modelAliasRev, $modelAliasDonnees );

							// Ajout des jointures
							$modelAlias = Inflector::camelize( "{$prefix}{$suffix}romev3" );
							$query['joins'][] = $this->join( $modelAlias, array( 'type' => 'LEFT OUTER' ) );
							$query['joins'][] = array_words_replace(
									$this->DspRev->join( $modelAlias, array( 'type' => 'LEFT OUTER' ) ),
									array( $modelAlias => "{$modelAlias}Rev" )
							);
						}
					}
				}

				Configure::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 *
		 * @param array $params
		 * @return array
		 */
		public function search( array $params ) {
			$querydata = $this->searchQuery();

			// TODO: début de searchConditions()
			$querydata['conditions'] = $this->conditionsAdresse( $querydata['conditions'], $params );
			$querydata['conditions'] = $this->conditionsPersonneFoyerDossier( $querydata['conditions'], $params );
			$querydata['conditions'] = $this->conditionsDernierDossierAllocataire( $querydata['conditions'], $params );

			// Secteur d'activité et code métier, texte libre
			foreach( array( 'libsecactderact', 'libderact', 'libsecactdomi', 'libactdomi', 'libsecactrech', 'libemploirech' ) as $fieldName ) {
				if( !empty( $params['Dsp'][$fieldName] ) ) {
					$querydata['conditions'][] = $this->_searchConditionDspDspRev( array( "Dsp.{$fieldName} ILIKE" => $this->wildcard( $params['Dsp'][$fieldName] ) ) );
					/* $querydata['conditions'][]['OR'] = array(
					  array(
					  "Dsp.{$fieldName} ILIKE" => $this->wildcard( $params['Dsp'][$fieldName] )
					  ),
					  array(
					  "DspRev.{$fieldName} ILIKE" => $this->wildcard( $params['Dsp'][$fieldName] )
					  )
					  ); */
				}
			}

			$champs = array( 'nivetu', 'hispro' );
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$champs = array_merge( $champs, array( 'libderact66_metier_id', 'libsecactderact66_secteur_id', 'libsecactdomi66_secteur_id', 'libactdomi66_metier_id', 'libsecactrech66_secteur_id', 'libemploirech66_metier_id' ) );
			}
			foreach( $champs as $fieldName ) {
				if( !empty( $params['Dsp'][$fieldName] ) ) {
					$querydata['conditions'][] = $this->_searchConditionDspDspRev( array( "Dsp.{$fieldName}" => suffix( $params['Dsp'][$fieldName] ) ) );
					/* $querydata['conditions'][]['OR'] = array(
					  array(
					  "Dsp.{$fieldName}" => suffix( $params['Dsp'][$fieldName] )
					  ),
					  array(
					  "DspRev.{$fieldName}" => suffix( $params['Dsp'][$fieldName] )
					  )
					  ); */
				}
			}

			// ----------------------------------------------------------------

			// Référent du parcours
			$querydata = $this->Personne->PersonneReferent->completeQdReferentParcours( $querydata, $params );

			// ----------------------------------------------------------------

			$links = array(
				'Detaildifsoc.difsoc',
				'Detailaccosocindi.nataccosocindi',
				'Detaildifdisp.difdisp',
			);

			foreach( $links as $link ) {
				list( $linkedModelName, $linkedFieldName ) = model_field( $link );
				$fields = array();

				foreach( array( 'Dsp', 'DspRev' ) as $modelName ) {
					if( $modelName == 'DspRev' ) {
						$linkedModelName = "{$linkedModelName}Rev";
					}

					$foreignKey = Inflector::underscore( $modelName ).'_id';

					// Champ virtuel
					$qdVirtualField = array(
						'fields' => array( "{$linkedModelName}.{$linkedFieldName}" ),
						'conditions' => array(
							"{$linkedModelName}.{$foreignKey} = {$modelName}.id"
						),
						'contain' => false
					);

					$fields[$modelName] = $this->Personne->{$modelName}->{$linkedModelName}->vfListe( $qdVirtualField );
				}

				$virtualField = "( CASE WHEN \"Dsp\".\"id\" IS NOT NULL THEN {$fields['Dsp']} ELSE {$fields['DspRev']} END ) AS \"Donnees__{$linkedFieldName}\"";
				$querydata['fields'][] = $virtualField; // INFO: à ajouter dans le query de base (?)

				// Conditions
				$value = Hash::get( $params, $link );
				if( !empty( $value ) ) {
					list( $linkedModelName, $linkedFieldName ) = model_field( $link );

					// Dsp
					$tableName = Inflector::tableize( $linkedModelName );
					$sqDsp = $this->{$linkedModelName}->sq(
							array(
								'alias' => $tableName,
								'fields' => array( "{$tableName}.id" ),
								'conditions' => array(
									"{$tableName}.dsp_id = Dsp.id",
									"{$tableName}.{$linkedFieldName}" => $value,
								),
								'contain' => false,
							)
					);

					// DspRev
					$tableName = Inflector::tableize( $linkedModelName ).'_revs';
					$linkedModelName = "{$linkedModelName}Rev";
					$sqDspRev = $this->DspRev->{$linkedModelName}->sq(
							array(
								'alias' => $tableName,
								'fields' => array( "{$tableName}.id" ),
								'conditions' => array(
									"{$tableName}.dsp_rev_id = DspRev.id",
									"{$tableName}.{$linkedFieldName}" => $value,
								),
								'contain' => false,
							)
					);

					$querydata['conditions'][] = array(
						'OR' => array(
							"EXISTS( {$sqDsp} )",
							"EXISTS( {$sqDspRev} )",
						)
					);
				}
			}

			// Filtres concernant le catalogue ROME V3
			if( Configure::read( 'Romev3.enabled' ) ) {
				$conditionsDspRomeV3 = array();

				foreach( $this->prefixesRomev3 as $prefix ) {
					foreach( $this->suffixesRomev3 as $suffix ) {
						$field = "{$this->alias}.{$prefix}{$suffix}romev3_id";
						$value = suffix( Hash::get( $params, $field ) );
						if( !empty( $value ) ) {
							$conditionsDspRomeV3[$field] = $value;
						}
					}
				}

				if( !empty( $conditionsDspRomeV3 ) ) {
					$querydata['conditions'][] = $this->_searchConditionDspDspRev( $conditionsDspRomeV3 );
				}
			}

			// -----------------------------------------------------------------

			return $querydata;
		}

		/**
		 * Tentative de sauvegarde de certains champs des Dsp, soit par la création
		 * d'une Dsp (si l'allocataire ne possède ni Dsp, ni DspRev), soit par
		 * création d'une DspRev avec reprise des données de la Dsp (s'il ne
		 * possédait aucune DspRev), soit par création d'une nouvelle version des
		 * DspRev avec reprise des données de la dernière DspRev (s'il existait
		 * déjà une DspRev).
		 *
		 * Si on n'envoie que des données null ou chaîne vide, on n'essairea pas
		 * d'enregistrer des données mais la méthode retournera true.
		 *
		 * @param integer $personne_id
		 * @param array $data ATTENTION: le nom de modèle sera toujours Dsp
		 * @return boolean
		 */
		public function updateDerniereDsp( $personne_id, array $data ) {
			$fields = array_keys( (array)Hash::get( $data, 'Dsp' ) );
			$success = true;

			$query = array(
				'fields' => array(
					'Dsp.id',
					'DspRev.id',
				),
				'contain' => false,
				'joins' => array(
					$this->Personne->join(
						'Dsp',
						array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Dsp.id IN ( '.$this->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
						)
							)
					),
					$this->Personne->join(
						'DspRev',
						array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'DspRev.id IN ( '.$this->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
						)
							)
					),
				),
				'conditions' => array(
					'Personne.id' => $personne_id
				)
			);

			foreach( $fields as $field ) {
				$query['fields'][] = "Dsp.{$field}";
				$query['fields'][] = "DspRev.{$field}";
			}

			$oldRecord = $this->Personne->find( 'first', $query );

			$newRecord = array();
			$newModelName = null;

			// Si on a des DspRev
			if( !empty( $oldRecord['DspRev']['id'] ) ) {
				// Si on a des différences dans les données
				$equal = true;
				foreach( $fields as $field ) {
					$equal = ( $oldRecord['DspRev'][$field] == $data['Dsp'][$field] ) && $equal;
				}
				if( !$equal ) {
					$oldModelName = 'DspRev';
					$newModelName = 'DspRev';
					$linkedSuffix = '';
				}
			}
			// Pas de DspRev mais une Dsp
			else if( !empty( $oldRecord['Dsp']['id'] ) ) {
				// Si on a des différences dans les données
				$equal = true;
				foreach( $fields as $field ) {
					$equal = ( $oldRecord['Dsp'][$field] == $data['Dsp'][$field] ) && $equal;
				}
				if( !$equal ) {
					$oldModelName = 'Dsp';
					$newModelName = 'DspRev';
					$linkedSuffix = 'Rev';
				}
			}
			// S'il faut créer un enregistrement de Dsp parce que l'on a des données non vides
			else {
				$allNull = true;
				foreach( $fields as $field ) {
					$allNull = empty( $data['Dsp'][$field] ) && $allNull;
				}
				if( !$allNull ) {
					$oldModelName = 'Dsp';
					$newModelName = 'Dsp';
					$linkedSuffix = '';
				}
			}

			if( $newModelName !== null ) {
				// Début
				$removePaths = array(
					"{$oldModelName}.id",
					"{$oldModelName}.created",
					"{$oldModelName}.modified",
				);
				$replacements = array( 'Dsp' => 'DspRev' );

				$query = array(
					'contain' => array(),
					'conditions' => array(
						"{$oldModelName}.id" => $oldRecord[$oldModelName]['id']
					)
				);

				// Modèles liés aux modèle Dsp/DspRev
				foreach( $this->Personne->{$oldModelName}->hasMany as $alias => $params ) {
					if( strstr( $alias, 'Detail' ) !== false ) {
						$query['contain'][] = $alias;

						$removePaths[] = "{$alias}.{n}.id";
						$removePaths[] = "{$alias}.{n}.{$params['foreignKey']}";

						$replacements[$alias] = "{$alias}{$linkedSuffix}";
					}
				}
				$newRecord = $this->Personne->{$oldModelName}->find( 'first', $query );

				foreach( $removePaths as $removePath ) {
					$newRecord = Hash::remove( $newRecord, $removePath );
				}

				$newRecord = array_words_replace( $newRecord, $replacements );
				$newRecord[$newModelName]['personne_id'] = $personne_id;
				$newRecord[$newModelName]['dsp_id'] = Hash::get( $oldRecord, 'Dsp.id' );

				foreach( $fields as $field ) {
					$newRecord[$newModelName][$field] = Hash::get( $data, "Dsp.{$field}" );
				}

				// Pour les DspRev, le champ haspiecejointe est obligatoire
				if( $newModelName === 'DspRev' && !isset( $newRecord[$newModelName]['haspiecejointe'] ) ) {
					$newRecord[$newModelName]['haspiecejointe'] = '0';
				}

				$success = $this->saveResultAsBool(
								$this->Personne->{$newModelName}->saveAll(
						$newRecord,
						array( 'atomic' => false, 'deep' => true )
								)
						) && $success;
			}

			return $success;
		}

		/**
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$params = $params + array( 'find' => true, 'autre' => true );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $params ) );
			$return = Cache::read( $cacheKey );

			if( $return === false ) {
				$return = array();

				if( $params['autre'] ) {
					$return = $this->enums();
				}

				if( $params['find'] ) {
					$Catalogueromev3 = ClassRegistry::init( 'Catalogueromev3' );

					$selects = $Catalogueromev3->dependantSelects();

					foreach( $this->prefixesRomev3 as $prefix ) {
						foreach( array( 'familleromev3_id', 'domaineromev3_id', 'metierromev3_id', 'appellationromev3_id' ) as $field ) {
							$return[$this->alias]["{$prefix}{$field}"] = $selects['Catalogueromev3'][$field];
						}
					}
				}

				Cache::write( $cacheKey, $return );
				ModelCache::write( $cacheKey, $Catalogueromev3->modelesParametrages );
			}

			return $return;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$success = true;

			$results = $this->searchQuery();
			$success = $success && !empty( $results );

			$results = $this->getRomev3Contains();
			$success = $success && !empty( $results );

			$results = $this->options();
			$success = $success && !empty( $results );

			return $success;
		}

		/**
		 * Retourne une partie de l'array des contain avec les name (+ code) des
		 * réponses ROME V3.
		 *
		 * @return array
		 */
		public function getRomev3Contains() {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$return = Cache::read( $cacheKey );

			if( $return === false ) {
				$return = array();

				$parents = array(
					'famille' => array(),
					'domaine' => array( 'famille' ),
					'metier' => array( 'famille', 'domaine' ),
					'appellation' => array( 'famille', 'domaine', 'metier' )
				);

				foreach( $this->prefixesRomev3 as $prefix ) {
					foreach( $this->suffixesRomev3 as $suffix ) {
						$alias = Inflector::classify( "{$prefix}{$suffix}romev3" );

						$fields = array();
						if( $suffix !== 'appellation' ) {
							$codes = array();

							if( $suffix !== 'famille' ) {
								foreach( $parents[$suffix] as $parent ) {
									$parentAlias = Inflector::classify( $prefix )."{$parent}romev3";
									$codes[] = "\"{$parentAlias}\".\"code\"";
								}
							}
							$codes[] = "\"{$alias}\".\"code\"";
							$codes = implode( " || ", $codes );

							$fields[] = "( {$codes} || ' - ' || \"{$alias}\".\"name\" ) AS \"{$alias}__name\""; // TODO: fullname en champ virtuel
						}
						else {
							$fields[] = "{$alias}.name";
						}


						$return[$alias] = array( 'fields' => $fields );
					}
				}

				Cache::write( $cacheKey, $return );
			}

			return $return;
		}
	}
?>