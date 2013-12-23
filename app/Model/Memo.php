<?php
	/**
	 * Code source de la classe Memo.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Memo ...
	 *
	 * @package app.Model
	 */
	class Memo extends AppModel
	{
		public $name = 'Memo';

		public $actsAs = array(
			'Allocatairelie',
			'Validation.Autovalidate',
			'Formattable',
			'Enumerable'
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Memo\'',
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
		 * Sous-requête permettant de savoir si une entrée existe dans la table memos 
         * pour une entrée d'une table d'un autre modèle.
		 *
		 * @param Model $Model
		 * @param string $fieldName Si null, renvoit uniquement la sous-reqête,
		 * 	sinon renvoit la sous-requête aliasée pour un champ (avec l'alias du
		 * 	modèle).
		 * @param string $modelAlias Si null, utilise l'alias de la class Fichiermodule, sinon la valeur donnée.
		 * @return string
		 */
		public function sqNbMemosLies( Model $Model, $fieldId = 'Personne.id', $fieldName = null, $modelAlias = null ) {
			$alias = Inflector::underscore( $this->alias );

			$modelAlias = ( is_null( $modelAlias ) ? $this->alias : $modelAlias );

			$sq = $this->sq(
					array(
						'fields' => array(
							"COUNT( {$alias}.id )"
						),
						'alias' => $alias,
						'conditions' => array(
							"{$alias}.personne_id = $fieldId"
						)
					)
			);

			if( !is_null( $fieldName ) ) {
				$sq = "( {$sq} ) AS \"{$modelAlias}__{$fieldName}\"";
			}

			return $sq;
		}
	}
?>
