<?php
	/**
	 * Code source de la classe DspRev.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DspRev ...
	 *
	 * @package app.Model
	 */
	class DspRev extends AppModel
	{
		public $name = 'DspRev';

		public $actsAs = array(
			'Allocatairelie',
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe'
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
		* Permet de récupérer les dernières DSP d'une personne, en attendant l'index unique sur personne_id
		*/

		public function sqDerniere( $personneIdFied = 'Personne.id' ) {
			return $this->sq(
				array(
					'alias' => 'tmp_dsps_revs',
					'fields' => array(
						'tmp_dsps_revs.id'
					),
					'conditions' => array(
						"tmp_dsps_revs.personne_id = {$personneIdFied}"
					),
					'order' => 'tmp_dsps_revs.modified DESC',
					'limit' => 1
				)
			);
		}
	}
?>
