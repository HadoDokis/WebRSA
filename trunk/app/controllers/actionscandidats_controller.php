<?php

    class ActionscandidatsController extends AppController 
    {
        var $name = 'Actionscandidats';
        var $uses = array( 'Actioncandidat', 'ActioncandidatPersonne', 'ActioncandidatPartenaire',
        	'Option', 'Personne' ,'Referent'
         );
        var $helpers = array( 'Xform', 'Default', 'Theme' );
        var $components = array( 'Default' );
        
		var $commeDroit = array(
			'view' => 'Actionscandidats:index',
			'add' => 'Actionscandidats:edit'
		);

        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $options = array();
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( compact( 'options', 'typevoie' ) );
            return $return;
        }

        
       	protected function _setOptions() {
			$options = $this->Actioncandidat->enums();
    		if( $this->action != 'index' ) {
				$options['Actioncandidat']['referent_id'] = $this->Referent->find('list');
				$options['Zonegeographique'] = $this->Actioncandidat->Zonegeographique->find( 'list' );
			}			
			$this->set( compact( 'options' ) );
		}        
        
        
        /**
        *   Ajout à la suite de l'utilisation des nouveaux helpers
        *   - default.php
        *   - theme.php
        */

        public function index() {
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

        function _add_edit(){
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
