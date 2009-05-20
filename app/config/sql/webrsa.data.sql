INSERT INTO users ( id, username, password ) VALUES
(
    1,
    'cg23',
    'e711d517faf274f83262f0cdd616651e7590927e'
),
(
    2,
    'cg54',
    '13bdf5c43c14722e3e2d62bfc0ff0102c9955cda'
),
(
    3,
    'cg58',
    '5054b94efbf033a5fe624e0dfe14c8c0273fe320'
),
(
    4,
    'cg66',
    'c41d80854d210d5f7512ab216b53b2f2b8e742dc'
),
(
    5,
    'cg93',
    'ac860f0d3f51874b31260b406dc2dc549f4c6cde'
),
(
    6,
    'webrsa',
    '83a98ed2a57ad9734eb0a1694293d03c74ae8a57'
);


-- -----------------------------------------------------------------------------
--       table Action: pour les prestations et aides
-- -----------------------------------------------------------------------------
INSERT INTO typesactions VALUES
   (
      1,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      'Facilités offertes'                  --         libelle             VARCHAR(250)
   );

INSERT INTO typesactions VALUES
   (
      2,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      'Autonomie sociale'                  --         libelle             VARCHAR(250)
   );

INSERT INTO typesactions VALUES
   (
      3,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      'Logement'                  --         libelle             VARCHAR(250)
   );

INSERT INTO typesactions VALUES
   (
      4,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      'Insertion professionnelle (stage, prestation, formation'                  --         libelle             VARCHAR(250)
   );

INSERT INTO typesactions VALUES
   (
      5,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      'Emploi'                  --         libelle             VARCHAR(250)
   );

-----------------------------------------
---  actions avec Type Facilités offertes (typeaction_id=1)
-----------------------------------------
INSERT INTO actions VALUES
   (
      1,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '1P',                  --         code                CHAR(2),
      'Soutien, suivi social, accompagnement personnel'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      2,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '1F',                  --         code                CHAR(2),
      'Soutien, suivi social, accompagnement familial'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      3,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '02',                  --         code                CHAR(2),
      'Aide au retour d\'enfants placés'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      4,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '03',                  --         code                CHAR(2),
      'Soutien éducatif lié aux enfants'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      5,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '04',                  --         code                CHAR(2),
      'Aide pour la garde des enfants'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      6,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '05',                  --         code                CHAR(2),
      'Aide financière liée au logement'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      7,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '06',                  --         code                CHAR(2),
      'Autre aide liée au logement'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      8,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '07',                  --         code                CHAR(2),
      'Prise en charge financière des frais de formation (y compris stage de conduite automobile)'                  --         libelle             VARCHAR(250)
   );


INSERT INTO actions VALUES
   (
      9,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      1,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '10',                  --         code                CHAR(2),
      'Autre facilité offerte'                  --         libelle             VARCHAR(250)
   );

----------------------------------------------------------
---  actions avec Type Autonomie sociale (typeaction_id=2)
----------------------------------------------------------
INSERT INTO actions VALUES
   (
      10,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      2,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '21',                  --         code                CHAR(2),
      'Démarche liée à la santé'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      11,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      2,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '22',                  --         code                CHAR(2),
      'Alphabétisation, lutte contre l\'illétrisme'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      12,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      2,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '23',                  --         code                CHAR(2),
      'Organisation quotidienne'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      13,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      2,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '24',                  --         code                CHAR(2),
      'Démarches administratives (COTOREP, demande d\'AAH, de retraite, etc...)'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      14,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      2,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '26',                  --         code                CHAR(2),
      'Bilan social'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      15,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      2,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '29',                  --         code                CHAR(2),
      'Autre action visant à l\'autonomie sociale'                  --         libelle             VARCHAR(250)
   );

-------------------------------------------------
---  actions avec Type Logement (typeaction_id=3)
-------------------------------------------------

INSERT INTO actions VALUES
   (
      16,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      3,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '31',                  --         code                CHAR(2),
      'Recherche d\'un logement'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      17,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      3,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '33',                  --         code                CHAR(2),
      'Demande d\'intervention d\'un organisme ou d\'un fonds d\'aide'                  --         libelle             VARCHAR(250)
   );

-------------------------------------------------
---  actions avec Type Insertion pro (typeaction_id=4)
-------------------------------------------------

INSERT INTO actions VALUES
   (
      18,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '41',                  --         code                CHAR(2),
      'Aide ou suivi pour une recherche de stage ou de formation'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      19,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '42',                  --         code                CHAR(2),
      'Activité en atelier de réinsertion (centre d\'hébergement et de réadaptation sociale)'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      20,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '43',                  --         code                CHAR(2),
      'Chantier école'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      21,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '44',                  --         code                CHAR(2),
      'Stage de conduite automobile (véhicules légers)'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      22,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '45',                  --         code                CHAR(2),
      'Stage de formation générale, préparation aux concours, poursuite d\'études, etc...'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      23,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '46',                  --         code                CHAR(2),
      'Stage de formation professionnelle (stage d\'insertion et de formation à l\'emploi, permis poids lourd, crédit-formation individuel, etc...)'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      24,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      4,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '48',                  --         code                CHAR(2),
      'Bilan professionnel et orientation (évaluation du niveau de compétences professionnelles, module d\'orientation approfondie, session d\'oientation approfondie, évaluation en milieu de travail, VAE, etc...)'                  --         libelle             VARCHAR(250)
   );

-------------------------------------------------
---  actions avec Type Insertion pro (typeaction_id=5)
-------------------------------------------------

INSERT INTO actions VALUES
   (
      25,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '51',                  --         code                CHAR(2),
      'Aide ou suivi pour une recherche d\'emploi'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      26,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '52',                  --         code                CHAR(2),
      'Contrat initiative emploi'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      27,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '53',                  --         code                CHAR(2),
      'Contrat de qualification, contrat d\'apprentissage'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      28,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '54',                  --         code                CHAR(2),
      'Emploi dans une association intermédiaire ou une entreprise d\'insertion'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      29,                  --         id                  SERIAL NOT NULL PRIMARY KEY
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '55',                  --         code                CHAR(2),
      'Création d\'entreprise'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      30,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '56',                  --         code                CHAR(2),
      'Contrats aidés, Contrat d\'Avenir, CIRMA'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      31,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '57',                  --         code                CHAR(2),
      'Emploi consolidé: CDI'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      32,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '58',                  --         code                CHAR(2),
      'Emploi familial, service de proximité'                  --         libelle             VARCHAR(250)
   );

INSERT INTO actions VALUES
   (
      33,                  --         id                  SERIAL NOT NULL PRIMARY KEY,
      5,                    --       typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
      '59',                  --         code                CHAR(2),
      'Autre forme d\'emploi: CDD, CNE'                  --         libelle             VARCHAR(250)
   );

--------------------------------------
INSERT INTO nataccosocfams VALUES(
    1,
    '0410',
    'Logement'
);

INSERT INTO nataccosocfams VALUES(
    2,
    '0411',
    'Endettement'
);

INSERT INTO nataccosocfams VALUES(
    3,
    '0412',
    'Familiale'
);

INSERT INTO nataccosocfams VALUES(
    4,
    '0413',
    'Autres'
);
--------------------------------------
--------------------------------------
INSERT INTO diflogs VALUES(
    1,
    '1001',
    'Pas de difficultés'
);

INSERT INTO diflogs VALUES(
    2,
    '1002',
    'Impayés de loyer ou de remboursement'
);

INSERT INTO diflogs VALUES(
    3,
    '1003',
    'Problèmes financiers'
);

INSERT INTO diflogs VALUES(
    4,
    '1004',
    'Qualité du logement (insalubrité, indécence)'
);

INSERT INTO diflogs VALUES(
    5,
    '1005',
    'Qualité de l\'environnement (isolement, absence de transport collectif)'
);

INSERT INTO diflogs VALUES(
    6,
    '1006',
    'Fin de bail, expulsion'
);

INSERT INTO diflogs VALUES(
    7,
    '1007',
    'Conditions de logement (surpeuplement)'
);

INSERT INTO diflogs VALUES(
    8,
    '1008',
    'Eloignement entre le lieu de résidence et le lieu de travail'
);

INSERT INTO diflogs VALUES(
    9,
    '1009',
    'Autres'
);

--------------------------------------
INSERT INTO difsocs VALUES(
    1,
    '0401',
    'Aucune difficulté'
);
INSERT INTO difsocs VALUES(
    2,
    '0402',
    'Santé'
);
INSERT INTO difsocs VALUES(
    3,
    '0403',
    'Reconnaissance de la qualité du travailleur handicapé'
);
INSERT INTO difsocs VALUES(
    4,
    '0404',
    'Lecture, écriture ou compréhension du fançais'
);
INSERT INTO difsocs VALUES(
    5,
    '0405',
    'Démarches et formalités administratives'
);
INSERT INTO difsocs VALUES(
    6,
    '0406',
    'Endettement'
);
INSERT INTO difsocs VALUES(
    7,
    '0407',
    'Autres'
);
--------------------------------------
--------------------------------------
INSERT INTO nataccosocindis VALUES(
    1,
    '0416',
    'Santé'
);
INSERT INTO nataccosocindis VALUES(
    2,
    '0417',
    'Emploi'
);
INSERT INTO nataccosocindis VALUES(
    3,
    '0418',
    'Insertion professionnelle'
);
INSERT INTO nataccosocindis VALUES(
    4,
    '0419',
    'Formation'
);
INSERT INTO nataccosocindis VALUES(
    5,
    '0420',
    'Autres'
);
--------------------------------------
--------------------------------------
INSERT INTO difdisps VALUES(
    1,
    '0501',
    'La garde d\'enfant de moins de 6 ans'
);

INSERT INTO difdisps VALUES(
    2,
    '0502',
    'La garde d\'enfant de plus de 6 ans'
);

INSERT INTO difdisps VALUES(
    3,
    '0503',
    'La garde d\'enfant(s) ou de proche(s) invalide(s)'
);

INSERT INTO difdisps VALUES(
    4,
    '0504',
    'La charge de proche(s) dépendant(s)'
);
--------------------------------------
--------------------------------------
-- INSERT INTO nivetus VALUES(
--     1,
--     '1201',
--     'Niveau I/II: enseignement supérieur'
-- );
-- INSERT INTO nivetus VALUES(
--     2,
--     '1202',
--     'Niveau III: BAC + 2'
-- );
-- INSERT INTO nivetus VALUES(
--     3,
--     '1203',
--     'Niveau IV: BAC ou équivalent'
-- );
-- INSERT INTO nivetus VALUES(
--     4,
--     '1204',
--     'Niveau V: CAP/BEP'
-- );
-- INSERT INTO nivetus VALUES(
--     5,
--     '1205',
--     'Niveau Vbis: fin de scolarité obligatoire'
-- );
-- INSERT INTO nivetus VALUES(
--     6,
--     '1206',
--     'Niveau VI: pas de niveau'
-- );
-- INSERT INTO nivetus VALUES(
--     7,
--     '1207',
--     'Niveau VII: jamais scolarisé'
-- );
--------------------------------------
--------------------------------------
INSERT INTO natmobs VALUES(
    1,
    '2501',
    'Sur la commune'
);
INSERT INTO natmobs VALUES(
    2,
    '2502',
    'Sur le département'
);
INSERT INTO natmobs VALUES(
    3,
    '2503',
    'Sur un autre département'
);

----------------------------------------
-- ------ Types orientations -----------
-- -------------------------------------
INSERT INTO typesorients VALUES
   (
     1,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     null,   --            INTEGER,
     'Emploi'
   );

INSERT INTO typesorients VALUES
   (
     2,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --                INTEGER,
     'Pôle emploi'
   );

INSERT INTO typesorients VALUES
   (
     3,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --                INTEGER,
    'Exploitant agricole MSA'
   );

INSERT INTO typesorients VALUES
   (
     4,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     null,   --                INTEGER,
     'Préprofessionnelle'
   );

INSERT INTO typesorients VALUES
   (
     5,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     4,     --                INTEGER,
    'Conseil Général'   --     name varchar(30) null
   );

INSERT INTO typesorients VALUES
   (
     6,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     null,  --                INTEGER,
    'Social'
   );

INSERT INTO typesorients VALUES
   (
     7,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'Conseil Général'
   );

INSERT INTO typesorients VALUES
   (
     8,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'MSA'
   );

INSERT INTO typesorients VALUES
   (
     9,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'Organisme agréés ACAL'
   );

INSERT INTO typesorients VALUES
   (
     10,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'ATR'
   );
--------------
--------------------------
-- ------ Zones geograhiques -----------
-- -------------------------------------
INSERT INTO zonesgeographiques VALUES
(
     1,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     '34090',   --     codeinsee           CHAR(5) NOT NULL,
     'Pole Montpellier-Nord'   --     libelle             VARCHAR(50) NOT NULL
);

INSERT INTO zonesgeographiques VALUES
(
     2,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     '34070',   --     codeinsee           CHAR(5) NOT NULL,
     'Pole Montpellier Sud-Est'   --     libelle             VARCHAR(50) NOT NULL
);

INSERT INTO zonesgeographiques VALUES
(
     3,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     '34080',   --     codeinsee           CHAR(5) NOT NULL,
     'Pole Montpellier Ouest'   --     libelle             VARCHAR(50) NOT NULL
);

----------------------------------------
-- ------ Structures référentes -----------
-- -------------------------------------
INSERT INTO structuresreferentes VALUES
   (
      1,  --     id                  SERIAL NOT NULL PRIMARY KEY,
      1,  --     zonegeographique_id      INTEGER NOT NULL REFERENCES zonesgeographiques(id),
      1,  --     typeorient_id           INTEGER NOT NULL REFERENCES typesorients(id),
      'Pole emploi',  --     lib_struc           VARCHAR(32) NOT NULL,
      '125',  --     num_voie            VARCHAR(6) NOT NULL, 
      'Avenue',  --     type_voie           VARCHAR(6) NOT NULL,
      'Alco', --     nom_voie            VARCHAR(30) NOT NULL,
      '34090',  --     code_postal         CHAR(5) NOT NULL,
      'Montpellier',  --     ville               VARCHAR(45) NOT NULL,
      '34095'  --     code_insee          CHAR(5) NOT NULL
   );

INSERT INTO structuresreferentes VALUES
   (
      2,  --     id                  SERIAL NOT NULL PRIMARY KEY,
      1,  --     zonegeographique_id      INTEGER NOT NULL REFERENCES zonesgeographiques(id),
      2,  --     typeorient_id           INTEGER NOT NULL REFERENCES typesorients(id),
      'Assedic',  --     lib_struc           VARCHAR(32) NOT NULL,
      '10',  --     num_voie            VARCHAR(6) NOT NULL, 
      'rue',  --     type_voie           VARCHAR(6) NOT NULL,
      'Georges Freche', --     nom_voie            VARCHAR(30) NOT NULL,
      '34000',  --     code_postal         CHAR(5) NOT NULL,
      'Montpellier',  --     ville               VARCHAR(45) NOT NULL,
      '34005'  --     code_insee          CHAR(5) NOT NULL
   );

INSERT INTO structuresreferentes VALUES
   (
      3,  --     id                  SERIAL NOT NULL PRIMARY KEY,
      1,  --     zonegeographique_id      INTEGER NOT NULL REFERENCES zonesgeographiques(id),
      3,  --     typeorient_id           INTEGER NOT NULL REFERENCES typesorients(id),
      'Assedic',  --     lib_struc           VARCHAR(32) NOT NULL,
      '44',  --     num_voie            VARCHAR(6) NOT NULL, 
      'chemin',  --     type_voie           VARCHAR(6) NOT NULL,
      'Parrot', --     nom_voie            VARCHAR(30) NOT NULL,
      '30000',  --     code_postal         CHAR(5) NOT NULL,
      'Nimes',  --     ville               VARCHAR(45) NOT NULL,
      '30009'  --     code_insee          CHAR(5) NOT NULL
   );
----------------------------------------
-- ------Référents -----------
-- -------------------------------------
INSERT INTO referents VALUES
   (
     1,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure',   --     nom varchar(28) null  ,
     'N° 11',   --     prenom varchar(32) null  ,
     '1111',   --     numero_poste char(4) null  ,
     'struct11@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     2,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure',   --     nom varchar(28) null  ,
     'N° 22',   --     prenom varchar(32) null  ,
     '2222',   --     numero_poste char(4) null  ,
     'boite_structure@fai.fr'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     3,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure',   --     nom varchar(28) null  ,
     'N° 33',   --     prenom varchar(32) null  ,
     '3333',   --     numero_poste char(4) null  ,
     ''  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     4,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     2,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Service social',   --     nom varchar(28) null  ,
     'X',   --     prenom varchar(32) null  ,
     '4444',   --     numero_poste char(4) null  ,
     ''  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     5,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     2,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Plan de ville',   --     nom varchar(28) null  ,
     'Y',   --     prenom varchar(32) null  ,
     '5555',   --     numero_poste char(4) null  ,
     'struct55@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     6,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     2,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure',   --     nom varchar(28) null  ,
     'N° 99',   --     prenom varchar(32) null  ,
     '6666',   --     numero_poste char(4) null  ,
     'struct99@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     7,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     3,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Plan de ville',   --     nom varchar(28) null  ,
     'Z',   --     prenom varchar(32) null  ,
     '7777',   --     numero_poste char(4) null  ,
     'pdv77@fai.com'  --     email varchar(78) null
   );


INSERT INTO referents VALUES
   (
     8,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     3,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Service social',   --     nom varchar(28) null  ,
     'Y',   --     prenom varchar(32) null  ,
     '8888',   --     numero_poste char(4) null  ,
     'ssocY@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     9,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     3,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure sociale',   --     nom varchar(28) null  ,
     'N° XY',   --     prenom varchar(32) null  ,
     '9999',   --     numero_poste char(4) null  ,
     'structXY@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     10,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     3,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Service social',   --     nom varchar(28) null  ,
     'ZZ',   --     prenom varchar(32) null  ,
     '1010',   --     numero_poste char(4) null  ,
     'xcs@fai.com'  --     email varchar(78) null
   );