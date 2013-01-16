<?php
	/**
	 * Fichier source du modèle Transfertpdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Transfertpdv93.
	 *
	 * @package app.Model
	 */
	class Transfertpdv93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Transfertpdv93';

		public $recursive = -1;

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_dst_id'
				)
			),
			'Validation.Autovalidate',
			'Gedooo.Gedooo'
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'NvOrientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'nv_orientstruct_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'VxOrientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'vx_orientstruct_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'NvAdressefoyer' => array(
				'className' => 'Adressefoyer',
				'foreignKey' => 'nv_adressefoyer_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'VxAdressefoyer' => array(
				'className' => 'Adressefoyer',
				'foreignKey' => 'vx_adressefoyer_id',
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
		 * Retourne le chemin relatif du modèle de document à utiliser pour
		 * l'enregistrement du PDF.
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return "Transfertpdv93/transfert.odt";
		}

		/**
		 * Récupère les données pour le PDF.
		 *
		 * @param integer $nvorientstruct_id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $nvorientstruct_id, $user_id ) {

			$joins = array(
				$this->join( 'User', array( 'type' => 'INNER' ) ),
				$this->join( 'NvOrientstruct', array( 'type' => 'INNER' ) ),
				$this->join( 'VxOrientstruct', array( 'type' => 'INNER' ) ),
				$this->NvOrientstruct->join( 'Personne', array( 'type' => 'INNER' )),
				array_words_replace(
					$this->NvOrientstruct->join( 'Structurereferente', array( 'type' => 'INNER' )),
					array(
						'Structurereferente' => 'NvStructurereferente'
					)
				),
				array_words_replace(
					$this->VxOrientstruct->join( 'Structurereferente', array( 'type' => 'INNER' )),
					array(
						'Structurereferente' => 'VxStructurereferente'
					)
				),
				$this->NvOrientstruct->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
				$this->NvOrientstruct->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
				$this->NvOrientstruct->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
				$this->NvOrientstruct->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				$this->NvOrientstruct->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) )
			);

			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->User->fields(),
					$this->NvOrientstruct->fields(),
					$this->VxOrientstruct->fields(),
					array_words_replace(
						$this->NvOrientstruct->Structurereferente->fields(),
						array(
							'Structurereferente' => 'NvStructurereferente'
						)
					),
					array_words_replace(
						$this->VxOrientstruct->Structurereferente->fields(),
						array(
							'Structurereferente' => 'VxStructurereferente'
						)
					),
					$this->NvOrientstruct->Personne->fields(),
					$this->NvOrientstruct->Personne->Prestation->fields(),
					$this->NvOrientstruct->Personne->Foyer->fields(),
					$this->NvOrientstruct->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->NvOrientstruct->Personne->Foyer->Dossier->fields()
				),
				'joins' => $joins,
				'conditions' => array(
					'Transfertpdv93.nv_orientstruct_id' => $nvorientstruct_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->NvOrientstruct->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					)
				),
				'contain' => false
			);
			
			$data = $this->find( 'first', $queryData );
// debug($data);
// die();
			return $data;
		}
		
		/**
		 * Retourne le PDF par défaut, stocké, ou généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo et le stocke,
		 *
		 * @param integer $id Id du Trasnfert réalisé
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $nvorientstruct_id, $user_id ) {
			$data = $this->getDataForPdf( $nvorientstruct_id, $user_id );
			$modeleodt = $this->modeleOdt( $data );

			$Option = ClassRegistry::init( 'Option' );
			$options =  Set::merge(
				array(
					'Personne' => array(
						'qual' => $Option->qual()
					),
					'Adresse' => array(
						'typevoie' => $Option->typevoie()
					)
				)
			);
// debug($data);
// die();
			return $this->ged( $data, $modeleodt, false, $options );
		}
	}
?>