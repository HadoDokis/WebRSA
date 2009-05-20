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
    'Uccle',                -- nomcomnai               VARCHAR(20),
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


INSERT INTO modescontact VALUES (
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

INSERT INTO contratsinsertion VALUES
   (
     1,           --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,           --     personne_id   INTEGER NOT NULL REFERENCES personnes(id),
     1,           --     structurereferente_id   INTEGER NOT NULL REFERENCES referents(id),
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

INSERT INTO contratsinsertion VALUES
   (
     2,           --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,           --     personne_id   INTEGER NOT NULL REFERENCES personnes(id),
     3,           --     structurereferente_id   INTEGER NOT NULL REFERENCES referents(id),
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
-- -----------------------------------------------------------------------------
--       table : aides_directes
-- -----------------------------------------------------------------------------
INSERT INTO aidesdirectes VALUES
   (
     1,                            --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,                         --      actioninsertion_id INTEGER NOT NULL REFERENCES actionsinsertion(id)
    '1F',                       --     lib_aide            varchar(32) null
    '1',
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
--       table : ref_presta
-- -----------------------------------------------------------------------------


INSERT INTO refsprestas VALUES
   (
     1,           --     id                  SERIAL NOT NULL PRIMARY KEY,
     --1,             --      prestform_id         INTEGER NOT NULL REFERENCES prestsform(id),
     'auzolat',           --     nom                 varchar(28) null  ,
     'arnaud',          --     prenom              varchar(32) null  ,
     'arnauz@adullact.com',           --     email               varchar(78) null  ,
     '1109'           --     numero_poste        varchar(4) null
   );


INSERT INTO prestsform VALUES
   (
     1,                   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,                     --  actioninsertion_id
     1,                   --     refpresta_id        INTEGER NOT NULL REFERENCES refsprestas(id),
     '1P'                   --     lib_presta          varchar(32) null
   );

-- -----------------------------------------------------------------------------
--       table : actions_insertion
-- -----------------------------------------------------------------------------
/*
INSERT INTO actionsinsertion VALUES
   (
     1,               --     id                  SERIAL NOT NULL PRIMARY KEY,
     'aide'               --     lib_action varchar(32) null
   );*/

-- -----------------------------------------------------------------------------
--       table : Infosfinancieres
-- -----------------------------------------------------------------------------
INSERT INTO infosfinancieres VALUES
   (
       1,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,     --         dossier_rsa_id                              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
       '2009-05-12',     --         moismoucompta                               date null,
       'AllocationComptabilisee',     --         type_allocation                             varchar(25) null  ,
       'RSB',     --         natpfcre                                    char(3) null  ,
       '1',     --         rgcre                                       float(3) null  ,
       '1234567',     --         numintmoucompta                             float(7) null  ,
       'PRA',     --         typeopecompta                               char(3) null  ,
       'AJ',     --         sensopecompta                               char(2) null  ,
       '1452',     --         mtmoucompta                                 float(11) null  ,
       '2009-05-31',     --         ddregu                                      date null ,
       '2009-05-22',     --         dttraimoucompta                             date null  ,
       '2009-05-10'     --         heutraimoucompta                            date null
   );

INSERT INTO infosfinancieres VALUES
   (
       2,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,     --         dossier_rsa_id                              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
       '2009-06-12',     --         moismoucompta                               date null,
       'AnnulationsFaibleMontant',     --         type_allocation                             varchar(25) null  ,
       'INP',     --         natpfcre                                    char(3) null  ,
       '2',     --         rgcre                                       float(3) null  ,
       '2468101',     --         numintmoucompta                             float(7) null  ,
       'CAF',     --         typeopecompta                               char(3) null  ,
       'DE',     --         sensopecompta                               char(2) null  ,
       '2140',     --         mtmoucompta                                 float(11) null  ,
       '2009-06-30',     --         ddregu                                      date null ,
       '2009-06-15',     --         dttraimoucompta                             date null  ,
       '2009-06-01'     --         heutraimoucompta                            date null
   );
-- -----------------------------------------------------------------------------
--       table : totalisations acomptes
-- -----------------------------------------------------------------------------
INSERT INTO identificationsflux VALUES
    (
       1,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       'jou',     --         applieme                                    char(3) null  ,
       '1111',     --         numversionapplieme                          char(4) null  ,
       '1',     --         typeflux                                    char(1) null  ,
       '2',     --         natflux                                     char(1) null  ,
       '2009-05-14',     --         dtcreaflux                                  date null  ,
       '2009-05-01',     --         heucreaflux                                 date null  ,
       '2009-05-22'     --         dtref                                       date null  
    );

INSERT INTO totalisationsacomptes VALUES
    (
       1,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,     --         identificationflux_id                       INTEGER NOT NULL REFERENCES identificationsflux(id),
       'TotalIndusConstates',     --         type_totalisation                           char(20) null,
       '1240',     --         mttotsoclrsa                                float(12) null  ,
       '1420',     --         mttotsoclmajorsa                            float(12) null  ,
       '1452',     --         mttotlocalrsa                               float(12) null  ,
       '4012'     --         mttotrsa                                    float(12) null
    );

INSERT INTO totalisationsacomptes VALUES
    (
       2,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,     --         identificationflux_id                       INTEGER NOT NULL REFERENCES identificationsflux(id),
       'TotalRemisesIndus',     --         type_totalisation                           char(20) null,
       '1240',     --         mttotsoclrsa                                float(12) null  ,
       '1420',     --         mttotsoclmajorsa                            float(12) null  ,
       '1452',     --         mttotlocalrsa                               float(12) null  ,
       '4012'     --         mttotrsa                                    float(12) null
    );

INSERT INTO totalisationsacomptes VALUES
    (
       3,     --         id                                          SERIAL NOT NULL PRIMARY KEY,
       1,     --         identificationflux_id                       INTEGER NOT NULL REFERENCES identificationsflux(id),
       'TotalRemisesIndus',     --         type_totalisation                           char(20) null,
       '1240.10',     --         mttotsoclrsa                                float(12) null  ,
       '1420.22',     --         mttotsoclmajorsa                            float(12) null  ,
       '1452.44',     --         mttotlocalrsa                               float(12) null  ,
       '4012.76'     --         mttotrsa                                    float(12) null
    );

-- -----------------------------------------------------------------------------
--       table : suivis instruction
-- -----------------------------------------------------------------------------
INSERT INTO suivisinstruction VALUES
    (
       1,     --     id                      SERIAL NOT NULL PRIMARY KEY,
       1,     --     dossier_rsa_id          INTEGER NOT NULL REFERENCES dossiers_rsa(id),
       '05',     --     etatirsa                CHAR(2) NOT NULL,
       '2009-05-15',     --     date_etat_instruction   DATE,
       'Auzolat',     --     nomins                  VARCHAR(28),
       'Arnaud',     --     prenomins               VARCHAR(28),
       '034',     --     numdepins               CHAR(3),
       '1',     --     typeserins              CHAR(1),
       '111',     --     numcomins               CHAR(3),
       '10'     --     numagrins               INTEGER
    );

INSERT INTO suivisinstruction VALUES
    (
       2,     --     id                      SERIAL NOT NULL PRIMARY KEY,
       1,     --     dossier_rsa_id          INTEGER NOT NULL REFERENCES dossiers_rsa(id),
       '06',     --     etatirsa                CHAR(2) NOT NULL,
       '2009-06-15',     --     date_etat_instruction   DATE,
       'Buffin',     --     nomins                  VARCHAR(28),
       'Christian',     --     prenomins               VARCHAR(28),
       '030',     --     numdepins               CHAR(3),
       '2',     --     typeserins              CHAR(1),
       '222',     --     numcomins               CHAR(3),
       '5'     --     numagrins               INTEGER
    );

-- -----------------------------------------------------------------------------
--       table : Situations du dossier rsa
-- -----------------------------------------------------------------------------

INSERT INTO situationsdossiersrsa VALUES
    (
       1,  --     id                          SERIAL NOT NULL PRIMARY KEY,
       1, --     dossier_rsa_id              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
       '1', --     etatdosrsa                  CHAR(1) NOT NULL,
       '2009-05-15', --     dtrefursa                   DATE,
       'EFF', --     moticlorsa                  CHAR(3),
       '2009-05-14' --     dtclorsa                    date
    );

INSERT INTO suspensionsversements VALUES
    (
       1,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     situationdossierrsa_id      INTEGER NOT NULL REFERENCES situationsdossiersrsa(id),
       '31',     --     motisusversrsa              CHAR(2) NOT NULL,
       '2009-05-13'     --     ddsusversrsa                DATE
    );

INSERT INTO suspensionsversements VALUES
    (
       2,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     situationdossierrsa_id      INTEGER NOT NULL REFERENCES situationsdossiersrsa(id),
       'AB',     --     motisusversrsa              CHAR(2) NOT NULL,
       '2009-05-18'     --     ddsusversrsa                DATE
    );


INSERT INTO suspensionsdroits VALUES
    (
       1,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     situationdossierrsa_id      INTEGER NOT NULL REFERENCES situationsdossiersrsa(id),
       'GK',     --     motisusdrorsa              CHAR(2) NOT NULL,
       '2009-05-11'     --     ddsusdrorsa                DATE
    );

INSERT INTO suspensionsdroits VALUES
    (
       2,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     situationdossierrsa_id      INTEGER NOT NULL REFERENCES situationsdossiersrsa(id),
       'GR',     --     motisusdrorsa              CHAR(2) NOT NULL,
       '2009-06-11'     --     ddsusdrorsa                DATE
    );
-- -----------------------------------------------------------------------------
--       table : Avis du PCG droit rsa
-- -----------------------------------------------------------------------------

INSERT INTO avispcgdroitrsa VALUES
    (
       1,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     dossier_rsa_id              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
       'D',     --     avisdestpairsa              CHAR(2) NOT NULL,
       '2009-05-15',     --     dtavisdestpairsa            DATE,
       'arnauz',     --     nomtie                      VARCHAR(64),
       'P'     --     typeperstie                 CHAR(1)
    );

INSERT INTO reducsrsa VALUES
    (
        1,    --     id                          SERIAL NOT NULL PRIMARY KEY,
        1,    --     avispcgdroitrsa_id          INTEGER NOT NULL REFERENCES avispcgdroitrsa(id),
        '114',    --     mtredrsa                    float(9),
        '2009-06-15',    --     ddredrsa                    DATE,
        '2009-07-11'    --     dfredrsa                    DATE
    );
INSERT INTO reducsrsa VALUES
    (
        2,    --     id                          SERIAL NOT NULL PRIMARY KEY,
        1,    --     avispcgdroitrsa_id          INTEGER NOT NULL REFERENCES avispcgdroitrsa(id),
        '4758',    --     mtredrsa                    float(9),
        '2009-02-13',    --     ddredrsa                    DATE,
        '2009-04-14'    --     dfredrsa                    DATE
    );

INSERT INTO condsadmins VALUES
    (
       1,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     avispcgdroitrsa_id          INTEGER NOT NULL REFERENCES avispcgdroitrsa(id),
       'R',     --     aviscondadmrsa              CHAR(1),
       'AF',     --     moticondadmrsa              CHAR(2),
       'Aucun commentaire 1',     --     comm1condadmrsa             VARCHAR(60),
       'Aucun commentaire 2',     --     comm2condadmrsa             VARCHAR(60),
       '2009-06-11'     --     dteffaviscondadmrsa         DATE
    );

INSERT INTO condsadmins VALUES
    (
       2,     --     id                          SERIAL NOT NULL PRIMARY KEY,
       1,     --     avispcgdroitrsa_id          INTEGER NOT NULL REFERENCES avispcgdroitrsa(id),
       'A',     --     aviscondadmrsa              CHAR(1),
       'DE',     --     moticondadmrsa              CHAR(2),
       'Youhou',     --     comm1condadmrsa             VARCHAR(60),
       'Cest super',     --     comm2condadmrsa             VARCHAR(60),
       '2009-08-11'     --     dteffaviscondadmrsa         DATE
    );


-- --------------------------------------------
-- -- Détails des droits rsa liés au Dossier --
-- --------------------------------------------
INSERT INTO detailsdroitsrsa VALUES
   (
     1, --     id                          SERIAL NOT NULL PRIMARY KEY,
     1, --     dossier_rsa_id              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
     '1', --     topsansdomfixe     CHAR(1),
     '12', --     nbenfautcha           FLOAT(2),
     'RMI', --     oridemrsa            CHAR(3),
     '2009-05-16', --     dtoridemrsa           DATE,
     '0', --     topfoydrodevorsa       CHAR(1),
     '2009-05-17', --     ddelecal          DATE,
     '2009-05-18', --     dfelecal          DATE,
     '1234', --     mtrevminigararsa        FLOAT(9),
     '2222', --     mtpentrsa           FLOAT(9),
     '3333', --     mtlocalrsa          FLOAT(9),
     '4444', --     mtrevgararsa        FLOAT(9),
     '5555', --     mtpfrsa         FLOAT(9),
     '6666', --     mtalrsa         FLOAT(9),
     '7777', --     mtressmenrsa        FLOAT(9),
     '8888', --     mtsanoblalimrsa     FLOAT(9),
     '1111', --     mtredhosrsa         FLOAT(9),
     '9999', --     mtredcgrsa          FLOAT(9),
     '1112', --     mtcumintegrsa       FLOAT(9),
     '2578', --     mtabaneursa         FLOAT(9),
     '6574' --     mttotdrorsa          FLOAT(9)
   );
   
INSERT INTO detailscalculsdroitsrsa VALUES
  (
     1, --     id               SERIAL NOT NULL PRIMARY KEY,
     1, --     detaildroitrsa_id        INTEGER NOT NULL REFERENCES detailsdroitsrsa(id),
     'RSD', --     natpf            CHAR(3),
     'RSDN2', --     sousnatpf          CHAR(5),
     '2009-06-01', --     ddnatdro          DATE,
     '2009-06-02', --     dfnatdro          DATE,
     '1578', --     mtrsavers           FLOAT(9),
     '2009-06-03' --     dtderrsavers       DATE  
  );
  
INSERT INTO detailscalculsdroitsrsa VALUES
  (
     2, --     id               SERIAL NOT NULL PRIMARY KEY,
     1, --     detaildroitrsa_id        INTEGER NOT NULL REFERENCES detailsdroitsrsa(id),
     'RCD', --     natpf            CHAR(3),
     'RCDN2', --     sousnatpf          CHAR(5),
     '2009-07-01', --     ddnatdro          DATE,
     '2009-07-02', --     dfnatdro          DATE,
     '1573', --     mtrsavers           FLOAT(9),
     '2009-07-03' --     dtderrsavers       DATE  
  );
  
------------------------------------------
-----Infos agricoles liées à la personne ----
------------------------------------------

INSERT INTO infosagricoles VALUES
( --l.120 - 128 Instructions
     1, --     id               SERIAL NOT NULL PRIMARY KEY,
     1, --     personne_id              INTEGER NOT NULL REFERENCES personnes(id),
     '1234', --     mtbenagri           FLOAT(10),
     '2009-05-21', --     dtbenagri         DATE,
     'F' --     regfisagri          CHAR(1)
);

INSERT INTO aidesagricoles VALUES
(
     1, --     id               SERIAL NOT NULL PRIMARY KEY,
     1, --     infoagricole_id          INTEGER NOT NULL REFERENCES infosagricoles(id),
     '2009', --     annrefaideagri      char(4),
     'Vendangeuse', --     libnataideagri       varchar(30),
     '2500' --     mtaideagri           FLOAT(9)
);

-- --------------------------------------------
-- -- Infos agricoles liées à la personne ----
-- --------------------------------------------
-- 
INSERT INTO informationseti VALUES
(
    1,  --     id               SERIAL NOT NULL PRIMARY KEY,
    1,  --     personne_id              INTEGER NOT NULL REFERENCES personnes(id),
    '1',  --     topcreaentre       CHAR(1),
    '0',  --     topaccre           CHAR(1),
    'A',  --     acteti         CHAR(1),
    '1',  --     topempl1ax         CHAR(1),
    '1',  --     topstag1ax         CHAR(1),
    '1',  --     topsansempl            CHAR(1),
    '2009-05-22',  --     ddchiaffaeti      DATE,
    '2009-05-23', --     dfchiaffaeti       DATE,
    '1111',  --     mtchiaffaeti        FLOAT(9),
    'R',  --     regfiseti          CHAR(1),
    '1',  --     topbeneti          CHAR(1),
    'S',  --     regfisetia1            CHAR(1),
    '2222',  --     mtbenetia1          FLOAT(9),
    '1234',  --     mtamoeti            FLOAT(9),
    '7896',  --     mtplusvalueti       FLOAT(9),
    '1',  --     topevoreveti       CHAR(1),
    'Evolution stable',  --     libevoreveti        VARCHAR(30),
    '0'  --     topressevaeti       CHAR(1)
);

--------------------------------------------
-- Grossesses liées à la personne ----
--------------------------------------------
INSERT INTO grossesses VALUES
( --l.112 - 116 Beneficiaires
    1,  --     id               SERIAL NOT NULL PRIMARY KEY,
    1,  --     personne_id              INTEGER NOT NULL REFERENCES personnes(id),
    '2009-01-15',  --     ddgro         DATE,
    '2009-10-10',  --     dfgro         DATE,
    '2009-03-20',  --     dtdeclgro         DATE,
    'I'  --     natfingro           CHAR(1)    
);


-- ----------------------------------------
-- -- ------ Types orientations -----------
-- -- -------------------------------------
INSERT INTO typesorients VALUES
   (
     1,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     null,   --            INTEGER,
     'Emploi',
     'Notifica 1'   
   );

INSERT INTO typesorients VALUES
   (
     2,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --                INTEGER,
     'Pôle emploi',
     'Notifica 2'
   );

INSERT INTO typesorients VALUES
   (
     3,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     1,   --                INTEGER,
    'Exploitant agricole MSA',
     'Notifica 3'
   );

INSERT INTO typesorients VALUES
   (
     4,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     null,   --                INTEGER,
     'Préprofessionnelle',
     'Notifica 1'
   );

INSERT INTO typesorients VALUES
   (
     5,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     4,     --                INTEGER,
    'Conseil Général',   --     name varchar(30) null
     'Notifica 2'
   );

INSERT INTO typesorients VALUES
   (
     6,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     null,  --                INTEGER,
    'Social',
     'Notifica 2'
   );

INSERT INTO typesorients VALUES
   (
     7,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'Conseil Général',
     'Notifica 3'
   );

INSERT INTO typesorients VALUES
   (
     8,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'MSA',
     'Notifica 1'
   );

INSERT INTO typesorients VALUES
   (
     9,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'Organisme agréés ACAL',
     'Notifica 2'
   );

INSERT INTO typesorients VALUES
   (
     10,   --     id                  SERIAL NOT NULL PRIMARY KEY,
     6,  --                INTEGER,
    'ATR',
     'Notifica 3'
   );
-- ----------------------------------------
-- -- ------ Orient structs -----------
-- -- -------------------------------------
INSERT INTO orientsstructs VALUES
   (
      1,  --     id                      SERIAL NOT NULL PRIMARY KEY,
      1,  --     personne_id             INTEGER NOT NULL REFERENCES personnes(id),
      1,  --     structurereferente_id             INTEGER NOT NULL REFERENCES structurereferente(id),
      1,      --     propo_algo                      INTEGER  REFERENCES typesorients(id),
--       1,      --     propo_cg                        INTEGER  REFERENCES typesorients(id),
      '1',  --     valid_cg                boolean null  ,
      '2009-04-28',  --     date_propo              date null  ,
      '2009-05-20',  --     date_valid              date null,
      'Non orienté',  --     statut_orient           VARCHAR(15)
      '2009-05-21'  
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