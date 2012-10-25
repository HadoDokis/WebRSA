<?php
	/**
	 * Code source de la classe Histochoixcer93Test.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Histochoixcer93', 'Model' );

	/**
	 * Classe Histochoixcer93Test.
	 *
	 * @package app.Test.Case.Model
	 */
	class Histochoixcer93Test extends CakeTestCase
	{
		/**
		 * Fixtures utilisées dans ce test unitaire.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Histochoixcer93',
		);

		/**
		 * Méthode exécutée avant chaque test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$this->Histochoixcer93 = ClassRegistry::init( 'Histochoixcer93' );
		}

		/**
		 * Méthode exécutée après chaque test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Histochoixcer93 );
		}

		/**
		 * Test de la méthode Histochoixcer93::prepareFormData()
		 *
		 * @return void
		 */
		public function testPrepareFormData() {
			// 1°) Ajout à l'étape 03attdecisioncg
			$contratinsertion = array(
				'Cer93' => array(
					'id' => 1,
					'Histochoixcer93' => array(
						array(
							'id' => '1',
							'cer93_id' => '1',
							'user_id' => '1',
							'commentaire' => 'Commentaire ...',
							'formeci' => 'S',
							'etape' => '02attdecisioncpdv',
							'prevalide' => null,
							'decisioncs' => null,
							'decisioncadre' => null,
							'datechoix' => '2012-10-24',
							'created' => '2012-10-24 11:44:38',
							'modified' => '2012-10-24 11:44:38',
						),
					),
				),
			);
			$result = $this->Histochoixcer93->prepareFormData( $contratinsertion, '03attdecisioncg', 1 );
			$expected = array (
				'Histochoixcer93' =>  array (
					'cer93_id' => 1,
					'user_id' => 1,
					'etape' => '03attdecisioncg',
					'formeci' => 'S',
					'commentaire' => 'Commentaire ...',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2°) Modification à l'étape 03attdecisioncg
			$contratinsertion = array(
				'Cer93' => array(
					'id' => 1,
					'Histochoixcer93' => array(
						array(
							'id' => '1',
							'cer93_id' => '1',
							'user_id' => '1',
							'commentaire' => 'Commentaire ...',
							'formeci' => 'S',
							'etape' => '02attdecisioncpdv',
							'prevalide' => null,
							'decisioncs' => null,
							'decisioncadre' => null,
							'datechoix' => '2012-10-24',
							'created' => '2012-10-24 11:44:38',
							'modified' => '2012-10-24 11:44:38',
						),
						array(
							'id' => '2',
							'cer93_id' => '1',
							'user_id' => '2',
							'commentaire' => 'Commentaire ...',
							'formeci' => 'S',
							'etape' => '03attdecisioncg',
							'prevalide' => null,
							'decisioncs' => null,
							'decisioncadre' => null,
							'datechoix' => '2012-10-24',
							'created' => '2012-10-24 12:44:38',
							'modified' => '2012-10-24 12:44:38',
						),
					),
				),
			);
			$result = $this->Histochoixcer93->prepareFormData( $contratinsertion, '03attdecisioncg', 1 );
			$expected = array(
				'Histochoixcer93' => array(
					'id' => '2',
					'cer93_id' => '1',
					'user_id' => '2',
					'commentaire' => 'Commentaire ...',
					'formeci' => 'S',
					'etape' => '03attdecisioncg',
					'prevalide' => NULL,
					'decisioncs' => NULL,
					'decisioncadre' => NULL,
					'datechoix' => '2012-10-24',
					'created' => '2012-10-24 12:44:38',
					'modified' => '2012-10-24 12:44:38',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Histochoixcer93::saveDecision()
		 *
		 * @return void
		 */
		public function testSaveDecision() {
			$data = array(
				'Histochoixcer93' => array(
					'id' => '',
					'cer93_id' => '3',
					'user_id' => '6',
					'formeci' => 'S',
					'commentaire' => 'drsg',
					'datechoix' => array(
						'day' => '25',
						'month' => '10',
						'year' => '2012'
					),
					'etape' => '02attdecisioncpdv'
				)
			);
			$result = $this->Histochoixcer93->saveDecision( $data );
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
