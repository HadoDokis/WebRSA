<?php

	class ActionFixture extends CakeTestFixture {
		var $name = 'Action';
		var $table = 'actions';
		var $import = array( 'table' => 'actions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typeaction_id' => '1',
				'code' => '1P',
				'libelle' => 'Soutien, suivi social, accompagnement personnel',
			),
			array(
				'id' => '2',
				'typeaction_id' => '1',
				'code' => '1F',
				'libelle' => 'Soutien, suivi social, accompagnement familial',
			),
			array(
				'id' => '3',
				'typeaction_id' => '1',
				'code' => '02',
				'libelle' => '02 - Aide au retour d\'enfants placés',
			),
			array(
				'id' => '4',
				'typeaction_id' => '1',
				'code' => '03',
				'libelle' => 'Soutien éducatif lié aux enfants',
			),
			array(
				'id' => '5',
				'typeaction_id' => '1',
				'code' => '04',
				'libelle' => 'Aide pour la garde des enfants',
			),
			array(
				'id' => '6',
				'typeaction_id' => '1',
				'code' => '05',
				'libelle' => 'Aide financière liée au logement',
			),
			array(
				'id' => '7',
				'typeaction_id' => '1',
				'code' => '06',
				'libelle' => 'Autre aide liée au logement',
			),
			array(
				'id' => '8',
				'typeaction_id' => '1',
				'code' => '07',
				'libelle' => 'Prise en charge financière des frais de formation (y compris stage de conduite automobile)',
			),
			array(
				'id' => '9',
				'typeaction_id' => '1',
				'code' => '10',
				'libelle' => 'Autre facilité offerte',
			),
			array(
				'id' => '10',
				'typeaction_id' => '2',
				'code' => '21',
				'libelle' => 'Démarche liée à la santé',
			),
			array(
				'id' => '11',
				'typeaction_id' => '2',
				'code' => '22',
				'libelle' => 'Alphabétisation, lutte contre l\'illétrisme',
			),
			array(
				'id' => '12',
				'typeaction_id' => '2',
				'code' => '23',
				'libelle' => 'Organisation quotidienne',
			),
			array(
				'id' => '13',
				'typeaction_id' => '2',
				'code' => '24',
				'libelle' => 'Démarches administratives (COTOREP, demande d\'AAH, de retraite, etc...)',
			),
			array(
				'id' => '14',
				'typeaction_id' => '2',
				'code' => '26',
				'libelle' => 'Bilan social',
			),
			array(
				'id' => '15',
				'typeaction_id' => '2',
				'code' => '29',
				'libelle' => 'Autre action visant à l\'autonomie sociale',
			),
			array(
				'id' => '16',
				'typeaction_id' => '3',
				'code' => '31',
				'libelle' => 'Recherche d\'un logement',
			),
			array(
				'id' => '17',
				'typeaction_id' => '3',
				'code' => '33',
				'libelle' => 'Demande d\'intervention d\'un organisme ou d\'un fonds d\'aide',
			),
			array(
				'id' => '18',
				'typeaction_id' => '4',
				'code' => '41',
				'libelle' => 'Aide ou suivi pour une recherche de stage ou de formation',
			),
			array(
				'id' => '19',
				'typeaction_id' => '4',
				'code' => '42',
				'libelle' => 'Activité en atelier de réinsertion (centre d\'hébergement et de réadaptation sociale)',
			),
			array(
				'id' => '20',
				'typeaction_id' => '4',
				'code' => '43',
				'libelle' => 'Chantier école',
			),
			array(
				'id' => '21',
				'typeaction_id' => '4',
				'code' => '44',
				'libelle' => 'Stage de conduite automobile (véhicules légers)',
			),
			array(
				'id' => '22',
				'typeaction_id' => '4',
				'code' => '45',
				'libelle' => 'Stage de formation générale, préparation aux concours, poursuite d\'études, etc...',
			),
			array(
				'id' => '23',
				'typeaction_id' => '4',
				'code' => '46',
				'libelle' => 'Stage de formation professionnelle (stage d\'insertion et de formation à l\'emploi, permis poids lourd, crédit-formation individuel, etc...)',
			),
			array(
				'id' => '24',
				'typeaction_id' => '4',
				'code' => '48',
				'libelle' => 'Bilan professionnel et orientation (évaluation du niveau de compétences professionnelles, module d\'orientation approfondie, session d\'oientation approfondie, évaluation en milieu de travail, VAE, etc...)',
			),
			array(
				'id' => '25',
				'typeaction_id' => '5',
				'code' => '51',
				'libelle' => 'Aide ou suivi pour une recherche d\'emploi',
			),
			array(
				'id' => '26',
				'typeaction_id' => '5',
				'code' => '52',
				'libelle' => 'Contrat initiative emploi',
			),
			array(
				'id' => '27',
				'typeaction_id' => '5',
				'code' => '53',
				'libelle' => 'Contrat de qualification, contrat d\'apprentissage',
			),
			array(
				'id' => '28',
				'typeaction_id' => '5',
				'code' => '54',
				'libelle' => 'Emploi dans une association intermédiaire ou une entreprise d\'insertion',
			),
			array(
				'id' => '29',
				'typeaction_id' => '5',
				'code' => '55',
				'libelle' => 'Création d\'entreprise',
			),
			array(
				'id' => '30',
				'typeaction_id' => '5',
				'code' => '56',
				'libelle' => 'Contrats aidés, Contrat d\'Avenir, CIRMA',
			),
			array(
				'id' => '31',
				'typeaction_id' => '5',
				'code' => '57',
				'libelle' => 'Emploi consolidé: CDI',
			),
			array(
				'id' => '32',
				'typeaction_id' => '5',
				'code' => '58',
				'libelle' => 'Emploi familial, service de proximité',
			),
			array(
				'id' => '33',
				'typeaction_id' => '5',
				'code' => '59',
				'libelle' => 'Autre forme d\'emploi: CDD, CNE',
			),
		);
	}

?>
