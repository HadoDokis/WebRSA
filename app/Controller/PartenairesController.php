<?php
	/**
	 * Code source de la classe PartenairesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
     App::import('Behaviors', 'Occurences');

	/**
	 * La classe PartenairesController ...
	 *
	 * @package app.Controller
	 */
	class PartenairesController extends AppController
	{
		public $name = 'Partenaires';
		public $uses = array( 'Partenaire', 'ActioncandidatPartenaire', 'Option', 'Personne' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array(
			'Default',
			'Search.SearchPrg' => array( 'actions' => array( 'index' ) )
		);

		public $commeDroit = array(
			'view' => 'Partenaires:index',
			'add' => 'Partenaires:edit',
			'ajax_coordonnees' => 'Partenaires:edit',
		);


		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		protected function _setOptions() {
			// Options
            $Options = ClassRegistry::init( 'Option' );
			$options = array();
			$options = array(
				'Personne' => array(
					'qual' => $Options->qual()
				),
                'Partenaire'=> array(
					'typevoie' => $Options->typevoie()
				),
			);

			$secteursactivites = $this->Partenaire->Contactpartenaire->Actioncandidat->Cui->Personne->Dsp->Libsecactderact66Secteur->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Libsecactderact66Secteur.code' )
				)
			);
			$this->set( 'secteursactivites', $secteursactivites );

			$options[$this->modelClass]['raisonsocialepartenairecui66_id'] = $this->Partenaire->Raisonsocialepartenairecui66->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Raisonsocialepartenairecui66.name DESC' )
				)
			);

			if( Configure::read( 'CG.cantons' ) ) {
				$Canton = ClassRegistry::init( 'Canton' );
				$this->set( 'cantons', $Canton->selectList() );
			}

			$options = Set::merge(
				$this->Partenaire->enums(),
				$options
			);

			$this->set( compact( 'options' ) );
		}

		public function index() {

			if( !empty( $this->request->data ) ) {
                $this->Partenaire->Behaviors->attach( 'Occurences' );
                $querydata = $this->Partenaire->search( $this->request->data );
                $querydata = $this->Partenaire->qdOccurencesExists( $querydata );
                $this->paginate = $querydata;
                $partenaires = $this->paginate( 'Partenaire' );
                $this->set( compact('partenaires'));
			}
			$this->_setOptions();
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $partenaire_id = null ){
			$args = func_get_args();
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'partenaires', 'action' => 'index' ) );
			}


			if( $this->action == 'edit') {
				// Vérification du format de la variable
				if( !$this->Partenaire->exists( $partenaire_id ) ) {
					throw new NotFoundException();
				}
			}

			// Tentative de sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Partenaire->begin();
				if( $this->Partenaire->saveAll( $this->request->data ) ) {
					$this->Partenaire->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Partenaire->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit') {
				$this->request->data = $this->Partenaire->find(
					'first',
					array(
						'conditions' => array(
							'Partenaire.id' => $partenaire_id
						),
						'contain' => false
					)
				);
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
// 			$this->Default->{$this->action}( $args );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->Default->view( $id );
		}

		/**
		 * Permet de récupérer les informations d'un partenaire sous forme de JSON
		 * 
		 * @param Number $id
		 */
		public function ajax_coordonnees( $id ){
			$fields = $this->Partenaire->fields();
			$fields[] = 'Raisonsocialepartenairecui66.name';
			$query = array(
				'fields' => $fields,
				'recursive' => -1,
				'joins' => array(
					$this->Partenaire->join( 'Raisonsocialepartenairecui66' )
				),
				'conditions' => array( 'Partenaire.id' => $id )
			);
			
			$json = $this->Partenaire->find('first', $query);
			$json['Partenaire']['raisonsociale'] = $json['Raisonsocialepartenairecui66']['name'];
			unset($json['Raisonsocialepartenairecui66']);

			$this->set( compact( 'json' ) );
			$this->layout = 'ajax';
			$this->render( '/Elements/json' );
		}
	}
?>