<?php
	class DspRev extends AppModel
	{
		public $name = 'DspRev';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe'
				)
			),
			'Formattable' => array(
				'suffix' => array( 'libderact66_metier_id', 'libactdomi66_metier_id', 'libemploirech66_metier_id' )
			)
		);

		public $belongsTo = array(
			'Dsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'dsp_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
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
			'DetaildifsocRev' => array(
				'className' => 'DetaildifsocRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailaccosocfamRev' => array(
				'className' => 'DetailaccosocfamRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailaccosocindiRev' => array(
				'className' => 'DetailaccosocindiRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetaildifdispRev' => array(
				'className' => 'DetaildifdispRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailnatmobRev' => array(
				'className' => 'DetailnatmobRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetaildiflogRev' => array(
				'className' => 'DetaildiflogRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailmoytransRev' => array(
				'className' => 'DetailmoytransRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetaildifsocproRev' => array(
				'className' => 'DetaildifsocproRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailprojproRev' => array(
				'className' => 'DetailprojproRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailfreinformRev' => array(
				'className' => 'DetailfreinformRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailconfortRev' => array(
				'className' => 'DetailconfortRev',
				'foreignKey' => 'dsp_rev_id',
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
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Dsp\'',
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

		/**
		 * Retourne l'id du dossier à partir de l'id d'une DspRev
		 *
		 * @param integer $id
		 * @return integer
		 */
		public function dossierId( $id ) {
			$dsp_rev = $this->find(
				'first',
				array(
					'fields' => array(
						'Foyer.dossier_id'
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'DspRev.id' => $id
					),
					'contain' => false
				)
			);

			if( !empty( $dsp_rev ) ) {
				return $dsp_rev['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
	}
?>
