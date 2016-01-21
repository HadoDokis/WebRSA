<?php
	/**
	 * Code source de la classe WebrsaCohortesNonorientes66ImpressionsNewComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractCohortesImpressionsComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesNonorientes66ImpressionsNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesNonorientes66ImpressionsNewComponent extends WebrsaAbstractCohortesImpressionsComponent
	{
		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * @return array
		 */
		protected function _optionsEnums( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			
			if( !isset( $Controller->Nonoriente66 ) ) {
				$Controller->loadModel( 'Nonoriente66' );
			}
			
			$options = parent::_optionsEnums( $params );
			$options = array_merge(
				$options,
				$Controller->Nonoriente66->Historiqueetatpe->enums()
			);
			
			return $options;
		}
		
		/**
		 * Modifie la requête pour ramener la clé primaire de l'enregistrement,
		 * le document PDF et le chemin cmspath dans les résultats.
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		protected function _queryPdfs( array $query, array $params ) {
			$Controller = $this->_Collection->getController();
			
			// Champs nécessaires
			$query['fields'] = array(
				'Personne.id',
			);
			// On récupère les Personne.id de la recherche
			$ids = Hash::extract($Controller->{$params['modelName']}->find('all', $query), '{n}.Personne.id');
			
			// On récupère les données nécéssaire au remplissage du PDF
			$query = $Controller->Nonoriente66->getDataForPdf();
			$query['conditions']['Personne.id'] = $ids;
			
			return $query;
		}

		/**
		 * Retourne un array de PDF, sous la clé $params['documentPath'] (par défaut : Pdf.document) 
		 * à partir du query, ou le nombre de documents n'ayant pas pu être imprimés.
		 *
		 * @param array $query
		 * @param array $params
		 * @return integer|array
		 */
		protected function _pdfs( array $query, array $params ) {
			$Controller = $this->_Collection->getController();
			
			$query = $this->_queryPdfs( $query, $params );

			$Controller->{$params['modelName']}->forceVirtualFields = true;
			$datas = $Controller->{$params['modelName']}->find( 'all', $query );
			
			// Traductions
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Personne' => array(
					'qual' => $Option->qual()
				)
			);
			
			$pdf = $Controller->Nonoriente66->ged(
				array( 'cohorte' => $datas ),
				$Controller->Nonoriente66->modeleOdt($datas),
				true,
				$options
			);
			
			$results = array(
				'Pdf' => array(
					'document' => $pdf
				),
				'Personne' => array(
					'id' => $query['conditions']['Personne.id']
				)
			);
			
			return $results;
		}
		
		/**
		 * Post-traitement des résultats de la requête (par exemple pour la mise
		 * à jour d'une date d'impression).
		 * Cette fonction doit retourner vrai pour que l'envoi se fasse.
		 *
		 * @param array $results - Contenu du retour de la fonction _pdfs()
		 * @param array $params
		 * @return boolean
		 */
		protected function _postProcess( array $results, array $params ) {
			$Controller = $this->_Collection->getController();
			
			foreach ($results['Personne']['id'] as $personne_id) {
				$Controller->Nonoriente66->saveImpression($personne_id, $Controller->Session->read( 'Auth.User.id' ));
			}
		 
			return parent::_postProcess($results, $params);
		}
		
		/**
		 * Surcharge de la méthode _concat(), il n'y a pas besoin de concaténation dans cette cohorte
		 *
		 * @param integer|array $results
		 * @param array $params
		 * @return string
		 */
		protected function _concat( $results, array $params ) {
			return Hash::get($results, $params['documentPath']);
		}
	}
?>