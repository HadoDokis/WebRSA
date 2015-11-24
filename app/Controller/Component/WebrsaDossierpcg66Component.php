<?php
	/**
	 * Code source de la classe WebrsaDossierpcg66Component.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Component.php.
	 */

	/**
	 * La classe WebrsaDossierpcg66Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaDossierpcg66Component extends Component
	{
		/**
		 * Permet de remplir un formulaire de cohorte
		 * 
		 * @return array
		 */
		public function formulaireCohorte_heberge() {
			$Controller = $this->_Collection->getController();
			
			$fields = array(
				'Personne.id' => array( 'type' => 'hidden', 'hidden' => true ),
				'Foyer.id' => array( 'type' => 'hidden', 'hidden' => true ),
				
				// Dossierpcg
				'Dossierpcg66.typepdo_id' => array( 'empty' => true, 'type' => 'select' ),
				'Dossierpcg66.datereceptionpdo' => array( 
					'type' => 'date', 
					'dateFormat' => 'DMY', 
					'minYear' => date('Y')-1, 
					'maxYear' => date('Y')+1  
				),
				'Dossierpcg66.originepdo_id' => array( 'empty' => true, 'type' => 'select' ),
				'Dossierpcg66.orgpayeur' => array( 'empty' => true, 'type' => 'select' ),
				'Dossierpcg66.serviceinstructeur_id' => array( 'empty' => true, 'type' => 'select' ),
				'Dossierpcg66.haspiecejointe' => array( 'type' => 'hidden', 'value' => '0' ),
				'Dossierpcg66.commentairepiecejointe' => array( 'empty' => true, 'type' => 'textarea' ),
				'Dossierpcg66.poledossierpcg66_id' => array( 'empty' => true, 'type' => 'select' ),
				'Dossierpcg66.user_id' => array( 'empty' => true, 'type' => 'select', 'value' => $Controller->Session->read( 'Auth.User.id' ) ),
				'Dossierpcg66.dateaffectation' => array( 
					'type' => 'date', 
					'dateFormat' => 'DMY', 
					'minYear' => date('Y')-1, 
					'maxYear' => date('Y')+1  
				),

				// Personnepcg
				'Situationpdo.Situationpdo' => array(
					'type' => 'select', 
					'multiple' => 'checkbox', 
					'class' => 'divideInto2Collumn', 
					'fieldset' => true 
				),
				'Statutpdo.Statutpdo' => array(
					'type' => 'select', 
					'multiple' => 'checkbox', 
					'class' => 'divideInto2Collumn', 
					'fieldset' => true 
				),
				
				// Traitement
				'Traitementpcg66.typetraitement' => array( 'type' => 'radio', 'value' => 'courrier' ),
				'Traitementpcg66.typecourrierpcg66_id' => array( 'empty' => true, 'type' => 'select' ),
				'Traitementpcg66.affiche_couple' => array( 'type' => 'checkbox' ),
				'Traitementpcg66.haspiecejointe' => array( 'type' => 'hidden', 'value' => '0' ),
				'Traitementpcg66.serviceinstructeur_id' => array( 'empty' => true, 'type' => 'select' ),
				'Traitementpcg66.personnepcg66_situationpdo_id' => array( 'empty' => true, 'type' => 'select' ),
				'Traitementpcg66.descriptionpdo_id' => array( 'empty' => true, 'type' => 'select' ),
				'Traitementpcg66.datedepart' => array( 
					'type' => 'date', 
					'dateFormat' => 'DMY', 
					'minYear' => date('Y')-1, 
					'maxYear' => date('Y')+1  
				),
				'Traitementpcg66.datereception' => array( 
					'type' => 'date', 
					'dateFormat' => 'DMY', 
					'minYear' => date('Y')-1, 
					'maxYear' => date('Y')+1  
				),
				'Traitementpcg66.dureeecheance' => array( 'empty' => true, 'type' => 'select' ),
				'Traitementpcg66.dateecheance' => array( 
					'type' => 'date', 
					'dateFormat' => 'DMY', 
					'minYear' => date('Y')-1, 
					'maxYear' => date('Y')+1  
				),
				
				// Tag
				'Tag.modele' => array( 'type' => 'hidden', 'value' => 'Foyer' ),
				'Tag.valeurtag_id' => array(
					'type' => 'select', 
					'multiple' => 'checkbox', 
					'class' => 'divideInto2Collumn', 
					'fieldset' => true 
				),
				'Tag.calcullimite' => array( 'type' => 'select', 'empty' => true, 'options' => Configure::read($Controller->name.'.'.$Controller->action.'.range_date_butoir') ),
				'Tag.limite' => array( 
					'dateFormat' => 'DMY', 'type' => 'date', 'maxYear' => date('Y')+4 
				),
				'Tag.commentaire' => array( 'type' => 'textarea' ),
			);
			
			return $this->_autoLabel(
				$this->_chooseAndHide( 
					$fields 
				)
			);
		}
		
		/**
		 * Application automatique des labels
		 * 
		 * @param array $fields
		 * @return array
		 */
		protected function _autoLabel( array $fields ) {
			$results = array();
			foreach( $fields as $field => $param ) {
				if ( Hash::get($param, 'type') !== 'hidden' ) {
					$param['label'] = __m($field);
				}
				$results[$field] = $param;
			}
			return $results;
		}
		
		/**
		 * Applique la conf choose_and_hide aux champs envoyé
		 * 
		 * @param array $fields
		 * @return array
		 */
		protected function _chooseAndHide( $fields ) {
			$Controller = $this->_Collection->getController();
			$keyConf = implode('.', array($Controller->name, $Controller->action, 'options', 'choose_and_hide'));
			foreach ((array)Configure::read($keyConf) as $key => $value) {
				$fields[$key]['value'] = $value;
				$fields[$key]['type'] = 'hidden';
			}
			return $fields;
		}
	}
?>