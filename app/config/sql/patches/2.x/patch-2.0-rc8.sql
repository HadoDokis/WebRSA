SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

BEGIN;

-- *****************************************************************************

CREATE TYPE type_munir AS ENUM ( 'CER', 'NCA', 'CV', 'AUT' );
ALTER TABLE actionscandidats_personnes ADD COLUMN pieceallocataire type_munir;

ALTER TABLE actionscandidats_personnes ADD COLUMN autrepiece VARCHAR(50);
ALTER TABLE actionscandidats_personnes ADD COLUMN precisionmotif TEXT;

ALTER TABLE actionscandidats_personnes ADD COLUMN presencecontrat type_no;
ALTER TABLE actionscandidats_personnes ADD COLUMN integrationaction type_no;

-- *****************************************************************************
-- ***** Modifications pour les équipes pluridisciplinaires (17/05/2010)   *****
-- *****************************************************************************

ALTER TABLE demandesreorient ADD COLUMN dtprementretien DATE NOT NULL;

ALTER TABLE precosreorients ALTER COLUMN referent_id DROP NOT NULL;
ALTER TABLE precosreorients ALTER COLUMN referent_id SET DEFAULT NULL;
ALTER TABLE precosreorients ADD COLUMN dtconcertation DATE DEFAULT NULL;

COMMIT;

-- *****************************************************************************
-- ***** Modifications pour les équipes pluridisciplinaires (26/05/2010)   *****
-- *****************************************************************************

BEGIN;

-- ********************************************************************
-- ***** Création des tables nécessaires au CUI ( 25/05/2010 )  *******
-- ********************************************************************

CREATE TYPE type_secteur AS ENUM ( 'CIE', 'CAE' );
-- CIE = Secteur marchand
-- CAE = secteur non marchand
CREATE TYPE type_avenant AS ENUM ( 'REN', 'MOD' );
-- REN = renouvellement
-- MOD = Modification
CREATE TYPE type_orgrecouvcotis AS ENUM ( 'URS', 'MSA', 'AUT' );
--URS = URSSAF
--MSA = MSA
--AUT = AUTRE
CREATE TYPE type_assurance AS ENUM ( 'UNE', 'LUI' );
-- UNE = l'employeur public ou privé est affilié à l'UNEDIC
-- LUI = l'employeur public assure lui-même ce risque
CREATE TYPE type_statutemployeur AS ENUM ( '10', '11', '21', '22', '50', '60', '70', '80', '90', '98', '99' );
-- cf formulaire cerfa du CUI tableau 1
CREATE TYPE type_niveauformation AS ENUM ( '70', '60', '50', '51', '40', '41', '30', '20', '10', '00' );
-- cf formulaire cerfa du CUI tableau 2
CREATE TYPE type_emploi AS ENUM ( '06', '11', '23', '24' );
-- 06 = moins de 6 mois
-- 11 = de 6 à 11 mois
-- 23 = de 12 à 23 mois
-- 24 = 24 et plus
CREATE TYPE type_typecontratcui AS ENUM ( 'CDI', 'CDD' );
CREATE TYPE type_initiative AS ENUM ( '1', '2', '3' ); -- A l'initiative de :
-- 1 = l'employeur
-- 2 = le salarié
-- 3 = le prescripteur
CREATE TYPE type_formation AS ENUM ( 'INT', 'EXT' );
-- INT = interne
-- EXT = externe

CREATE TYPE type_orgapayeur AS ENUM ( 'DEP', 'CAF', 'MSA', 'ASP', 'AUT' );
-- DEP = département
-- CAF = CAF
-- MSA = MSA
-- ASP = ASP
-- AUT = Autre

CREATE TYPE type_convention AS ENUM ( 'CES', 'EES' );

-- CES = Le Conseil Général, l'Employeur et le Salarié
-- EES = L'Etat, l'Employeur et le Salarié

CREATE TABLE cuis (
    id                               SERIAL NOT NULL PRIMARY KEY,
    personne_id                      INTEGER NOT NULL REFERENCES personnes(id),
    referent_id                      INTEGER NOT NULL REFERENCES referents(id),
    convention                       type_convention DEFAULT NULL,
    secteur                          type_secteur DEFAULT NULL,
    numsecteur                       VARCHAR(11) DEFAULT NULL,
    avenant                          type_avenant DEFAULT NULL,
    numconventioncollect             VARCHAR(9),
    avenantcg                        type_avenant DEFAULT NULL,
    datedepot                        DATE DEFAULT NULL,
    codeprescripteur                 VARCHAR(6) DEFAULT NULL,
    numeroide                        VARCHAR(8) DEFAULT NULL,
    nomemployeur                     VARCHAR(50),
    numvoieemployeur                 VARCHAR(6),
    typevoieemployeur                VARCHAR(4) NOT NULL,
    nomvoieemployeur                 VARCHAR(50) NOT NULL,
    compladremployeur                VARCHAR(50) NOT NULL,
    numtelemployeur                  VARCHAR(14),
    emailemployeur                   VARCHAR(78),
    codepostalemployeur              CHAR(5) NOT NULL,
    villeemployeur                   VARCHAR(45) NOT NULL,
    siret                            CHAR(14),
    codenaf2                         CHAR(5),
    identconvcollec                  CHAR(4),
    statutemployeur                  type_statutemployeur DEFAULT NULL,
    effectifemployeur                INTEGER,
    orgrecouvcotis                   type_orgrecouvcotis DEFAULT NULL,
    atelierchantier                  type_no DEFAULT NULL,
    numannexefinanciere              VARCHAR(9),
    assurancechomage                 type_assurance DEFAULT NULL,
    iscie                            type_no DEFAULT NULL,
    isadresse2                       type_no DEFAULT NULL,
    numvoieemployeur2                VARCHAR(50),
    typevoieemployeur2               VARCHAR(6),
    nomvoieemployeur2                VARCHAR(30) ,
    compladremployeur2               VARCHAR(32) ,
    numtelemployeur2                 VARCHAR(14),
    emailemployeur2                  VARCHAR(78),
    codepostalemployeur2             CHAR(5) ,
    villeemployeur2                  VARCHAR(45),
    niveauformation                  type_niveauformation DEFAULT NULL,
    dureesansemploi                  type_emploi DEFAULT NULL,
    isinscritpe                      type_no DEFAULT NULL,
    dureeinscritpe                   type_emploi DEFAULT NULL,
    isbeneficiaire                   type_no DEFAULT NULL,
    ass                              type_no DEFAULT NULL,
    rsadept                          type_no DEFAULT NULL,
    rsadeptmaj                       type_no DEFAULT NULL,
    aah                              type_no DEFAULT NULL,
    ata                              type_no DEFAULT NULL,
    dureebenefaide                   type_emploi DEFAULT NULL,
    handicap                         type_no DEFAULT NULL,
    typecontrat                      type_typecontratcui DEFAULT NULL,
    dateembauche                     DATE DEFAULT NULL,
    datefincontrat                   DATE DEFAULT NULL,
    codeemploi                       CHAR(5),
    salairebrut                      CHAR(5),
    dureehebdosalarieheure           INT,
    dureehebdosalarieminute          INT,
    modulation                       type_no DEFAULT NULL,
    dureecollhebdoheure              INT,
    dureecollhebdominute             INT,
    numlieucontrat                   VARCHAR(6),
    typevoielieucontrat              VARCHAR(4) NOT NULL,
    nomvoielieucontrat               VARCHAR(50) NOT NULL,
    codepostallieucontrat            CHAR(5) NOT NULL,
    villelieucontrat                 VARCHAR(45) NOT NULL,
    qualtuteur                       CHAR(3),
    nomtuteur                        VARCHAR(50),
    prenomtuteur                     VARCHAR(50),
    fonctiontuteur                   VARCHAR(50),
    structurereferente_id            INTEGER REFERENCES structuresreferentes(id),
    isaas                            type_no DEFAULT NULL,
    remobilisation                   type_initiative DEFAULT NULL,
    aidereprise                      type_initiative DEFAULT NULL,
    elaboprojetpro                   type_initiative DEFAULT NULL,
    evaluation                       type_initiative DEFAULT NULL,
    aiderechemploi                   type_initiative DEFAULT NULL,
    autre                            VARCHAR(50),
    adaptation                       type_initiative DEFAULT NULL,
    remiseniveau                     type_initiative DEFAULT NULL,
    prequalification                 type_initiative DEFAULT NULL,
    nouvellecompetence               type_initiative DEFAULT NULL,
    formqualif                       type_initiative DEFAULT NULL,
    formation                        type_formation DEFAULT NULL,
    isperiodepro                     type_no DEFAULT NULL,
    niveauqualif                     CHAR(2),
    validacquis                      type_no DEFAULT NULL,
    iscae                            type_no DEFAULT NULL,
    datedebprisecharge               DATE,
    datefinprisecharge               DATE,
    dureehebdoretenueheure           INT,
    dureehebdoretenueminute          INT,
    opspeciale                       CHAR(5),
    tauxfixe                         INTEGER,
    tauxprisencharge                 INTEGER,
    financementexclusif              type_no DEFAULT NULL,
    tauxfinancementexclusif          INTEGER,
    orgapayeur                       type_orgapayeur DEFAULT NULL,
    organisme                        VARCHAR(50),
    adresseorganisme                 TEXT,
    datecontrat                      DATE
);
COMMENT ON TABLE cuis IS 'Table pour les CUIs';
-- *****************************************************************************

ALTER TABLE demandesreorient ALTER COLUMN reforigine_id DROP NOT NULL;
ALTER TABLE demandesreorient ALTER COLUMN reforigine_id SET DEFAULT NULL;

-- *****************************************************************************
-- ***** Liste des départements (26/05/2010)                               *****
-- *****************************************************************************

CREATE TABLE departements (
    id      SERIAL NOT NULL PRIMARY KEY,
    numdep	VARCHAR(3) NOT NULL,
	name	VARCHAR(250) NOT NULL
);

CREATE UNIQUE INDEX departements_numdep_idx ON departements(numdep);
CREATE UNIQUE INDEX departements_name_idx ON departements(name);

INSERT INTO departements ( numdep, name ) VALUES
( '01', 'Ain' ),
( '02', 'Aisne' ),
( '03', 'Allier' ),
( '04', 'Alpes-de-Haute-Provence' ),
( '05', 'Hautes-Alpes' ),
( '06', 'Alpes-Maritimes' ),
( '07', 'Ardèche' ),
( '08', 'Ardennes' ),
( '09', 'Ariège' ),
( '10', 'Aube' ),
( '11', 'Aude' ),
( '12', 'Aveyron' ),
( '13', 'Bouches-du-Rhône' ),
( '14', 'Calvados' ),
( '15', 'Cantal' ),
( '16', 'Charente' ),
( '17', 'Charente-Maritime' ),
( '18', 'Cher' ),
( '19', 'Corrèze' ),
( '2A', 'Corse-du-Sud' ),
( '2B', 'Haute-Corse' ),
( '21', 'Côte-d''Or' ),
( '22', 'Côtes-d''Armor' ),
( '23', 'Creuse' ),
( '24', 'Dordogne' ),
( '25', 'Doubs' ),
( '26', 'Drôme' ),
( '27', 'Eure' ),
( '28', 'Eure-et-Loir' ),
( '29', 'Finistère' ),
( '30', 'Gard' ),
( '31', 'Haute-Garonne' ),
( '32', 'Gers' ),
( '33', 'Gironde' ),
( '34', 'Hérault' ),
( '35', 'Ille-et-Vilaine' ),
( '36', 'Indre' ),
( '37', 'Indre-et-Loire' ),
( '38', 'Isère' ),
( '39', 'Jura' ),
( '40', 'Landes' ),
( '41', 'Loir-et-Cher' ),
( '42', 'Loire' ),
( '43', 'Haute-Loire' ),
( '44', 'Loire-Atlantique' ),
( '45', 'Loiret' ),
( '46', 'Lot' ),
( '47', 'Lot-et-Garonne' ),
( '48', 'Lozère' ),
( '49', 'Maine-et-Loire' ),
( '50', 'Manche' ),
( '51', 'Marne' ),
( '52', 'Haute-Marne' ),
( '53', 'Mayenne' ),
( '54', 'Meurthe-et-Moselle' ),
( '55', 'Meuse' ),
( '56', 'Morbihan' ),
( '57', 'Moselle' ),
( '58', 'Nièvre' ),
( '59', 'Nord' ),
( '60', 'Oise' ),
( '61', 'Orne' ),
( '62', 'Pas-de-Calais' ),
( '63', 'Puy-de-Dôme' ),
( '64', 'Pyrénées-Atlantiques' ),
( '65', 'Hautes-Pyrénées' ),
( '66', 'Pyrénées-Orientales' ),
( '67', 'Bas-Rhin' ),
( '68', 'Haut-Rhin' ),
( '69', 'Rhône' ),
( '70', 'Haute-Saône' ),
( '71', 'Saône-et-Loire' ),
( '72', 'Sarthe' ),
( '73', 'Savoie' ),
( '74', 'Haute-Savoie' ),
( '75', 'Paris' ),
( '76', 'Seine-Maritime' ),
( '77', 'Seine-et-Marne' ),
( '78', 'Yvelines' ),
( '79', 'Deux-Sèvres' ),
( '80', 'Somme' ),
( '81', 'Tarn' ),
( '82', 'Tarn-et-Garonne' ),
( '83', 'Var' ),
( '84', 'Vaucluse' ),
( '85', 'Vendée' ),
( '86', 'Vienne' ),
( '87', 'Haute-Vienne' ),
( '88', 'Vosges' ),
( '89', 'Yonne' ),
( '90', 'Territoire de Belfort' ),
( '91', 'Essonne' ),
( '92', 'Hauts-de-Seine' ),
( '93', 'Seine-Saint-Denis' ),
( '94', 'Val-de-Marne' ),
( '95', 'Val-d''Oise' ),
( '971', 'Guadeloupe' ),
( '972', 'Martinique' ),
( '973', 'Guyane' ),
( '974', 'La Réunion' );

COMMIT;


BEGIN;
ALTER TABLE apres ADD COLUMN isdecision type_no DEFAULT NULL;
COMMIT;



-- *****************************************************************************
-- ***** Modifications de la table PDO (03/06/2010)                        *****
-- *****************************************************************************

BEGIN;
CREATE TYPE type_choixpdo AS ENUM ( 'PDO', 'JUS' );
ALTER TABLE propospdos ADD COLUMN choixpdo type_choixpdo DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN dateenvoiop DATE;
ALTER TABLE propospdos ADD COLUMN daterevision DATE;
ALTER TABLE propospdos ADD COLUMN dateecheance DATE;
ALTER TABLE propospdos ADD COLUMN decision type_booleannumber DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN suivi type_booleannumber DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN autres type_booleannumber DEFAULT NULL;

-----------------------------------------------------
CREATE TABLE statutsdecisionspdos (
    id                          SERIAL NOT NULL PRIMARY KEY,
    libelle                     VARCHAR(50)
);
CREATE INDEX statutsdecisionspdos_libelle_idx ON statutsdecisionspdos (libelle);
COMMENT ON TABLE statutsdecisionspdos IS 'Table des statuts des décisions de PDOs';

-----------------------------------------------------
CREATE TABLE propospdos_statutsdecisionspdos (
    id              SERIAL NOT NULL PRIMARY KEY,
    propopdo_id           INTEGER NOT NULL REFERENCES propospdos(id),
    statutdecisionpdo_id       INTEGER NOT NULL REFERENCES statutsdecisionspdos(id)
);

CREATE INDEX propospdos_statutsdecisionspdos_propopdo_id_idx ON propospdos_statutsdecisionspdos (propopdo_id);
CREATE INDEX propospdos_statutsdecisionspdos_statutpdo_id_idx ON propospdos_statutsdecisionspdos (statutdecisionpdo_id);

COMMENT ON TABLE propospdos_statutspdos IS 'Statuts des décisions liés aux PDOs';

ALTER TABLE propospdos DROP COLUMN statutdecision;
COMMIT;

-- **************************** Ajout du 04/06/2010 *****************************
BEGIN;
ALTER TABLE propospdos ADD COLUMN referent_id INTEGER DEFAULT NULL REFERENCES referents(id);
CREATE TYPE type_nonadmis AS ENUM ( 'CAN', 'RSP' );
-- CAN = Conditions d'admission non remplies
-- RSP = Ressource > plafond
ALTER TABLE propospdos ADD COLUMN nonadmis type_nonadmis DEFAULT NULL;
-- FIXME: trouver meilleur solution:
-- pour le moment on se contente d'un varchar car la liste des métiers à faire apparaître n'est peut-être pas la bonne
ALTER TABLE propospdos ADD COLUMN categoriegeneral VARCHAR(3) DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN categoriedetail VARCHAR(3) DEFAULT NULL;
COMMIT;

-- **************************** Ajout du 04/06/2010 - nouvelle gestion des droits *****************************

BEGIN;

UPDATE aros
        SET model=substring(alias FROM '^([^:]+)'),
                alias = substring(alias FROM '^.*:(.+)$');

UPDATE acos
        SET alias = ( 'Module:' || alias )
        WHERE substring(alias FROM '^.*:(.+)$') IS NULL;

COMMIT;

-- **************************** Ajout du 07/06/2010 - Traitement des PDOs *****************************
BEGIN;
DROP TABLE propospdos_typesnotifspdos;

CREATE TABLE descriptionspdos (
    id                              SERIAL NOT NULL PRIMARY KEY,
    name                            VARCHAR(50),
    modelenotification              VARCHAR(250),
    sensibilite                     type_no DEFAULT 'N'
);
COMMENT ON TABLE descriptionspdos IS 'Descriptions pour les traitmeents des PDOs';

CREATE TABLE traitementstypespdos (
    id                              SERIAL NOT NULL PRIMARY KEY,
    name                            VARCHAR(50)
);
COMMENT ON TABLE traitementstypespdos IS 'Types pour les traitements des PDOs';



CREATE TABLE traitementspdos (
    id                              SERIAL NOT NULL PRIMARY KEY,
    propopdo_id                     INTEGER NOT NULL REFERENCES propospdos(id),
    descriptionpdo_id                  INTEGER NOT NULL REFERENCES descriptionspdos(id),
    traitementtypepdo_id            INTEGER NOT NULL REFERENCES traitementstypespdos(id),
    datereception                   DATE,
    datedepart                      DATE,
    hascourrier                     type_booleannumber DEFAULT NULL,
    hasrevenu                       type_booleannumber DEFAULT NULL,
    haspiecejointe                  type_booleannumber DEFAULT NULL
);
COMMENT ON TABLE traitementspdos IS 'Traitements des PDOs';

ALTER TABLE cuis ALTER COLUMN typevoielieucontrat DROP NOT NULL;
ALTER TABLE cuis ALTER COLUMN typevoielieucontrat SET DEFAULT NULL;

ALTER TABLE cuis ALTER COLUMN nomvoielieucontrat DROP NOT NULL;
ALTER TABLE cuis ALTER COLUMN nomvoielieucontrat SET DEFAULT NULL;

ALTER TABLE cuis ALTER COLUMN codepostallieucontrat DROP NOT NULL;
ALTER TABLE cuis ALTER COLUMN codepostallieucontrat SET DEFAULT NULL;

ALTER TABLE cuis ALTER COLUMN villelieucontrat DROP NOT NULL;
ALTER TABLE cuis ALTER COLUMN villelieucontrat SET DEFAULT NULL;

COMMIT;

BEGIN;
ALTER TABLE actionscandidats_personnes ADD COLUMN rendezvous_id INTEGER REFERENCES rendezvous DEFAULT NULL;
COMMIT;