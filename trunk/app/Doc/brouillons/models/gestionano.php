<?php
	/**
	* FIXME: Gestionanobdd (gestionanosbdd)
	*/

	class Gestionano extends AppModel
	{
		public $name = 'Gestionano';

		public $useTable = false;

		/**
		*
		*/

		public function constraints() {
			$models = Set::normalize( array( 'Adressefoyer', 'Dossier', 'Foyer', 'Personne', 'Prestation' ) );
			foreach( array_keys( $models ) as $model ) {
				$modelClass = ClassRegistry::init( $model );
				$modelClass->Behaviors->attach( 'Pgsqlcake.Schema' );
				$models[$model] = $modelClass;
			}

			return array(
				'Adressefoyer' => array(
					'adressesfoyers_foyer_id_rgadr_idx' => $models['Adressefoyer']->hasUniqueIndex( array( 'foyer_id', 'rgadr' ), 'adressesfoyers_foyer_id_rgadr_idx' ),
					'adressesfoyers_actuelle_rsa_idx' => $models['Adressefoyer']->hasUniqueIndex( array( 'foyer_id', 'rgadr' ), 'adressesfoyers_actuelle_rsa_idx' ),
					'adressesfoyers_adresse_id_idx' => $models['Adressefoyer']->hasUniqueIndex( 'adresse_id' ),
					// TODO: ALTER TABLE adressesfoyers ADD CONSTRAINT adressesfoyers_rgadr_correct_chk CHECK ( rgadr IN ( '01', '02', '03' ) );
					'adressesfoyers_rgadr_correct_chk' => $models['Adressefoyer']->hasCheck( 'adressesfoyers_rgadr_correct_chk' ),
				),
				'Dossier' => array(
					// TODO: DROP INDEX IF EXISTS dossiers_rsa_numdemrsa_idx; CREATE UNIQUE INDEX dossiers_numdemrsa_idx ON dossiers (numdemrsa);
					'dossiers_numdemrsa_idx' => $models['Dossier']->hasUniqueIndex( 'numdemrsa' ),
				),
				'Foyer' => array(
					// TODO: DROP INDEX IF EXISTS foyers_dossier_id_idx; DROP INDEX IF EXISTS foyers_dossier_rsa_id_idx; CREATE UNIQUE INDEX foyers_dossier_id_idx ON foyers (dossier_id);
					'foyers_dossier_id_idx' => $models['Foyer']->hasUniqueIndex( 'dossier_id' ),
				),
				'Personne' => array(
					// TODO(?): Création d'une colonne nir_brut où on met les nir actuels, nettoyage des nirs actuels avec contrainte dessus
					/*// TODO: ALTER TABLE personnes ADD CONSTRAINT personnes_nir_correct_chk CHECK ( nir IS NULL OR nir_correct13( nir ) );
					'personnes_nir_correct_chk' => $models['Personne']->hasCheck( 'personnes_nir_correct13_chk' ),*/
					// TODO: ALTER TABLE personnes ADD CONSTRAINT personnes_nom_correct_chk CHECK ( nom ~ E'^[A-Z]+([A-Z \'\-]*)[A-Z]+$' );
					'personnes_nom_correct_chk' => $models['Personne']->hasCheck( 'personnes_nom_correct_chk' ),
					'personnes_prenom_correct_chk' => $models['Personne']->hasCheck( 'personnes_prenom_correct_chk' ),
					'personnes_nomnai_correct_chk' => $models['Personne']->hasCheck( 'personnes_nomnai_correct_chk' ),
					'personnes_prenom2_correct_chk' => $models['Personne']->hasCheck( 'personnes_prenom2_correct_chk' ),
					'personnes_prenom3_correct_chk' => $models['Personne']->hasCheck( 'personnes_prenom3_correct_chk' ),
					// TODO: CREATE UNIQUE INDEX personnes_unique_par_foyer_idx ON personnes (foyer_id, nom, prenom, dtnai);
					'personnes_unique_par_foyer_idx' => $models['Personne']->hasUniqueIndex( array( 'foyer_id', 'nom', 'prenom', 'dtnai', 'nir' ) ),
				),
				'Prestation' => array(
					'prestations_allocataire_rsa_idx' => $models['Prestation']->hasUniqueIndex( array( 'personne_id', 'natprest', 'rolepers' ), 'prestations_allocataire_rsa_idx' ),
					'prestations_personne_id_natprest_idx' => $models['Prestation']->hasUniqueIndex( array( 'personne_id', 'natprest' ) ),
				),
			);
		}
	}
?>