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
			// Début ROME V2
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
			// Fin ROME V2
			// Début ROME V3
			'Deractromev3' => array(
				'className' => 'Entreeromev3',
				'foreignKey' => 'deractromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Deractdomiromev3' => array(
				'className' => 'Entreeromev3',
				'foreignKey' => 'deractdomiromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Actrechromev3' => array(
				'className' => 'Entreeromev3',
				'foreignKey' => 'actrechromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
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
			'Populationb3pdv93' => array(
				'className' => 'Populationb3pdv93',
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
				)
			)
		);

		/**
		 * Préfixes des champs liés au catalogue ROME v3.
		 *
		 * @deprecated
		 *
		 * @var array
		 */
		public $prefixesRomev3 = array( 'deract', 'deractdomi', 'actrech' );

		/**
		 * Suffixes des champs liés au catalogue ROME v3.
		 *
		 * @deprecated
		 *
		 * @var array
		 */
		public $suffixesRomev3 = array( 'famille', 'domaine', 'metier', 'appellation' );

		/**
		 * Liste des alias vers Entreeromev3
		 *
		 * @var array
		 */
		public $romev3LinkedModels = array( 'Deractromev3', 'Deractdomiromev3', 'Actrechromev3' );

		/**
		 * Liste des champs intéressants de Entreeromev3
		 *
		 * @var array
		 */
		public $romev3Fields = array( 'familleromev3_id', 'domaineromev3_id', 'metierromev3_id', 'appellationromev3_id' );

		/**
		 * Liste des modèles liés contenant des valeurs de cases à cocher ainsi
		 * qu'un éventuel champ "autre", pour tous les CG, ainsi que des modèles
		 * liés à un CG en particulier.
		 *
		 * @var array
		 */
		public $checkboxes = array(
			// Tous CG
			'all' => array(
				// Rencontrez vous des difficultés sociales ?
			   'Detaildifsoc' => array(
				   'name' => 'difsoc',
				   'text' => 'libautrdifsoc'
			   ),
			   // Dans quel domaine accompagnement familial ?
			   'Detailaccosocfam' => array(
				   'name' => 'nataccosocfam',
				   'text' => 'libautraccosocfam'
			   ),
			   // Dans quel domaine accompagnement individuel ?
			   'Detailaccosocindi' => array(
				   'name' => 'nataccosocindi',
				   'text' => 'libautraccosocindi'
			   ),
			   // Parmi les propositions suivantes certaines constituent elles des obstacles à une recherche d'emploi ?
			   'Detaildifdisp' => array(
				   'name' => 'difdisp',
				   'text' => false
			   ),
			   // Etes-vous mobile ?
			   'Detailnatmob' => array(
				   'name' => 'natmob',
				   'text' => false
			   ),
			   // Difficultés logement ?
			   'Detaildiflog' => array(
				   'name' => 'diflog',
				   'text' => 'libautrdiflog'
			   )
			),
			// CG 58
			58 => array(
				// Quel moyen de transport ?
				'Detailmoytrans' => array(
					'name' => 'moytrans',
					'text' => 'libautrmoytrans'
				),
				// Quelles sont les difficultés sociales décelées par le professionnel ?
				'Detaildifsocpro' => array(
					'name' => 'difsocpro',
					'text' => 'libautrdifsocpro'
				),
				// Quelles sont les difficultés sociales décelées par le professionnel ?
				'Detailprojpro' => array(
					'name' => 'projpro',
					'text' => 'libautrprojpro'
				),
				// Frein(s) à la formation :
				'Detailfreinform' => array(
					'name' => 'freinform',
					'text' => false
				),
				// Confort :
				'Detailconfort' => array(
					'name' => 'confort',
					'text' => false
				)
			)
		);

		/**
		 * Contient la liste des valeurs "Aucun(e)", pour l'ensemble des CG et
		 * pour chaque CG en particulier des modèles liés par cases à cocher.
		 * Si cette valeur n'existe pas, alors est vaudra null.
		 *
		 * @todo Grouper avec $checkboxes.
		 *
		 * @var array
		 */
		public $checkboxesValuesNone = array(
			// Tous CG
			'all' => array(
				'Detaildifsoc' => '0401',
				'Detailaccosocfam' => null,
				'Detailaccosocindi' => null,
				'Detaildifdisp' => '0501',
				'Detailnatmob' => '2504',
				'Detaildiflog' => '1001'
			),
			// CG 58
			58 => array(
				'Detailmoytrans' => null,
				'Detaildifsocpro' => null,
				'Detailprojpro' => null,
				'Detailfreinform' => null,
				'Detailconfort' => null
			)
		);

		/**
		 * Liste des modèles liés contenant des cases à cocher et utilisés dans
		 * le formulaire de recherche.
		 *
		 * @var array
		 */
		public $searchCheckboxes = array(
			'Detaildifsoc',
			'Detailaccosocindi',
			'Detaildifdisp',
		);

		/**
		 * Liste des alias codes ROME V2 (CG 66).
		 *
		 * @var array
		 */
		public $modelesRomeV2 = array(
			'Libsecactderact66Secteur',
			'Libderact66Metier',
			'Libsecactdomi66Secteur',
			'Libactdomi66Metier',
			'Libsecactrech66Secteur',
			'Libemploirech66Metier'
		);

		/**
		 * Filtre les options disponibles en fonction du CG.
		 * Utilisé pour limiter certaines valeurs au CG 58 et aux autres CG.
		 *
		 * @param array $options
		 * @return array
		 */
		public function getFilteredOptions( $options ) {
			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$values = array( '0502', '0503', '0504' );
			}
			else {
				$values = array( '0507', '0508', '0509', '0510', '0511', '0512', '0513', '0514' );
			}

			foreach( $values as $value ) {
				unset( $options['Detaildifdisp']['difdisp'][$value] );
			}

			return $options;
		}

		/**
		 * Permet de récupérer les dernières DSP d'une personne, en attendant
		 * l'index unique sur personne_id.
		 *
		 * @param string $personneIdFied
		 * @return string
		 */
		public function sqDerniereDsp( $personneIdFied = 'Personne.id' ) {
			$query = array(
				'alias' => 'dsps',
				'fields' => array( 'dsps.id' ),
				'conditions' => array(
					"dsps.personne_id = {$personneIdFied}"
				),
				'contain' => false,
				'order' => array( 'dsps.id DESC' ),
				'limit' => 1
			);

			return $this->sq( $query );
		}

		/**
		 * Retourne un array de conditions permettant de s'assurer cibler à la fois
		 * le modèle Dsp et le modèle DspRev.
		 *
		 * @deprecated
		 *
		 * @param array $condition
		 * @param array $aliases
		 * @return array
		 */
		protected function _searchConditionDspDspRev( array $condition, array $aliases = array( 'Dsp' => 'DspRev' ) ) {
			$return = array(
				'OR' => array(
					$condition,
					array_words_replace( $condition, $aliases )
				)
			);

			return $return;
		}

		/**
		 * Retourne une condition permettant d'obtenir un champ dans un modèle
		 * principal (si la Dsp existe) ou un modèle secondaire (si la DspRev
		 * existe).
		 *
		 * @deprecated
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
		 * Retourne la liste des valeurs "Aucun(e)" pour chacun des modèles liés
		 * par cases à cocher, suivant le CG connecté.
		 *
		 * @return array
		 */
		public function getCheckboxesValuesNone() {
			return Hash::merge(
				(array)Hash::get( $this->checkboxesValuesNone, 'all' ),
				(array)Hash::get( $this->checkboxesValuesNone, Configure::read( 'Cg.departement' ) )
			);
		}

		/**
		 * Retourne les modèles liés par cases à cocher ainsi que pour chacun
		 * d'eux, les champs intitulé et libellé autre, suivant le CG connecté.
		 *
		 * @return array
		 */
		public function getCheckboxes() {
			return Hash::merge(
				(array)Hash::get( $this->checkboxes, 'all' ),
				(array)Hash::get( $this->checkboxes, Configure::read( 'Cg.departement' ) )
			);
		}

		/**
		 * Retourne les champs virtuels des modèles liés par cases à cocher.
		 *
		 * @see getCheckboxes()
		 * @return array
		 */
		public function getCheckboxesVirtualFields() {
			$checkboxes = $this->getCheckboxes();
			$return = array();

			foreach( $checkboxes as $modelName => $params ) {
				$return[] = "{$modelName}.{$params['name']}";
			}

			return $return;
		}

		/**
		 *
		 * @see Allocataire::searchQuery()
		 *
		 * @deprecated
		 *
		 * @return array
		 */
		public function searchQuery() {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				// 1. Récupération des différents champs des modèles; si le modèle est Dsp, il sera aliasé par Donnees.
				$Models = array(
					$this->Personne,
					$this->Personne->Calculdroitrsa,
					$this->Personne->Foyer,
					$this->Personne->Foyer->Dossier,
					$this->Personne->Foyer->Dossier->Situationdossierrsa,
					$this->Personne->Memo,
					$this->Personne->Prestation,
					$this->Personne->Foyer->Adressefoyer,
					$this->Personne->Foyer->Adressefoyer->Adresse,
					$this->Personne->Foyer->Modecontact,
					$this
				);
				$fields = array( 'Dsp.id', 'DspRev.id', 'Personne.id', 'Dossier.numdemrsa' );
				foreach( $Models as $Model ) {
					foreach( array_keys( $Model->schema() ) as $fieldName ) {
						$unwanted = ( $fieldName === 'id' || strpos( $fieldName, '_id' ) === strlen( $fieldName ) - 3 );
						if( !$unwanted ) { // if Dsp/DspRev
							if( $Model->alias !== 'Dsp' ) {
								$fields["{$Model->alias}.{$fieldName}"] = "{$Model->alias}.{$fieldName}";
							}
							else {
								$fields["Donnees.{$fieldName}"] = $this->_searchCaseFieldDspDspRev( $fieldName, $Model->alias, "{$Model->alias}Rev" );
							}
						}
					}
					foreach( array_keys( (array)$Model->virtualFields ) as $fieldName ) {
						if( $Model->alias !== 'Dsp' ) {
							$fields["{$Model->alias}.{$fieldName}"] = "{$Model->alias}.{$fieldName}";
						}
						else {
							$fields["Donnees.{$fieldName}"] = $this->_searchCaseFieldDspDspRev( $fieldName, $Model->alias, "{$Model->alias}Rev" );
						}
					}
				}

				// 2. Ajout d'autres champs virtuels
				// 2.1. Nombre d'enfants du foyer
				$fields['Foyer.nbenfants'] = '( '.$this->Personne->Foyer->vfNbEnfants().' ) AS "Foyer__nbenfants"';

				// 2.2 Nombre de fichiers liés
				$Fichiermodule = ClassRegistry::init( 'Fichiermodule' );
				$sqlDsp = $Fichiermodule->sqNbFichiersLies( $this );
				$sqlDspRev = str_replace( '"Dsp"', '"DspRev"', $sqlDsp );
				$fields['Donnees.nb_fichiers_lies'] = "( CASE WHEN \"Dsp\".\"id\" IS NOT NULL THEN ( {$sqlDsp} ) ELSE ( {$sqlDspRev} ) END ) AS \"Donnees__nb_fichiers_lies\"";

				// 2.3 Nature de la prestation
				$qdVirtualField = array(
					'fields' => array( "Detailcalculdroitrsa.natpf" ),
					'conditions' => array(
						'Detaildroitrsa.dossier_id = Dossier.id'
					),
					'contain' => false,
					'joins' => array(
						$this->Personne->Foyer->Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'INNER' ) )
					)
				);
				$virtualField = '( '.$this->Personne->Foyer->Dossier->vfListe( $qdVirtualField ).' ) AS "Detaildroitrsa__natpf"';
				$fields['Detaildroitrsa.natpf'] = $virtualField;

				// 3. Query
				$query = array(
					'fields' => $fields,
					'joins' => array(
						$this->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->join(
							'Memo',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Memo.id IN ( '.$this->Personne->Memo->sqDernier().' )'
								)
							)
						),
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
						$this->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
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
						),
						$this->Personne->Foyer->join(
							'Modecontact',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Modecontact.id IN ( '.$this->Personne->Foyer->Modecontact->sqDerniere( 'Foyer.id', array( 'Modecontact.autorutitel' => 'A' ) ).' )',
								)
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

				// 4. Champs et jointures ROME V2 (CG 66)
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					foreach( $this->modelesRomeV2 as $alias ) {
						foreach( array_keys( $this->{$alias}->schema() ) as $fieldName ) {
							$unwanted = ( $fieldName === 'id' || strpos( $fieldName, '_id' ) === strlen( $fieldName ) - 3 );
							if( !$unwanted ) {
								$query['fields']["{$alias}.{$fieldName}"] = $this->_searchCaseFieldDspDspRev( $fieldName, $alias, "{$alias}Rev", $alias );
							}
						}

						$query['joins'][] = $this->join( $alias, array( 'type' => 'LEFT OUTER' ) );
						$query['joins'][] = array_words_replace(
							$this->DspRev->join( $alias, array( 'type' => 'LEFT OUTER' ) ),
							array( $alias => "{$alias}Rev" )
						);
					}
				}

				// 5. Champs et jointures ROME V3
				if( Configure::read( 'Romev3.enabled' ) ) {
					$aliases = array_keys( ClassRegistry::init( 'Entreeromev3' )->belongsTo );
					foreach( $this->romev3LinkedModels as $modelAlias ) {
						$modelAliasRev = "{$modelAlias}Rev";
						$modelAliasDonnees = $modelAlias;

						// Ajout des jointures
						$query['joins'][] = $this->join( $modelAlias, array( 'type' => 'LEFT OUTER' ) );
						$query['joins'][] = $this->DspRev->join( $modelAliasRev, array( 'type' => 'LEFT OUTER' ) );

						foreach( $aliases as $alias ) {
							$modelAliasDonnees = "{$modelAlias}__".Inflector::underscore( $alias );
							$query['fields'][str_replace( '__', '.', $modelAliasDonnees )] = $this->_searchCaseFieldDspDspRev( 'name', "{$modelAlias}__{$alias}", "{$modelAliasRev}__{$alias}", $modelAliasDonnees );

							$query['joins'][] = array_words_replace(
								$this->{$modelAlias}->join( $alias, array( 'type' => 'LEFT OUTER' ) ),
								array( $alias => "{$modelAlias}__{$alias}" )
							);

							$query['joins'][] = array_words_replace(
								$this->DspRev->{$modelAliasRev}->join( $alias, array( 'type' => 'LEFT OUTER' ) ),
								array( $alias => "{$modelAliasRev}__{$alias}" )
							);
						}
					}
				}

				// 6. Référent du parcours
				$query = $this->Personne->PersonneReferent->completeSearchQueryReferentParcours( $query );

				// 7. Champs virtuels modèles liés cases à cocher
				foreach( $this->getCheckboxes() as $linkedModelName => $params ) {
					$linkedFieldName = $params['name'];

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
					$query['fields']["Donnees.{$linkedFieldName}"] = $virtualField; // INFO: à ajouter dans le query de base (?)
				}

				// 8. Si on utilise les cantons, on ajoute une jointure
				if( Configure::read( 'CG.cantons' ) ) {
					$Canton = ClassRegistry::init( 'Canton' );
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $Canton->joinAdresse();
				}

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @deprecated
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$query['conditions'] = $this->conditionsAdresse( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsPersonneFoyerDossier( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsDernierDossierAllocataire( $query['conditions'], $search );

			// Secteur d'activité et code métier, texte libre
			foreach( array( 'libsecactderact', 'libderact', 'libsecactdomi', 'libactdomi', 'libsecactrech', 'libemploirech' ) as $fieldName ) {
				if( !empty( $search['Dsp'][$fieldName] ) ) {
					$query['conditions'][] = $this->_searchConditionDspDspRev( array( "Dsp.{$fieldName} ILIKE" => $this->wildcard( $search['Dsp'][$fieldName] ) ) );
				}
			}

			$champs = array( 'nivetu', 'hispro' );
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$champs = array_merge( $champs, array( 'libsecactderact66_secteur_id', 'libderact66_metier_id', 'libsecactdomi66_secteur_id', 'libactdomi66_metier_id', 'libsecactrech66_secteur_id', 'libemploirech66_metier_id' ) );
			}
			foreach( $champs as $fieldName ) {
				if( !empty( $search['Dsp'][$fieldName] ) ) {
					$query['conditions'][] = $this->_searchConditionDspDspRev( array( "Dsp.{$fieldName}" => suffix( $search['Dsp'][$fieldName] ) ) );
				}
			}

			// Référent du parcours
			$query = $this->Personne->PersonneReferent->completeSearchConditionsReferentParcours( $query, $search );

			// Conditions modèles liés cases à cocher
			foreach( $this->searchCheckboxes as $linkedModelName ) {
				$linkedFieldName = $this->checkboxes['all'][$linkedModelName]['name'];
				$value = Hash::get( $search, "{$linkedModelName}.{$linkedFieldName}" );

				if( !empty( $value ) ) {
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

					$query['conditions'][] = array(
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
				$aliases = array();

				foreach( $this->romev3LinkedModels as $alias ) {
					$aliases[$alias] = "{$alias}Rev";
					foreach( $this->romev3Fields as $fieldName ) {
						$field = "{$alias}.{$fieldName}";
						$value = suffix( Hash::get( $search, $field ) );
						if( !empty( $value ) ) {
							$conditionsDspRomeV3[$field] = $value;
						}
					}
				}

				if( !empty( $conditionsDspRomeV3 ) ) {
					$query['conditions'][] = $this->_searchConditionDspDspRev( $conditionsDspRomeV3, $aliases );
				}
			}

			return $query;
		}

		/**
		 * Moteur de recherche par Dsp, export des champs disponibles lorsque l'on
		 * est en debug > 0.
		 *
		 * @param array $search
		 * @return array
		 */
		public function search( array $search ) {
			$query = $this->searchQuery();
			$query = $this->searchConditions( $query, $search );

			return $query;
		}

		/**
		 * Vérification que les champs spécifiés dans le paramétrage par les clés
		 * Dsps.index.fields, Dsps.index.innerTable et Dsps.exportcsv dans le
		 * webrsa.inc existent bien dans la requête de recherche renvoyée par
		 * la méthode search().
		 *
		 * @param array $params Paramètres supplémentaires (clé 'query' possible)
		 * @return array
		 * @todo Utiliser AbstractWebrsaRecherche
		 */
		public function checkParametrage( array $params = array() ) {
			$keys = array( 'Dsps.index.fields', 'Dsps.index.innerTable', 'Dsps.exportcsv' );
			$query = $this->search( array() );

			App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );
			$return = ConfigurableQueryFields::getErrors( $keys, $query );

			return $return;
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
		 * @throws RuntimeException
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

				// Si on utilise les codes ROME v.3, il y aura de la copie de données à faire
				foreach( $this->romev3LinkedModels as $linkedModelName ) {
					$linkedFieldName = Inflector::underscore( $linkedModelName ).'_id';
					$value = Hash::get( $newRecord, "{$newModelName}.{$linkedFieldName}" );

					if( !empty( $value ) ) {
						$query = array( 'conditions' => array( "{$linkedModelName}.id" => $value ), 'contain' => false );
						$record = $this->{$linkedModelName}->find( 'first', $query );

						if( empty($record) ) {
							$msg = sprintf( 'Impossible de trouver l\'enregistrement de %s d\'id %d.', $linkedModelName, $value );
							throw new RuntimeException( $msg );
						}

						foreach( array( 'id', 'created', 'modified' ) as $field ) {
							unset( $record[$linkedModelName][$field] );
						}

						$this->{$linkedModelName}->create( $record );
						$success = $this->{$linkedModelName}->save() && $success;

						$newRecord[$newModelName][$linkedFieldName] = $this->{$linkedModelName}->id;
					}
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
		 * Retourne les options à utiliser dans le moteur de recherche, le
		 * formulaire d'ajout / de modification, etc.. suivant le CG connecté.
		 *
		 * Il est possible d'aliaser le modèle Dsp.
		 *
		 * $params par défaut: array(
		 *	 'find' => true,
		 *	 'allocataire' => false,
		 *	 'alias' => 'Dsp'
		 * )
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$params = $params + array( 'find' => true, 'allocataire' => false, 'alias' => 'Dsp' );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $params ) );
			$return = Cache::read( $cacheKey );

			if( $return === false ) {
				$Catalogueromev3 = ClassRegistry::init( 'Catalogueromev3' );

				$return = $this->enums();
				foreach( array_keys( $this->getCheckboxes() ) as $modelDetail ) {
					$return = Hash::merge( $return, $this->{$modelDetail}->enums() );
				}

				if( $params['allocataire'] ) {
					$return = Hash::merge(
						$return,
						ClassRegistry::init( 'Allocataire' )->options()
					);
				}

				if( $params['find'] ) {
					foreach( $this->romev3LinkedModels as $alias ) {
						$return = Hash::merge(
							$return,
							$this->{$alias}->options()
						);
					}
				}

				// Codes ROME V2
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					// Coderomesecteurdsp66
					$query = array(
						'fields' => array(
							'Libsecactderact66Secteur.id',
							'Libsecactderact66Secteur.intitule'
						),
						'contain' => false,
						'order' => array( 'Libsecactderact66Secteur.code' )
					);
					$results = $this->Libsecactderact66Secteur->find( 'all', $query );
					$results = array( $this->Libsecactderact66Secteur->name => (array)Hash::combine( $results, '{n}.Libsecactderact66Secteur.id', '{n}.Libsecactderact66Secteur.intitule' ) );
					$return = Hash::merge( $return, $results );

					// Coderomemetierdsp66
					$query = array(
						'fields' => array(
							'( "Libderact66Metier"."coderomesecteurdsp66_id" || \'_\' || "Libderact66Metier"."id" ) AS "Libderact66Metier__id"',
							'Libderact66Metier.intitule'
						),
						'contain' => false,
						'order' => array( 'Libderact66Metier.code' )
					);
					$results = $this->Libderact66Metier->find( 'all', $query );
					$results = array( $this->Libderact66Metier->name => (array)Hash::combine( $results, '{n}.Libderact66Metier.id', '{n}.Libderact66Metier.intitule' ) );
					$return = Hash::merge( $return, $results );
				}

				$return = $this->getFilteredOptions( $return );

				if( $params['alias'] !== 'Dsp' ) {
					$return[$params['alias']] = $return['Dsp'];
					unset( $return['Dsp'] );
				}

				Cache::write( $cacheKey, $return );

				$models = $Catalogueromev3->modelesParametrages;
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					$models = array_merge(
						$models,
						array(
							$this->Libderact66Metier->name,
							$this->Libsecactderact66Secteur->name
						)
					);
				}
				ModelCache::write( $cacheKey, $models );
			}

			$correspondances = array(
				'Detaildifsoc' => 'difsoc',
				'Detailaccosocindi' => 'nataccosocindi',
				'Detaildifdisp' => 'difdisp'
			);
			foreach( $correspondances as $alias => $fieldName ) {
				$return['Donnees'][$fieldName] =& $return[$alias][$fieldName];
			}

			return $return;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * Export de la liste des champs disponibles pour le moteur de recherche
		 * dans le fichier app/tmp/Dsp__searchQuery__cgXX.csv.
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$success = true;

			$query = $this->searchQuery();
			$success = $success && !empty( $query );

			// Export des champs disponibles
			App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );
			$fileName = TMP.DS.'logs'.DS.__CLASS__.'__searchQuery__cg'.Configure::read( 'Cg.departement' ).'.csv';
			ConfigurableQueryFields::exportQueryFields( $query, 'dsps', $fileName );

			$results = $this->options();
			$success = $success && !empty( $results );

			return $success;
		}
	}
?>