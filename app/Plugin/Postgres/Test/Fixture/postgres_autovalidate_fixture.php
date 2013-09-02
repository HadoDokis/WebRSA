<?php
	/**
	 * Code source de la classe PostgresAutovalidateFixture.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Cette classe permet d'ajouter des contraintes de CHECK aux tables créées
	 * pour les fixtures à partir des contraintes de CHECK se trouvant dans l'attribut
	 * $constraints.
	 *
	 * Cette classe est uniquement destinée à être sous-classée et ne fonctionne
	 * qu'avec le driver PostgreSQL.
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 */
	abstract class PostgresAutovalidateFixture extends CakeTestFixture
	{

		/**
		 * Liste des noms de contraintes CHECK pour la table, avec la mise en
		 * contrainte en base de données.
		 *
		 * Les fonctions retournent un boolean:
		 *	- cakephp_validate_in_list (text, text[])
		 *	- cakephp_validate_in_list (integer, integer[])
		 *	- cakephp_validate_inclusive_range (double precision, double precision, double precision)
		 *	- cakephp_validate_range (double precision, double precision, double precision)
		 *
		 * @var array
		 */
		public $constraints = array();

		/**
		 * Nombre de passages dans la fonction init()
		 *
		 * @var integer
		 */
		public $inits = 0;

		/**
		 * Ajoute les contraintes de CHECK à la table lors de l'initialisation.
		 *
		 * @param DataSource $db
		 * @return type
		 */
		public function create( $db ) {
			$success = parent::create( $db );

			if( $this->inits == 0 ) {
				if( !empty( $this->constraints ) ) {
					foreach( $this->constraints as $constraintName => $constraintCheckClause ) {
						try {
							$sql = "ALTER TABLE {$this->table} ADD CONSTRAINT {$constraintName} CHECK ( {$constraintCheckClause} );";
							$db->query( $sql );
						}
						catch( Exception $e ) {
							debug( $e->getMessage() );
							return false;
						}
					}
				}

				$this->inits++;
			}

			return $success;
		}
	}
?>
