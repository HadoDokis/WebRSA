<?php
	class EpsController extends AppController
	{
		var $name = 'Eps';
        var $uses = array( 'Ep', 'Partep', 'EpPartep', 'Motifdemreorient', 'Demandereorient'/*, 'Rolepartep'*/ );
        var $helpers = array( 'Locale' );
//         var $components = array( 'Jetonsfonctions' );


		public $components = array(
			'Prg' => array(
				'actions' => array( 'index', 'liste', 'detection' )
			),
            'Jetonsfonctions'
		);

		/**
		*
		*/

		public function beforeFilter() {
			parent::beforeFilter();

            $options = $this->Ep->EpPartep->allEnumLists();
//             $options = Set::merge( $options, $this->Comiteapre->ComiteapreParticipantcomite->allEnumLists() );
            $this->set( 'options', $options );

            $options = array();
            foreach( array( /*'Rolepartep',*/ 'EpPartep'/*, 'Partep'*/ ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }
            $this->set( 'motifdemreorient', $this->Motifdemreorient->find( 'list' ) );
            $this->set( compact( 'options' ) );

		}

        /**
        *
        */

        public function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }
            $this->Default->index();
        }

        /**
        *   Index pour les paramétrages des EPs
        */

        public function indexparams() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }
        }

		/**
		*   Liste des EPs mais pour les demandes de réorientation
		*/

		public function liste() {
            if( !empty( $this->data ) ) {
                $eps = $this->Default->search( array( 'Ep.name' => 'LIKE', 'Ep.localisation' => 'LIKE' ), $this->data );
            }
		}


        /**
        *   Liste des EPs mais pour les détections de parcours
        */

        public function detection( $operations = array() ) {
            if( !empty( $this->data ) ) {
                $eps = $this->Default->search( $operations, $this->data );
            }
        }

        /**
        *   Ordre du jour
        */

        function ordre( $ep_id = null ) {

            $ep = $this->Ep->findById( $ep_id, null, null, 2 );
            $this->assert( !empty( $ep ), 'invalidParameter' );

            $parcoursdetectes = $this->Ep->Parcoursdetecte->find( 'all', array( 'conditions' => array( 'Parcoursdetecte.ep_id' => $ep_id ), 'recursive' => 2 ) );

            /// FIXME
            $ep = Set::insert( $ep, 'Parcoursdetecte', $parcoursdetectes );
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

            $eppartep = $this->EpPartep->findByEpId( $ep_id, null, null, 0 );

            $participants = $this->Ep->Partep->find( 'list' );
            $roles = $this->Ep->Rolepartep->find( 'list' );

            $this->set( compact( 'eppartep', 'ep', 'participants'/*, 'roles'*/ ) );
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

        function _add_edit(){
            $args = func_get_args();
			$this->Default->{$this->action}( $args );
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
	}
?>