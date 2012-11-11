<?php	
	/**
	 * Code source de la classe IndicateursmensuelsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe IndicateursmensuelsController ...
	 *
	 * @package app.Controller
	 */
	class IndicateursmensuelsController extends AppController
	{
		public $name = 'Indicateursmensuels';

		public function index() {
			$annee = Set::extract( $this->request->data, 'Indicateurmensuel.annee' );
			if( !empty( $annee ) ) {
				$indicateurs = $this->Indicateurmensuel->liste( $annee );
				$this->set( compact( 'indicateurs' ) );
			}
		}
	}
?>