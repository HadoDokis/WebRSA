SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
-- INFO: version actuelle: rSa_Echange_Instruction_v20091112.xls -> VIRS0203 IRSA-v4
-- *****************************************************************************
-- DROP TYPE type_booleannumber;
-- DROP TYPE type_no;
-- DROP TYPE type_nos;
-- DROP TYPE type_nov;
-- DROP TYPE type_sitpersdemrsa;
-- DROP TYPE type_nivetu;
-- DROP TYPE type_nivdipmaxobt;
-- DROP TYPE type_hispro;
-- DROP TYPE type_cessderact;
-- DROP TYPE type_duractdomi;
-- DROP TYPE type_inscdememploi;
-- DROP TYPE type_accoemploi;
-- DROP TYPE type_natlog;
-- DROP TYPE type_demarlog;

CREATE TYPE type_booleannumber AS ENUM ( '0', '1' );
CREATE TYPE type_no AS ENUM ( 'N', 'O' );
CREATE TYPE type_nos AS ENUM ( 'N', 'O', 'S' );
CREATE TYPE type_nov AS ENUM ( 'N', 'O', 'V' );
CREATE TYPE type_sitpersdemrsa AS ENUM ( '0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109' );
CREATE TYPE type_nivetu AS ENUM ( '1201', '1202', '1203', '1204', '1205', '1206', '1207' );
CREATE TYPE type_nivdipmaxobt AS ENUM ( '2601', '2602', '2603', '2604', '2605', '2606' );
CREATE TYPE type_hispro AS ENUM ( '1901', '1902', '1903', '1904' );
CREATE TYPE type_cessderact AS ENUM ( '2701', '2702' );
CREATE TYPE type_duractdomi AS ENUM ( '2104', '2105', '2106', '2107'  );
CREATE TYPE type_inscdememploi AS ENUM ( '4301', '4302', '4303', '4304'  );
CREATE TYPE type_accoemploi AS ENUM ( '1801', '1802', '1803'  );
CREATE TYPE type_natlog AS ENUM ( '0901', '0902', '0903', '0904', '0905', '0906', '0907', '0908', '0909', '0910', '0911', '0912', '0913'  );
CREATE TYPE type_demarlog AS ENUM ( '1101', '1102', '1103' );

CREATE TABLE dsps (
    id                      SERIAL NOT NULL PRIMARY KEY,
    personne_id             INTEGER NOT NULL REFERENCES personnes(id),
	-- Généralités
    sitpersdemrsa   		type_sitpersdemrsa DEFAULT NULL,
    topisogroouenf   		type_booleannumber DEFAULT NULL,
    topdrorsarmiant   		type_booleannumber DEFAULT NULL,
    drorsarmianta2   		type_nos DEFAULT NULL,
    topcouvsoc    			type_booleannumber DEFAULT NULL,
	-- SituationSociale - CommunSituationSociale
    accosocfam    			type_nov DEFAULT NULL,
    libcooraccosocfam  		VARCHAR(250) DEFAULT NULL,
    accosocindi    			type_nov DEFAULT NULL,
    libcooraccosocindi  	VARCHAR(250) DEFAULT NULL,
    soutdemarsoc   			type_nov DEFAULT NULL,
	-- NiveauEtude
    nivetu     				type_nivetu DEFAULT NULL,
    nivdipmaxobt   			type_nivdipmaxobt DEFAULT NULL,
    annobtnivdipmax   		CHAR(4) DEFAULT NULL,
    topqualipro    			type_booleannumber DEFAULT NULL,
    libautrqualipro   		VARCHAR(100) DEFAULT NULL,
    topcompeextrapro  		type_booleannumber DEFAULT NULL,
    libcompeextrapro  		VARCHAR(100) DEFAULT NULL,
	-- DisponibiliteEmploi
    topengdemarechemploi	type_booleannumber DEFAULT NULL,
	-- SituationProfessionnelle
    hispro     				type_hispro DEFAULT NULL,
    libderact    			VARCHAR(100) DEFAULT NULL,
    libsecactderact   		VARCHAR(100) DEFAULT NULL,
    cessderact    			type_cessderact DEFAULT NULL,
    topdomideract   		type_booleannumber DEFAULT NULL,
    libactdomi    			VARCHAR(100) DEFAULT NULL,
    libsecactdomi   		VARCHAR(100) DEFAULT NULL,
    duractdomi    			type_duractdomi DEFAULT NULL,
    inscdememploi   		type_inscdememploi DEFAULT NULL,
    topisogrorechemploi  	type_booleannumber DEFAULT NULL,
    accoemploi    			type_accoemploi DEFAULT NULL,
    libcooraccoemploi  		VARCHAR(100) DEFAULT NULL,
    topprojpro    			type_booleannumber DEFAULT NULL,
    libemploirech   		VARCHAR(250) DEFAULT NULL,
    libsecactrech   		VARCHAR(250) DEFAULT NULL,
    topcreareprientre		type_booleannumber DEFAULT NULL,
    concoformqualiemploi 	type_nos DEFAULT NULL,
	-- Mobilite - CommunMobilite
    topmoyloco    			type_booleannumber DEFAULT NULL,
    toppermicondub   		type_booleannumber DEFAULT NULL,
    topautrpermicondu  		type_booleannumber DEFAULT NULL,
    libautrpermicondu  		VARCHAR(100) DEFAULT NULL,
	-- DifficulteLogement - CommunDifficulteLogement
    natlog     				type_natlog DEFAULT NULL,
    demarlog				type_demarlog DEFAULT NULL
);
CREATE UNIQUE INDEX dsps_personne_id_idx ON dsps (personne_id);

/*
	0-n
		- SituationSociale
			* DetailDifficulteSituationSociale
			* DetailAccompagnementSocialFamilial
			* DetailAccompagnementSocialIndividuel
			* DetailDifficulteDisponibilite
		- Mobilite
			* DetailMobilite
		- DifficulteLogement
			* DetailDifficulteLogement
*/

-- -----------------------------------------------------------------------------
-- FIXME: nom de table et de modèle à faire partout (detailsdifsocs) plutôt que supprimer difsocs
-- DROP TABLE difsocs CASCADE; -- FIXME
CREATE TYPE type_difsoc AS ENUM ( '0401', '0402', '0403', '0404', '0405', '0406', '0407' );
CREATE TABLE difsocs (
    id      		SERIAL NOT NULL PRIMARY KEY,
    dsp_id			INTEGER NOT NULL REFERENCES dsps(id),
	difsoc			type_difsoc NOT NULL,
	libautrdifsoc	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX difsocs_dsp_id_idx ON difsocs (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_nataccosocfam AS ENUM ( '0410', '0411', '0412', '0413' );
CREATE TABLE detailsaccosocfams (
    id      			SERIAL NOT NULL PRIMARY KEY,
    dsp_id				INTEGER NOT NULL REFERENCES dsps(id),
	nataccosocfam		type_nataccosocfam NOT NULL,
	libautraccosocfam	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsaccosocfams_dsp_id_idx ON detailsaccosocfams (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_nataccosocindi AS ENUM ( '0416', '0417', '0418', '0419', '0420' );
CREATE TABLE detailsaccosocindis (
    id      			SERIAL NOT NULL PRIMARY KEY,
    dsp_id				INTEGER NOT NULL REFERENCES dsps(id),
	nataccosocindi		type_nataccosocindi NOT NULL,
	libautraccosocindi	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsaccosocindis_dsp_id_idx ON detailsaccosocindis (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_difdisp AS ENUM ( '0501', '0502', '0503', '0504', '0505', '0506' );
CREATE TABLE detailsdifdisps (
    id      	SERIAL NOT NULL PRIMARY KEY,
    dsp_id		INTEGER NOT NULL REFERENCES dsps(id),
	difdisp		type_difdisp NOT NULL
);
CREATE INDEX detailsdifdisps_dsp_id_idx ON detailsdifdisps (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_natmob AS ENUM ( '2504', '2501', '2502', '2503' );
CREATE TABLE detailsnatmobs (
    id      	SERIAL NOT NULL PRIMARY KEY,
    dsp_id		INTEGER NOT NULL REFERENCES dsps(id),
	natmob		type_natmob NOT NULL
);
CREATE INDEX detailsnatmobs_dsp_id_idx ON detailsnatmobs (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_diflog AS ENUM ( '1001', '1002', '1003', '1004', '1005', '1006', '1007', '1008', '1009' );
CREATE TABLE detailsdiflogs (
    id      		SERIAL NOT NULL PRIMARY KEY,
    dsp_id			INTEGER NOT NULL REFERENCES dsps(id),
	diflog			type_diflog NOT NULL,
	libautrdiflog	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsdiflogs_dsp_id_idx ON detailsdiflogs (dsp_id);

-- -----------------------------------------------------------------------------

-- INSERT INTO dsps (personne_id, sitpersdemrsa, topdrorsarmiant, drorsarmianta2, topcouvsoc, accosocfam, libcooraccosocfam, accosocindi, libcooraccosocindi, soutdemarsoc, libautrqualipro, libcompeextrapro, nivetu, annobtnivdipmax, topisogrorechemploi, accoemploi, libcooraccoemploi, hispro, libderact, libsecactderact, cessderact, topdomideract, libactdomi, libsecactdomi, duractdomi, libemploirech, libsecactrech, topcreareprientre, natlog, demarlog, topmoyloco, toppermicondub, libautrpermicondu)
-- 	SELECT	dspps.personne_id AS personne_id,
-- 			CAST( dspfs.motidemrsa AS type_sitpersdemrsa) AS sitpersdemrsa,
-- 			CAST( dspps.drorsarmiant AS type_no) AS topdrorsarmiant,
-- 			CAST( dspps.drorsarmianta2 AS type_nos) AS drorsarmianta2,
-- 			CAST( dspps.couvsoc AS type_no) AS topcouvsoc,
-- 			CAST( dspfs.accosocfam AS type_nov) AS accosocfam,
-- 			dspfs.libcooraccosocfam AS libcooraccosocfam,
-- 			CAST ( CASE WHEN COUNT(dspps_nataccosocindis.*) > 0 THEN 'O' ELSE null END AS type_nov ) AS accosocindi,
-- 			dspps.libcooraccosocindi AS libcooraccosocindi,
-- 			CAST( dspps.soutdemarsoc AS type_nov) AS soutdemarsoc,
-- 			dspps.libautrqualipro AS libautrqualipro,
-- 			dspps.libcompeextrapro AS libcompeextrapro,
-- 			MIN(nivetus.code) AS nivetu,
-- 			EXTRACT(YEAR FROM annderdipobt) AS annobtnivdipmax,
-- 			( CASE WHEN persisogrorechemploi = true THEN 'O' WHEN persisogrorechemploi = false THEN 'N' ELSE NULL END ) AS topisogrorechemploi,
-- 			MIN(accoemplois.code) AS accoemploi,
-- 			dspps.libcooraccoemploi AS libcooraccoemploi,
-- 			dspps.hispro AS hispro,
-- 			dspps.libderact AS libderact,
-- 			dspps.libsecactderact AS libsecactderact,
-- 			dspps.dfderact AS cessderact,
-- 			dspps.domideract AS topdomideract,
-- 			dspps.libactdomi AS libactdomi,
-- 			dspps.libsecactdomi AS libsecactdomi,
-- 			dspps.duractdomi AS duractdomi,
-- 			dspps.libemploirech AS libemploirech,
-- 			dspps.libsecactrech AS libsecactrech,
-- 			dspps.creareprisentrrech AS topcreareprientre,
-- 			dspfs.natlog AS natlog,
-- 			dspfs.demarlog AS demarlog,
-- 			( CASE WHEN moyloco = true THEN '1' WHEN moyloco = false THEN '0' ELSE NULL END ) AS topmoyloco,
-- 			( CASE WHEN permicondub = true THEN '1' WHEN permicondub = false THEN '0' ELSE NULL END ) AS toppermicondub,
-- 			dspps.libautrpermicondu AS libautrpermicondu
-- 		FROM dspps
-- 			INNER JOIN personnes ON personnes.id = dspps.personne_id
-- 			INNER JOIN foyers ON personnes.foyer_id = foyers.id
-- 			INNER JOIN dspfs ON dspfs.foyer_id = foyers.id
-- 			LEFT OUTER JOIN dspps_nataccosocindis ON dspps_nataccosocindis.dspp_id = dspps.id
-- 			LEFT OUTER JOIN dspps_nivetus ON dspps_nivetus.dspp_id = dspps.id
-- 			LEFT OUTER JOIN nivetus ON dspps_nivetus.nivetu_id = nivetus.id
-- 			LEFT OUTER JOIN dspps_accoemplois ON dspps_accoemplois.dspp_id = dspps.id
-- 			LEFT OUTER JOIN accoemplois ON dspps_accoemplois.accoemploi_id = accoemplois.id
-- 		GROUP BY dspps_nataccosocindis.dspp_id, dspps.id, dspps.personne_id,
-- 			dspfs.motidemrsa, dspps.drorsarmiant, dspps.drorsarmianta2, dspps.couvsoc, dspfs.accosocfam, dspfs.libcooraccosocfam, dspps.soutdemarsoc, dspps.libcooraccosocindi, dspps.libautrqualipro, dspps.libcompeextrapro, dspps_nivetus.dspp_id, dspps.annderdipobt, dspps.persisogrorechemploi, dspps.libcooraccoemploi, dspps.hispro, dspps.libderact, dspps.libsecactderact, dspps.dfderact, dspps.domideract, dspps.libactdomi, dspps.libsecactdomi, dspps.duractdomi, dspps.libemploirech, dspps.libsecactrech, dspps.creareprisentrrech, dspps_accoemplois.dspp_id, dspfs.natlog, dspfs.demarlog, dspps_nataccosocindis.dspp_id, dspps.moyloco, dspps.permicondub, dspps.libautrpermicondu;



-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "calculsdroitsrsa" liée à 'personnes'
-- --------------------------------------------------------------------------------------------------------

-- CREATE TABLE calculsdroitsrsa (
--     id                          SERIAL NOT NULL PRIMARY KEY,
--     personne_id                 INTEGER NOT NULL REFERENCES personnes(id),
--     toppersdrodevorsa           type_booleannumber DEFAULT NULL,
--     mtpersressmenrsa            NUMERIC(9,2),
--     mtpersabaneursa             NUMERIC(9,2)
-- );
CREATE INDEX calculsdroitsrsa_personne_id_idx ON calculsdroitsrsa (personne_id);

ALTER TABLE calculsdroitsrsa ADD COLUMN _tmp type_booleannumber DEFAULT NULL;
UPDATE calculsdroitsrsa SET _tmp = CAST( ( CASE WHEN toppersdrodevorsa = true THEN '1' WHEN toppersdrodevorsa = false THEN '0' ELSE NULL END ) AS type_booleannumber ); -- FIXME:
ALTER TABLE calculsdroitsrsa DROP COLUMN toppersdrodevorsa;
ALTER TABLE calculsdroitsrsa RENAME _tmp TO toppersdrodevorsa;

-- --------------------------------------------------------------------------------------------------------
-- Déplacement des trois champs toppersdrodevorsa / mtpersressmenrsa / mtpersabaneursa de leur table respectives (prestations / ressources / ressourcesmensuelles) vers la table calculsdroitsrsa
-- --------------------------------------------------------------------------------------------------------
INSERT INTO calculsdroitsrsa ( personne_id, toppersdrodevorsa, mtpersressmenrsa, mtpersabaneursa )
  SELECT  prestations.personne_id AS personne_id,
          CAST( ( CASE WHEN prestations.toppersdrodevorsa = true THEN '1' WHEN prestations.toppersdrodevorsa = false THEN '0' ELSE NULL END ) AS type_booleannumber ) AS toppersdrodevorsa,
          ressources.mtpersressmenrsa AS mtpersressmenrsa,
          SUM(ressourcesmensuelles.mtabaneu) AS mtpersabaneursa
      FROM prestations
          INNER JOIN personnes ON personnes.id = prestations.personne_id
          LEFT OUTER JOIN ressources ON personnes.id = ressources.personne_id
              AND ressources.dfress = ( SELECT MAX( latestRessource.dfress ) FROM ressources AS latestRessource WHERE latestRessource.personne_id = personnes.id )
          LEFT OUTER JOIN ressourcesmensuelles ON ressources.id = ressourcesmensuelles.ressource_id
      WHERE prestations.natprest = 'RSA' AND prestations.rolepers IN ( 'DEM', 'CJT' )
      GROUP BY prestations.personne_id, prestations.toppersdrodevorsa, ressources.mtpersressmenrsa, ressourcesmensuelles.ressource_id;

