<?php
	App::import( 'Model', array( 'Structurereferente' ) );

	class Covstructurereferente extends Structurereferente
	{
		public $name = 'Covstructurereferente';
		public $useTable = 'structuresreferentes';

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			return null;
		}
	}
?>