--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('actions', 'id'), 1, false);


--
-- Name: actionsinsertion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('actionsinsertion', 'id'), 1, false);


--
-- Name: activites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('activites', 'id'), 1, false);


--
-- Name: adresses_foyers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('adresses_foyers', 'id'), 21, true);


--
-- Name: adresses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('adresses', 'id'), 22, true);


--
-- Name: aidesdirectes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('aidesdirectes', 'id'), 1, false);


--
-- Name: contratsinsertion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('contratsinsertion', 'id'), 48, true);


--
-- Name: details_ressources_mensuelles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('details_ressources_mensuelles', 'id'), 1, false);


--
-- Name: difdisps_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('difdisps', 'id'), 1, false);


--
-- Name: diflogs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('diflogs', 'id'), 1, false);


--
-- Name: difsocs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('difsocs', 'id'), 1, false);


--
-- Name: dossiers_rsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dossiers_rsa', 'id'), 22, true);


--
-- Name: dspfs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dspfs', 'id'), 1, false);


--
-- Name: dspps_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dspps', 'id'), 11, true);


--
-- Name: fins_droits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('fins_droits', 'id'), 1, false);


--
-- Name: foyers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('foyers', 'id'), 21, true);


--
-- Name: modes_contact_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('modes_contact', 'id'), 1, false);


--
-- Name: nataccosocfams_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('nataccosocfams', 'id'), 1, false);


--
-- Name: nataccosocindis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('nataccosocindis', 'id'), 1, false);


--
-- Name: natmobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('natmobs', 'id'), 1, false);


--
-- Name: nivetus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('nivetus', 'id'), 1, false);


--
-- Name: orientsstructs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('orientsstructs', 'id'), 1, false);


--
-- Name: paiements_foyers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('paiements_foyers', 'id'), 1, false);


--
-- Name: personnes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('personnes', 'id'), 27, true);


--
-- Name: prestsform_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('prestsform', 'id'), 1, false);


--
-- Name: referents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('referents', 'id'), 1, false);


--
-- Name: refsprestas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('refsprestas', 'id'), 1, false);


--
-- Name: ressources_mensuelles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ressources_mensuelles', 'id'), 1, false);


--
-- Name: ressources_trimestrielles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ressources_trimestrielles', 'id'), 1, false);


--
-- Name: servicesreferents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('servicesreferents', 'id'), 1, false);


--
-- Name: suivis_instruction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('suivis_instruction', 'id'), 1, false);


--
-- Name: titres_sejour_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('titres_sejour', 'id'), 1, false);


--
-- Name: typesactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('typesactions', 'id'), 1, false);


--
-- Name: typesorients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('typesorients', 'id'), 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('users', 'id'), 1, false);


--
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO actions VALUES (1, 1, '1P', 'Soutien, suivi social, accompagnement personnel');
INSERT INTO actions VALUES (2, 1, '1F', 'Soutien, suivi social, accompagnement familial');
INSERT INTO actions VALUES (3, 1, '02', 'Aide au retour d''enfants placés');
INSERT INTO actions VALUES (4, 1, '03', 'Soutien éducatif lié aux enfants');
INSERT INTO actions VALUES (5, 1, '04', 'Aide pour la garde des enfants');
INSERT INTO actions VALUES (6, 1, '05', 'Aide financière liée au logement');
INSERT INTO actions VALUES (7, 1, '06', 'Autre aide liée au logement');
INSERT INTO actions VALUES (8, 1, '07', 'Prise en charge financière des frais de formation (y compris stage de conduite automobile)');
INSERT INTO actions VALUES (9, 1, '10', 'Autre facilité offerte');
INSERT INTO actions VALUES (10, 2, '21', 'Démarche liée à la santé');
INSERT INTO actions VALUES (11, 2, '22', 'Alphabétisation, lutte contre l''illétrisme');
INSERT INTO actions VALUES (12, 2, '23', 'Organisation quotidienne');
INSERT INTO actions VALUES (13, 2, '24', 'Démarches administratives (COTOREP, demande d''AAH, de retraite, etc...)');
INSERT INTO actions VALUES (14, 2, '26', 'Bilan social');
INSERT INTO actions VALUES (15, 2, '29', 'Autre action visant à l''autonomie sociale');
INSERT INTO actions VALUES (16, 3, '31', 'Recherche d''un logement');
INSERT INTO actions VALUES (17, 3, '33', 'Demande d''intervention d''un organisme ou d''un fonds d''aide');
INSERT INTO actions VALUES (18, 4, '41', 'Aide ou suivi pour une recherche de stage ou de formation');
INSERT INTO actions VALUES (19, 4, '42', 'Activité en atelier de réinsertion (centre d''hébergement et de réadaptation sociale)');
INSERT INTO actions VALUES (20, 4, '43', 'Chantier école');
INSERT INTO actions VALUES (21, 4, '44', 'Stage de conduite automobile (véhicules légers)');
INSERT INTO actions VALUES (22, 4, '45', 'Stage de formation générale, préparation aux concours, poursuite d''études, etc...');
INSERT INTO actions VALUES (23, 4, '46', 'Stage de formation professionnelle (stage d''insertion et de formation à l''emploi, permis poids lourd, crédit-formation individuel, etc...)');
INSERT INTO actions VALUES (24, 4, '48', 'Bilan professionnel et orientation (évaluation du niveau de compétences professionnelles, module d''orientation approfondie, session d''oientation approfondie, évaluation en milieu de travail, VAE, etc...)');
INSERT INTO actions VALUES (25, 5, '51', 'Aide ou suivi pour une recherche d''emploi');
INSERT INTO actions VALUES (26, 5, '52', 'Contrat initiative emploi');
INSERT INTO actions VALUES (27, 5, '53', 'Contrat de qualification, contrat d''apprentissage');
INSERT INTO actions VALUES (28, 5, '54', 'Emploi dans une association intermédiaire ou une entreprise d''insertion');
INSERT INTO actions VALUES (29, 5, '55', 'Création d''entreprise');
INSERT INTO actions VALUES (30, 5, '56', 'Contrats aidés, Contrat d''Avenir, CIRMA');
INSERT INTO actions VALUES (31, 5, '57', 'Emploi consolidé: CDI');
INSERT INTO actions VALUES (32, 5, '58', 'Emploi familial, service de proximité');
INSERT INTO actions VALUES (33, 5, '59', 'Autre forme d''emploi: CDD, CNE');


--
-- Data for Name: actionsinsertion; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO actionsinsertion VALUES (1, 'dépôt');


--
-- Data for Name: actionsinsertion_liees; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO actionsinsertion_liees VALUES (1, 1, '2008-01-01', '2009-01-01');


--
-- Data for Name: activites; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: adresses; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO adresses VALUES (1, '8', 'rue', 'des rosiers', NULL, NULL, 'Agde', '34003', '34300', '34300', 'Agde', 'FRA');
INSERT INTO adresses VALUES (2, '9', 'rue', 'Rogier', NULL, NULL, 'Dampremy', '6020 ', '6020 ', '6020 ', 'Dampremy', 'HOR');
INSERT INTO adresses VALUES (7, '15', 'rue', '', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (8, 't', 'g', '', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (9, 'sere', 'sese', '', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (10, '15', 'rue', 'de la république', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (6, '0', 'Av', 'd Elne', '', '', '', '     ', '     ', '66200', 'LATOUR BAS ELNE', 'FRA');
INSERT INTO adresses VALUES (12, '22', 'AVE', 'Jacques Duclos', 'Résidence du vieux pays', '', '', '     ', '     ', '93600', 'Aulnay sous bois', 'FRA');
INSERT INTO adresses VALUES (11, '1', 'IMP', 'des beaux Acacias', '', 'Résidence Guynemer', '', '     ', '     ', '93150', 'Le Blanc Mesnil', 'FRA');
INSERT INTO adresses VALUES (13, '0', 'Av', 'd Elne', '', '', '', '     ', '     ', '66200', 'LATOUR BAS ELNE', '');
INSERT INTO adresses VALUES (14, '8', 'chem', 'des pierres', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (15, '1', 'rue', 'droit', '', '', '', '     ', '     ', '34000', 'montpellier', '');
INSERT INTO adresses VALUES (16, '1', 'rue', 'rrrrrrr', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (17, 'xxx', 'xxx', 'xxx', '', '', 'xxx', 'xxx  ', '     ', 'xxx  ', '', 'FRA');
INSERT INTO adresses VALUES (18, 'GFHEZH', 'HLHG', 'GHKJGHFDKJGHKJFD', 'KGFHKJGFDKHGKJFDH', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (19, '38', 'RE D', '', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (20, '12', 'rue', 'a', '', '', '', '     ', '     ', '     ', '', '');
INSERT INTO adresses VALUES (21, '9', '76', 'sdnsqds', 's;qldsq', 'qdqsd', 'sdqsld', 'sqdqs', 'dsqld', 'qsdqs', 'ds', 'FRA');
INSERT INTO adresses VALUES (22, '20', 'rue', 'des marthyrs', '', '', '', '     ', '     ', '     ', '', '');


--
-- Data for Name: adresses_foyers; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO adresses_foyers VALUES (1, 1, 1, '01', '2007-12-01', 'D');
INSERT INTO adresses_foyers VALUES (2, 2, 1, '02', '2006-12-01', 'D');
INSERT INTO adresses_foyers VALUES (6, 7, 7, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (7, 8, 8, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (8, 9, 9, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (9, 10, 10, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (5, 6, 1, '03', '2008-06-03', 'D');
INSERT INTO adresses_foyers VALUES (11, 12, 11, '02', '1985-05-23', 'D');
INSERT INTO adresses_foyers VALUES (10, 11, 11, '01', '2006-08-25', 'D');
INSERT INTO adresses_foyers VALUES (12, 13, 12, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (13, 14, 13, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (14, 15, 14, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (15, 16, 15, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (16, 17, 16, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (17, 18, 17, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (18, 19, 18, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (19, 20, 19, '01', NULL, ' ');
INSERT INTO adresses_foyers VALUES (20, 21, 20, '01', '1993-02-18', 'D');
INSERT INTO adresses_foyers VALUES (21, 22, 21, '01', NULL, ' ');


--
-- Data for Name: aides_liees; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO aides_liees VALUES (1, 1, '2009-04-27');


--
-- Data for Name: aidesdirectes; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO aidesdirectes VALUES (1, '1F');


--
-- Data for Name: contratsinsertion; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO contratsinsertion VALUES (6, 9, 9, '2009-05-01', '2009-12-31', 'BAC +2', 'DUT', '', 'Personnel, formation, développement, analyse', NULL, 'pre', NULL, NULL, NULL, 'Service de l''action sociale', 'Mme RSA', '', '', 'Administration', 'Analyste programmeur', '35h', 'CDI', '', 3, '', '', 'v', '2009-05-01');
INSERT INTO contratsinsertion VALUES (12, 1, 1, '2008-01-01', '2009-01-01', '3', 'dut', 'travail de saison', 'tjs dans le public', NULL, 'ren', NULL, NULL, NULL, 'Du CG66, parce que c bien', 'Président', 'repos', 'ne pas se fatiguer', 'le transat', 'sieste', '8', 'cdi', '', NULL, '', '', 'v', '2009-01-18');
INSERT INTO contratsinsertion VALUES (9, 6, 6, '2008-02-01', '2009-02-01', 'aaa', 'bbb', 'ddd', 'ccc', NULL, 'pre', NULL, NULL, NULL, 'désignation service soutien', 'personne chargée suivi', 'objectifs', 'moyens atteinte objectifs', '', '', '', '   ', '', NULL, '', '', 'v', '2009-02-19');
INSERT INTO contratsinsertion VALUES (19, 9, 9, '2009-10-01', '2009-12-31', '', '', '', '', NULL, 'ren', NULL, NULL, NULL, '', '', '', '', '', '', '', '   ', '', NULL, '', '', 'v', NULL);
INSERT INTO contratsinsertion VALUES (1, 1, 1, '2008-01-01', '2009-01-01', 'Bac +5', 'Bac, DEUG MIAS, DUP, Master 2 Pro', 'Aucune', 'Technicien, Hot line, Développeur', '', 'pre', 1, '', '', 'Pole emploi 
, Montpellier', 'Maurice LUC', 'De nombreux objectifs', 'Envoi de CV 
, postuler sur internet, créer mon projet professionnel', 'Informatique', 'Développeur', '35h', 'CDD', '3 mois', 3, 'Créer mon entreprise', 'Pas dobservations notables', 'v', '2009-04-24');
INSERT INTO contratsinsertion VALUES (4, 10, 10, '2008-05-10', '2009-05-09', 'Bac +3', 'licence', '', 'exp prof', NULL, 'ren', NULL, '', '', '', '', '', '', '', '', '', 'CDD', '1 an', NULL, '', '', 'r', NULL);
INSERT INTO contratsinsertion VALUES (20, 1, 1, '2009-01-01', '2008-01-01', '', '', '', '', NULL, 'ren', NULL, '', '', '', '', '', '', '', '', '', '   ', '', NULL, '', '', 'a', NULL);
INSERT INTO contratsinsertion VALUES (33, 9, 9, '2009-01-01', '2009-03-31', '', '', '', '', NULL, 'red', NULL, '', '', '', '', '', '', '', '', '', '   ', '', NULL, '', '', 'v', '2009-04-01');
INSERT INTO contratsinsertion VALUES (36, 1, 1, '2009-04-06', '2009-10-18', '', '', '', '', NULL, 'ren', NULL, '', '', '', '', '', '', '', '', '', '   ', '', NULL, '', '', 'r', NULL);
INSERT INTO contratsinsertion VALUES (3, 8, 8, '2008-06-01', '2009-05-30', 'Maitrise', 'Bac +2
', 'artisanat Boulangerie', 'Travail social', NULL, 'pre', NULL, NULL, NULL, 'CG66 MSP Perp', 'MR X ', 'Orientation emploi dans les 12 mois', 'Orientation vers une action pré professionnelle permettant de travailler sur le projet professionnel', 'Social', 'Conseiller d''Insertion', '35', 'cdd', '12', 12, '', 'fsdfdsfsfsdfds', 'v', '2009-06-01');
INSERT INTO contratsinsertion VALUES (5, 7, 7, '2009-01-01', '2009-12-01', 'bac', 'bac', 'garage', 'mécanicien', NULL, 'pre', NULL, NULL, NULL, 'msp perpignan', 'robert redford', 'trouver un job', 'formation', 'mecanique', 'gonfleur', '35', 'cdd', '12', 12, 'test', 'test', 'v', '2009-01-20');
INSERT INTO contratsinsertion VALUES (43, 2, 2, '2008-01-01', '2009-02-02', '', '', '', '', NULL, 'ren', NULL, '', '', '', '', '', '', '', '', '', '   ', '', NULL, '', '', 'a', NULL);


--
-- Data for Name: details_ressources_mensuelles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: difdisps; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO difdisps VALUES (1, '0501', 'La garde d''enfant de moins de 6 ans');
INSERT INTO difdisps VALUES (2, '0502', 'La garde d''enfant de plus de 6 ans');
INSERT INTO difdisps VALUES (3, '0503', 'La garde d''enfant(s) ou de proche(s) invalide(s)');
INSERT INTO difdisps VALUES (4, '0504', 'La charge de proche(s) dépendant(s)');


--
-- Data for Name: diflogs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO diflogs VALUES (1, '1001', 'Pas de difficultés');
INSERT INTO diflogs VALUES (2, '1002', 'Impayés de loyer ou de remboursement');
INSERT INTO diflogs VALUES (3, '1003', 'Problèmes financiers');
INSERT INTO diflogs VALUES (4, '1004', 'Qualité du logement (insalubrité, indécence)');
INSERT INTO diflogs VALUES (5, '1005', 'Qualité de l''environnement (isolement, absence de transport collectif)');
INSERT INTO diflogs VALUES (6, '1006', 'Fin de bail, expulsion');
INSERT INTO diflogs VALUES (7, '1007', 'Conditions de logement (surpeuplement)');
INSERT INTO diflogs VALUES (8, '1008', 'Eloignement entre le lieu de résidence et le lieu de travail');
INSERT INTO diflogs VALUES (9, '1009', 'Autres');


--
-- Data for Name: difsocs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO difsocs VALUES (1, '0401', 'Aucune difficulté');
INSERT INTO difsocs VALUES (2, '0402', 'Santé');
INSERT INTO difsocs VALUES (3, '0403', 'Reconnaissance de la qualité du travailleur handicapé');
INSERT INTO difsocs VALUES (4, '0404', 'Lecture, écriture ou compréhension du fançais');
INSERT INTO difsocs VALUES (5, '0405', 'Démarches et formalités administratives');
INSERT INTO difsocs VALUES (6, '0406', 'Endettement');
INSERT INTO difsocs VALUES (7, '0407', 'Autres');


--
-- Data for Name: dossiers_rsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dossiers_rsa VALUES (1, 'AJ8ID907T5', '2009-03-15', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO dossiers_rsa VALUES (8, '1109198110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL);
INSERT INTO dossiers_rsa VALUES (9, '1111111111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL);
INSERT INTO dossiers_rsa VALUES (10, 'GGGGGGG066', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL);
INSERT INTO dossiers_rsa VALUES (11, 'ZERTGFDERT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);
INSERT INTO dossiers_rsa VALUES (12, 'RSA1313131', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL);
INSERT INTO dossiers_rsa VALUES (13, '1454789066', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);
INSERT INTO dossiers_rsa VALUES (14, 'AZ7898ZER5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);
INSERT INTO dossiers_rsa VALUES (15, '1245788741', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);
INSERT INTO dossiers_rsa VALUES (16, '0123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL);
INSERT INTO dossiers_rsa VALUES (17, 'xxxxxxxxxx', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL);
INSERT INTO dossiers_rsa VALUES (18, 'GHRGHFDGHI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL);
INSERT INTO dossiers_rsa VALUES (19, '1313121321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL);
INSERT INTO dossiers_rsa VALUES (20, '1KI1232123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL);
INSERT INTO dossiers_rsa VALUES (21, 'RHHGFDDSEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL);
INSERT INTO dossiers_rsa VALUES (22, '2222222222', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: dspfs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspfs VALUES (1, 1, '0101', true, 'aucun', '20 avenue du loup 
 Montpellier ', '0912', 'Manque de moyen', '1102');


--
-- Data for Name: dspfs_diflogs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspfs_nataccosocfams; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspps VALUES (1, 1, false, false, true, 'Aucunes', false, false, true, 'Aucun', 'Pôle-emploi 
6 rue du travail 
34080 MONTPELLIER', '2007-07-04', true, true, 'Développement JAVA', true, 'Aucunes', 'Webmaster', true, '1802', 'Pôle-emploi 
6 rue du travail 
34080 MONTPELLIER', '1903', 'Développeur RetD', 'Informatique', '2006-12-20', true, 'Codage', 'Info/devpt', '2106', 'Informaticien', 'Informatique', true, true);
INSERT INTO dspps VALUES (2, 7, true, false, false, NULL, false, false, false, NULL, 'fgdgdfgdfgfd', '2009-04-30', false, false, '', true, '', '', false, '1802', 'gfdgfdg', '1901', 'gdfg', 'gdfg', '2009-04-30', false, 'gdfg', 'gdf', '2105', 'gdfg', 'gfdgdfg', false, true);
INSERT INTO dspps VALUES (4, 9, false, false, true, NULL, true, true, false, NULL, '124 rue carnot', '1993-05-26', true, true, '', true, '', '', false, '1801', '', '1901', '', '', '2009-04-30', true, 'Systèmes d''Information', 'Collectivité territoriale', '2107', 'Analyste programmeur', '', false, true);
INSERT INTO dspps VALUES (7, 12, false, false, true, NULL, false, false, true, NULL, 'xxx', '2009-04-30', false, false, '', false, '', '', false, '1801', NULL, '1904', NULL, NULL, NULL, false, '', '', '2104', '', '', false, false);
INSERT INTO dspps VALUES (8, 10, false, false, false, NULL, true, false, true, NULL, 'hfjf', '2009-04-30', true, false, '', false, '', '', false, '1801', NULL, '1902', '', '', '2009-04-30', false, '', '', '2104', '', '', true, true);
INSERT INTO dspps VALUES (9, 2, true, false, true, NULL, true, false, false, NULL, 'fezze', '2009-04-30', false, false, '', false, '', '', false, '1801', NULL, '1904', NULL, NULL, NULL, false, '', '', '2106', '', '', false, false);
INSERT INTO dspps VALUES (5, 6, true, true, true, NULL, false, false, false, NULL, 'aaaa', '2009-04-30', false, false, '', true, '', '', false, '1803', '', '1902', '', '', '2009-04-30', false, '', '', '2105', '', '', false, false);
INSERT INTO dspps VALUES (10, 13, true, true, false, NULL, false, false, false, 'autre', 'iii', '2004-04-30', false, false, '', false, '', '', false, '1802', 'pdv projet ville de ...', '1902', '', '', '2009-04-30', false, '', '', '2104', '', '', false, false);
INSERT INTO dspps VALUES (11, 12, false, false, true, NULL, false, false, true, NULL, 'Papa Maman', '2001-06-30', true, true, '', true, '', '', false, '1801', '', '1901', 'Fonctionnaire', 'Informatique', '2009-04-30', true, 'RSA', 'Département', '2107', 'TST', 'Technique', false, true);
INSERT INTO dspps VALUES (6, 16, true, true, true, 'JHFGHQODGHOIFDHG', true, true, false, 'KJHDSGHDSOFDQHGOIHGOH', 'OIFJOIFDOIGJDOI', '2009-04-30', false, false, '', false, '', '', false, '1801', '', '1901', '', '', '2009-04-30', false, '', '', '2105', '', '', false, false);
INSERT INTO dspps VALUES (3, 8, true, true, true, NULL, true, false, true, NULL, 'Pôle Emploi Agence Kennedy - ', '2009-04-30', false, true, '', false, '', '', false, '1802', '', '1902', '', '', '2009-04-30', false, 'travail social', 'social', '2104', 'conseiller d''insertion', 'Social', false, true);


--
-- Data for Name: dspps_difdisps; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspps_difdisps VALUES (4, 2);
INSERT INTO dspps_difdisps VALUES (2, 4);
INSERT INTO dspps_difdisps VALUES (3, 8);
INSERT INTO dspps_difdisps VALUES (2, 9);
INSERT INTO dspps_difdisps VALUES (1, 6);
INSERT INTO dspps_difdisps VALUES (2, 6);
INSERT INTO dspps_difdisps VALUES (3, 6);
INSERT INTO dspps_difdisps VALUES (4, 6);
INSERT INTO dspps_difdisps VALUES (1, 3);
INSERT INTO dspps_difdisps VALUES (2, 3);


--
-- Data for Name: dspps_difsocs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspps_difsocs VALUES (4, 2);
INSERT INTO dspps_difsocs VALUES (5, 4);
INSERT INTO dspps_difsocs VALUES (1, 7);
INSERT INTO dspps_difsocs VALUES (5, 8);
INSERT INTO dspps_difsocs VALUES (5, 5);
INSERT INTO dspps_difsocs VALUES (1, 10);
INSERT INTO dspps_difsocs VALUES (1, 11);
INSERT INTO dspps_difsocs VALUES (1, 6);
INSERT INTO dspps_difsocs VALUES (2, 6);
INSERT INTO dspps_difsocs VALUES (3, 6);
INSERT INTO dspps_difsocs VALUES (4, 6);
INSERT INTO dspps_difsocs VALUES (5, 6);
INSERT INTO dspps_difsocs VALUES (6, 6);
INSERT INTO dspps_difsocs VALUES (7, 6);
INSERT INTO dspps_difsocs VALUES (1, 3);
INSERT INTO dspps_difsocs VALUES (2, 3);


--
-- Data for Name: dspps_nataccosocindis; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspps_nataccosocindis VALUES (2, 2);
INSERT INTO dspps_nataccosocindis VALUES (4, 4);
INSERT INTO dspps_nataccosocindis VALUES (3, 8);
INSERT INTO dspps_nataccosocindis VALUES (4, 5);
INSERT INTO dspps_nataccosocindis VALUES (2, 10);
INSERT INTO dspps_nataccosocindis VALUES (4, 10);
INSERT INTO dspps_nataccosocindis VALUES (5, 10);
INSERT INTO dspps_nataccosocindis VALUES (4, 11);
INSERT INTO dspps_nataccosocindis VALUES (1, 6);
INSERT INTO dspps_nataccosocindis VALUES (2, 6);
INSERT INTO dspps_nataccosocindis VALUES (3, 6);
INSERT INTO dspps_nataccosocindis VALUES (4, 6);
INSERT INTO dspps_nataccosocindis VALUES (5, 6);
INSERT INTO dspps_nataccosocindis VALUES (1, 3);
INSERT INTO dspps_nataccosocindis VALUES (2, 3);
INSERT INTO dspps_nataccosocindis VALUES (3, 3);


--
-- Data for Name: dspps_natmobs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspps_natmobs VALUES (1, 2);
INSERT INTO dspps_natmobs VALUES (1, 4);
INSERT INTO dspps_natmobs VALUES (2, 4);
INSERT INTO dspps_natmobs VALUES (3, 4);
INSERT INTO dspps_natmobs VALUES (1, 8);
INSERT INTO dspps_natmobs VALUES (2, 5);
INSERT INTO dspps_natmobs VALUES (1, 11);
INSERT INTO dspps_natmobs VALUES (2, 11);
INSERT INTO dspps_natmobs VALUES (1, 3);


--
-- Data for Name: dspps_nivetus; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dspps_nivetus VALUES (2, 2);
INSERT INTO dspps_nivetus VALUES (2, 4);
INSERT INTO dspps_nivetus VALUES (2, 8);
INSERT INTO dspps_nivetus VALUES (3, 10);
INSERT INTO dspps_nivetus VALUES (2, 11);
INSERT INTO dspps_nivetus VALUES (1, 6);
INSERT INTO dspps_nivetus VALUES (2, 6);
INSERT INTO dspps_nivetus VALUES (3, 6);
INSERT INTO dspps_nivetus VALUES (4, 6);
INSERT INTO dspps_nivetus VALUES (5, 6);
INSERT INTO dspps_nivetus VALUES (6, 6);
INSERT INTO dspps_nivetus VALUES (7, 6);


--
-- Data for Name: fins_droits; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: foyers; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO foyers VALUES (1, 1, 'CEL', '1979-01-24', 'HGP', 0, 0, NULL);
INSERT INTO foyers VALUES (7, 8, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (8, 9, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (9, 10, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (10, 11, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (11, 12, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (12, 13, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (13, 14, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (14, 15, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (15, 16, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (16, 17, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (17, 18, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (18, 19, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (19, 20, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (20, 21, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO foyers VALUES (21, 22, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: modes_contact; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO modes_contact VALUES (1, 1, '0673940888', NULL, 'D', 'TEL', 'A', 'christian.buffin@gmail.com', 'A');


--
-- Data for Name: nataccosocfams; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO nataccosocfams VALUES (1, '0410', 'Logement');
INSERT INTO nataccosocfams VALUES (2, '0411', 'Endettement');
INSERT INTO nataccosocfams VALUES (3, '0412', 'Familiale');
INSERT INTO nataccosocfams VALUES (4, '0413', 'Autres');


--
-- Data for Name: nataccosocindis; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO nataccosocindis VALUES (1, '0416', 'Santé');
INSERT INTO nataccosocindis VALUES (2, '0417', 'Emploi');
INSERT INTO nataccosocindis VALUES (3, '0418', 'Insertion professionnelle');
INSERT INTO nataccosocindis VALUES (4, '0419', 'Formation');
INSERT INTO nataccosocindis VALUES (5, '0420', 'Autres');


--
-- Data for Name: natmobs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO natmobs VALUES (1, '2501', 'Sur la commune');
INSERT INTO natmobs VALUES (2, '2502', 'Sur le département');
INSERT INTO natmobs VALUES (3, '2503', 'Sur un autre département');


--
-- Data for Name: nivetus; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO nivetus VALUES (1, '1201', 'Niveau I/II: enseignement supérieur');
INSERT INTO nivetus VALUES (2, '1202', 'Niveau III: BAC + 2');
INSERT INTO nivetus VALUES (3, '1203', 'Niveau IV: BAC ou équivalent');
INSERT INTO nivetus VALUES (4, '1204', 'Niveau V: CAP/BEP');
INSERT INTO nivetus VALUES (5, '1205', 'Niveau Vbis: fin de scolarité obligatoire');
INSERT INTO nivetus VALUES (6, '1206', 'Niveau VI: pas de niveau');
INSERT INTO nivetus VALUES (7, '1207', 'Niveau VII: jamais scolarisé');


--
-- Data for Name: orientsreferents; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: orientsstructs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: paiements_foyers; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: personnes; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO personnes VALUES (1, 1, 'MR', 'Buffin', 'Christian', NULL, 'Marie', 'Joseph', 'Uccle', '1979-01-24', 1, 'N', '179019901601013', false, '1', 'C', '1979-01-24', 'E', 'DEM');
INSERT INTO personnes VALUES (2, 7, 'MR', 'auzolat', 'arnaud', 'arnaud', 'aimé', '', '', '1981-09-11', NULL, ' ', '               ', false, '1', ' ', NULL, ' ', 'DEM');
INSERT INTO personnes VALUES (3, 8, 'MLE', 'trgr', '', '', '', '', '', '1993-02-17', NULL, ' ', '               ', false, '2', ' ', NULL, ' ', 'CJT');
INSERT INTO personnes VALUES (4, 9, 'MME', 'ae', '', '', '', '', '', '2008-02-01', NULL, ' ', '               ', false, '2', ' ', NULL, ' ', 'DEM');
INSERT INTO personnes VALUES (5, 10, 'MR', 'Feuct', '', '', '', '', '', '2004-01-01', NULL, ' ', '               ', false, '1', ' ', NULL, ' ', 'DEM');
INSERT INTO personnes VALUES (6, 1, 'MME', 'DUPONT', '', '', '', '', '', '1970-01-01', NULL, ' ', '               ', false, '2', 'C', NULL, 'E', 'CJT');
INSERT INTO personnes VALUES (10, 1, 'MR', 'Red', 'Bull', 'Red', 'Green', 'Yellow', 'Torine', '1994-03-04', 4, 'O', '12365894742523 ', true, '1', 'C', '1998-09-15', 'E', 'DEM');
INSERT INTO personnes VALUES (7, 1, 'MR', 'tartempion', 'robert', '', '', '', 'perpignan', '1971-08-15', 1, 'N', '999999999      ', false, '1', 'C', '1991-09-18', 'E', 'CJT');
INSERT INTO personnes VALUES (11, 1, 'MR', 'HAMZAOUI', 'Michel', '', '', '', '', '2004-03-05', NULL, 'J', '               ', false, '1', ' ', NULL, ' ', 'AUT');
INSERT INTO personnes VALUES (12, 1, 'MLE', 'Bufin', '', '', '', '', '', '2000-12-15', NULL, ' ', '               ', false, '2', ' ', NULL, ' ', 'ENF');
INSERT INTO personnes VALUES (13, 1, 'MME', 'buffin', 'julie', '', '', '', 'bobigny', '1970-05-15', NULL, 'J', '               ', false, '2', 'F', NULL, 'E', 'CJT');
INSERT INTO personnes VALUES (14, 1, 'MR', 'HAMZAOUI', 'Michel', '', '', '', '', '2004-03-05', NULL, 'J', '               ', false, '1', ' ', NULL, ' ', 'AUT');
INSERT INTO personnes VALUES (8, 1, 'MLE', 'Buffin', 'Nathalie', 'Fabienne ', 'Régine ', 'Jean Luce ', 'Perpignan', '2009-06-01', 2, 'N', 'cni121245889633', true, '2', 'F', '2009-06-01', 'E', 'ENF');
INSERT INTO personnes VALUES (15, 11, 'MME', 'ROBERT', 'Nathalie', '', 'Marie', '', '', '1965-01-27', 1, 'N', '               ', true, '2', 'C', '1965-01-27', 'E', 'CJT');
INSERT INTO personnes VALUES (16, 1, 'MME', 'Buffin', 'Marie', 'hebert', '2ème', '"é''(é(''"(''"("(', 'UHLHLH', '1980-08-01', NULL, 'N', '               ', false, '2', ' ', NULL, ' ', 'CJT');
INSERT INTO personnes VALUES (17, 1, 'MR', 'Buffin', 'Alfred', '', '', '', 'Uccle (Belgique)', '1999-01-12', NULL, 'N', '               ', true, '1', 'C', '1999-01-12', 'E', 'AUT');
INSERT INTO personnes VALUES (9, 11, 'MR', 'ROBERT', 'Thierry', '', 'Patrick', 'Rava', 'Drancy', '1964-01-19', 1, 'N', '1640175029052  ', true, '1', 'F', '1964-01-19', 'E', 'DEM');
INSERT INTO personnes VALUES (18, 12, 'MR', 'DANOT', 'christophe', '', '', '', 'perpignan', '1978-11-22', NULL, 'N', '               ', true, '1', 'F', NULL, 'E', 'DEM');
INSERT INTO personnes VALUES (20, 14, 'MME', 'pareil', '', '', '', '', '', '1971-01-20', NULL, ' ', '               ', false, '2', ' ', NULL, ' ', 'CJT');
INSERT INTO personnes VALUES (22, 16, 'MLE', 'rival', '', '', '', '', '', '1984-12-12', NULL, ' ', '               ', false, '2', 'F', NULL, ' ', 'DEM');
INSERT INTO personnes VALUES (19, 13, 'MR', 'Arnold', 'Willie', '', '', '', '', '1993-03-19', NULL, ' ', '               ', false, '1', ' ', NULL, ' ', 'DEM');
INSERT INTO personnes VALUES (23, 17, 'MME', 'HOOIJUPOI', 'OIJOIJO', 'LOJOJOIJ', 'OJOIJOIJ', 'OJJOIJOIJO', '', '1998-04-02', NULL, 'N', '               ', true, '2', 'A', '2007-01-01', 'E', 'DEM');
INSERT INTO personnes VALUES (24, 18, '', 'GT', 'G', 'GT', '', '', '', '1967-10-29', NULL, 'N', '               ', false, NULL, 'F', '1990-12-19', ' ', 'DEM');
INSERT INTO personnes VALUES (21, 15, 'MR', 'CG58', 'Création dossie', '', '', '', '', '1992-01-01', NULL, ' ', '               ', false, '1', 'F', '1992-01-01', ' ', 'DEM');
INSERT INTO personnes VALUES (25, 19, 'MLE', 'rasoa', 'james', '', '', '', '', '1960-01-12', NULL, ' ', '               ', false, '2', 'F', NULL, ' ', 'DEM');
INSERT INTO personnes VALUES (26, 20, 'MR', 'sammy', '', 'sahnoune', '', '', '', '1996-02-18', NULL, ' ', '               ', false, '1', 'A', '1994-02-17', 'E', 'DEM');
INSERT INTO personnes VALUES (27, 21, 'MME', 'DUCHEMOL', '', '', '', '', '', '1992-02-20', NULL, ' ', '               ', false, '2', ' ', NULL, ' ', 'DEM');


--
-- Data for Name: presta_lies; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO presta_lies VALUES (1, 1);


--
-- Data for Name: prestsform; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO prestsform VALUES (1, 1, 'Dépannage');


--
-- Data for Name: rattachements; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: referents; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO referents VALUES (1, 1, 'Structure', 'N° 11', '1111', 'struct11@fai.com');
INSERT INTO referents VALUES (2, 1, 'Structure', 'N° 22', '2222', 'boite_structure@fai.fr');
INSERT INTO referents VALUES (3, 1, 'Structure', 'N° 33', '3333', '');
INSERT INTO referents VALUES (4, 1, 'Service social', 'X', '4444', '');
INSERT INTO referents VALUES (5, 1, 'Plan de ville', 'Y', '5555', 'struct55@fai.com');
INSERT INTO referents VALUES (6, 1, 'Structure', 'N° 99', '6666', 'struct99@fai.com');
INSERT INTO referents VALUES (7, 1, 'Plan de ville', 'Z', '7777', 'pdv77@fai.com');
INSERT INTO referents VALUES (8, 1, 'Service social', 'Y', '8888', 'ssocY@fai.com');
INSERT INTO referents VALUES (9, 1, 'Structure sociale', 'N° XY', '9999', 'structXY@fai.com');
INSERT INTO referents VALUES (10, 1, 'Service social', 'ZZ', '1010', 'xcs@fai.com');


--
-- Data for Name: refsprestas; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO refsprestas VALUES (1, 'auzolat', 'arnaud', 'arnauz@adullact.com', '1109');


--
-- Data for Name: refsprestas_liees; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO refsprestas_liees VALUES (1, 1);


--
-- Data for Name: ressources_mensuelles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: ressources_trimestrielles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: servicesreferents; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO servicesreferents VALUES (1, 1, 'super');


--
-- Data for Name: suivis_instruction; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: titres_sejour; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: typesactions; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO typesactions VALUES (1, 'Facilités offertes');
INSERT INTO typesactions VALUES (2, 'Autonomie sociale');
INSERT INTO typesactions VALUES (3, 'Logement');
INSERT INTO typesactions VALUES (4, 'Insertion professionnelle (stage, prestation, formation');
INSERT INTO typesactions VALUES (5, 'Emploi');


--
-- Data for Name: typesorients; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO typesorients VALUES (1, 'orientation');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO users VALUES (1, 'webrsa', '83a98ed2a57ad9734eb0a1694293d03c74ae8a57');
INSERT INTO users VALUES (2, 'cg93', 'ac860f0d3f51874b31260b406dc2dc549f4c6cde');
INSERT INTO users VALUES (3, 'cg66', 'c41d80854d210d5f7512ab216b53b2f2b8e742dc');


--
-- PostgreSQL database dump complete
--

