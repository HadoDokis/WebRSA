<?php
	/**
	 * Fichier source du modèle Histochoixcer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Histochoixcer93.
	 *
	 * @package app.Model
	 */
	class Histochoixcer93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Histochoixcer93';

		/**
		 * Récursivité.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Validation.Autovalidate',
			'Formattable',
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Cer93' => array(
				'className' => 'Cer93',
				'foreignKey' => 'cer93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			)
		);
		
		/**
		 *
		 */
		public function prepareFormData( $contratinsertion, $etape, $user_id ) {
			$formData = array();

			// Ajout ou modification
			$action = 'add';
			$nbHistochoixcer93 = count( $contratinsertion['Cer93']['Histochoixcer93'] );
			if( $nbHistochoixcer93 > 0 ) {
				$etapeHistochoixcer93 = $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1]['etape'];

				$intEtapeHistochoixcer93 = preg_replace( '/^([0-9]{2}).*$/', '\1', $etapeHistochoixcer93 );
				$intEtape = preg_replace( '/^([0-9]{2}).*$/', '\1', $etape );

				if( !( ( $intEtapeHistochoixcer93 == $intEtape ) || ( $intEtapeHistochoixcer93 == ( $intEtape - 1 ) ) ) ) {
					throw new error500Exception( 'Incohérence des étapes' );
				}

				if( $etapeHistochoixcer93 == $etape ) {
					$action = 'edit';
				}
			}
			
			if( $action == 'add' ) {
				$formData = array(
					'Histochoixcer93' => array(
						'cer93_id' => $contratinsertion['Cer93']['id'],
						'user_id' => $user_id,
						'etape' => $etape,
					)
				);

				// FIXME: pas toujours
				if( ( $nbHistochoixcer93 > 0 ) && $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1] ) {
					foreach( array( 'formeci', 'commentaire' ) as $field ) {
						$formData['Histochoixcer93'][$field] = $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1][$field];
					}
				}
			}
			else {
				$formData = array( 'Histochoixcer93' => $contratinsertion['Cer93']['Histochoixcer93'][$nbHistochoixcer93-1] );
			}

			debug( $formData );
			
			return $formData;
		}

		/**
		 * Sauvegarde des différentes étapes de décisions du CER au cours du workflow.
		 *	Lorsque le CPDV refuse, le contrat est en état final 99rejete
		 *
		 * FIXME: plus les états finaux pour Chargé de suivi et avis cadre
		 */
		public function saveDecision( $data ) {
			$success = $this->save( $data );

			if( $data['Histochoixcer93']['etape'] == '03attdecisioncg' && $data['Histochoixcer93']['isrejet'] ) {
				$success = $this->Cer93->updateAll(
					array( 'Cer93.positioncer' => '\'99rejete\'' ),
					array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
				) && $success;
				
				$this->Cer93->id = $data['Histochoixcer93']['cer93_id'];
				$contratinsertion_id = $this->Cer93->field( 'contratinsertion_id' );

				$success = $this->Cer93->Contratinsertion->updateAll(
					array(
						'Contratinsertion.decision_ci' => '\'R\'',
						'Contratinsertion.datedecision' => '\''.date_cakephp_to_sql( $data['Histochoixcer93']['datechoix'] ).'\''
					),
					array( '"Contratinsertion"."id"' => $contratinsertion_id )
				) && $success;
			}
			else {
				$success = $this->Cer93->updateAll(
					array( 'Cer93.positioncer' => '\''.$data['Histochoixcer93']['etape'].'\'' ),
					array( '"Cer93"."id"' => $data['Histochoixcer93']['cer93_id'] )
				) && $success;
			}

			return $success;
		}
	}
?>