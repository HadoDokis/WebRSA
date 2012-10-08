<?php
	class Offreinsertion extends AppModel
	{
		public $name = 'Offreinsertion';

		public $useTable = false;

		/**
		*
		*/

		public function search( $params ) {
			/// Conditions de base
			$conditions = array();

			/// Critères
			$actionname = Set::extract( $params, 'Search.Actioncandidat.name' );
			$partenaire_id = Set::extract( $params, 'Search.Partenaire.id' );
			$contact_id = Set::extract( $params, 'Search.Contactpartenaire.id' );
			$codepartenaire = Set::extract( $params, 'Search.Partenaire.codepartenaire' );
			$themecode = Set::extract( $params, 'Search.Actioncandidat.themecode' );
			$codefamille = Set::extract( $params, 'Search.Actioncandidat.codefamille' );
			$numcodefamille = Set::extract( $params, 'Search.Actioncandidat.numcodefamille' );

			if( !empty( $actionname ) ){
				$conditions[] = 'Actioncandidat.id = \''.Sanitize::clean( $actionname ).'\'';
			}
			
			if( is_numeric( $partenaire_id ) ){
				$conditions[] = 'Partenaire.id = \''.Sanitize::clean( $partenaire_id ).'\'';
			}

			if( is_numeric( $contact_id ) ){
				$conditions[] = 'Contactpartenaire.id = \''.Sanitize::clean( $contact_id ).'\'';
			}
			
			if( !empty( $codepartenaire ) ){
				$conditions[] = 'Partenaire.codepartenaire = \''.$codepartenaire.'\'';
			}

			if( is_numeric( $themecode ) ){
				$conditions[] = 'Actioncandidat.themecode = \''.Sanitize::clean( $themecode ).'\'';
			}
			
			if( !empty( $codefamille ) ){
				$conditions[] = 'Actioncandidat.codefamille ILIKE \''.$codefamille.'\'';
			}
			
			if( !empty( $numcodefamille ) ){
				$conditions[] = 'Actioncandidat.numcodefamille = \''.Sanitize::clean( $numcodefamille ).'\'';
			}

			$Actioncandidat = ClassRegistry::init( 'Actioncandidat' );
			$query = array(
				'fields' => array_merge(
					$Actioncandidat->fields(),
					$Actioncandidat->Contactpartenaire->fields(),
					$Actioncandidat->Contactpartenaire->Partenaire->fields(),
					$Actioncandidat->Chargeinsertion->fields(),
					$Actioncandidat->Secretaire->fields(),
					array(
						$Actioncandidat->Contactpartenaire->sqVirtualField( 'nom_candidat' ),
						$Actioncandidat->Partenaire->sqVirtualField( 'adresse' ),
						$Actioncandidat->Secretaire->sqVirtualField( 'nom_complet' ),
						$Actioncandidat->Chargeinsertion->sqVirtualField( 'nom_complet' ),
						$Actioncandidat->Fichiermodule->sqNbFichiersLies( $Actioncandidat, 'nb_fichiers_lies' )
					)
				),
				'joins' => array(
					$Actioncandidat->join( 'Contactpartenaire', array( 'LEFT OUTER' ) ),
					$Actioncandidat->Contactpartenaire->join( 'Partenaire', array( 'LEFT OUTER' ) ),
					$Actioncandidat->join( 'Chargeinsertion', array( 'LEFT OUTER' ) ),
					$Actioncandidat->join( 'Secretaire', array( 'LEFT OUTER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions,
				'order' => array( 'Actioncandidat.name ASC' )
			);

			return $query;
		}
	}
?>