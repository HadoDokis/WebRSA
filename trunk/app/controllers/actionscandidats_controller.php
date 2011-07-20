<?php

    class ActionscandidatsController extends AppController 
    {
        public $name = 'Actionscandidats';
        public $uses = array( 'Actioncandidat', 'Option' );
        public $helpers = array( 'Xform', 'Default', 'Theme' );
        public $components = array( 'Default' );
        
		public $commeDroit = array(
			'view' => 'Actionscandidats:index',
			'add' => 'Actionscandidats:edit'
		);

        /**
        *
        */

        public function beforeFilter() {
            $return = parent::beforeFilter();
            $options = array();
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( compact( 'options', 'typevoie' ) );
            return $return;
        }

        
       	protected function _setOptions() {
			$options = $this->Actioncandidat->enums();
    		if( $this->action != 'index' ) {
				$options['Actioncandidat']['referent_id'] = $this->Actioncandidat->ActioncandidatPersonne->Referent->find('list');
				
				$options['Zonegeographique'] = $this->Actioncandidat->Zonegeographique->find( 'list' );
				$zonesselected = $this->Actioncandidat->Zonegeographique->find( 'list', array( 'fields' => array( 'id' ) ) );
				$this->set( compact( 'zonesselected' ) );
                $this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );

				//FIXME : Nb magique !!
                $options['Actioncandidat']['chargeinsertion_id'] = $this->Actioncandidat->Chargeinsertion->find('list', array( 'fields' => array( 'id', 'nom_complet' ), 'conditions' => array(  'Chargeinsertion.nom IS NOT NULL', 'Chargeinsertion.group_id = 7' ) ) );
                $options['Actioncandidat']['secretaire_id'] = $this->Actioncandidat->Secretaire->find('list', array( 'fields' => array( 'id', 'nom_complet' ), 'conditions' => array(  'Secretaire.nom IS NOT NULL', 'Secretaire.group_id = 7' ) ) );
                
			}
			
            foreach( array( 'Contactpartenaire') as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }


// 			debug($options);
			$this->set( compact( 'options' ) );
		}

        /**
        *   Ajout à la suite de l'utilisation des nouveaux helpers
        *   - default.php
        *   - theme.php
        */

        public function index() {

            $this->Actioncandidat->forceVirtualFields = true;
            $this->Actioncandidat->recursive = 0;

            $this->paginate = array(
                'contain' => array(
                    'Contactpartenaire' => array(
                        'Partenaire'
                    ),
                    'Chargeinsertion',
                    'Secretaire'
                )
            );

            $this->set(
                Inflector::tableize( $this->modelClass ),
                $this->paginate( $this->modelClass )
            );
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

        protected function _add_edit(){
        	$this->_setOptions();
        	
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
