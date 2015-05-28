<?php
	/**
	 * Fichier source de la classe Decisioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractAppModelLieCui66', 'Model/Abstractclass' );

	/**
	 * La classe Decisioncui66 est la classe contenant les avis techniques du CUI pour le CG 66.
	 *
	 * @package app.Model
	 */
	class Decisioncui66 extends AbstractAppModelLieCui66
	{
		/**
		 * Alias de la table et du model
		 * @var string
		 */
		public $name = 'Decisioncui66';
		
		/**
		 * Order des find par défaut
		 * @var type 
		 */
		public $order =  array(
			'Decisioncui66.datedecision' => 'DESC',
			'Decisioncui66.created' => 'DESC'
		);
		
		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'default' => 'CUI/%s/impression.odt',
			'decisionelu' => 'CUI/%s/decisionelu.odt',
			'notifbenef' => 'CUI/%s/notifbenef.odt',
			'notifemployeur' => 'CUI/%s/notifemployeur.odt',
			'attestationcompetence' => 'CUI/%s/attestationcompetence.odt',
		);
		
		/**
		 * Récupère les informations pour l'affichage des propositions dans les décisions
		 * 
		 * @param type $id
		 * @param type $action
		 * @return type
		 */
		public function getPropositions( $id, $action ){
			$query = array(
				'fields' => array_merge(
					$this->Cui66->Propositioncui66->fields()
				),
				'joins' => array(
					$this->Cui66->join( 'Propositioncui66', array( 'type' => 'INNER' ) ),
					$this->Cui66->join( 'Decisioncui66', array( 'type' => 'LEFT OUTER' ) ),
				),
				'order' => array( 'Propositioncui66.created DESC' )
			);
			
			if ( $action === 'add' ){
				$query['conditions']['Cui66.cui_id'] = $id;
			}
			else{
				$query['conditions']['Decisioncui66.id'] = $id;
			}
			
			return $this->Cui66->find( 'all', $query );
		}
		
		/**
		 * Récupère les donnés par defaut dans le cas d'un ajout, ou récupère les données stocké en base dans le cas d'une modification
		 * 
		 * @param integer $cui66_id
		 * @param integer $id
		 * @return array
		 */
		public function prepareAddEditFormData( $cui66_id, $id = null ) {
			// Ajout
			if( empty( $id ) ) {
				$decision['Decisioncui66']['cui66_id'] = $cui66_id;
			}
			// Mise à jour
			else {
				$query['conditions'] = array(
					'Decisioncui66.id' => $id,
					'Decisioncui66.cui66_id' => $cui66_id,
				);
				$decision = $this->find( 'first', $query );
			}
			
			if ( empty($decision) ){
				throw new HttpException(404, "HTTP/1.1 404 Not Found");
			}

			return $decision;
		}
				
		/**
		 * Retourne les options nécessaires au formulaire de recherche, au formulaire,
		 * aux impressions, ...
		 *
		 * @param array $params <=> array( 'allocataire' => true, 'find' => false, 'autre' => false, 'pdf' => false )
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = array();
			
			$optionRefus = $this->enums();
			$optionRefus['Decisioncui66']['motif'] = ClassRegistry::init( 'motifrefuscui66' )->find( 'list' );
			$optionRefus['Propositioncui66']['motif'] = $optionRefus['Decisioncui66']['motif'];

			$options = Hash::merge(
				$options,
				$optionRefus,
				$this->Cui66->Propositioncui66->enums()
			);

			return $options;
		}
	}
?>