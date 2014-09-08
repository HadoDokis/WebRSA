<?php
	/**
	 * Code source de la classe Piecemailcui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Piecemailcui66 ...
	 *
	 * @package app.Model
	 */
	class Piecemailcui66 extends AppModel
	{
		public $name = 'Piecemailcui66';

		public $actsAs = array(
			'Pgsqlcake.PgsqlAutovalidate',
			'Formattable'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				)
			)
		);

        /**
         * Associations "Has And Belongs To Many".
         * @var array
         */
        public $hasAndBelongsToMany = array(
            'Cui' => array(
                'className' => 'Cui',
                'joinTable' => 'cuis_piecesmailscuis66',
                'foreignKey' => 'piecemailcui66_id',
                'associationForeignKey' => 'cui_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'deleteQuery' => '',
                'insertQuery' => '',
                'with' => 'CuiPiecemailcui66'
            )
        );
        
        /**
         * Associations "Has Many".
         * @var array
         */
        public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Piecemailcui66\'',
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
	}
?>