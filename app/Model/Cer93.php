<?php
	/**
	 * Code source de la classe Cer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cer93 ...
	 *
	 * @package app.Model
	 */
	class Cer93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cer93';

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
			'Validation.Autovalidate',
			'Enumerable',
			'Formattable',
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);
		/**
		 * Liaisons "hasMany" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Compofoyercer93' => array(
				'className' => 'Compofoyercer93',
				'foreignKey' => 'cer93_id',
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
			'Diplomecer93' => array(
				'className' => 'Diplomecer93',
				'foreignKey' => 'cer93_id',
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
		
		/**
		*	Fonction permettant la sauvegarde du CER 93
		*	Une règle de validation est supprimée en amont
		*	Les valeurs de la table Compofoyercer93 sont mises à jour à chaque modifciation
		*	@param $data
		*	@return boolean
		*/
		
		public function saveFormulaire( $data ){
			$success = true;
			// Sinon, ça pose des problèmes lors du add car la valeur n'existe pas encore
			$this->unsetValidationRule( 'contratinsertion_id', 'notEmpty' );

			if( isset( $data['Cer93']['id'] ) && !empty( $data['Cer93']['id'] ) ) {
				$success = $this->Compofoyercer93->deleteAll(
					array( 'Compofoyercer93.cer93_id' => $data['Cer93']['id'] )
				);
			}
				
			$success = $this->saveResultAsBool(
				$this->saveAssociated( $data, array( 'validate' => 'first', 'atomic' => false, 'deep' => true ) )
			) && $success;

			return $success;
		}

		public function prepareFormData( $personneId, $contratinsertion_id  ) {
			// Donnée de la CAF stockée en base
			$this->Contratinsertion->Personne->forceVirtualFields = true;
			$Informationpe = ClassRegistry::init( 'Informationpe' );
			$dataCaf = $this->Contratinsertion->Personne->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Contratinsertion->Personne->fields(),
						$this->Contratinsertion->Personne->Prestation->fields(),
						$this->Contratinsertion->Personne->Dsp->fields(),
						$this->Contratinsertion->Personne->DspRev->fields(),
						$this->Contratinsertion->Personne->Foyer->fields(),
						$this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Contratinsertion->Personne->Foyer->Dossier->fields(),
						array(
							$this->Contratinsertion->vfRgCiMax( '"Personne"."id"' ),
							'Historiqueetatpe.identifiantpe',
							'Historiqueetatpe.etat',
							'Adresse.adresse_complete'
						)
					),
					'joins' => array(
						$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
						$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
						$this->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
						$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
						$this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
						$this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Personne.id' => $personneId,
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'Dsp.id IS NULL',
								'Dsp.id IN ( '.$this->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'DspRev.id IS NULL',
								'DspRev.id IN ( '.$this->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
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
					),
					'contain' => false
				)
			);

			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $dataCaf['DspRev']['id'] ) ) {
				$dataCaf['Dsp'] = $dataCaf['DspRev'];
				unset( $dataCaf['DspRev'], $dataCaf['Dsp']['id'], $dataCaf['Dsp']['dsp_id'] );
			}

			// Transposition des données
			//Bloc 2 : Etat civil
			$dataCaf['Cer93']['matricule'] = $dataCaf['Dossier']['matricule'];
			$dataCaf['Cer93']['numdemrsa'] = $dataCaf['Dossier']['numdemrsa'];
			$dataCaf['Cer93']['rolepers'] = $dataCaf['Prestation']['rolepers'];
			$dataCaf['Cer93']['dtdemrsa'] = $dataCaf['Dossier']['dtdemrsa'];
			$dataCaf['Cer93']['identifiantpe'] = $dataCaf['Historiqueetatpe']['identifiantpe'];
			$dataCaf['Cer93']['qual'] = $dataCaf['Personne']['qual'];
			$dataCaf['Cer93']['nom'] = $dataCaf['Personne']['nom'];
			$dataCaf['Cer93']['nomnai'] = $dataCaf['Personne']['nomnai'];
			$dataCaf['Cer93']['prenom'] = $dataCaf['Personne']['prenom'];
			$dataCaf['Cer93']['dtnai'] = $dataCaf['Personne']['dtnai'];
			$dataCaf['Cer93']['adresse'] = $dataCaf['Adresse']['adresse_complete'];//FIXME virtual fiuelds adresse.php
			$dataCaf['Cer93']['codepos'] = $dataCaf['Adresse']['codepos'];
			$dataCaf['Cer93']['locaadr'] = $dataCaf['Adresse']['locaadr'];
			$dataCaf['Cer93']['sitfam'] = $dataCaf['Foyer']['sitfam'];
			$dataCaf['Cer93']['natlog'] = $dataCaf['Dsp']['natlog'];
			// Bloc 3
			$dataCaf['Cer93']['inscritpe'] = ( ( !empty( $dataCaf['Historiqueetatpe']['etat'] ) && ( $dataCaf['Historiqueetatpe']['etat'] == 'inscription' ) ) ? true : null );
			
			// Bloc 2 : Composition du foyer 
			// Récupération des informations de composition du foyer de l'allocataire
			$composfoyerscers93 = $this->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'"Personne"."qual" AS "Compofoyercer93__qual"',
						'"Personne"."nom" AS "Compofoyercer93__nom"',
						'"Personne"."prenom" AS "Compofoyercer93__prenom"',
						'"Personne"."dtnai" AS "Compofoyercer93__dtnai"',
						'"Prestation"."rolepers" AS "Compofoyercer93__rolepers"'
					),
					'conditions' => array( 'Personne.foyer_id' => $dataCaf['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);
			$composfoyerscers93 = array( 'Compofoyercer93' => Set::classicExtract( $composfoyerscers93, '{n}.Compofoyercer93' ) );
			$dataCaf = Set::merge( $dataCaf, $composfoyerscers93 );

			
			// Bloc 4 : Diplômes
			// Récupération des informations de diplômes de l'allocataire
			$diplomescers93 = $this->Cer93->Diplomecer93->find(
				'all',
				array(
					'fields' => array(
						'Diplomecer93.id',
						'Diplomecer93.cer93_id',
						'Diplomecer93.name',
						'Diplomecer93.annee'
					),
					'conditions' => array( 'Diplomecer93.cer93_id' => $id ),
					'contain' => false
				)
			);
			$dataCaf = Set::merge( $dataCaf, $composfoyerscers93 );

			//Donnée du CER actuel
			$dataActuelCer= array();
			if( !empty( $contratinsertion_id )) {
				$dataActuelCer = $this->Contratinsertion->find(
					'first',
					array(
						'conditions' => array(
							'Contratinsertion.id' => $contratinsertion_id,
						),
						'contain' => array(
							'Cer93'
						)
					)
				);
			}
			
			//Donnée du précédent CER validé
			$dataPcdCer = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personneId,
						'OR' => array(
							'Contratinsertion.id IS NULL',
							'Contratinsertion.id IN ( '.$this->Contratinsertion->sqDernierContrat( 'Contratinsertion.personne_id', true ).' )'
						)
					),
					'contain' => false
				)
			);

			$formData = Set::merge( Set::merge( $dataCaf, $dataPcdCer ), $dataActuelCer );
			
			$formData['Cer93']['nivetu'] = $formData['Dsp']['nivetu'];

			return $formData;
		}
	}
?>