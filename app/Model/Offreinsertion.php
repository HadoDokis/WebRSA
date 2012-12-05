<?php	
	/**
	 * Code source de la classe Offreinsertion.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Offreinsertion ...
	 *
	 * @package app.Model
	 */
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
			$correspondant = Set::extract( $params, 'Search.Actioncandidat.referent_id' );

			if( !empty( $actionname ) ){
				$conditions[] = 'Actioncandidat.id = \''.Sanitize::clean( $actionname, array( 'encode' => false ) ).'\'';
			}
			
			if( is_numeric( $partenaire_id ) ){
				$conditions[] = 'Partenaire.id = \''.Sanitize::clean( $partenaire_id, array( 'encode' => false ) ).'\'';
			}

			if( is_numeric( $contact_id ) ){
				$conditions[] = 'Contactpartenaire.id = \''.Sanitize::clean( $contact_id, array( 'encode' => false ) ).'\'';
			}
			
			if( !empty( $codepartenaire ) ){
				$conditions[] = 'Partenaire.codepartenaire = \''.$codepartenaire.'\'';
			}

			if( is_numeric( $themecode ) ){
				$conditions[] = 'Actioncandidat.themecode = \''.Sanitize::clean( $themecode, array( 'encode' => false ) ).'\'';
			}
			
			if( !empty( $codefamille ) ){
				$conditions[] = 'Actioncandidat.codefamille ILIKE \''.$codefamille.'\'';
			}
			
			if( !empty( $numcodefamille ) ){
				$conditions[] = 'Actioncandidat.numcodefamille = \''.Sanitize::clean( $numcodefamille, array( 'encode' => false ) ).'\'';
			}
			
			if( !empty( $correspondant ) ){
				$conditions[] = 'Actioncandidat.referent_id = \''.Sanitize::clean( $correspondant, array( 'encode' => false ) ).'\'';
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
					$Actioncandidat->join( 'Secretaire', array( 'LEFT OUTER' ) ),
					$Actioncandidat->join( 'Referent', array( 'LEFT OUTER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions,
				'order' => array( 'Actioncandidat.name ASC' )
			);

			return $query;
		}
	}
?>