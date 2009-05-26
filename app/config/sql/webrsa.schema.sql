/*
    TODO:
        - NULL, pas NULL -> voir xls
        - voir si des INT peuvent passer en TINYINT
        - nataccosocfam et diflog: faire les tables associées
        - vérifier types pour dossiers et personnes
*/

CREATE TABLE groups (
    id      SERIAL NOT NULL PRIMARY KEY,
    name    VARCHAR(50)
);

CREATE TABLE users (
    id                  SERIAL NOT NULL PRIMARY KEY,
    username            VARCHAR(50) NOT NULL,
    password            VARCHAR(50) NOT NULL,
    nom                 VARCHAR(50),
    prenom              VARCHAR(50),
    date_naissance      date,
    date_deb_hab        date,
    date_fin_hab        date
);

CREATE TABLE zonesgeographiques (
    id                  SERIAL NOT NULL PRIMARY KEY,
    codeinsee           CHAR(5) NOT NULL,
    libelle             VARCHAR(50) NOT NULL
);

CREATE TABLE users_zonesgeographiques (
    user_id             INT NOT NULL REFERENCES users (id),
    zonegeographique_id INT NOT NULL REFERENCES zonesgeographiques (id),
    PRIMARY KEY( user_id, zonegeographique_id )
);



CREATE TABLE dossiers_rsa (
    id                      SERIAL NOT NULL PRIMARY KEY,
    numdemrsa               VARCHAR(11),
    dtdemrsa                DATE,
    dtdemrmi                DATE,
    numdepinsrmi            CHAR(3),
    typeinsrmi              CHAR(1),
    numcominsrmi            FLOAT(3),
    numagrinsrmi            CHAR(2),
    numdosinsrmi            FLOAT(5),
    numcli                  FLOAT(3),
    numorg                  CHAR(3),
    fonorg                  CHAR(3),
    matricule               CHAR(15),
    statudemrsa             CHAR(1),
    typeparte               CHAR(4),
    ideparte                CHAR(3),
    fonorgcedmut            CHAR(3),
    numorgcedmut            CHAR(3),
    matriculeorgcedmut      CHAR(15),
    ddarrmut                DATE,
    codeposanchab           CHAR(5),
    fonorgprenmut           CHAR(3),
    numorgprenmut           CHAR(3),
    dddepamut               DATE,
    details_droits_rsa_id   INTEGER,
    avis_pcg_id             INTEGER,
    organisme_id            INTEGER,
    acompte_rsa_id          INTEGER
);


CREATE TABLE foyers (
    id                  SERIAL NOT NULL PRIMARY KEY,
    dossier_rsa_id      INT NOT NULL REFERENCES dossiers_rsa(id),
    sitfam              CHAR(3),
    ddsitfam            DATE,
    typeocclog          CHAR(3),
    mtvallocterr        FLOAT,
    mtvalloclog         FLOAT,
    contefichliairsa    TEXT
);

CREATE TABLE adresses (
    id          SERIAL NOT NULL PRIMARY KEY,
    numvoie     VARCHAR(6),
    typevoie    VARCHAR(4),
    nomvoie     VARCHAR(25),
    complideadr VARCHAR(38),
    compladr    VARCHAR(26),
    lieudist    VARCHAR(32),
    numcomrat   CHAR(5),
    numcomptt   CHAR(5),
    codepos     CHAR(5),
    locaadr     VARCHAR(26),
    pays        VARCHAR(3),
    canton      VARCHAR(20)
);

CREATE TABLE adresses_foyers (
    id          SERIAL NOT NULL PRIMARY KEY,
    adresse_id  INTEGER NOT NULL REFERENCES adresses(id),
    foyer_id    INTEGER NOT NULL REFERENCES foyers(id),
    rgadr       CHAR(2),
    dtemm       DATE,
    typeadr     CHAR(1)
);

CREATE TABLE paiementsfoyers (
    id                  SERIAL NOT NULL PRIMARY KEY,
    foyer_id            INTEGER NOT NULL REFERENCES foyers(id),
    topverstie          BOOLEAN,
    modepai             CHAR(2),
    topribconj          BOOLEAN,
    titurib             CHAR(3),
    nomprenomtiturib    VARCHAR(24),
    etaban              CHAR(5),
    guiban              CHAR(5),
    numcomptban         CHAR(11),
    clerib              SMALLINT,
    comban              VARCHAR(24)
);

CREATE TABLE personnes (
    id                      SERIAL NOT NULL PRIMARY KEY,
    foyer_id                INTEGER NOT NULL REFERENCES foyers(id),
    qual                    VARCHAR(3),
    nom                     VARCHAR(20),
    prenom                  VARCHAR(15),
    nomnai                  VARCHAR(20),
    prenom2                 VARCHAR(15),
    prenom3                 VARCHAR(15),
    nomcomnai               VARCHAR(26),
    dtnai                   DATE,
    rgnai                   INTEGER,
    typedtnai               CHAR(1),
    nir                     CHAR(15),
    topvalec                BOOLEAN,
    sexe                    CHAR(1),
    nati                    CHAR(1),
    dtnati                  DATE,
    pieecpres               CHAR(1),
    natprest                CHAR(3),
    rolepers                CHAR(3),
    topchapers              BOOLEAN, -- FIXME: pas dans l'édition de personne ?
    toppersdrodevorsa       BOOLEAN,
    idassedic               VARCHAR(8)
);

CREATE TABLE modes_contact (
    id              SERIAL NOT NULL PRIMARY KEY,
    foyer_id        INTEGER NOT NULL REFERENCES foyers(id),
    numtel          VARCHAR(11),
    numposte        INTEGER,
    nattel          CHAR(1),
    matetel         CHAR(3),
    autorutitel     CHAR(1),
    adrelec         VARCHAR(78),
    autorutiadrelec CHAR(1)
);

CREATE TABLE titres_sejour (
    id              SERIAL NOT NULL PRIMARY KEY,
    personne_id     INTEGER NOT NULL REFERENCES personnes(id),
    dtentfra        DATE,
    nattitsej       CHAR(3),
    menttitsej      CHAR(2),
    ddtitsej        DATE,
    dftitsej        DATE,
    numtitsej       VARCHAR(10),
    numduptitsej    INTEGER
);

CREATE TABLE rattachements (
    personne_id INTEGER NOT NULL REFERENCES personnes(id),
    rattache_id INTEGER NOT NULL REFERENCES personnes(id),
    typepar     CHAR(2),
    PRIMARY KEY( personne_id, rattache_id )
);

/* Suivis d'instruction */
CREATE TABLE suivisinstruction (
    id                      SERIAL NOT NULL PRIMARY KEY,
    dossier_rsa_id          INTEGER NOT NULL REFERENCES dossiers_rsa(id),
    etatirsa                CHAR(2) NOT NULL,
    date_etat_instruction   DATE,
    nomins                  VARCHAR(28),
    prenomins               VARCHAR(32),
    numdepins               CHAR(3),
    typeserins              CHAR(1),
    numcomins               CHAR(3),
    numagrins               INTEGER
);

/* Situations du dossier rsa */
CREATE TABLE situationsdossiersrsa (
    id                          SERIAL NOT NULL PRIMARY KEY,
    dossier_rsa_id              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
    etatdosrsa                  CHAR(1) NOT NULL,
    dtrefursa                   DATE,
    moticlorsa                  CHAR(3),
    dtclorsa                    date
);

CREATE TABLE suspensionsversements (
    id                          SERIAL NOT NULL PRIMARY KEY,
    situationdossierrsa_id      INTEGER NOT NULL REFERENCES situationsdossiersrsa(id),
    motisusversrsa              CHAR(2) NOT NULL,
    ddsusversrsa                DATE
);

CREATE TABLE suspensionsdroits (
    id                          SERIAL NOT NULL PRIMARY KEY,
    situationdossierrsa_id      INTEGER NOT NULL REFERENCES situationsdossiersrsa(id),
    motisusdrorsa               CHAR(2) NOT NULL,
    ddsusdrorsa                 DATE
);

/* Avis PCG droit rsa */
CREATE TABLE avispcgdroitrsa (
    id                          SERIAL NOT NULL PRIMARY KEY,
    dossier_rsa_id              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
    avisdestpairsa              CHAR(1) NOT NULL,
    dtavisdestpairsa            DATE,
    nomtie                      VARCHAR(64),
    typeperstie                 CHAR(1)
);

CREATE TABLE reducsrsa (
    id                          SERIAL NOT NULL PRIMARY KEY,
    avispcgdroitrsa_id          INTEGER NOT NULL REFERENCES avispcgdroitrsa(id),
    mtredrsa                    float(9),
    ddredrsa                    DATE,
    dfredrsa                    DATE
);

CREATE TABLE condsadmins (
    id                          SERIAL NOT NULL PRIMARY KEY,
    avispcgdroitrsa_id          INTEGER NOT NULL REFERENCES avispcgdroitrsa(id),
    aviscondadmrsa              CHAR(1),
    moticondadmrsa              CHAR(2),
    comm1condadmrsa             VARCHAR(60),
    comm2condadmrsa             VARCHAR(60),
    dteffaviscondadmrsa         DATE
);


CREATE TABLE activites (
    id              SERIAL NOT NULL PRIMARY KEY,
    personne_id     INTEGER NOT NULL REFERENCES personnes(id),
    reg             CHAR(2),
    act             CHAR(3),
    paysact         CHAR(3),
    ddact           DATE,
    dfact           DATE,
    natcontrtra     CHAR(3),
    topcondadmeti   BOOLEAN,
    hauremuscmic    CHAR(1) -- FIXME : hauremusmic cf flux instruction
);

CREATE TABLE dspfs (
    id                  SERIAL NOT NULL PRIMARY KEY,
    foyer_id            INTEGER NOT NULL REFERENCES foyers(id),
    motidemrsa          CHAR(4),
    accosocfam          BOOLEAN,
    libautraccosocfam   VARCHAR(100),
    libcooraccosocfam   VARCHAR(250),
    natlog              CHAR(4),
    libautrdiflog       VARCHAR(100),
    demarlog            CHAR(4)
);

CREATE TABLE nataccosocfams (
    id                         SERIAL NOT NULL PRIMARY KEY,
    code                       CHAR(4),
    name                       VARCHAR(100) -- FIXME
);

CREATE TABLE dspfs_nataccosocfams (
    nataccosocfam_id        INTEGER NOT NULL REFERENCES nataccosocfams(id),
    dspf_id                 INTEGER NOT NULL REFERENCES dspfs(id)
);


CREATE TABLE diflogs (
    id                         SERIAL NOT NULL PRIMARY KEY,
    code                       CHAR(4),
    name                       VARCHAR(100) -- FIXME
);

CREATE TABLE dspfs_diflogs (
    diflog_id       INTEGER NOT NULL REFERENCES diflogs(id),
    dspf_id         INTEGER NOT NULL REFERENCES dspfs(id)
);

/* Données socio-professionnelles */

CREATE TABLE dspps (
    id                  SERIAL NOT NULL PRIMARY KEY,
    personne_id         INTEGER NOT NULL REFERENCES personnes(id),
    drorsarmiant        BOOLEAN,
    drorsarmianta2      BOOLEAN,
    couvsoc              BOOLEAN,
    libautrdifsoc       VARCHAR(100),
    elopersdifdisp      BOOLEAN,
    obstemploidifdisp   BOOLEAN,
    soutdemarsoc        BOOLEAN,
    libautraccosocindi  VARCHAR(100),
    libcooraccosocindi  VARCHAR(250),
    annderdipobt        DATE,
    rappemploiquali     BOOLEAN,
    rappemploiform      BOOLEAN,
    libautrqualipro     VARCHAR(100),
    permicondub     BOOLEAN,
    libautrpermicondu   VARCHAR(100),
    libcompeextrapro    VARCHAR(100),
    persisogrorechemploi    BOOLEAN,
    accoemploi       CHAR(4),
    libcooraccoemploi   VARCHAR(100),
    hispro           CHAR(4),
    libderact       VARCHAR(100),
    libsecactderact     VARCHAR(100),
    dfderact            DATE,
    domideract      BOOLEAN,
    libactdomi      VARCHAR(100),
    libsecactdomi       VARCHAR(100),
    duractdomi       CHAR(4),
    libemploirech       VARCHAR(100),
    libsecactrech       VARCHAR(100),
    creareprisentrrech  BOOLEAN,
    moyloco             BOOLEAN,
    nivetu              CHAR(4)
);
-------------------------------
-------------------------------
CREATE TABLE difsocs (
    id      SERIAL NOT NULL PRIMARY KEY,
    code    CHAR(4),
    name    VARCHAR(100)
);


CREATE TABLE dspps_difsocs (
    difsoc_id   INTEGER NOT NULL REFERENCES difsocs(id),
    dspp_id   INTEGER NOT NULL REFERENCES dspps(id)
);
-------------------------------
-------------------------------
CREATE TABLE nataccosocindis (
    id      SERIAL NOT NULL PRIMARY KEY,
    code    CHAR(4),
    name    VARCHAR(100)
);

CREATE TABLE dspps_nataccosocindis (
    nataccosocindi_id   INTEGER NOT NULL REFERENCES nataccosocindis(id),
    dspp_id   INTEGER NOT NULL REFERENCES dspps(id)
);
-------------------------------
-------------------------------
CREATE TABLE difdisps (
    id      SERIAL NOT NULL PRIMARY KEY,
    code    CHAR(4),
    name    VARCHAR(100)
);

CREATE TABLE dspps_difdisps (
    difdisp_id   INTEGER NOT NULL REFERENCES difdisps(id),
    dspp_id   INTEGER NOT NULL REFERENCES dspps(id)
);

-------------------------------
-------------------------------
CREATE TABLE nivetus (
    id                         SERIAL NOT NULL PRIMARY KEY,
    code                       CHAR(4),
    name                       VARCHAR(100) -- FIXME
);

CREATE TABLE dspps_nivetus (
    nivetu_id        INTEGER NOT NULL REFERENCES nivetus(id),
    dspp_id                 INTEGER NOT NULL REFERENCES dspps(id)
);
-------------------------------
-------------------------------
CREATE TABLE natmobs (
    id      SERIAL NOT NULL PRIMARY KEY,
    code    CHAR(4),
    name    VARCHAR(100)
);

CREATE TABLE dspps_natmobs (
    natmob_id   INTEGER NOT NULL REFERENCES natmobs(id),
    dspp_id   INTEGER NOT NULL REFERENCES dspps(id)
);

-- -----------------------------------------------------------------------------
--       table : typesorients
-- -----------------------------------------------------------------------------
CREATE TABLE typesorients
   (
    id                 SERIAL NOT NULL PRIMARY KEY,
    parentid            INTEGER,
    lib_type_orient     VARCHAR(30),
    modele_notif        VARCHAR(20)
   );

---------------------------------
----------------------
--       table : structures_referents
 ----------------------------------------------------------------------------------------------

create table structuresreferentes
   (
    id                      SERIAL NOT NULL PRIMARY KEY,
    zonegeographique_id     INTEGER NOT NULL REFERENCES zonesgeographiques(id),
    typeorient_id           INTEGER NOT NULL REFERENCES typesorients(id),
    lib_struc               VARCHAR(32) NOT NULL,
    num_voie                VARCHAR(6) NOT NULL,
    type_voie               VARCHAR(6) NOT NULL,
    nom_voie                VARCHAR(30) NOT NULL,
    code_postal             CHAR(5) NOT NULL,
    ville                   VARCHAR(45) NOT NULL,
    code_insee              CHAR(5) NOT NULL
   );

-- -----------------------------------------------------------------------------
--       table : orient_struct
-- -----------------------------------------------------------------------------
create table orientsstructs
   (
    id                              SERIAL NOT NULL PRIMARY KEY,
    personne_id                     INTEGER NOT NULL REFERENCES personnes(id),
    structurereferente_id           INTEGER NOT NULL REFERENCES structuresreferentes(id),
    propo_algo                      INTEGER  REFERENCES typesorients(id),
--     propo_cg                        INTEGER  REFERENCES typesorients(id),
    valid_cg                        boolean null  ,
    date_propo                      date null  ,
    date_valid                      date null,
    statut_orient                   VARCHAR(15)
   );

-- -----------------------------------------------------------------------------
--       table : referents
-- -----------------------------------------------------------------------------
create table referents
   (
    id                      SERIAL NOT NULL PRIMARY KEY,
    structurereferente_id      INTEGER NOT NULL REFERENCES structuresreferentes(id),
    nom                     varchar(28) null  ,
    prenom                  varchar(32) null  ,
    numero_poste            char(4) null  ,
    email                   varchar(78) null
   );


-- -----------------------------------------------------------------------------
--       table : contratsinsertion
-- -----------------------------------------------------------------------------
create table contratsinsertion
   (
    id                              SERIAL NOT NULL PRIMARY KEY,
    personne_id                     INTEGER NOT NULL REFERENCES personnes(id),
    structurereferente_id           INTEGER NOT NULL REFERENCES structuresreferentes(id),
    dd_ci                           date null  ,
    df_ci                           date null  ,
    niv_etude                       varchar(30) null  ,
    diplomes                        varchar(120) null  ,
    form_compl                      varchar(60) null  ,
    expr_prof                       varchar(240) null  ,
    aut_expr_prof                   varchar(120) null  ,
    type_ci                         char(3) null     check (type_ci in ('pre', 'ren', 'red')),
    rg_ci                           int4 null  ,
    actions_prev                    varchar(120) null  ,
    obsta_renc                      varchar(120) null  ,
    service_soutien                 varchar(120) null  ,
    pers_charg_suivi                varchar(50) null  ,
    objectifs_fixes                 varchar(240) null  ,
    engag_object                    varchar(300) null  ,
    sect_acti_emp                   varchar(20) null  ,
    emp_occupe                      varchar(30) null  ,
    duree_hebdo_emp                 varchar(20) null  ,
    nat_cont_trav                   char(3) null  ,
    duree_cdd                       varchar(20) null  ,
    duree_engag                     int4 null  ,
    nature_projet                   varchar(300) null  ,
    observ_ci                       varchar(240) null  ,
    decision_ci                     char(1) null,
    datevalidation_ci               date
   );


-- -----------------------------------------------------------------------------
--       table : actions_insertion
-- -----------------------------------------------------------------------------

create table actionsinsertion
   (
    id                          SERIAL NOT NULL PRIMARY KEY,
    contratinsertion_id         INTEGER NOT NULL REFERENCES contratsinsertion(id),
    dd_action                   date null  ,
    df_action                   date null
   );

-- -----------------------------------------------------------------------------
--       table : aides_directes
-- -----------------------------------------------------------------------------
create table aidesdirectes
   (
    id                          SERIAL NOT NULL PRIMARY KEY,
    actioninsertion_id          INTEGER NOT NULL REFERENCES actionsinsertion(id),
    lib_aide                    varchar(32) null,
    typo_aide                   varchar(32) null,
    date_aide                   date null
   );

-- -----------------------------------------------------------------------------
--       table : prestsform
-- -----------------------------------------------------------------------------


create table refsprestas
   (
    id                  SERIAL NOT NULL PRIMARY KEY,
    nomrefpresta                 varchar(28) null  ,
    prenomrefpresta              varchar(32) null  ,
    emailrefpresta               varchar(78) null  ,
    numero_posterefpresta        varchar(4) null
   );



create table prestsform
   (
    id                  SERIAL NOT NULL PRIMARY KEY,
    actioninsertion_id  INTEGER NOT NULL REFERENCES actionsinsertion(id),
    refpresta_id        INTEGER NOT NULL REFERENCES refsprestas(id),
    lib_presta          varchar(32) null,
    date_presta         date
   );


-- -----------------------------------------------------------------------------
--       table Action: pour les prestations et aides
-- -----------------------------------------------------------------------------
create table typesactions
   (
        id                  SERIAL NOT NULL PRIMARY KEY,
        libelle             VARCHAR(250)
   );

create table actions
   (
        id                  SERIAL NOT NULL PRIMARY KEY,
        typeaction_id       INTEGER NOT NULL REFERENCES typesactions(id),
        code                CHAR(2),
        libelle             VARCHAR(250)
   );

-- -----------------------------------------------------------------------------
--       table : ressources
-- -----------------------------------------------------------------------------
create table ressources
    (
        id                                          SERIAL NOT NULL PRIMARY KEY,
        personne_id                                 INTEGER NOT NULL REFERENCES personnes(id),
        topressnul                                  boolean null  ,
        mtpersressmenrsa                            int4 null  ,
        ddress                                      date null  ,
        dfress                                      date null
    );

-- -----------------------------------------------------------------------------
--       table : ressources_mensuelles
-- -----------------------------------------------------------------------------

create table ressourcesmensuelles
   (
        id                                          SERIAL NOT NULL PRIMARY KEY,
        ressource_id                                INTEGER NOT NULL REFERENCES ressources(id),
        moisress                                    date null  ,
        nbheumentra                                 int4 null  ,
        mtabaneu                                    int4 null
   );

-- CREATE TABLE ressources_ressourcesmensuelles
--   (
--     ressourcemensuelle_id   INTEGER NOT NULL REFERENCES ressourcesmensuelles(id),
--     ressource_id   INTEGER NOT NULL REFERENCES ressources(id)
--  );
-- -----------------------------------------------------------------------------
--       table ressources_mensuelles
-- -----------------------------------------------------------------------------

create table detailsressourcesmensuelles
   (
        id                                          SERIAL NOT NULL PRIMARY KEY,
        ressourcemensuelle_id                       INTEGER NOT NULL REFERENCES ressourcesmensuelles(id),
        natress                                     char(3) null  ,
        mtnatressmen                                int4 null  ,
        abaneu                                      char(1) null,
        dfpercress                                  date,
        topprevsubsress                             boolean
   );

-- create table ressourcesmensuelles_detailsressourcesmensuelles
--    (
--         detailressourcemensuelle_id                 INTEGER NOT NULL REFERENCES detailsressourcesmensuelles(id),
--         ressourcemensuelle_id                       INTEGER NOT NULL REFERENCES ressourcesmensuelles(id)
--    );

-- -- -----------------------------------------------------------------------------
-- --       table : infosfinancieres (Volet Allocation)
-- -- -----------------------------------------------------------------------------
create table infosfinancieres
   (
        id                                          SERIAL NOT NULL PRIMARY KEY,
        dossier_rsa_id                              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
        moismoucompta                               date null,
        type_allocation                             varchar(25) null  ,
        natpfcre                                    char(3) null  ,
        rgcre                                       float(3) null  ,
        numintmoucompta                             float(7) null  ,
        typeopecompta                               char(3) null  ,
        sensopecompta                               char(2) null  ,
        mtmoucompta                                 float(11) null  ,
        ddregu                                      date null ,
        dttraimoucompta                             date null  ,
        heutraimoucompta                            date null
   );

-- -- -----------------------------------------------------------------------------
-- --       table : Identification Flux
-- -- -----------------------------------------------------------------------------
create table identificationsflux
    (
        id                                          SERIAL NOT NULL PRIMARY KEY,
        applieme                                    char(3) null  ,
        numversionapplieme                          char(4) null  ,
        typeflux                                    char(1) null  ,
        natflux                                     char(1) null  ,
        dtcreaflux                                  date null  ,
        heucreaflux                                 date null  ,
        dtref                                       date null
    );

create table totalisationsacomptes
    (
        id                                          SERIAL NOT NULL PRIMARY KEY,
        identificationflux_id                       INTEGER NOT NULL REFERENCES identificationsflux(id),
        type_totalisation                           varchar(30) null,
        mttotsoclrsa                                float(12) null  ,
        mttotsoclmajorsa                            float(12) null  ,
        mttotlocalrsa                               float(12) null  ,
        mttotrsa                                    float(12) null
    );


/* Details des droit rsa liés au Dossie rsa  */
CREATE TABLE detailsdroitsrsa
( ---- l.176 - 205 Beneficiaires
    id                          SERIAL NOT NULL PRIMARY KEY,
    dossier_rsa_id              INTEGER NOT NULL REFERENCES dossiers_rsa(id),
    topsansdomfixe              BOOLEAN,
    nbenfautcha                 FLOAT(2),
    oridemrsa                   CHAR(3),
    dtoridemrsa                 DATE,
    topfoydrodevorsa            BOOLEAN,
    ddelecal                    DATE,
    dfelecal                    DATE,
    mtrevminigararsa            FLOAT(9),
    mtpentrsa                   FLOAT(9),
    mtlocalrsa                  FLOAT(9),
    mtrevgararsa                FLOAT(9),
    mtpfrsa                     FLOAT(9),
    mtalrsa                     FLOAT(9),
    mtressmenrsa                FLOAT(9),
    mtsanoblalimrsa             FLOAT(9),
    mtredhosrsa                 FLOAT(9),
    mtredcgrsa                  FLOAT(9),
    mtcumintegrsa               FLOAT(9),
    mtabaneursa                 FLOAT(9),
    mttotdrorsa                 FLOAT(9)
);

CREATE TABLE detailscalculsdroitsrsa
(
    id                      SERIAL NOT NULL PRIMARY KEY,
    detaildroitrsa_id       INTEGER NOT NULL REFERENCES detailsdroitsrsa(id),
    natpf                   CHAR(3),
    sousnatpf               CHAR(5),
    ddnatdro                DATE,
    dfnatdro                DATE,
    mtrsavers               FLOAT(9),
    dtderrsavers            DATE
);


/* Infos agricoles Liées à la Personne  */
CREATE TABLE infosagricoles
( --l.120 - 128 Instructions
    id                  SERIAL NOT NULL PRIMARY KEY,
    personne_id         INTEGER NOT NULL REFERENCES personnes(id),
    mtbenagri           FLOAT(10),
    dtbenagri           DATE,
    regfisagri          CHAR(1)
);

CREATE TABLE aidesagricoles
(
    id                  SERIAL NOT NULL PRIMARY KEY,
    infoagricole_id     INTEGER NOT NULL REFERENCES infosagricoles(id),
    annrefaideagri      char(4),
    libnataideagri      varchar(30),
    mtaideagri          FLOAT(9)
);

/* Informations ETI Liées à la Personne */

CREATE TABLE informationseti
( --l.131 - 151 Instructions
    id                  SERIAL NOT NULL PRIMARY KEY,
    personne_id         INTEGER NOT NULL REFERENCES personnes(id),
    topcreaentre        BOOLEAN,
    topaccre            BOOLEAN,
    acteti              CHAR(1),
    topempl1ax          BOOLEAN,
    topstag1ax          BOOLEAN,
    topsansempl         BOOLEAN,
    ddchiaffaeti        DATE,
    dfchiaffaeti        DATE,
    mtchiaffaeti        FLOAT(9),
    regfiseti           CHAR(1),
    topbeneti           BOOLEAN,
    regfisetia1         CHAR(1),
    mtbenetia1          FLOAT(9),
    mtamoeti            FLOAT(9),
    mtplusvalueti       FLOAT(9),
    topevoreveti        BOOLEAN,
    libevoreveti        VARCHAR(30),
    topressevaeti       BOOLEAN
);

/* Grossesse liée à Personne */
CREATE TABLE grossesses
( --l.112 - 116 Beneficiaires
    id                  SERIAL NOT NULL PRIMARY KEY,
    personne_id         INTEGER NOT NULL REFERENCES personnes(id),
    ddgro               DATE,
    dfgro               DATE,
    dtdeclgro           DATE,
    natfingro           CHAR(1)
);
