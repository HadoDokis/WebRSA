<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Repddtefp');

	class RepddtefpTestCase extends CakeAppModelTestCase {

		function test_query() {
			$sql = 'SELECT sexe FROM personnes WHERE id = 1;';
			$result = $this->Repddtefp->_query(null);
			$this->assertEqual('M', $result);

			$sql = 'SELECT sexe FROM personnes WHERE id = 1337;';
			$result = $this->Repddtefp->_query($sql);
			$this->assertFalse($result);
		}

		function test_conditionsTemporelles() {
			$annee = '2009';
			$semestre = '1';
			$result = $this->Repddtefp->_conditionsTemporelles($annee, $semestre);
			$this->assertTrue($result);

			$annee = '2009';
			$semestre = '2';
			$result = $this->Repddtefp->_conditionsTemporelles($annee, $semestre);
			$this->assertTrue($result);
		}

		function test_nbrPersonnesInstruitesParSexe() {
			$annee = '2009';
			$semestre = '1';
			$sexe = 'M';
			$numcomptt = '12345';
			$result = $this->Repddtefp->_nbrPersonnesInstruitesParSexe($annee, $semestre, $sexe, $numcomptt);
			$this->assertTrue($result);
		}

		function test_nbrPersonnesInstruitesParTrancheDAge() {
			$annee = '2009';
			$semestre = '1';
			$ageMin = '10';
			$ageMax = '99';
			$numcomptt = '12345';
			$result = $this->Repddtefp->_nbrPersonnesInstruitesParTrancheDAge($annee, $semestre, $ageMin, $ageMax, $numcomptt);
			$this->assertTrue($result);
		}

		function testListeSexe() {
			$annee = '2009';
			$semestre = '1';
			$numcomptt = '12345';
			$result = $this->Repddtefp->listeSexe($annee, $semestre, $numcomptt);
			$this->assertTrue($result);
		}

	}

?>va
