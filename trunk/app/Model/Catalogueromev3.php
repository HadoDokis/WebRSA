<?php
	/**
	 * Code source de la classe Catalogueromev3.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Catalogueromev3 ...
	 *
	 * @package app.Model
	 */
	class Catalogueromev3 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Catalogueromev3';

		/**
		 * Ce modèle n'utilise pas de table.
		 *
		 * @var integer
		 */
		public $useTable = false;

		/**
		 * Liste des modèles de paramétrages des codes ROME V3.
		 *
		 * @var array
		 */
		public $modelesParametrages = array(
			'Familleromev3',
			'Domaineromev3',
			'Metierromev3',
			'Appellationromev3',
			//'Correspondanceromev2v3'
		);
	}
?>