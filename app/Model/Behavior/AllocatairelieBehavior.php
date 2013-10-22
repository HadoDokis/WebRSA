<?php
	/**
	 * Code source de la classe AllocatairelieBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe AllocatairelieBehavior fournit les méthodes personneId et
	 * dossierId permettant respectivement d'obtenir la clé primaire d'un
	 * allocataire ou celle d'un dossier RSA à partir de la clé primaire d'un
	 * enregistrement lié à l'allocataire.
	 *
	 * @package app.Model.Behavior
	 */
	class AllocatairelieBehavior extends ModelBehavior
	{
		/**
		 * Retourne la clé primaire du dossier RSA de l'allocataire auquel est
		 * lié un enregistrement.
		 *
		 * @param Model $Model Le modèle qui utilise ce behavior
		 * @param integer $id La clé primaire de l'enregistrement lié
		 * @return integer
		 */
		public function dossierId( Model $Model, $id ) {
			$querydata = array(
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
                    $Model->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Model->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$Model->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $Model->find( 'first', $querydata );

			return Hash::get( $result, 'Foyer.dossier_id' );
		}

		/**
		 * Retourne la clé primaire de l'allocataire auquel est lié un
		 * enregistrement.
		 *
		 * @param Model $Model Le modèle qui utilise ce behavior
		 * @param integer $id La clé primaire de l'enregistrement lié
		 * @return integer
		 */
		public function personneId( Model $Model, $id ) {
			$querydata = array(
				'fields' => array( "{$Model->alias}.personne_id" ),
				'conditions' => array(
					"{$Model->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $Model->find( 'first', $querydata );

			return Hash::get( $result, "{$Model->alias}.personne_id" );
		}
	}
?>