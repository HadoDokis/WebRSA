<?php
	/**
	 * Code source de la classe Suspensioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Suspensioncui66 ...
	 *
	 * @package app.Model
	 */
	class Suspensioncui66 extends AppModel
	{
		public $name = 'Suspensioncui66';

		public $recursive = -1;

		public $actsAs = array(
			'Containable',
			'Enumerable',
			'Formattable',
            'Pgsqlcake.PgsqlAutovalidate'
		);

		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
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
					'Fichiermodule.modele = \'Suspensioncui66\'',
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
         * * Associations "Has And Belongs To Many".
         * @var array
         */
        public $hasAndBelongsToMany = array(
            'Motifsuspensioncui66' => array(
                'className' => 'Motifsuspensioncui66',
                'joinTable' => 'motifssuspensioncuis66_suspensionscuis66',
                'foreignKey' => 'suspensioncui66_id',
                'associationForeignKey' => 'motifsuspensioncui66_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'deleteQuery' => '',
                'insertQuery' => '',
                'with' => 'Motifsuspensioncui66Suspensioncui66'
            )
        );
		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "Cui.personne_id" ),
				'joins' => array(
					$this->join( 'Cui', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result['Cui']['personne_id'];
			}
			else {
				return null;
			}
		}
	}
?>