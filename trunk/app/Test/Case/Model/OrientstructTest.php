<?php
	/**
	 * Code source de la classe OrientstructTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Orientstruct', 'Model' );

	/**
	 * La classe OrientstructTest réalise les tests unitaires de la classe Orientstruct.
	 *
	 * @package app.Test.Case.Model
	 */
	class OrientstructTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Dossier',
			'app.Foyer',
			'app.Orientstruct',
			'app.Pdf', // FIXME: mock
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Referent',
			'app.Structurereferente',
			'app.Typeorient',
			'app.User',
		);

		/**
		 * Le modèle à tester.
		 *
		 * @var Orientstruct
		 */
		public $Orientstruct = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			// On mock la méthode ged()
			$this->Orientstruct = $this->getMock(
				'Orientstruct',
				array( 'ged' ),
				array( array( 'ds' => 'test' ) )
			);
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Orientstruct );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Orientstruct::beforeSave() pour une première entrée.
		 */
		public function testBeforeSavePremiereOrientation() {
			// 1. Sans autre donnée que l'id de la personne
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => '',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => '',
					'origine' => null,
					'rgorient' => null,
					'date_valid' => null,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Avec une orientation "En attente"
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'En attente',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'En attente',
					'rgorient' => null,
					'date_valid' => null,
					'origine' => null,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Avec une orientation "Non orienté"
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'Non orienté',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'Non orienté',
					'rgorient' => null,
					'date_valid' => null,
					'origine' => null,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Avec une orientation "Orienté"
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'rgorient' => 1,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Orientstruct::beforeSave() lors de la modification
		 * d'une orientation.
		 */
		public function testBeforeSaveModificationOrientation() {
			// 0. Enregistrement de la première orientation
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$success = $this->Orientstruct->save();
			$this->assertTrue( !empty( $success ) );

			$id = $this->Orientstruct->id;

			// 1. Modification de l'orientation de la personne
			$data = array(
				'Orientstruct' => array(
					'id' => $id,
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'id' => $id,
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
					'rgorient' => 1,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Orientstruct::beforeSave() pour une seconde entrée
		 * "Orienté".
		 */
		public function testBeforeSaveSecondeOrientation() {
			// 0. Enregistrement de la première orientation
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$success = $this->Orientstruct->save();
			$this->assertTrue( !empty( $success ) );

			// 1. Ajout d'une nouvelle orientation à la personne
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
					'rgorient' => 2,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Orientstruct::beforeSave() pour une seconde entrée
		 * "Orienté" alors que la première est "En attente".
		 */
		public function testBeforeSavePremiereOrientationSecondeEntree() {
			// 0. Enregistrement de la première orientation
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'En attente',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$success = $this->Orientstruct->save();
			$this->assertTrue( !empty( $success ) );

			// 1. Enregistrement de la seconde orientation
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'En attente',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$success = $this->Orientstruct->save();
			$this->assertTrue( !empty( $success ) );

			$id = $this->Orientstruct->id;

			// 2. Modification de la seconde orientation de la personne
			$data = array(
				'Orientstruct' => array(
					'id' => $id,
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'id' => $id,
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
					'rgorient' => 1,
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$success = $this->Orientstruct->save();
			$this->assertTrue( !empty( $success ) );

			// 3. Ajout d'une troisième orientation à la personne
			$data = array(
				'Orientstruct' => array(
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
					'origine' => 'manuelle',
				)
			);
			$this->Orientstruct->create( $data );
			$this->assertTrue( $this->Orientstruct->beforeSave() );
			$result = $this->Orientstruct->data;
			$expected = array(
				'Orientstruct' => array(
					'valid_cg' => false,
					'statutrelance' => 'E',
					'typenotification' => 'normale',
					'personne_id' => 1,
					'statut_orient' => 'Orienté',
					'typeorient_id' => 1,
					'structurereferente_id' => 1,
					'haspiecejointe' => '0',
					'rgorient' => 2,
					'origine' => 'reorientation',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
