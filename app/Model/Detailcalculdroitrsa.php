<?php
	/**
	 * Code source de la classe Detailcalculdroitrsa.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Detailcalculdroitrsa ...
	 *
	 * @package app.Model
	 */
	class Detailcalculdroitrsa extends AppModel
	{
		public $name = 'Detailcalculdroitrsa';

		public $validate = array(
			'detaildroitrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'detaildroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Champs virtuels.
		 *
		 * @var array
		 */
		public $virtualFields = array(
			'natpf_socle' => array(
				'type'      => 'boolean',
				'postgres'  => '"%s"."natpf" IN ( \'RSD\', \'RSI\', \'RSU\', \'RSB\', \'RSJ\' )'
			),
			'natpf_activite' => array(
				'type'      => 'boolean',
				'postgres'  => '"%s"."natpf" IN ( \'RCD\', \'RCI\', \'RCU\', \'RCB\', \'RCJ\' )'
			),
			'natpf_majore' => array(
				'type'      => 'boolean',
				'postgres'  => '"%s"."natpf" IN ( \'RSI\', \'RCI\' )'
			),
		);

		/**
		 * Retourne le dernier détail du droit rsa d'un dossier RSA
		 *
		 * @param string $detaildroitrsaIdField
		 * @return string
		 */
		public function sqDernier( $detaildroitrsaIdField = 'Detaildroitrsa.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'detailscalculsdroitsrsa.id'
					),
					'alias' => 'detailscalculsdroitsrsa',
					'conditions' => array(
						"detailscalculsdroitsrsa.detaildroitrsa_id = {$detaildroitrsaIdField}"
					),
					'order' => array( 'detailscalculsdroitsrsa.ddnatdro DESC' ),
					'limit' => 1
				)
			);
		}

		/**
		 * Champs virtuels pour connaître la nature de la prestation en une fois.
		 *
		 * @param string $alias
		 * @param array $conditions
		 * @return array
		 */
		public function vfsSummary( $alias = null, $conditions = array( 'Detailcalculdroitrsa.detaildroitrsa_id = Detaildroitrsa.id' ) ) {
			$alias = ( is_null( $alias ) ? $this->alias : $alias );

			$vfNatpf = array();
			foreach( array( 'socle', 'activite', 'majore' ) as $natpf ) {
				$vfNatpf[$natpf] = $this->sq(
					array(
						'fields' => array(
							"{$this->alias}.{$this->primaryKey}"
						),
						'conditions' => array_merge(
							$conditions,
							array(
								$this->sqVirtualfield(
									"natpf_{$natpf}",
									false
								)
							)
						)
					)
				);
				$vfNatpf[$natpf] = "( EXISTS( {$vfNatpf[$natpf]} ) ) AS \"{$alias}__natpf_{$natpf}\"";
			}

			return $vfNatpf;
		}
	}
?>