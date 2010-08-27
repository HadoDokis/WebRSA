<?php
	class EpsController extends AppController
	{
		public $name = 'Eps';

		public $uses = array( 'Ep', 'Zonegeographique' );

		/**
		* @access public
		*/

		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Eps:index',
			'add' => 'Eps:edit'
		);

        /**
        *
        */

        protected function _options() {
//             $options = $this->{$this->modelClass}->enums();
            $options['Zonegeographique'] = $this->Zonegeographique->find( 'list' );
//             $options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->find( 'list' );

            return $options;
        }


        /**
        *   Index pour les paramétrages des EPs
        */

        public function indexparams() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

            $compteurs = array(
                'Ep' => ClassRegistry::init( 'Ep' )->find( 'count' ),
                'Fonctionpartep' => ClassRegistry::init( 'Fonctionpartep' )->find( 'count' ),
                'Partep' => ClassRegistry::init( 'Partep' )->find( 'count' ),
                'Seanceep' => ClassRegistry::init( 'Seanceep' )->find( 'count' ),
            );
            $this->set( compact( 'compteurs' ) );
        }

        /**
        *   Liste des EPs mais pour les demandes de réorientation
        */

        public function liste() {
            $this->set( 'options', $this->_options() );

            if( !empty( $this->data ) ) {
                $eps = $this->Default->search( array( 'Ep.name' => 'LIKE', 'Ep.localisation' => 'LIKE' ), $this->data );
            }
        }

        /**
        *   Ordre du jour
        */

        function ordre( $ep_id = null ) {

            $ep = $this->Ep->findById( $ep_id, null, null, 2 );
            $this->assert( !empty( $ep ), 'invalidParameter' );

//             $parcoursdetectes = $this->Ep->Parcoursdetecte->find( 'all', array( 'conditions' => array( 'Parcoursdetecte.ep_id' => $ep_id ), 'recursive' => 2 ) );

            /// FIXME
//             $ep = Set::insert( $ep, 'Parcoursdetecte', $parcoursdetectes );
//debug( $ep );
            // Si finalisation de l'ordre du jour,
            // --> on modifie la valeur de validordre et on ferme l'ordre du jour
            if( isset( $this->params['form']['Valid'] ) ) {
                $eps_update = array();
                if( $ep['Ep']['validordre'] == 0 ) {
                    $eps_update[] = array(
                        'Ep' => array(
                            'id' => $ep['Ep']['id'],
                            'validordre' => 1
                        )
                    );
                }
                $this->Ep->saveAll( $eps_update );
                $this->redirect( array( 'controller' => 'eps', 'action' => 'liste', '/Search__active:1' ) );
            }

//             $eppartep = $this->EpPartep->findByEpId( $ep_id, null, null, 0 );

//             $participants = $this->Ep->Partep->find( 'list' );
//             $roles = $this->Ep->Rolepartep->find( 'list' );

            $this->set( compact( 'eppartep', 'ep', 'participants'/*, 'roles'*/ ) );
        }

		/**
		*
		*/

		public function index() {
            $this->set(
                Inflector::tableize( $this->modelClass ),
                $this->paginate( $this->modelClass )
            );
			$this->{$this->modelClass}->recursive = 0;
			$this->Default->search(
				$this->data
			);
		}

		/**
		*
		*/

		public function view( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->view( $id );
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

		public function edit( $id = null ) {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function _add_edit( $id = null ) {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            $options = $this->_options();
            $this->set( 'options', $options );
            $zglist = $this->Zonegeographique->find( 'list' );
            $this->set( 'zglist', $zglist );
			$this->{$this->modelClass}->recursive = -1;
            $this->Default->_add_edit( $id, null, null, array( 'action' => 'index' ) );
//             $this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id = null ) {
			$this->Default->delete( $id, array( 'action' => 'index' ) );
		}
	}
?>
