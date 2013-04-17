<?php
	/**
	 * Code source de la classe ActioncandidatPersonne.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ActioncandidatPersonne ...
	 *
	 * @package app.Controller
	 */
	class ActioncandidatPersonne extends AppModel
	{
		public $name = 'ActioncandidatPersonne';

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Motifsortie' => array(
				'className' => 'Motifsortie',
				'foreignKey' => 'motifsortie_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $actsAs = array (
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'enattente' => array(
						'values' => array( 'O', 'N' )
					),
					'bilanvenu' => array(
						'values' => array( 'VEN', 'NVE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanretenu' => array(
						'values' => array( 'RET', 'NRE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanrecu' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'presencecontrat' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'pieceallocataire' => array(
						'values' => array( 'CER', 'NCA', 'CV', 'AUT' ),
						'domain' => 'actioncandidat_personne'
					),
					'integrationaction' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'positionfiche' => array(
						'domain' => 'actioncandidat_personne'
					),
					'haspiecejointe' => array(
						'domain' => 'actioncandidat_personne'
					),
					'naturemobile' => array(
						'domain' => 'actioncandidat_personne'
					)
				)
			),
			'Formattable',
			'Gedooo.Gedooo',
			'Autovalidate2'
		);


		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'ActioncandidatPersonne\'',
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
			'personne_id' => array(
				'notEmpty' => array( 'rule' => 'notEmpty' )
			),
			'referent_id' => array(
				'notEmpty'=> array( 'rule' => 'notEmpty' )
			),
			'actioncandidat_id' => array(
				'notEmpty' => array( 'rule' => 'notEmpty' )
			),
//			'enattente'  => array(
//				'rule' => 'notEmpty',
//				'message' => 'Champ obligatoire'
//			),
			'nivetu'  => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
            'bilanvenu' => array(
                // INFO: il s'agit d'un champ "virtuel" dans les cohortes de fiches de candidature
                'notEmptyIf' => array(
                    'rule' => array( 'notEmptyIf', 'atraiter', true, array( '1' ) ),
                    'message' => 'Champ obligatoire',
                )
            ),
// 			'bilanvenu'  => array(
// 				'rule' => 'notEmpty',
// 				'message' => 'Champ obligatoire'
// 			),
			'bilanretenu'  => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'bilanvenu', true, array( 'VEN' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'horairerdvpartenaire' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'rendezvouspartenaire', true, array( '1' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'ddaction' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'motifdemande' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'sortiele' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'issortie', true, array( 1 ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'motifsortie_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'issortie', true, array( 1 ) ),
					'message' => 'Champ obligatoire',
				)
			)
		);


		/**
		*   BeforeSave
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );
			//  Calcul de la position de la fiche de calcul
			//$this->data[$this->alias]['positionfiche'] = $this->_calculPosition( $this->data );
			$this->data = $this->_bilanAccueil( $this->data );

			return $return;
		}

		/**
		* Venu
		* 	Retenu
		* 		Pas de sortie ?
		* 			-> Position: en cours
		* 			-> nullify: date, motifsortie
		* 		Sinon ?
		* 			-> sauve tout
		* 			-> position: sortie
		* 	Non retenu
		* 		-> Position: Non retenu
		* 		-> nullify: sortie, date, motifdemande
		* Non venu
		* 	bilanretenu: non retenu
		* 	position: nonretenue
		* 	nullify: sortie, date, motifdemande
		*/
		protected function _bilanAccueil( $data ) {
			$bilanvenu = Set::classicExtract( $data, "{$this->alias}.bilanvenu" );
			$bilanretenu = Set::classicExtract( $data, "{$this->alias}.bilanretenu" );
			$issortie = Set::classicExtract( $data, "{$this->alias}.issortie" );

			if( empty( $bilanvenu ) ) {
				$data[$this->alias]['positionfiche'] = 'enattente';
				$data[$this->alias]['bilanretenu'] = null;
				$data[$this->alias]['issortie'] = null;
				$data[$this->alias]['sortiele'] = null;
				$data[$this->alias]['motifsortie_id'] = null;
			}
			else {
				if( $bilanvenu == 'VEN' ) {
					if( $bilanretenu == 'RET' ) {
						if( !$issortie ) {
							$data[$this->alias]['positionfiche'] = 'encours';
							$data[$this->alias]['sortiele'] = null;
							$data[$this->alias]['motifsortie_id'] = null;
						}
						else {
							$data[$this->alias]['positionfiche'] = 'sortie';
						}
					}
					else if( $bilanretenu == 'NRE' ) {
						$data[$this->alias]['positionfiche'] = 'nonretenue';
						$data[$this->alias]['issortie'] = null;
						$data[$this->alias]['sortiele'] = null;
						$data[$this->alias]['motifsortie_id'] = null;
					}
				}
				else if( $bilanvenu == 'NVE' ) {
					$data[$this->alias]['bilanretenu'] = 'NRE';
					$data[$this->alias]['positionfiche'] = 'nonretenue';
					$data[$this->alias]['issortie'] = null;
					$data[$this->alias]['sortiele'] = null;
					$data[$this->alias]['motifsortie_id'] = null;
				}
			}

			return $data;
		}

		/*protected function _calculPosition( $data ){

			$bilanrecu = Set::classicExtract( $data, 'ActioncandidatPersonne.bilanvenu' );
			$bilanretenu = Set::classicExtract( $data, 'ActioncandidatPersonne.bilanretenu' );
			$issortie = Set::classicExtract( $data, 'ActioncandidatPersonne.issortie' );

			$positionfiche = null;
			// enattente,encours,nonretenue,sortie,annule


			if ( empty( $bilanrecu ) && empty( $bilanretenu ) && empty( $motifsortie ) ){
				$positionfiche = 'enattente';
			}
			elseif ( !empty( $bilanrecu ) && ( $bilanrecu == 'NVE' ) ){
				$positionfiche = 'nonretenue';
			}
			elseif ( !empty( $bilanrecu ) && ( $bilanrecu == 'VEN' )  && ( $bilanretenu == 'RET' ) && empty( $issortie ) ){
				$positionfiche = 'encours';
			}
			elseif ( !empty( $bilanrecu ) && ( $bilanrecu == 'VEN' ) && ( $bilanretenu == 'RET' ) && !empty( $issortie ) ){
				$positionfiche = 'sortie';
			}

			return $positionfiche;
		}*/




		/**
		*
		*/

		public function getPdfFiche( $actioncandidat_personne_id ) {


			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Actioncandidat->fields(),
					$this->Actioncandidat->Contactpartenaire->fields(),
					$this->Actioncandidat->Contactpartenaire->Partenaire->fields(),
					$this->Personne->fields(),
					$this->Referent->fields(),
					$this->Personne->Foyer->fields(),
					$this->Personne->Foyer->Dossier->fields(),
					$this->Personne->Foyer->Adressefoyer->fields(),
					$this->Personne->Foyer->Adressefoyer->Adresse->fields()
				),
				'joins' => array(
					array(
						'table'      => 'actionscandidats',
						'alias'      => 'Actioncandidat',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Actioncandidat.id = ActioncandidatPersonne.actioncandidat_id'
						),
					),
					array(
						'table'      => 'contactspartenaires',
						'alias'      => 'Contactpartenaire',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Contactpartenaire.id = Actioncandidat.contactpartenaire_id' ),
					),
					array(
						'table'      => 'partenaires',
						'alias'      => 'Partenaire',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Partenaire.id = Contactpartenaire.partenaire_id' ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "ActioncandidatPersonne.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Referent.id = ActioncandidatPersonne.referent_id' ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
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
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'conditions' => array(
					'ActioncandidatPersonne.id' => $actioncandidat_personne_id
				),
				'recursive' => -1
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			$options = Hash::insert( $options, 'ActioncandidatPersonne.naturemobile', $this->Personne->Dsp->Detailnatmob->enumList( 'natmob' ) );


			$options = Set::merge( $options, $this->enums() );


			$actioncandidat = $this->find( 'first', $queryData );
			$referents = $this->Referent->find( 'list' );
			$motifssortie = ClassRegistry::init( 'Motifsortie' )->find( 'list' );

			$correspondantaction = Set::classicExtract( $actioncandidat, 'Actioncandidat.correspondantaction' );

			if( !empty( $correspondantaction ) ){
				$actioncandidat['Actioncandidat']['correspondantaction_nom_complet'] = Set::enum( $actioncandidat['Actioncandidat']['referent_id'],  $referents );
			}
			$actioncandidat['Actioncandidat']['codeaction'] = Set::classicExtract( $actioncandidat, 'Actioncandidat.themecode' ).' '. Set::classicExtract( $actioncandidat, 'Actioncandidat.codefamille' ).' '.Set::classicExtract( $actioncandidat, 'Actioncandidat.numcodefamille' );



			$actioncandidat['ActioncandidatPersonne']['motifsortie_id'] = Set::enum( Set::classicExtract( $actioncandidat, 'ActioncandidatPersonne.motifsortie_id' ), $motifssortie );


			// Nom du modèle devant être généré
			$modeleodt = Set::classicExtract( $actioncandidat, 'Actioncandidat.modele_document' );

//             debug($options);
// debug($actioncandidat);
// die();
			return $this->ged( array( $actioncandidat ), "Candidature/{$modeleodt}.odt", true, $options );
		}

		/**
		 * Retourne la liste des modèles odt paramétrés pour le impressions de
		 * cette classe.
		 *
		 * @return array
		 */
		public function modelesOdt() {
			$prefix = 'Candidature'.DS;

			$items = $this->Actioncandidat->find(
				'all',
				array(
					'fields' => array(
						'( \''.$prefix.'\' || "'.$this->Actioncandidat->alias.'"."modele_document" || \'.odt\' ) AS "'.$this->Actioncandidat->alias.'__modele"',
					),
					'recursive' => -1
				)
			);
			return Set::extract( $items, '/'.$this->Actioncandidat->alias.'/modele' );
		}

		/**
		 * Retourne l'id du dossier à partir de l'id de l'enregistrement du modèle.
		 *
		 * @param integer $actioncandidat_personne_id
		 * @return integer
		 */
		public function dossierId( $actioncandidat_personne_id ) {
			$querydata = array(
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
                    $this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'ActioncandidatPersonne.id' => $actioncandidat_personne_id
				),
				'recursive' => -1
			);

			$actioncandidat_personne = $this->find( 'first', $querydata );

			if( !empty( $actioncandidat_personne ) ) {
				return $actioncandidat_personne['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
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
	}
?>