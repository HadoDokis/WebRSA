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
			/*'Adressefoyer' => array(
				'className' => 'Adressefoyer',
				'foreignKey' => 'adressefoyer_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'dossier_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'StructurereferenteDst' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_dst_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'StructurereferenteSrc' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_src_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),*/
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
	}
?>