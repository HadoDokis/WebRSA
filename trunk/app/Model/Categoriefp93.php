<?php
	/**
	 * Code source de la classe Categoriefp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractElementCataloguefp93', 'Model/Abstractclass' );

	/**
	 * La classe Categoriefp93 ...
	 *
	 * @package app.Model
	 */
	class Categoriefp93 extends AbstractElementCataloguefp93
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Categoriefp93';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Cataloguepdifp93',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable'
		);

		/**
		 * Ajout des règles de validation des champs virtuels du formulaire de
		 * paramétrage.
		 *
		 * @var array
		 */
		public $validate = array(
			'typethematiquefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'thematiquefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Thematiquefp93' => array(
				'className' => 'Thematiquefp93',
				'foreignKey' => 'thematiquefp93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Filierefp93' => array(
				'className' => 'Filierefp93',
				'foreignKey' => 'categoriefp93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
		);

		/**
		 * Retourne une condition qui est en fait une sous-requête, avec les
		 * jointures nécessaires pour atteindre le modèle Actionfp93, et comprenant
		 * les conditions passées en paramètre.
		 *
		 * @param array $conditions Les conditions à appliquer sur le modèle Actionfp93
		 * @return string
		 */
		public function getActionfp93Condition( array $conditions ) {
			$conditions[] = "Filierefp93.categoriefp93_id = {$this->alias}.{$this->primaryKey}";

			$query = array(
				'alias' => 'Filierefp93',
				'fields' => array( 'Filierefp93.categoriefp93_id' ),
				'joins' => array(
					$this->Filierefp93->join( 'Actionfp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions
			);

			$replacements = array(
				'Filierefp93' => 'filieresfps93',
				'Actionfp93' => 'actionsfps93',
			);

			$sql = $this->Filierefp93->sq( array_words_replace( $query, $replacements ) );

			return "{$this->alias}.{$this->primaryKey} IN ( {$sql} )";
		}

		/**
		 * Tentative de sauvegarde d'un élément du catalogue à partir de la
		 * partie paramétrage.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveParametrage( array $data ) {
			$this->create( $data );
			return $this->save();
		}

		/**
		 * Retourne la liste des champs à utiliser dans le formulaire d'ajout / de
		 * modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageFields() {
			$fields = array(
				"{$this->alias}.id" => array(),
				"{$this->alias}.typethematiquefp93_id" => array( 'empty' => true ),
				"{$this->alias}.thematiquefp93_id" => array( 'empty' => true ),
				"{$this->alias}.name" => array(),
			);

			return $fields;
		}

		/**
		 * Retourne les données à utiliser dans le formulaire de modification de
		 * la partie paramétrage.
		 *
		 * @param integer $id
		 * @return array
		 */
		public function getParametrageFormData( $id ) {
			$query = array(
				'fields' => array(
					"Thematiquefp93.type",
					"{$this->alias}.{$this->primaryKey}",
					"{$this->alias}.{$this->displayField}",
					"{$this->alias}.thematiquefp93_id",
				),
				'joins' => array(
					$this->join( 'Thematiquefp93', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.{$this->primaryKey}" => $id
				)
			);

			$result = $this->find( 'first', $query );

			if( !empty( $result ) ) {
				$typethematiquefp93_id = Hash::get( $result, "Thematiquefp93.type" );

				$result = array(
					$this->alias => array(
						$this->primaryKey => Hash::get( $result, "{$this->alias}.{$this->primaryKey}" ),
						'typethematiquefp93_id' => $typethematiquefp93_id,
						'thematiquefp93_id' => $typethematiquefp93_id.'_'.Hash::get( $result, "{$this->alias}.thematiquefp93_id" ),
						$this->displayField => Hash::get( $result, "{$this->alias}.{$this->displayField}" ),
					)
				);
			}

			return $result;
		}

		/**
		 * Retourne les options à utiliser dans le formulaire d'ajout / de
		 * modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageOptions() {
			$options = array(
				$this->alias => array(
					'typethematiquefp93_id' => $this->Thematiquefp93->enum( 'type' )
				)
			);

			$query = array(
				'fields' => array(
					'( "Thematiquefp93"."type" || \'_\' || "Thematiquefp93"."id" ) AS "Thematiquefp93__id"',
					'Thematiquefp93.name',
				)
			);
			$results = $this->Thematiquefp93->find( 'all', $query );
			$options[$this->alias]['thematiquefp93_id'] = Hash::combine( $results, '{n}.Thematiquefp93.id', '{n}.Thematiquefp93.name' );

			return $options;
		}
	}
?>