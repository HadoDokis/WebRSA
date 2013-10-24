<?php
	/**
	 * Code source de la classe Tableaud2Helper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Tableaud2Helper fournit des méthodes permettant de construire
	 * le tableau de résultat D2.
	 *
	 * @package app.View.Helper
	 */
	class Tableaud2Helper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Html', 'Locale' );

		/**
		 * Les colonnes du tableau (sachant que pour chacune d'entre elles, on
		 * ajoutera une colonne de pourcentage).
		 *
		 * @var array
		 */
		public $columns = array(
			'nombre',
			'hommes',
			'femmes',
			'cer',
		);

		/**
		 *
		 * @param array $data
		 * @return string
		 */
		public function numberCells( array $data ) {
			$cells = '';

			foreach( $this->columns as $column ) {
				// Valeur
				$cells .= $this->Html->tag(
					'td',
					$this->Locale->number( Hash::get( $data, $column ) ),
					array( 'class' => 'number' )
				);

				// Pourcentage
				$cells .= $this->Html->tag(
					'td',
					$this->Locale->number( Hash::get( $data, "{$column}_%" ), 2 ),
					array( 'class' => 'number' )
				);
			}

			return $cells;
		}

		/**
		 * Retourne une partie de tableau lorsqu'on n'a qu'une seule catégorie.
		 *
		 * @param string $categorie
		 * @param array $results
		 * @return string
		 */
		public function line1Categorie( $categorie, array $results ) {
			$cells = '';

			$cells .= $this->Html->tag( 'th', $categorie, array( 'colspan' => 3 ) );

			$cells .= $this->numberCells( $results[$categorie] );

			return $this->Html->tag( 'tr', $cells );
		}

		/**
		 * Retourne une partie de tableau lorsqu'on a deux catégories.
		 *
		 * @todo: sous-totaux
		 *
		 * @param string $categorie
		 * @param array $results
		 * @param array $categories
		 * @return string
		 */
		public function line2Categorie( $categorie, array $results, array $categories ) {
			$rows = '';
			$i = 0;

			$total = array();
			foreach( $this->columns as $column ) {
				$total[$column] = $total["{$column}_%"] = 0;
			}

			foreach( $results[$categorie] as $label => $data ) {
				$cells = '';

				if( $i == 0 ) {
					$cells .= $this->Html->tag( 'th', $categorie, array( 'rowspan' => count( Hash::flatten( $categories[$categorie] ) ) + 1 ) );
				}

				$cells .= $this->Html->tag( 'th', $label, array( 'colspan' => 2 ) );
				$cells .= $this->numberCells( $data );

				foreach( $this->columns as $column ) {
					$total[$column] += $data[$column];
					$total["{$column}_%"] += $data["{$column}_%"];
				}

				$rows .= $this->Html->tag( 'tr', $cells );

				$i++;
			}

			// Total
			$rows .= $this->Html->tag(
				'tr',
				$this->Html->tag( 'th', "Total {$categorie}", array( 'colspan' => 2, 'class' => 'total' ) )
				.$this->numberCells( $total )
			);

			return $rows;
		}

		/**
		 * Retourne une partie de tableau lorsqu'on a trois catégories.
		 *
		 * @todo: sous-totaux
		 *
		 * @param string $categorie
		 * @param array $results
		 * @param array $categories
		 * @return string
		 */
		public function line3Categorie( $categorie, array $results, array $categories ) {
			$rows = '';
			$i1 = 0;

			foreach( $results[$categorie] as $label1 => $data1 ) {
				$i2 = 0;

				$total = array();
				foreach( $this->columns as $column ) {
					$total[$column] = $total["{$column}_%"] = 0;
				}


				foreach( $data1 as $label2 => $data2 ) {
					$cells = '';

					if( $i1 == 0 ) {
						$cells .= $this->Html->tag( 'th', $categorie, array( 'rowspan' => count( Hash::flatten( $categories[$categorie] ) ) + count( array_keys( $results[$categorie] ) ) ) );
					}

					if( $i2 == 0 ) {
						$cells .= $this->Html->tag( 'th', $label1, array( 'rowspan' => count( array_keys( $data1 ) ) ) );
					}

					$cells .= $this->Html->tag( 'th', $label2 );
					$cells .= $this->numberCells( $data2 );

					foreach( $this->columns as $column ) {
						$total[$column] += $data2[$column];
						$total["{$column}_%"] += $data2["{$column}_%"];
					}

					$rows .= $this->Html->tag( 'tr', $cells );

					$i1++;
					$i2++;
				}

				// Total
				$rows .= $this->Html->tag(
					'tr',
					$this->Html->tag( 'th', "Total {$label1}", array( 'class' => 'total', 'colspan' => 2 ) )
					.$this->numberCells( $total )
				);
			}

			return $rows;
		}
	}
?>