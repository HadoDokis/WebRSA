CREATE TABLE dsps (
    id                      SERIAL NOT NULL PRIMARY KEY,
    personne_id             INTEGER NOT NULL REFERENCES personnes(id),
	sitpersdemrsa   		CHAR(4) DEFAULT NULL,
	topisogroouenf   		CHAR(1) DEFAULT NULL,
	topdrorsarmiant   		CHAR(1) DEFAULT NULL,
	drorsarmianta2   		CHAR(1) DEFAULT NULL,
	topcouvsoc    			CHAR(1) DEFAULT NULL,
	accosocfam    			CHAR(1) DEFAULT NULL,
	libcooraccosocfam  		VARCHAR(250) DEFAULT NULL,
	accosocindi    			CHAR(1) DEFAULT NULL,
	libcooraccosocindi  	VARCHAR(250) DEFAULT NULL,
	soutdemarsoc   			CHAR(1) DEFAULT NULL,
	nivetu     				CHAR(4) DEFAULT NULL,
	nivdipmaxobt   			CHAR(4) DEFAULT NULL,
	annobtnivdipmax   		CHAR(4) DEFAULT NULL,
	topqualipro    			CHAR(1) DEFAULT NULL,
	libautrqualipro   		VARCHAR(100) DEFAULT NULL,
	topcompeextrapro  		CHAR(1) DEFAULT NULL,
	libcompeextrapro  		VARCHAR(100) DEFAULT NULL,
	topengdemarechemploi	CHAR(1) DEFAULT NULL,
	hispro     				CHAR(4) DEFAULT NULL,
	libderact    			VARCHAR(100) DEFAULT NULL,
	libsecactderact   		VARCHAR(100) DEFAULT NULL,
	cessderact    			CHAR(4) DEFAULT NULL,
	topdomideract   		CHAR(1) DEFAULT NULL,
	libactdomi    			VARCHAR(100) DEFAULT NULL,
	libsecactdomi   		VARCHAR(100) DEFAULT NULL,
	duractdomi    			CHAR(4) DEFAULT NULL,
	inscdememploi   		CHAR(4) DEFAULT NULL,
	topisogrorechemploi  	CHAR(1) DEFAULT NULL,
	accoemploi    			CHAR(4) DEFAULT NULL,
	libcooraccoemploi  		VARCHAR(100) DEFAULT NULL,
	topprojpro    			CHAR(1) DEFAULT NULL,
	libemploirech   		VARCHAR(250) DEFAULT NULL,
	libsecactrech   		VARCHAR(250) DEFAULT NULL,
	topcreareprientre		CHAR(1) DEFAULT NULL,
	concoformqualiemploi 	CHAR(1) DEFAULT NULL,
	topmoyloco    			CHAR(1) DEFAULT NULL,
	toppermicondub   		CHAR(1) DEFAULT NULL,
	topautrpermicondu  		CHAR(1) DEFAULT NULL,
	libautrpermicondu  		VARCHAR(100) DEFAULT NULL,
	natlog     				CHAR(4) DEFAULT NULL,
	demarlog				CHAR(4) DEFAULT NULL
);

CREATE /*UNIQUE*/ INDEX dsps_personne_id_idx ON dsps (personne_id);

-- -----------------------------------------------------------------------------

INSERT INTO dsps (personne_id, sitpersdemrsa, topdrorsarmiant, drorsarmianta2, topcouvsoc, accosocfam, libcooraccosocfam, accosocindi, libcooraccosocindi, soutdemarsoc, libautrqualipro, libcompeextrapro, nivetu, annobtnivdipmax, topisogrorechemploi, accoemploi, libcooraccoemploi, hispro, libderact, libsecactderact, cessderact, topdomideract, libactdomi, libsecactdomi, duractdomi, libemploirech, libsecactrech, topcreareprientre, natlog, demarlog, topmoyloco, toppermicondub, libautrpermicondu)
	SELECT	dspps.personne_id,
			dspfs.motidemrsa,
			dspps.drorsarmiant,
			dspps.drorsarmianta2,
			dspps.couvsoc,
			dspfs.accosocfam,
			dspfs.libcooraccosocfam,
			( CASE WHEN COUNT(dspps_nataccosocindis.*) > 0 THEN 'O' ELSE null END ),
			dspps.libcooraccosocindi,
			dspps.soutdemarsoc,
			dspps.libautrqualipro,
			dspps.libcompeextrapro,
			MIN(nivetus.code),
			dspps.annderdipobt,
			( CASE WHEN persisogrorechemploi = true THEN 'O' WHEN persisogrorechemploi = false THEN 'N' ELSE NULL END ),
			MIN(accoemplois.code),
			dspps.libcooraccoemploi,
			dspps.hispro,
			dspps.libderact,
			dspps.libsecactderact,
			dspps.dfderact,
			dspps.domideract,
			dspps.libactdomi,
			dspps.libsecactdomi,
			dspps.duractdomi,
			dspps.libemploirech,
			dspps.libsecactrech,
			dspps.creareprisentrrech,
			dspfs.natlog,
			dspfs.demarlog,
			( CASE WHEN moyloco = true THEN '1' WHEN moyloco = false THEN '0' ELSE NULL END ),
			( CASE WHEN permicondub = true THEN '1' WHEN permicondub = false THEN '0' ELSE NULL END ),
			dspps.libautrpermicondu
		FROM dspps
			INNER JOIN personnes ON personnes.id = dspps.personne_id
			INNER JOIN foyers ON personnes.foyer_id = foyers.id
			INNER JOIN dspfs ON dspfs.foyer_id = foyers.id
			LEFT OUTER JOIN dspps_nataccosocindis ON dspps_nataccosocindis.dspp_id = dspps.id
			LEFT OUTER JOIN dspps_nivetus ON dspps_nivetus.dspp_id = dspps.id
			LEFT OUTER JOIN nivetus ON dspps_nivetus.nivetu_id = nivetus.id
			LEFT OUTER JOIN dspps_accoemplois ON dspps_accoemplois.dspp_id = dspps.id
			LEFT OUTER JOIN accoemplois ON dspps_accoemplois.accoemploi_id = accoemplois.id
		GROUP BY dspps_nataccosocindis.dspp_id, dspps.id, dspps.personne_id,
			dspfs.motidemrsa, dspps.drorsarmiant, dspps.drorsarmianta2, dspps.couvsoc, dspfs.accosocfam, dspfs.libcooraccosocfam, dspps.soutdemarsoc, dspps.libcooraccosocindi, dspps.libautrqualipro, dspps.libcompeextrapro, dspps_nivetus.dspp_id, dspps.annderdipobt, dspps.persisogrorechemploi, dspps.libcooraccoemploi, dspps.hispro, dspps.libderact, dspps.libsecactderact, dspps.dfderact, dspps.domideract, dspps.libactdomi, dspps.libsecactdomi, dspps.duractdomi, dspps.libemploirech, dspps.libsecactrech, dspps.creareprisentrrech, dspps_accoemplois.dspp_id, dspfs.natlog, dspfs.demarlog, dspps_nataccosocindis.dspp_id, dspps.moyloco, dspps.permicondub, dspps.libautrpermicondu;