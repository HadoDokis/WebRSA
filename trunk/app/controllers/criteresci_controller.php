<?php
    App::import('Sanitize');

    class CriteresciController extends AppController
    {
        public $name = 'Criteresci';

        public $uses = array( 'Cohorteci', 'Action', 'Contratinsertion', 'Option', 'Referent' );

        public $aucunDroit = array( 'constReq', 'ajaxreferent' );

        public $helpers = array( 'Csv', 'Ajax' );

        /**
        *
        */

        public function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

		/**
		*
		*/

        protected function _setOptions() {
            $struct = ClassRegistry::init( 'Structurereferente' )->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );

            $personne_suivi = $this->Contratinsertion->find(
                'list',
                array(
                    'fields' => array(
                        'Contratinsertion.pers_charg_suivi',
                        'Contratinsertion.pers_charg_suivi'
                    ),
                    'order' => 'Contratinsertion.pers_charg_suivi ASC',
                    'group' => 'Contratinsertion.pers_charg_suivi',
                )
            );
            $this->set( 'personne_suivi', $personne_suivi );
            $this->set( 'natpf', $this->Option->natpf() );

            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );
            $this->set( 'numcontrat', $this->Contratinsertion->allEnumLists() );

            $this->set( 'action', $this->Action->find( 'list' ) );
        }

        /**
        * Ajax pour lien référent - structure référente
        */

        public function _selectReferents( $structurereferente_id ) {
            $conditions = array();

            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = $structurereferente_id;
            }

            $referents = $this->Referent->find(
                'all',
                array(
					'fields' => array( 'Referent.id', 'Referent.nom', 'Referent.prenom' ),
                    'conditions' => $conditions,
                    'recursive' => -1
                )
            );
            return $referents;

        }

        /**
        *
        */

        public function ajaxreferent() { // FIXME
            Configure::write( 'debug', 2 );
            $referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Filtre.structurereferente_id' ) );
            $options = array( '<option value=""></option>' );
            foreach( $referents as $referent ) {
                $options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
            } ///FIXME: à mettre dans la vue
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }

		/**
		*
		*/

        public function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );
			}

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {

                $this->Cohorteci->begin(); // Pour les jetons

                $this->paginate = $this->Cohorteci->search( null, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $contrats = $this->paginate( 'Contratinsertion' );

                $this->Cohorteci->commit();

                $this->set( 'contrats', $contrats );
            }

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Zonegeographique' )->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Adresse' )->listeCodesInsee() );
			}

            /// Population du select référents liés aux structures
            $conditions = array();
            $structurereferente_id = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );

            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );
            }

            $referents = $this->Referent->find(
                'all',
                array(
                    'recursive' => -1,
                    'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
                    'conditions' => $conditions
                )
            );

            if( !empty( $referents ) ) {
                $ids = Set::extract( $referents, '/Referent/id' );
                $values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
                $referents = array_combine( $ids, $values );
            }

            $this->set( 'referents', $referents );
            $this->_setOptions();
        }

		/**
        * Export du tableau en CSV
        */

        public function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Cohorteci->search( null, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $contrats = $this->Contratinsertion->find( 'all', $querydata );

            /// Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Contratinsertion.structurereferente_id' );
            $referents = $this->Referent->referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'contrats' ) );
            $this->_setOptions();
        }
    }
?>
