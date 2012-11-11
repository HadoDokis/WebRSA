<?php	
	/**
	 * Code source de la classe Propodecisioncer66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Propodecisioncer66 ...
	 *
	 * @package app.Model
	 */
	class Propodecisioncer66 extends AppModel
	{
		public $name = 'Propodecisioncer66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'isvalidcer' => array( 'type' => 'no' ),
					'nonvalidationparticulier'
				)
			),
			'Formattable'
		);
		
		public $validate = array(
			'isvalidcer' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);
		
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		public $hasAndBelongsToMany = array(
			'Motifcernonvalid66' => array(
				'className' => 'Motifcernonvalid66',
				'joinTable' => 'motifscersnonvalids66_proposdecisioncers66',
				'foreignKey' => 'propodecisioncer66_id',
				'associationForeignKey' => 'motifcernonvalid66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Motifcernonvalid66Propodecisioncer66'
			)
		);
		
		
		/**
		 * BeforeSave
		 */
		public function beforeSave( $options = array( ) ) {
			$return = parent::beforeSave( $options );
		
			//  MAJ de la position du cER
			if( !empty( $this->data ) ) {

				$contratinsertion_id = $this->data['Propodecisioncer66']['contratinsertion_id'];
				$contratinsertion = $this->Contratinsertion->find(
					'first',
					array(
						'conditions' => array(
							'Contratinsertion.id' => $contratinsertion_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				
				if( $contratinsertion['Contratinsertion']['positioncer'] == 'attvalidpart' ) {
					$this->Contratinsertion->updateAll(
						array( 'Contratinsertion.positioncer' => '\'attvalidpartpropopcg\'' ),
						array(
							'"Contratinsertion"."id"' => $contratinsertion_id
						)
					);
				}
			}
			return $return;
		}
		
		/**
		* Sauvegarde des décisions du CER dans la table proposdecisionscers66
		*
		* @param array $data Les données du CER à sauvegarder.
		* @return boolean True en cas de succès, false sinon.
		* @access public
		*/

		public function sauvegardeCohorteCer( $data ) {
			$propodecisioncer66 = array();
			if( !empty( $data ) ) {
				foreach( $data as $value ){
					if( isset( $value['decision_ci'] ) ){
						if( $value['decision_ci'] == 'V' ) {
							$propodecisioncer66 = array(
								'Propodecisioncer66' => array(
									'isvalidcer' => 'O',
									'datevalidcer' => $value['datedecision'],
									'contratinsertion_id' => $value['id']
								)
							);
							$this->create( $propodecisioncer66 );
							$saved = $this->save();
						}
						else if( $value['decision_ci'] == 'N' ) {
							$propodecisioncer66 = array(
								'Propodecisioncer66' => array(
									'isvalidcer' => 'N',
									'datevalidcer' => $value['datedecision'],
									'contratinsertion_id' => $value['id']
								)
							);
							$this->create( $propodecisioncer66 );
							$saved = $this->save();
						}
					}
				}
			}
			return $saved;
		}
	}
?>