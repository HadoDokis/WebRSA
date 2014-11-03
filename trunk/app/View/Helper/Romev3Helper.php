<?php
	/**
	 * Code source de la classe Romev3Helper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Romev3Helper ...
	 *
	 * @package app.View.Helper
	 */
	class Romev3Helper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault',
				'useBuffer' => false
			),
			'Observer' => array(
				'className' => 'Prototype.PrototypeObserver',
				'useBuffer' => false
			)
		);

		/**
		 *
		 * @param array $params
		 * @return string
		 */
		public function fieldset( array $params = array() ) {
			$params += array(
				'domain' => $this->request->params['controller'],
				'modelName' => Inflector::classify( $this->request->params['controller'] ),
				'options' => array(),
				'prefix' => null,
				'suffix' => null,
				'id' => null,
				'fieldset' => true
			);

			$fieldsetPath = implode( '.', Hash::filter( array( $params['modelName'], $params['prefix'], $params['suffix'] ) ) );

			if( $params['id'] === null ) {
				$params['id'] = $this->domId( $fieldsetPath );
			}

			$return = $this->Default3->subform(
				array(
					"{$params['modelName']}.{$params['prefix']}familleromev3{$params['suffix']}_id" => array( "options" => $params['options'][$params['modelName']]["{$params['prefix']}familleromev3{$params['suffix']}_id"], 'empty' => true ),
					"{$params['modelName']}.{$params['prefix']}domaineromev3{$params['suffix']}_id" => array( "options" => $params['options'][$params['modelName']]["{$params['prefix']}domaineromev3{$params['suffix']}_id"], 'empty' => true ),
					"{$params['modelName']}.{$params['prefix']}metierromev3{$params['suffix']}_id" => array( "options" => $params['options'][$params['modelName']]["{$params['prefix']}metierromev3{$params['suffix']}_id"], 'empty' => true ),
					"{$params['modelName']}.{$params['prefix']}appellationromev3{$params['suffix']}_id" => array( "options" => $params['options'][$params['modelName']]["{$params['prefix']}appellationromev3{$params['suffix']}_id"], 'empty' => true )
				),
				array(
					"options" => $params['options']
				)
			);

			$return .= $this->Observer->dependantSelect(
				array(
					"{$params['modelName']}.{$params['prefix']}familleromev3{$params['suffix']}_id" => "{$params['modelName']}.{$params['prefix']}domaineromev3{$params['suffix']}_id",
					"{$params['modelName']}.{$params['prefix']}domaineromev3{$params['suffix']}_id" => "{$params['modelName']}.{$params['prefix']}metierromev3{$params['suffix']}_id",
					"{$params['modelName']}.{$params['prefix']}metierromev3{$params['suffix']}_id" => "{$params['modelName']}.{$params['prefix']}appellationromev3{$params['suffix']}_id",
				)
			);

			if( Hash::get( $params, 'fieldset' ) ) {
				$return = $this->Default3->DefaultHtml->tag(
					'fieldset',
					$this->Default3->DefaultHtml->tag( 'legend', __d( $params['domain'], $fieldsetPath ) )
					.$return
				);
			}

			return $return;
		}
	}
?>