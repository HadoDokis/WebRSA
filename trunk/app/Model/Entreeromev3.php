<?php
	/**
	 * Code source de la classe Entreeromev3.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Entreeromev3 ...
	 *
	 * @package app.Model
	 */
	class Entreeromev3 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Entreeromev3';

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
			'Catalogueromev3',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Has one".
		 *
		 * @var array
		 */
		public $hasOne = array(
			'Dspderact' => array(
				'className' => 'Dsp',
				'foreignKey' => 'deractromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'Dspderactdomi' => array(
				'className' => 'Dsp',
				'foreignKey' => 'deractdomiromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'Dspactrech' => array(
				'className' => 'Dsp',
				'foreignKey' => 'actrechromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'DspRevderact' => array(
				'className' => 'DspRev',
				'foreignKey' => 'deractromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'DspRevderactdomi' => array(
				'className' => 'DspRev',
				'foreignKey' => 'deractdomiromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'DspRevactrech' => array(
				'className' => 'DspRev',
				'foreignKey' => 'actrechromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			/*'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'entreeromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'emploiproposeromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			'Periodeimmersioncui66' => array(
				'className' => 'Periodeimmersioncui66',
				'foreignKey' => 'affectationromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),*/
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'categorieromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			),
			/*'Expprocer93' => array(
				'className' => 'Expprocer93',
				'foreignKey' => 'entreeromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => false
			)*/
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Familleromev3' => array(
				'className' => 'Familleromev3',
				'foreignKey' => 'familleromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Domaineromev3' => array(
				'className' => 'Domaineromev3',
				'foreignKey' => 'domaineromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Metierromev3' => array(
				'className' => 'Metierromev3',
				'foreignKey' => 'metierromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Appellationromev3' => array(
				'className' => 'Appellationromev3',
				'foreignKey' => 'appellationromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Règles de validation non déductibles depuis la base de données.
		 *
		 * @var array
		 */
		public $validate = array(
			'familleromev3_id' => array(
				'notNullIf' => array(
					'rule' => array( 'notNullIf', 'domaineromev3_id', false, array( null ) )
				)
			),
			'domaineromev3_id' => array(
				'notNullIf' => array(
					'rule' => array( 'notNullIf', 'metierromev3_id', false, array( null ) )
				)
			),
			'metierromev3_id' => array(
				'notNullIf' => array(
					'rule' => array( 'notNullIf', 'appellationromev3_id', false, array( null ) )
				)
			)
		);

		/**
		 *
		 * @return array
		 */
		public function options() {
			$Catalogueromev3 = ClassRegistry::init( 'Catalogueromev3' );
			$options = $Catalogueromev3->dependantSelects();
			return array( $this->alias => $options['Catalogueromev3'] );
		}

		/**
		 * Modifie les données passées en paramètre pour que les valeurs correspondent
		 * aux valeurs des listes déroulantes (avec le préfixe de l'id du parent).
		 *
		 * @param array $record
		 * @return array
		 */
		public function prepareFormDataAddEdit( array $record ) {
			$record[$this->alias]['appellationromev3_id'] = "{$record[$this->alias]['metierromev3_id']}_{$record[$this->alias]['appellationromev3_id']}";
			$record[$this->alias]['metierromev3_id'] = "{$record[$this->alias]['domaineromev3_id']}_{$record[$this->alias]['metierromev3_id']}";
			$record[$this->alias]['domaineromev3_id'] = "{$record[$this->alias]['familleromev3_id']}_{$record[$this->alias]['domaineromev3_id']}";

			return $record;
		}
	}
?>