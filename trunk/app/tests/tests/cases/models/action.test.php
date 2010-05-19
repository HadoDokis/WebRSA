<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Action');

	class ActionTestCase extends CakeAppModelTestCase {

		function testGrouplist() {
			$expected=array(
			'Facilités offertes' => array(
					'1P' => 'Soutien, suivi social, accompagnement personnel',
					'1F' => 'Soutien, suivi social, accompagnement familial',
					'02' => '02 - Aide au retour d\'enfants placés',
					'03' => 'Soutien éducatif lié aux enfants',
					'04' => 'Aide pour la garde des enfants',
					'05' => 'Aide financière liée au logement',
					'06' => 'Autre aide liée au logement',
					'07' => 'Prise en charge financière des frais de formation (y compris stage de conduite automobile)',
					'10' => 'Autre facilité offerte'
				),
				'Autonomie sociale' => array(
					'21' => 'Démarche liée à la santé',
					'22' => 'Alphabétisation, lutte contre l\'illétrisme',
					'23' => 'Organisation quotidienne',
					'24' => 'Démarches administratives (COTOREP, demande d\'AAH, de retraite, etc...)',
					'26' => 'Bilan social',
					'29' => 'Autre action visant à l\'autonomie sociale'
				),
				'Logement' => array(
					'31' => 'Recherche d\'un logement',
					'33' => 'Demande d\'intervention d\'un organisme ou d\'un fonds d\'aide'
				),
				'Insertion professionnelle (stage, prestation, formation' => array(
					'41' => 'Aide ou suivi pour une recherche de stage ou de formation',
					'42' => 'Activité en atelier de réinsertion (centre d\'hébergement et de réadaptation sociale)',
					'43' => 'Chantier école',
					'44' => 'Stage de conduite automobile (véhicules légers)',
					'45' => 'Stage de formation générale, préparation aux concours, poursuite d\'études, etc...',
					'46' => 'Stage de formation professionnelle (stage d\'insertion et de formation à l\'emploi, permis poids lourd, crédit-formation individuel, etc...)',
					'48' => 'Bilan professionnel et orientation (évaluation du niveau de compétences professionnelles, module d\'orientation approfondie, session d\'oientation approfondie, évaluation en milieu de travail, VAE, etc...)'
				),
				'Emploi' => array(
					'51' => 'Aide ou suivi pour une recherche d\'emploi',
					'52' => 'Contrat initiative emploi',
					'53' => 'Contrat de qualification, contrat d\'apprentissage',
					'54' => 'Emploi dans une association intermédiaire ou une entreprise d\'insertion',
					'55' => 'Création d\'entreprise',
					'56' => 'Contrats aidés, Contrat d\'Avenir, CIRMA',
					'57' => 'Emploi consolidé: CDI',
					'58' => 'Emploi familial, service de proximité',
					'59' => 'Autre forme d\'emploi: CDD, CNE'
				)
			);
			$this->assertEqual($expected,$this->Action->grouplist());

			$expected=array(
				'Facilités offertes' => array(
					'02' => '02 - Aide au retour d\'enfants placés',
					'04' => 'Aide pour la garde des enfants',
					'05' => 'Aide financière liée au logement',
					'06' => 'Autre aide liée au logement',
					'07' => 'Prise en charge financière des frais de formation (y compris stage de conduite automobile)',
					'10' => 'Autre facilité offerte'
				),
				'Logement' => array(
					'33' => 'Demande d\'intervention d\'un organisme ou d\'un fonds d\'aide'
				)
			);
			$this->assertEqual($expected,$this->Action->grouplist('aide'));

			$expected=array(
				'Facilités offertes' => array(
					'1P' => 'Soutien, suivi social, accompagnement personnel',
					'1F' => 'Soutien, suivi social, accompagnement familial',
					'03' => 'Soutien éducatif lié aux enfants'
				),
				'Autonomie sociale' => array(
					'21' => 'Démarche liée à la santé',
					'22' => 'Alphabétisation, lutte contre l\'illétrisme',
					'23' => 'Organisation quotidienne',
					'24' => 'Démarches administratives (COTOREP, demande d\'AAH, de retraite, etc...)',
					'26' => 'Bilan social',
					'29' => 'Autre action visant à l\'autonomie sociale'
				),
				'Logement' => array(
					'31' => 'Recherche d\'un logement'
				),
				'Insertion professionnelle (stage, prestation, formation' => array(
					'41' => 'Aide ou suivi pour une recherche de stage ou de formation',
					'42' => 'Activité en atelier de réinsertion (centre d\'hébergement et de réadaptation sociale)',
					'43' => 'Chantier école',
					'44' => 'Stage de conduite automobile (véhicules légers)',
					'45' => 'Stage de formation générale, préparation aux concours, poursuite d\'études, etc...',
					'46' => 'Stage de formation professionnelle (stage d\'insertion et de formation à l\'emploi, permis poids lourd, crédit-formation individuel, etc...)',
					'48' => 'Bilan professionnel et orientation (évaluation du niveau de compétences professionnelles, module d\'orientation approfondie, session d\'oientation approfondie, évaluation en milieu de travail, VAE, etc...)'
				),
				'Emploi' => array(
					'51' => 'Aide ou suivi pour une recherche d\'emploi'
				)
			);
			$this->assertEqual($expected,$this->Action->grouplist('prestation'));
		}
	}
?>
