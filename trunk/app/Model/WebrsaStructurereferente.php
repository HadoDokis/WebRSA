<?php
	/**
	 * Code source de la classe WebrsaStructurereferente.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe WebrsaStructurereferente s'occupe de la logique métier des
	 * structures référentes.
	 *
	 * @package app.Model
	 */
	class WebrsaStructurereferente extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaStructurereferente';

		/**
		 * On n'utilise pas de table.
		 *
		 * @var mixed
		 */
		public $useTable = false;

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Structurereferente'
		);

		/**
		 * Recherche des structures dans le paramétrage de l'application.
		 *
		 * @param array $search
		 * @return array
		 */
		public function search( array $search ) {
			// 1. Query de base
			if( false === $this->Structurereferente->Behaviors->attached( 'Occurences' ) ) {
				$this->Structurereferente->Behaviors->attach( 'Occurences' );
			}

			$query = array(
				'fields' => array_merge(
					$this->Structurereferente->fields(),
					$this->Structurereferente->Typeorient->fields(),
					array(
						$this->Structurereferente->sqHasLinkedRecords()
					)
				),
				'order' => array( 'Structurereferente.lib_struc ASC' ),
				'joins' => array(
					$this->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1,
				'conditions' => array()
			);

			// 2. Conditions
			// 2.1. Valeurs approchantes
			foreach( array( 'lib_struc', 'ville' ) as $field ) {
				$value = (string)Hash::get( $search, "Structurereferente.{$field}" );
				if( '' !== $value ) {
					$query['conditions'][] = 'Structurereferente.'.$field.' ILIKE \''.$this->Structurereferente->wildcard( $value ).'\'';
				}
			}

			// 2.1. Valeurs exactes
			foreach( array( 'typeorient_id', 'actif', 'typestructure', 'contratengagement', 'apre', 'orientation', 'pdo', 'cui' ) as $field ) {
				$value = (string)Hash::get( $search, "Structurereferente.{$field}" );
				if( '' !== $value ) {
					$query['conditions'][] = array( "Structurereferente.{$field}" => $value );
				}
			}

			return $query;
		}
	}
?>