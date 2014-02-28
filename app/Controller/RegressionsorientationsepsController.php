<?php
	/**
	 * Code source de la classe RegressionsorientationsepsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe RegressionsorientationsepsController ...
	 *
	 * @package app.Controller
	 */
	class RegressionsorientationsepsController extends AppController
	{

		public $helpers = array( 'Default2', 'Xpaginator' );

		public $uses = array( 'Regressionorientationep58' );

//		public $components = array( 'Search.SearchPrg' => array( 'actions' => array( 'index' ) ) );

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			$this->modelClass = 'Regressionorientationep'.Configure::read( 'Cg.departement' );
			parent::beforeFilter();
		}

		/**
		*
		*/

//		public function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Search.SearchPrg' => array( 'actions' => array( 'index' ) ) ) );
//			parent::__construct();
//		}

		/**
		 * Suppression d'un dossier d'EP pour cette thématique dès lors que ce dossier ne possède pas
		 * de passage en commission EP.
		 *
		 * @param integer $regressionorientationep_id L'id de l'entrée dans la table de la thématique.
		 * @return void
		 */
		public function delete( $regressionorientationep_id ) {
			$this->{$this->modelClass}->begin();

			$regressionorientationep = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						"{$this->modelClass}.id" => $regressionorientationep_id
					),
					'contain' => array(
						'Dossierep' => array(
							'Passagecommissionep'
						)
					)
				)
			);

			// L'enregistrement existe bien
			$this->assert( !empty( $regressionorientationep ), 'error404' );

			// Le dossier ne possède pas encore de passage en commission
			$this->assert( empty( $regressionorientationep['Dossierep']['Passagecommissionep'] ), 'error500' );

			$success = $this->{$this->modelClass}->Dossierep->delete( $regressionorientationep[$this->modelClass]['dossierep_id'] );

			$this->_setFlashResult( 'Delete', $success );
			if ( $success ) {
				$this->{$this->modelClass}->commit();
			}
			else {
				$this->{$this->modelClass}->rollback();
			}
			$this->redirect( $this->referer() );
		}
	}

?>