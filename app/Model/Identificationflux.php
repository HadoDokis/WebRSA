<?php	
	/**
	 * Code source de la classe Identificationflux.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Identificationflux ...
	 *
	 * @package app.Model
	 */
	class Identificationflux extends AppModel
	{
		public $name = 'Identificationflux';

		public $hasMany = array(
			'Totalisationacompte' => array(
				'className' => 'Totalisationacompte',
				'foreignKey' => 'identificationflux_id',
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
			'Transmissionflux' => array(
				'className' => 'Transmissionflux',
				'foreignKey' => 'identificationflux_id',
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
	}
?>