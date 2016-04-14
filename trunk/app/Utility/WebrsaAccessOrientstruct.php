<?php
	/**
	 * Code source de la classe WebrsaAccessOrientstruct.
	 *
	 * PHP 5.3
	 *
	 * @package app.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe WebrsaAccessOrientstruct ...
	 *
	 * @package app.Utility
	 */
	abstract class WebrsaAccessOrientstruct
	{
		/**
		 *
		 * @param array $params
		 * @return array
		 */
		public static function params( array $params = array() ) {
			return $params + array(
				'alias' => 'Orientstruct',
				'departement' => (int)Configure::read( 'Cg.departement' ),
				'ajout_possible' => null,
				'reorientationseps' => null
			);
		}

		/**
		 * On ne peut modifier que l'entrée la plus récente.
		 * Au CG 66, on ne peut modifier que la dernière orientation de statut
		 * "Orienté" (celle dont le rang est le plus élevé);
		 *
		 * Champs virtuels: dernier, dernier_oriente
		 *
		 * @todo champs virtuels dernier et dernier_oriente dans Orientstruct
		 *	(le charger à la demande uniquement ?)... était rgorient et $rgorientMax
		 *
		 * @param array $record
		 * @param array $params
		 * @return type
		 */
		protected static function _edit( array $record, array $params ) {
			$result = Hash::get( $record, "{$params['alias']}.dernier" ) == true
				&& Hash::get( $params, 'ajout_possible' ) == true;

			if( 66 == $params['departement'] ) {
				// Délai de modification orientation (10 jours par défaut)
				$date_valid = Hash::get( $record, "{$params['alias']}.date_valid" );
				$nbheure = Configure::read( 'Periode.modifiableorientation.nbheure' );
				$periodeblock = !empty( $date_valid )
					&& ( time() >= ( strtotime( $date_valid ) + 3600 * $nbheure ) );

				$result = $result
					&& $periodeblock == false
					&& Hash::get( $record, "{$params['alias']}.dernier_oriente" ) == true;
			}

			return $result;
		}

		/**
		 * On ne peut imprimer que certaines orientations (dans la table PDF pour
		 * les départements qui stockent).
		 *
		 * Champs virtuels: printable
		 *
		 * @param array $record
		 * @param array $params
		 * @return type
		 */
		protected static function _impression( array $record, array $params ) {
			return Hash::get( $record, "{$params['alias']}.printable" ) == 1;
		}

		/**
		 *
		 * Champs virtuels: dernier, dernier_oriente, linked_records
		 *
		 * @todo champs virtuels dernier et dernier_oriente dans Orientstruct
		 *	(le charger à la demande uniquement ?)... était rgorient et $rgorientMax
		 *
		 * @param array $record
		 * @param array $params
		 * @return type
		 */
		protected static function _delete( array $record, array $params ) {
			return Hash::get( $record, "{$params['alias']}.dernier" ) == true
				&& Hash::get( $record, "{$params['alias']}.dernier_oriente" ) == true
				&& Hash::get( $record, "{$params['alias']}.linked_records" ) == false
				&& empty( Hash::get( $params, 'reorientationseps' ) );
		}

		/**
		 * Peut-on imprimer la notif de changement de référent ou non ?
		 * Si 1ère orientation non sinon ok.
		 *
		 * Champs virtuels: premier_oriente
		 *
		 * @todo champs virtuels dernier et dernier_oriente dans Orientstruct
		 *	(le charger à la demande uniquement ?)... était rgorient et $rgorientMax
		 *
		 * @param array $record
		 * @param array $params
		 * @return type
		 */
		protected static function _impression_changement_referent( array $record, array $params ) {
			return Hash::get( $record, "{$params['alias']}.premier_oriente" ) == true
				&& Hash::get( $record, "{$params['alias']}.notifbenefcliquable" ) == true;
		}

		/**
		 *
		 * @param array $params
		 * @return array
		 */
		public static function actions( array $params = array() ) { // TODO: en attributs ?
			$params = self::params( $params );
			$result = array( 'edit', 'impression', 'delete' );

			if( 66 == $params['departement'] ) {
				$result[] = 'impression_changement_referent';
			}

			return $result;
		}

		/**
		 *
		 * @param array $record
		 * @param array $params
		 * @return array
		 */
		public final static function access( array $record, array $params = array() ) {
			$params = self::params( $params );
			$actions = self::actions( $params );

			foreach( $actions as $action ) {
				$method = "_{$action}";
				$record[$params['alias']][$action] = self::check($action, $record, $params);
			}

			return $record;
		}

		/**
		 *
		 * @param array $records
		 * @param array $params
		 * @return array
		 */
		public final static function accesses( array $records, array $params = array() ) {
			foreach( array_keys( $records ) as $key ) {
				$records[$key] = access( $records[$key], $params );
			}

			return $records;
		}

		/**
		 *
		 * @param string $action
		 * @param array $record
		 * @param array $params
		 * @return boolean
		 */
		public final static function check( $action, array $record, array $params = array() ) {
			$method = "_{$action}";

			return method_exists( __CLASS__, $method )
				&& in_array( $action, self::actions( $params ) )
				&& call_user_func_array(
					array( __CLASS__, $method ),
					array( $record, self::params( $params ) )
				);
		}
	}
?>