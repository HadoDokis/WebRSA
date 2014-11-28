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
				'dependent' => true
			),
			'Dspderactdomi' => array(
				'className' => 'Dsp',
				'foreignKey' => 'deractdomiromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'Dspactrech' => array(
				'className' => 'Dsp',
				'foreignKey' => 'actrechromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'DspRevderact' => array(
				'className' => 'DspRev',
				'foreignKey' => 'deractromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'DspRevderactdomi' => array(
				'className' => 'DspRev',
				'foreignKey' => 'deractdomiromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'DspRevactrech' => array(
				'className' => 'DspRev',
				'foreignKey' => 'actrechromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'entreeromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			/*'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'emploiproposeromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'Periodeimmersioncui66' => array(
				'className' => 'Periodeimmersioncui66',
				'foreignKey' => 'affectationromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'categorieromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'Expprocer93' => array(
				'className' => 'Expprocer93',
				'foreignKey' => 'entreeromev3_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
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

		public function options() {
			$Catalogueromev3 = ClassRegistry::init( 'Catalogueromev3' );
			$options = $Catalogueromev3->dependantSelects();
			return array( $this->alias => $options['Catalogueromev3'] );
		}
	}
?>