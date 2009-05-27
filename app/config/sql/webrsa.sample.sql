INSERT INTO users VALUES (
    1,
    'webrsa',
    '83a98ed2a57ad9734eb0a1694293d03c74ae8a57'
);

INSERT INTO users VALUES (
    2,
    'cg93',
    'ac860f0d3f51874b31260b406dc2dc549f4c6cde'
);

INSERT INTO users VALUES (
    3,
    'cg66',
    'c41d80854d210d5f7512ab216b53b2f2b8e742dc'
);

INSERT INTO dossiers_rsa VALUES (
    1,                      -- id                      SERIAL NOT NULL PRIMARY KEY,
    'AJ8ID907T5',           -- numdemrsa               VARCHAR(10),
    '2009-03-15',           -- dtdemrsa                DATE,
    '0',                    -- etatdosrsa              CHAR(1),
    NULL,                   -- dtrefursa               DATE,
    NULL,                   -- motisusversrsa          VARCHAR(50),
    NULL,                   -- ddsusversrsa            DATE,
    NULL,                   -- details_droits_rsa_id   INTEGER,
    NULL,                   -- avis_pcg_id             INTEGER,
    NULL,                   -- organisme_id            INTEGER,
    NULL                    -- acompte_rsa_id          INTEGER
);

INSERT INTO foyers VALUES (
    1,                  -- id                  SERIAL NOT NULL PRIMARY KEY,
    1,                  -- dossier_rsa_id      INT NOT NULL REFERENCES dossiers_rsa(id),
    'CEL',              -- sitfam              CHAR(3),
    '1979-01-24',       -- ddsitfam            DATE,
    'HGP',              -- typeocclog          CHAR(3),
    0,                  -- mtvallocterr        FLOAT,
    0,                  -- mtvalloclog         FLOAT,
    NULL                -- contefichliairsa    TEXT
);


INSERT INTO adresses VALUES (
    1,                  -- id          SERIAL NOT NULL PRIMARY KEY,
    '8',                -- numvoie     VARCHAR(6),
    'rue',              -- typevoie    VARCHAR(4),
    'des rosiers',      -- nomvoie     VARCHAR(25),
    NULL,               -- complideadr VARCHAR(38),
    NULL,               -- compladr    VARCHAR(32),
    'Agde',             -- lieudist    VARCHAR(32),
    '34003',            -- numcomrat   CHAR(5),
    '34300',            -- numcomptt   CHAR(5),
    '34300',            -- codepos     CHAR(5),
    'Agde',             -- locaadr     VARCHAR(26),
    'FRA'               -- pays        VARCHAR(3)
);

INSERT INTO adresses_foyers VALUES (
    1,                  -- id          SERIAL NOT NULL PRIMARY KEY,
    1,                  -- adresse_id  INTEGER NOT NULL REFERENCES adresses(id),
    1,                  -- foyer_id    INTEGER NOT NULL REFERENCES foyers(id),
    '01',               -- rgadr       CHAR(2),
    '2007-12-01',       -- dtemm       DATE,
    'D'                 -- typeadr     CHAR(1)
);

INSERT INTO adresses VALUES (
    2,                  -- id          SERIAL NOT NULL PRIMARY KEY,
    '9',                -- numvoie     VARCHAR(6),
    'rue',              -- typevoie    VARCHAR(4),
    'Rogier',      -- nomvoie     VARCHAR(25),
    NULL,               -- complideadr VARCHAR(38),
    NULL,               -- compladr    VARCHAR(32),
    'Dampremy',             -- lieudist    VARCHAR(32),
    '6020',            -- numcomrat   CHAR(5),
    '6020',            -- numcomptt   CHAR(5),
    '6020',            -- codepos     CHAR(5),
    'Dampremy',             -- locaadr     VARCHAR(26),
    'HOR'               -- pays        VARCHAR(3)
);

INSERT INTO adresses_foyers VALUES (
    2,                  -- id          SERIAL NOT NULL PRIMARY KEY,
    2,                  -- adresse_id  INTEGER NOT NULL REFERENCES adresses(id),
    1,                  -- foyer_id    INTEGER NOT NULL REFERENCES foyers(id),
    '02',               -- rgadr       CHAR(2),
    '2006-12-01',       -- dtemm       DATE,
    'D'                 -- typeadr     CHAR(1)
);

INSERT INTO personnes VALUES (
    1,                      -- id                      SERIAL NOT NULL PRIMARY KEY,
    1,                      -- foyer_id                INTEGER NOT NULL REFERENCES foyers(id),
    'MR',                   -- qual                    VARCHAR(3),
    'Buffin',               -- nom                     VARCHAR(20),
    'Christian',            -- prenom                  VARCHAR(15),
    NULL,                   -- nomnai                  VARCHAR(20),
    'Marie',                -- prenom2                 VARCHAR(15),
    'Joseph',               -- prenom3                 VARCHAR(15),
    'Uccle',                -- nomcomnais              VARCHAR(20),
    '1979-01-24',           -- dtnai                   DATE,
    1,                      -- rgnai                   INTEGER,
    'N',                    -- typedtnai               CHAR(1),
    '179019901601013',      -- nir                     CHAR(15),
    false,                  -- topvalec                CHAR(1),
    '1',                    -- sexe                    CHAR(1),
    'C',
    '1979-01-24',
    'E',
    'DEM'
);


INSERT INTO modes_contact VALUES (
    1,                              -- id              SERIAL NOT NULL PRIMARY KEY,
    1,                              -- foyer_id        INTEGER NOT NULL REFERENCES foyers(id),
    '0673940888',                   -- numtel          VARCHAR(11),
    NULL,                           -- numposte        INTEGER,
    'D',                            -- nattel          CHAR(1),
    'TEL',                          -- matetel         CHAR(3),
    'A',                            -- autorutitel     CHAR(1),
    'christian.buffin@gmail.com',   --adrelec         VARCHAR(78),
    'A'                             -- autorutiadrelec CHAR(1)
);
/* Données socioprofessionelles FOYER */

INSERT INTO dspfs VALUES (
    1,                                      --     id                  SERIAL NOT NULL PRIMARY KEY,
    1,                                      --     foyer_id            INTEGER NOT NULL REFERENCES foyers(id),
    '0101',                                 --     motidemrsa          CHAR(4),
    true,                                   --     accosocfam          BOOLEAN,
    'aucun',                                --     libautraccosocfam   VARCHAR(100),
    '20 avenue du loup \n Montpellier ',    --     libcooraccosocfam   VARCHAR(250),
    '0912',                                 --     natlog              CHAR(4),
    'Manque de moyen',                      --     libautrdiflog       VARCHAR(100),
    '1102'                                  --     demarlog            CHAR(4)
);
--------------------------------------
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

/* Données socio-professionnelles PERSONNE */

INSERT INTO dspps VALUES (
    1,
    1,
    '0',    --  --  drorsarmiant        BOOLEAN,
    '0',    --  -- drorsarmianta2       BOOLEAN,
    '1',    --  --couvsoc           BOOLEAN,
    'Aucunes',   --  --libautrdifsoc     VARCHAR(100),
    '0',    --  --elopersdifdisp        BOOLEAN,
    '0',    --  --obstemploidifdisp BOOLEAN,
    '1',    --  --soutdemarsoc      BOOLEAN,
    'Aucun', --  --libautraccosocindi    VARCHAR(100),
    'Pôle-emploi \n6 rue du travail \n34080 MONTPELLIER',   --  libcooraccosocindi  VARCHAR(250),
    '2007-07-04',   --  annderdipobt        DATE,
    '1',    --  rappemploiquali     BOOLEAN,
    '1',    --  rappemploiform      BOOLEAN,
    'Développement JAVA', --  libautrqualipro     VARCHAR(100),
    '1',    --  permicondub     BOOLEAN,
    'Aucunes',    --  libautrpermicondu   VARCHAR(100),
    'Webmaster',   --  libcompeextrapro    VARCHAR(100),
    '1',    --  persisogrorechemploi    BOOLEAN,
    '1802', -- accoemploi
    'Pôle-emploi \n6 rue du travail \n34080 MONTPELLIER',  --  libcooraccoemploi   VARCHAR(100),
    '1903',     --hispro
    'Développeur RetD', --  libderact       VARCHAR(100),
    'Informatique', --  libsecactderact     VARCHAR(100),
    '2006-12-20', --  dfderact        CHAR(10),
    '1',    --  domideract      BOOLEAN,
    'Codage',   --  libactdomi      VARCHAR(100),
    'Info/devpt',   --  libsecactdomi       VARCHAR(100),
    '2106',     --duractdomi
    'Informaticien',    --  libemploirech       VARCHAR(100),
    'Informatique', --  libsecactrech       VARCHAR(100),
    '1', --  creareprisentrrech  BOOLEAN,
    '1', --      moyloco     BOOLEAN
    '1201'  --      nivetu      CHAR(4)
);
--------------------------------------
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
--------------------------------------
--------------------------------------
-------------------------------
-- -------------------------------
----- Contrat d'insertion TESTS
-- ----------------------------------
INSERT INTO typesorients VALUES
   (
     1,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     'orientation'   --     name varchar(30) null
   );

insert into servicesreferents values
   (
     1,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     typeorient_id INTEGER NOT NULL REFERENCES typesorients(id),
     'super'  --     lib_serv varchar(32) null
   );

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
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Service social',   --     nom varchar(28) null  ,
     'X',   --     prenom varchar(32) null  ,
     '4444',   --     numero_poste char(4) null  ,
     ''  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     5,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Plan de ville',   --     nom varchar(28) null  ,
     'Y',   --     prenom varchar(32) null  ,
     '5555',   --     numero_poste char(4) null  ,
     'struct55@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     6,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure',   --     nom varchar(28) null  ,
     'N° 99',   --     prenom varchar(32) null  ,
     '6666',   --     numero_poste char(4) null  ,
     'struct99@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     7,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Plan de ville',   --     nom varchar(28) null  ,
     'Z',   --     prenom varchar(32) null  ,
     '7777',   --     numero_poste char(4) null  ,
     'pdv77@fai.com'  --     email varchar(78) null
   );


INSERT INTO referents VALUES
   (
     8,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Service social',   --     nom varchar(28) null  ,
     'Y',   --     prenom varchar(32) null  ,
     '8888',   --     numero_poste char(4) null  ,
     'ssocY@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     9,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Structure sociale',   --     nom varchar(28) null  ,
     'N° XY',   --     prenom varchar(32) null  ,
     '9999',   --     numero_poste char(4) null  ,
     'structXY@fai.com'  --     email varchar(78) null
   );

INSERT INTO referents VALUES
   (
     10,   --     --id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --     servicereferent_id INTEGER NOT NULL REFERENCES servicesreferents(id),
     'Service social',   --     nom varchar(28) null  ,
     'ZZ',   --     prenom varchar(32) null  ,
     '1010',   --     numero_poste char(4) null  ,
     'xcs@fai.com'  --     email varchar(78) null
   );


INSERT INTO contratsinsertion VALUES
   (
     1,           --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,           --     personne_id   INTEGER NOT NULL REFERENCES personnes(id),
     1,           --     referent_id   INTEGER NOT NULL REFERENCES referents(id),
     '2008-01-01',           --     dd_ci date null  ,
     '2009-01-01',           --     df_ci date null  ,
     'Bac +5',           --     niv_etude varchar(30) null  ,
     'Bac, DEUG MIAS, DUP, Master 2 Pro',           --     diplomes varchar(120) null  ,
     'Aucune',           --     form_compl varchar(60) null  ,
     'Technicien, Hot line, Développeur',           --     expr_prof varchar(240) null  ,
     '',           --     aut_expr_prof varchar(120) null  ,
     'pre',           --     type_ci char(3) null     check (type_ci in ('pre', 'ren', 'red')),
     '1',           --     rg_ci int4 null  ,
     '',           --     actions_prev varchar(120) null  ,
     '',           --     obsta_renc varchar(120) null  ,
     'Pole emploi \n, Montpellier',          --     service_soutien varchar(120) null  ,
     'Maurice LUC',           --     pers_charg_suivi varchar(50) null  ,
     'De nombreux objectifs',           --     objectifs_fixes varchar(240) null  ,
     'Envoi de CV \n, postuler sur internet, créer mon projet professionnel',           --     engag_object varchar(300) null  ,
     'Informatique',           --     sect_acti_emp varchar(20) null  ,
     'Développeur',           --     emp_occupe varchar(30) null  ,
     '35h',           --     duree_hebdo_emp varchar(20) null  ,
     'CDD',            --     nat_cont_trav char(3) null  ,
     '3 mois',           --     duree_cdd varchar(20) null  ,
     '3',           --     duree_engag int4 null  ,
     'Créer mon entreprise',           --     nature_projet varchar(300) null  ,
     'Pas dobservations notables',           --     observ_ci varchar(240) null  ,
     'v',           --     decision_ci char(1) null,
     '2009-04-24'           --     datevalidation_ci date
   );






-- INSERT INTO typoscontrats VALUES
--    (
--     1,                  --SERIAL NOT NULL PRIMARY KEY,
--     'cdd'
--    );



-- -----------------------------------------------------------------------------
--       table : types_aides
-- -----------------------------------------------------------------------------
/*
INSERT INTO typesaides VALUES
   (
     1,                   --     id                  SERIAL NOT NULL PRIMARY KEY,
     'sos'                   --     lib_typo_aide varchar(32) null
   );*/
-- -----------------------------------------------------------------------------
--       table : actions_insertion
-- -----------------------------------------------------------------------------

INSERT INTO actionsinsertion VALUES
   (
     1,               --     id                  SERIAL NOT NULL PRIMARY KEY,
     'dépôt'               --     lib_action varchar(32) null
   );

-- -----------------------------------------------------------------------------
--       table : aides_directes
-- -----------------------------------------------------------------------------
INSERT INTO aidesdirectes VALUES
   (
     1,                            --     id                  SERIAL NOT NULL PRIMARY KEY,
    '1F',                       --     lib_aide            varchar(32) null
     '2009-02-07'
    );


-- -----------------------------------------------------------------------------
--       table : actions_insertion_liees
-- -----------------------------------------------------------------------------

INSERT INTO actionsinsertion_liees VALUES
   (
     1,           --     contratinsertion_id INTEGER NOT NULL REFERENCES contratsinsertion(id),
     1,           --     actioninsertion_id INTEGER NOT NULL REFERENCES actionsinsertion(id),
     '2008-01-01',           --     dd_action date null  ,
     '2009-01-01'           --     df_action date null,
                --     constraint pk_actionsinsertion_liees primary key (contratinsertion_id, actioninsertion_id)

   );

-- -----------------------------------------------------------------------------
--       table : aides_liees
-- -----------------------------------------------------------------------------

INSERT INTO aides_liees VALUES
   (
     1,           --     actioninsertion_id INTEGER NOT NULL REFERENCES actionsinsertion(id),
     1--,           --     aidedirecte_id INTEGER NOT NULL REFERENCES aidesdirectes(id),
     --'2009-04-27'           --     date_aide date null,
                --     constraint pk_aides_liees primary key (actioninsertion_id, aidedirecte_id)
   );


-- -----------------------------------------------------------------------------
--       table : ref_presta
-- -----------------------------------------------------------------------------

INSERT INTO refsprestas VALUES
   (
     1,           --     id                  SERIAL NOT NULL PRIMARY KEY,
     'auzolat',           --     nom                 varchar(28) null  ,
     'arnaud',          --     prenom              varchar(32) null  ,
     'arnauz@adullact.com',           --     email               varchar(78) null  ,
     '1109'           --     numero_poste        varchar(4) null
   );


-- -----------------------------------------------------------------------------
--       table : prestsform
-- -----------------------------------------------------------------------------

INSERT INTO prestsform VALUES
   (
     1,                   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,                   --     refpresta_id        INTEGER NOT NULL REFERENCES refsprestas(id),
     'Dépannage'                   --     lib_presta          varchar(32) null
   );

-- -----------------------------------------------------------------------------
--       table : presta_lies
-- -----------------------------------------------------------------------------

INSERT INTO presta_lies VALUES
   (
     1,           --     prestsform_id INTEGER NOT NULL REFERENCES prestsform(id),
     1           --     refpresta_id INTEGER NOT NULL REFERENCES refsprestas(id),
                -- ,   constraint pk_presta_lies primary key (id, id_1)
   );
-- -----------------------------------------------------------------------------
--       table : refsprestas_lies
-- -----------------------------------------------------------------------------

INSERT INTO refsprestas_liees VALUES
   (
     1,
     1
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


/*

-- -----------------------------------------------------------------------------
--       table : ressources
-- -----------------------------------------------------------------------------
INSERT INTO ressources  VALUES
    (
       1,                     --         id                          SERIAL NOT NULL PRIMARY KEY,
        1,                  --         id                           INTEGER NOT NULL REFERENCES personnes(id),
       '1',                     --         topressnul                                  boolean null  ,
       '1500',                     --         mtpersressmenrsa                            int4 null  ,
       '2009-04-30',                     --         ddress                                      date null  ,
       '2009-07-31'                     --         dfress                                      date null
    );


-- -----------------------------------------------------------------------------
--       table : ressourcesmensuelles
-- -----------------------------------------------------------------------------

INSERT INTO ressourcesmensuelles VALUES
   (
       1,                     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,
       '2009-05-01',                     --         moisress                                    date null  ,
       '35',                     --         nbheumentra                                 int4 null  ,
       '99'                     --         mtabaneu                                    int4 null
   );
INSERT INTO ressourcesmensuelles VALUES
   (
       2,                     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,
       '2009-06-01',                     --         moisress                                    date null  ,
       '105',                     --         nbheumentra                                 int4 null  ,
       '150'                     --         mtabaneu                                    int4 null
   );
INSERT INTO ressourcesmensuelles VALUES
   (
       3,                     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,
       '2009-07-01',                     --         moisress                                    date null  ,
       '145',                     --         nbheumentra                                 int4 null  ,
       '200'                     --         mtabaneu                                    int4 null
   );

INSERT INTO ressources_ressourcesmensuelles VALUES(
      1,              --     ressourcemensuelle_id   INTEGER NOT NULL REFERENCES ressourcesmensuelles(id),
      1              --     ressource_id   INTEGER NOT NULL REFERENCES ressources(id)
);
INSERT INTO ressources_ressourcesmensuelles VALUES(
      2,              --     ressourcemensuelle_id   INTEGER NOT NULL REFERENCES ressourcesmensuelles(id),
      1              --     ressource_id   INTEGER NOT NULL REFERENCES ressources(id)
);
INSERT INTO ressources_ressourcesmensuelles VALUES(
      3,              --     ressourcemensuelle_id   INTEGER NOT NULL REFERENCES ressourcesmensuelles(id),
      1              --     ressource_id   INTEGER NOT NULL REFERENCES ressources(id)
);
-- -----------------------------------------------------------------------------
--       table ressources_mensuelles
-- -----------------------------------------------------------------------------

INSERT INTO detailsressourcesmensuelles VALUES
   (
       1,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
        1,      --          id                                      INTEGER NOT NULL REFERENCES personnes(id)
       '999',     --         natress                                     char(3) null  ,
       '1500',     --         mtnatressmen                                int4 null  ,
       'A',     --         abaneu                                      char(1) null  ,
       '2009-05-30',     --         dfpercress                                  date null  ,
       '0'     --         topprevsubsress                             boolean null
   );
INSERT INTO detailsressourcesmensuelles VALUES
   (
       2,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
        1,      --          id                                      INTEGER NOT NULL REFERENCES personnes(id)
       '400',     --         natress                                     char(3) null  ,
       '2400',     --         mtnatressmen                                int4 null  ,
       'A',     --         abaneu                                      char(1) null  ,
       '2009-06-30',     --         dfpercress                                  date null  ,
       '1'     --         topprevsubsress                             boolean null
   );
INSERT INTO detailsressourcesmensuelles VALUES
   (
       3,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
        1,      --          id                                      INTEGER NOT NULL REFERENCES personnes(id)
       '301',     --         natress                                     char(3) null  ,
       '1230',     --         mtnatressmen                                int4 null  ,
       'N',     --         abaneu                                      char(1) null  ,
       '2009-07-30',     --         dfpercress                                  date null  ,
       '1'     --         topprevsubsress                             boolean null
   );
INSERT INTO ressourcesmensuelles_detailsressourcesmensuelles VALUES
   (
      1,  --         detailressourcemensuelle_id                 INTEGER NOT NULL REFERENCES detailsressourcesmensuelles(id),
      1  --         ressourcemensuelle_id                       INTEGER NOT NULL REFERENCES ressourcesmensuelles(id)
   );

INSERT INTO ressourcesmensuelles_detailsressourcesmensuelles VALUES
   (
      2,  --         detailressourcemensuelle_id                 INTEGER NOT NULL REFERENCES detailsressourcesmensuelles(id),
      2  --         ressourcemensuelle_id                       INTEGER NOT NULL REFERENCES ressourcesmensuelles(id)
   );
INSERT INTO ressourcesmensuelles_detailsressourcesmensuelles VALUES
   (
      3,  --         detailressourcemensuelle_id                 INTEGER NOT NULL REFERENCES detailsressourcesmensuelles(id),
      3  --         ressourcemensuelle_id                       INTEGER NOT NULL REFERENCES ressourcesmensuelles(id)
   );*/