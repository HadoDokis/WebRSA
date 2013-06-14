<?php	
	/**
	 * Code source de la classe Coderomemetierdsp66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Coderomemetierdsp66 ...
	 *
	 * @package app.Model
	 */
	class Coderomemetierdsp66 extends AppModel
	{
		public $name = 'Coderomemetierdsp66';

		public $displayField = 'intitule';

		public $actsAs = array(
			'Autovalidate2'
		);

		public $hasMany = array(
			'Libactdomi66MetierDsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'libactdomi66_metier_id',
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
			'Libactdomi66MetierDspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'libactdomi66_metier_id',
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
			'Libderact66MetierDsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'libderact66_metier_id',
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
			'Libderact66MetierDspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'libderact66_metier_id',
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
			'Libemploirech66MetierDsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'libemploirech_metier_id',
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
			'Libemploirech66MetierDspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'libemploirech_metier_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		public $belongsTo = array(
			'Coderomesecteurdsp66' => array(
				'className' => 'Coderomesecteurdsp66',
				'foreignKey' => 'coderomesecteurdsp66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $virtualFields = array(
			'intitule' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."code" || \'. \' || "%s"."name" )'
			),
		);
	}
?>