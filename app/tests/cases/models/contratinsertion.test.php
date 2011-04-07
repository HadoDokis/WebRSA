<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Contratinsertion');

	class TestContratinsertion extends Contratinsertion {
	// Attention on surcharge la visibilite du parent
		function _calculPosition($data) {
			return parent::_calculPosition($data);
		}
	}

	class ContratinsertionTestCase extends CakeAppModelTestCase {

		function startTest() {
			parent::startTest();
			$testmodelname = preg_replace( '/TestCase$/', '', get_class( $this ) );
			$testname = 'Test'.$testmodelname;
			$this->{$testname} = ClassRegistry::init( $testname );
		}


		// test fonction beforesave()
		function testBeforeSave() {
			$this->Contratinsertion->data = array(
				'Contratinsertion' => array(
					'id' => '10',
					'personne_id' => '1001',
					'structurereferente_id' => '7',
					'typocontrat_id' => '1',
					'dd_ci' => '2009-05-08',
					'df_ci' => '2012-02-08',
					//'niv_etude' => null,
					'form_compl' => null,
					'aut_expr_prof' => null,
					'rg_ci' => '1',
					'actions_prev' => '0',
					'obsta_renc' => 'aucun',
					'service_soutien' => null,
					'pers_charg_suivi' => null,
					'sect_acti_emp' => null,
					'emp_occupe' => null,
					'duree_hebdo_emp' => null,
					'nat_cont_trav' => null,
					'duree_cdd' => null,
					'duree_engag' => '3',
					'decision_ci' => 'E',
					'datevalidation_ci' => null,
					'expr_prof' => null,
					'diplomes' => null,
					'objectifs_fixes' => null,
					'engag_object' => null,
					'nature_projet' => null,
					'observ_ci' => null,
					'date_saisi_ci' => null,
					'lieu_saisi_ci' => null,
					'emp_trouv' => null,
					'forme_ci' => null,
					'commentaire_action' => null,
					'raison_ci' => null,
					'aviseqpluri' => null,
					'sitfam_ci' => null,
					'sitpro_ci' => null,
					'observ_benef' => null,
					'referent_id' => null,
					// 'current_action' => null,
					'avisraison_ci' => null,
					'type_demande' => null,
					'num_contrat' => null,
					'typeinsertion' => null,
					'bilancontrat' => null,
					'engag_object_referent' => null,
					'outilsmobilises' => null,
					'outilsamobiliser' => null,
					'niveausalaire' => null,
					'zonegeographique_id' => '35',
				),
			);
			$result = $this->Contratinsertion->beforeSave($option=array());
			$this->assertTrue($result);
		}

		// test fonction valider()
		function testValider() {
			$datas = array(
				'Nonrespectsanctionep93' => array(
					'id' => '1',
					'dossierep_id' => '1',
					'propopdo_id' => null,
					'orientstruct_id' => '1001',
					'contratinsertion_id' => '10',
					'origine' => 'orientstruct',
					'decision' => null,
					'rgpassage' => '1',
					'montantreduction' => null,
					'dureesursis' => null,
					'sortienvcontrat' => 0,
					'active' => 1,
					'created' => '2010-11-04',
					'modified' => '2010-11-04',
				),
				'Contrainsertion' => array(
					'id' => '10',
					'personne_id' => '1001',
					'structurereferente_id' => '7',
					'typocontrat_id' => '1',
					'dd_ci' => '2009-05-08',
					'df_ci' => '2012-02-08',
					//'niv_etude' => null,
					'form_compl' => null,
					'aut_expr_prof' => null,
					'rg_ci' => '1',
					'actions_prev' => '0',
					'obsta_renc' => 'aucun',
					'service_soutien' => null,
					'pers_charg_suivi' => null,
					'sect_acti_emp' => null,
					'emp_occupe' => null,
					'duree_hebdo_emp' => null,
					'nat_cont_trav' => null,
					'duree_cdd' => null,
					'duree_engag' => '3',
					'decision_ci' => 'E',
					'datevalidation_ci' => null,
					'expr_prof' => null,
					'diplomes' => null,
					'objectifs_fixes' => null,
					'engag_object' => null,
					'nature_projet' => null,
					'observ_ci' => null,
					'date_saisi_ci' => null,
					'lieu_saisi_ci' => null,
					'emp_trouv' => null,
					'forme_ci' => null,
					'commentaire_action' => null,
					'raison_ci' => null,
					'aviseqpluri' => null,
					'sitfam_ci' => null,
					'sitpro_ci' => null,
					'observ_benef' => null,
					'referent_id' => null,
					// 'current_action' => null,
					'avisraison_ci' => null,
					'type_demande' => null,
					'num_contrat' => null,
					'typeinsertion' => null,
					'bilancontrat' => null,
					'engag_object_referent' => null,
					'outilsmobilises' => null,
					'outilsamobiliser' => null,
					'niveausalaire' => null,
					'zonegeographique_id' => '35',
				),
			);
			$this->Contratinsertion->data = $datas;
			$result = $this->Contratinsertion->valider($datas);
			$this->assertFalse($result);
		}
 
		// test fonction aftersave()
		function testAfterSave() {
			$this->Contratinsertion->data = array(
				'Contratinsertion' => array(
					'id' => '1',
					'personne_id' => '1',
					'structurereferente_id' => '4',
					'typocontrat_id' => '1',
					'dd_ci' => '2009-05-08',
					'df_ci' => '2012-02-08',
					//'niv_etude' => null,
					'form_compl' => null,
					'aut_expr_prof' => null,
					'rg_ci' => '1',
					'actions_prev' => '0',
					'obsta_renc' => 'aucun',
					'service_soutien' => null,
					'pers_charg_suivi' => null,
					'sect_acti_emp' => null,
					'emp_occupe' => null,
					'duree_hebdo_emp' => null,
					'nat_cont_trav' => null,
					'duree_cdd' => null,
					'duree_engag' => '3',
					'decision_ci' => 'E',
					'datevalidation_ci' => null,
					'expr_prof' => null,
					'diplomes' => null,
					'objectifs_fixes' => null,
					'engag_object' => null,
					'nature_projet' => null,
					'observ_ci' => null,
					'date_saisi_ci' => null,
					'lieu_saisi_ci' => null,
					'emp_trouv' => null,
					'forme_ci' => null,
					'commentaire_action' => null,
					'raison_ci' => null,
					'aviseqpluri' => null,
					'sitfam_ci' => null,
					'sitpro_ci' => null,
					'observ_benef' => null,
					'referent_id' => 1,
					// 'current_action' => null,
					'avisraison_ci' => null,
					'type_demande' => null,
					'num_contrat' => null,
					'typeinsertion' => null,
					'bilancontrat' => null,
					'engag_object_referent' => null,
					'outilsmobilises' => null,
					'outilsamobiliser' => null,
					'niveausalaire' => null,
					'zonegeographique_id' => 1,
				),
			);
			$created = null;
			$result = $this->Contratinsertion->afterSave($created);
			$this->assertFalse($result);

		}

		function testCalculPosition() {
			$data = array(
				'Contratinsertion' => array(
					'id' => '10',
					'personne_id' => '1001',
					'structurereferente_id' => '7',
					'typocontrat_id' => '1',
					'dd_ci' => '2009-05-08',
					'df_ci' => '2012-02-08',
					//'niv_etude' => null,
					'form_compl' => null,
					'aut_expr_prof' => null,
					'rg_ci' => '1',
					'actions_prev' => '0',
					'obsta_renc' => 'aucun',
					'service_soutien' => null,
					'pers_charg_suivi' => null,
					'sect_acti_emp' => null,
					'emp_occupe' => null,
					'duree_hebdo_emp' => null,
					'nat_cont_trav' => null,
					'duree_cdd' => null,
					'duree_engag' => '3',
					'decision_ci' => 'E',
					'datevalidation_ci' => null,
					'expr_prof' => null,
					'diplomes' => null,
					'objectifs_fixes' => null,
					'engag_object' => null,
					'nature_projet' => null,
					'observ_ci' => null,
					'date_saisi_ci' => null,
					'lieu_saisi_ci' => null,
					'emp_trouv' => null,
					'forme_ci' => null,
					'commentaire_action' => null,
					'raison_ci' => null,
					'aviseqpluri' => null,
					'sitfam_ci' => null,
					'sitpro_ci' => null,
					'observ_benef' => null,
					'referent_id' => null,
					// 'current_action' => null,
					'avisraison_ci' => null,
					'type_demande' => null,
					'num_contrat' => null,
					'typeinsertion' => null,
					'bilancontrat' => null,
					'engag_object_referent' => null,
					'outilsmobilises' => null,
					'outilsamobiliser' => null,
					'niveausalaire' => null,
					'zonegeographique_id' => '35',
				),
			);
			$result = $this->TestContratinsertion->_calculPosition($data);
			$this->assertNull($result);

			$data['Contratinsertion']['forme_ci'] = 'S';
			$data['Contratinsertion']['sitpro_ci'] = 'hey lu';
			$result = $this->TestContratinsertion->_calculPosition($data);
			$this->assertEqual('encours', $result);

			$data['Contratinsertion']['forme_ci'] = 'C';
			$data['Contratinsertion']['sitpro_ci'] = 'hey lu';
			$result = $this->TestContratinsertion->_calculPosition($data);
			$this->assertEqual('attvalid', $result);

			$data['Contratinsertion']['forme_ci'] = null;
			$data['Contratinsertion']['sitpro_ci'] = 'hey lu';
			$result = $this->TestContratinsertion->_calculPosition($data);
			$this->assertEqual('annule', $result);
		}

	}

?>
