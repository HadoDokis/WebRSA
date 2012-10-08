<?php
	App::uses( 'XShell', 'Console/Command' );
	class DerniersdossiersallocatairesShell extends XShell
	{
		public $uses = array( 'Personne' );

		/**
		 *
		 */
		public function main() {
			$success = true;
			$start = microtime( true );
			$this->Personne->begin();

			$sql = "DROP TABLE IF EXISTS derniersdossiersallocataires CASCADE;
CREATE TABLE derniersdossiersallocataires (
	id 				SERIAL NOT NULL PRIMARY KEY,
	personne_id		INTEGER NOT NULL REFERENCES personnes(id),
	dossier_id		INTEGER NOT NULL REFERENCES dossiers(id)
);
CREATE INDEX derniersdossiersallocataires_personne_id_idx ON derniersdossiersallocataires(personne_id);
CREATE INDEX derniersdossiersallocataires_dossier_id_idx ON derniersdossiersallocataires(dossier_id);
CREATE UNIQUE INDEX derniersdossiersallocataires_personne_id_dossier_id_idx ON derniersdossiersallocataires(personne_id,dossier_id);";
			$this->out( 'Recréation de la table derniersdossiersallocataires' );
			$success = ( $this->Personne->query( $sql ) !== false ) && $success;

			$sql = "INSERT INTO derniersdossiersallocataires (personne_id, dossier_id)
	SELECT
			personnes.id AS personne_id,
			(
				SELECT
						dossiers.id
					FROM personnes AS p2
						INNER JOIN prestations AS pr2 ON (
							p2.id = pr2.personne_id
							AND pr2.natprest = 'RSA'
						)
						INNER JOIN foyers ON (
							p2.foyer_id = foyers.id
						)
						INNER JOIN dossiers ON (
							dossiers.id = foyers.dossier_id
						)
					WHERE
						prestations.rolepers IN ( 'DEM', 'CJT' )
						AND (
							(
								nir_correct13( personnes.nir )
								AND nir_correct13( p2.nir )
								AND SUBSTRING( TRIM( BOTH ' ' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM p2.nir ) FROM 1 FOR 13 )
								AND personnes.dtnai = p2.dtnai
							)
							OR
							(
								UPPER(personnes.nom) = UPPER(p2.nom)
								AND UPPER(personnes.prenom) = UPPER(p2.prenom)
								AND personnes.dtnai = p2.dtnai
							)
						)
					ORDER BY dossiers.dtdemrsa DESC
					LIMIT 1
			) AS dossier_id
		FROM personnes
		INNER JOIN prestations ON (
			personnes.id = prestations.personne_id
			AND prestations.natprest = 'RSA'
		)
		WHERE prestations.rolepers IN ( 'DEM', 'CJT' );";

			$this->out( 'Population de la table derniersdossiersallocataires' );
			$success = ( $this->Personne->query( $sql ) !== false ) && $success;

			if( $success ) {
				$this->Personne->commit();
				$this->out( "Glop" );
			}
			else {
				$this->Personne->rollback();
				$this->err( "Pas glop" );
			}

			$this->out( sprintf( "\nExécuté en %s secondes.", number_format( microtime( true ) - $start, 2, ',', ' ' ) ) );
			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		 *
		 */
		public function help() {
			$this->out( "Usage: cake/console/cake derniersdossiersallocataires" );

			$this->_stop( 0 );
		}
	}
?>