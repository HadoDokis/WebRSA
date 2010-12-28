SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

-- INFO: http://archives.postgresql.org/pgsql-sql/2005-09/msg00266.php
CREATE OR REPLACE FUNCTION public.add_missing_table_field (text, text, text, text)
returns bool as '
DECLARE
  p_namespace alias for $1;
  p_table     alias for $2;
  p_field     alias for $3;
  p_type      alias for $4;
  v_row       record;
  v_query     text;
BEGIN
  select 1 into v_row from pg_namespace n, pg_class c, pg_attribute a
     where
         --public.slon_quote_brute(n.nspname) = p_namespace and
         n.nspname = p_namespace and
         c.relnamespace = n.oid and
         --public.slon_quote_brute(c.relname) = p_table and
         c.relname = p_table and
         a.attrelid = c.oid and
         --public.slon_quote_brute(a.attname) = p_field;
         a.attname = p_field;
  if not found then
    raise notice ''Upgrade table %.% - add field %'', p_namespace, p_table, p_field;
    v_query := ''alter table '' || p_namespace || ''.'' || p_table || '' add column '';
    v_query := v_query || p_field || '' '' || p_type || '';'';
    execute v_query;
    return ''t'';
  else
    return ''f'';
  end if;
END;' language plpgsql;

COMMENT ON FUNCTION public.add_missing_table_field (text, text, text, text) IS 'Add a column of a given type to a table if it is missing';

-- *****************************************************************************

ALTER TABLE orientsstructs ADD COLUMN rgorient INTEGER DEFAULT NULL;
-- NULL ou 0 par défaut

UPDATE orientsstructs
	SET rgorient = (
		SELECT ( COUNT(orientsstructspcd.id) + 1 )
			FROM orientsstructs AS orientsstructspcd
			WHERE orientsstructspcd.personne_id = orientsstructs.personne_id
				AND orientsstructspcd.id <> orientsstructs.id
				AND orientsstructspcd.date_valid <= orientsstructs.date_valid
				AND orientsstructspcd.date_valid IS NOT NULL
				AND orientsstructspcd.statut_orient = 'Orienté'
	);

-- Statistiques sur les personnes non demandeurs ou non conjoints RSA possédant une entrée dans orientsstructs
/*SELECT
	COUNT(orientsstructs.id), orientsstructs.statut_orient, prestations.rolepers
	FROM orientsstructs
	INNER JOIN personnes ON personnes.id = orientsstructs.personne_id
	INNER JOIN prestations ON prestations.personne_id = personnes.id
	WHERE prestations.natprest = 'RSA' AND prestations.rolepers NOT IN ('DEM', 'CJT')
	GROUP BY orientsstructs.statut_orient, prestations.rolepers;*/

-- *****************************************************************************

-- A-t'on des orientsstructs qui ont été relancées ?
/*SELECT
		COUNT(orientsstructs.id)
	FROM orientsstructs
	WHERE ( orientsstructs.statutrelance <> 'E' OR orientsstructs.statutrelance IS NULL )
		OR orientsstructs.daterelance IS NOT NULL
		OR orientsstructs.date_impression_relance IS NOT NULL;*/


-- actuellement relancesdetectionscontrats93
/*CREATE TABLE relancesxxx (
	id					SERIAL NOT NULL,
	personne_id			INTEGER DEFAULT NULL REFERENCES personnes(id),
	propopdo_id			INTEGER DEFAULT NULL REFERENCES propospdos(id),
	tempradiation_id	INTEGER DEFAULT NULL REFERENCES tempradiations(id), -- FIXME à l'avenir ?
	--saisine_id 			-- saisine -> FIXME
	orientstruct_id		INTEGER DEFAULT NULL REFERENCES orientsstructs(id),
	contratinsertion_id	INTEGER DEFAULT NULL REFERENCES contratsinsertion(id),
	cui_id				INTEGER DEFAULT NULL REFERENCES cuis(id)
	-- ppae -- bool
);*/

-- Combien de dernières orientsstructs qui n'ont pas signé de contrat lié à cette orientation
-- TODO: when au lieu du count (pour les performances) ?
/*SELECT
		orientsstructs.personne_id,
		( DATE( NOW() ) - orientsstructs.date_valid ) AS nbjours
	FROM orientsstructs
	WHERE
		-- la dernière orientation
		orientsstructs.id IN (
			SELECT dernierorientsstructs.id
				FROM orientsstructs AS dernierorientsstructs
				WHERE dernierorientsstructs.personne_id = orientsstructs.personne_id
					AND dernierorientsstructs.statut_orient = 'Orienté'
					AND dernierorientsstructs.date_valid IS NOT NULL
				ORDER BY dernierorientsstructs.date_valid DESC
				LIMIT 1
		)
		-- Ne possédant pas de contratsinsertion "lié à cette orientation"
		AND (
			SELECT COUNT(id) FROM (
				SELECT
						contratsinsertion.id AS id,
						contratsinsertion.dd_ci,
						contratsinsertion.personne_id
					FROM contratsinsertion
					WHERE
						contratsinsertion.personne_id = orientsstructs.personne_id
						AND (
							contratsinsertion.dd_ci >= orientsstructs.date_valid
							OR contratsinsertion.datevalidation_ci >= orientsstructs.date_valid
						)
					ORDER BY contratsinsertion.dd_ci DESC
					LIMIT 1
			) AS dernierscontratsinsertion
		) = 0
		-- Ne possédant pas de cuis "lié à cette orientation"
		AND (
			SELECT COUNT(id) FROM (
				SELECT
						cuis.id AS id,
						cuis.datecontrat,
						cuis.personne_id
					FROM cuis
					WHERE
						cuis.personne_id = orientsstructs.personne_id
						AND (
							cuis.datecontrat >= orientsstructs.date_valid
							OR cuis.datevalidationcui >= orientsstructs.date_valid
						)
					ORDER BY cuis.datecontrat DESC
					LIMIT 1
			) AS dernierscuis
		) = 0
	LIMIT 10;*/




CREATE TYPE type_statutoccupation AS ENUM ( 'proprietaire', 'locataire' );
ALTER TABLE dsps ADD COLUMN statutoccupation type_statutoccupation DEFAULT NULL;
ALTER TABLE dsps_revs ADD COLUMN statutoccupation type_statutoccupation DEFAULT NULL;

-- *****************************************************************************
-- Ajout de champs dans la table traitementspdos pour gérer la fiche de calcul
-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_REGIMEFICHECALCUL CASCADE;
CREATE TYPE TYPE_REGIMEFICHECALCUL AS ENUM ( 'fagri', 'ragri', 'reel', 'microbic', 'microbicauto', 'microbnc' );

SELECT add_missing_table_field ('public', 'traitementspdos', 'regime', 'TYPE_REGIMEFICHECALCUL');
SELECT add_missing_table_field ('public', 'traitementspdos', 'saisonnier', 'TYPE_BOOLEANNUMBER');
SELECT add_missing_table_field ('public', 'traitementspdos', 'nrmrcs', 'VARCHAR(20)');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtdebutactivite', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'raisonsocial', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtdebutperiode', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtfinperiode', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtprisecompte', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtecheance', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'forfait', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'mtaidesub', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'chaffvnt', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'chaffsrv', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'benefoudef', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'ammortissements', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'salaireexploitant', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'provisionsnonded', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'moinsvaluescession', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'autrecorrection', 'FLOAT');

SELECT add_missing_table_field ('public', 'traitementspdos', 'nbmoisactivite', 'INTEGER');
SELECT add_missing_table_field ('public', 'traitementspdos', 'mnttotalpriscompte', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'revenus', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'benefpriscompte', 'FLOAT');

DROP TYPE IF EXISTS TYPE_AIDESUBVREINT CASCADE;
CREATE TYPE TYPE_AIDESUBVREINT AS ENUM ( 'aide1', 'aide2', 'subv1', 'subv2' );
SELECT add_missing_table_field ('public', 'traitementspdos', 'aidesubvreint', 'TYPE_AIDESUBVREINT');

-- *****************************************************************************
-- Création du nouvel enum pour l'état des dossier de PDO
-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_ETATDOSSIERPDO CASCADE;
CREATE TYPE TYPE_ETATDOSSIERPDO AS ENUM ( 'attaffect', 'attinstr', 'instrencours', 'attval', 'decisionval', 'dossiertraite', 'attpj' );

SELECT add_missing_table_field ('public', 'propospdos', 'etatdossierpdo', 'TYPE_ETATDOSSIERPDO');

-- *****************************************************************************
-- Déplacement des champs de décisions de la PDO dans une autre table
-- *****************************************************************************

DROP TABLE IF EXISTS decisionspropospdos;
CREATE TABLE decisionspropospdos (
	id      				SERIAL NOT NULL PRIMARY KEY,
	datedecisionpdo			DATE,
	decisionpdo_id			INTEGER REFERENCES decisionspdos (id),
	commentairepdo			TEXT,
	isvalidation			type_booleannumber DEFAULT NULL,
	validationdecision		type_no DEFAULT NULL,
	datevalidationdecision	DATE,
	etatdossierpdo			TYPE_ETATDOSSIERPDO DEFAULT NULL,
	propopdo_id				INTEGER REFERENCES propospdos (id)
);

ALTER TABLE propospdos DROP COLUMN datedecisionpdo;
ALTER TABLE propospdos DROP COLUMN decisionpdo_id;
ALTER TABLE propospdos DROP COLUMN commentairepdo;
ALTER TABLE propospdos DROP COLUMN isvalidation;
ALTER TABLE propospdos DROP COLUMN validationdecision;
ALTER TABLE propospdos DROP COLUMN datevalidationdecision;

-- *****************************************************************************
-- Ajout dans le bilansparcours66
-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_ACCOMPAGNEMENT CASCADE;
CREATE TYPE TYPE_ACCOMPAGNEMENT AS ENUM ( 'prepro', 'social' );

SELECT add_missing_table_field ('public', 'bilansparcours66', 'accompagnement', 'TYPE_ACCOMPAGNEMENT');

DROP TYPE IF EXISTS TYPE_TYPEFORMULAIRE CASCADE;
CREATE TYPE TYPE_TYPEFORMULAIRE AS ENUM ( 'cg', 'pe' );

SELECT add_missing_table_field ('public', 'bilansparcours66', 'typeformulaire', 'TYPE_TYPEFORMULAIRE');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'textbilanparcours', 'TEXT');

-- *****************************************************************************
-- Nouvelle structure pour les informations venant de Pôle Emploi
-- *****************************************************************************

DROP TABLE IF EXISTS historiquecessationspe CASCADE;
DROP TABLE IF EXISTS historiqueradiationspe CASCADE;
DROP TABLE IF EXISTS historiqueinscriptionspe CASCADE;
DROP TABLE IF EXISTS informationspe CASCADE;


-- TODO: indexes (indexes uniques pour chacune des tables d'historique ?)
-- TODO: pourquoi une erreur avec les REFERENCES ?
CREATE TABLE informationspe (
	id				SERIAL NOT NULL,
	--personne_id		INTEGER DEFAULT NULL REFERENCES personnes(id) ON UPDATE CASCADE ON DELETE SET NULL,
	personne_id		INTEGER DEFAULT NULL REFERENCES personnes(id),
	nir				VARCHAR(15) DEFAULT NULL, -- obligatoire ?
	identifiantpe	VARCHAR(11) NOT NULL, -- 11 ou 8 et 3 pour la structure ? ... il change au cours du temps ?
	nom				VARCHAR(50) DEFAULT NULL,
	prenom			VARCHAR(50) NOT NULL,
	dtnai			DATE NOT NULL
);

CREATE INDEX informationspe_personne_id_idx ON informationspe ( personne_id );
CREATE INDEX informationspe_nir_idx ON informationspe ( nir varchar_pattern_ops );
CREATE INDEX informationspe_identifiantpe_idx ON informationspe ( identifiantpe varchar_pattern_ops );
CREATE INDEX informationspe_nom_idx ON informationspe ( nom varchar_pattern_ops );
CREATE INDEX informationspe_prenom_idx ON informationspe ( prenom varchar_pattern_ops );
CREATE INDEX informationspe_dtnai_idx ON informationspe ( dtnai );
-- FIXME
CREATE UNIQUE INDEX informationspe_unique_tuple_idx ON informationspe ( personne_id, nir, identifiantpe, nom, prenom, dtnai );

--

CREATE TABLE historiquecessationspe (
	id					SERIAL NOT NULL,
	--informationpe_id	INTEGER NOT NULL REFERENCES informationspe(id) ON UPDATE CASCADE ON DELETE CASCADE,
	informationpe_id	INTEGER NOT NULL,
	date				DATE NOT NULL,
	code				VARCHAR(2) DEFAULT NULL,
	motif				VARCHAR(250) DEFAULT NULL
);

CREATE UNIQUE INDEX historiquecessationspe_unique_tuple_idx ON historiquecessationspe ( informationpe_id, date, code, motif );

--

CREATE TABLE historiqueradiationspe (
	id					SERIAL NOT NULL,
	--informationpe_id	INTEGER NOT NULL REFERENCES informationspe(id) ON UPDATE CASCADE ON DELETE CASCADE,
	informationpe_id	INTEGER NOT NULL,
	date				DATE NOT NULL,
	code				VARCHAR(2) DEFAULT NULL,
	motif				VARCHAR(250) DEFAULT NULL
);

CREATE UNIQUE INDEX historiqueradiationspe_unique_tuple_idx ON historiqueradiationspe ( informationpe_id, date, code, motif );

--

CREATE TABLE historiqueinscriptionspe (
	id					SERIAL NOT NULL,
	--informationpe_id	INTEGER NOT NULL REFERENCES informationspe(id) ON UPDATE CASCADE ON DELETE CASCADE,
	informationpe_id	INTEGER NOT NULL,
	date				DATE NOT NULL,
	code				VARCHAR(2) DEFAULT NULL,
	motif				VARCHAR(250) DEFAULT NULL -- FIXME ?
);

CREATE UNIQUE INDEX historiqueinscriptionspe_unique_tuple_idx ON historiqueinscriptionspe ( informationpe_id, date, code, motif );

--------------------------------------------------------------------------------
-- Remplissage des nouvelles tables avec les anciennes tables
--------------------------------------------------------------------------------
-- 1°) Lorsque la personne a été trouvée en base
INSERT INTO informationspe ( personne_id, nir, identifiantpe, nom, prenom, dtnai )
SELECT
		infospoleemploi.personne_id,
		personnes.nir,
		infospoleemploi.identifiantpe,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai
	FROM infospoleemploi
	INNER JOIN personnes ON (
		infospoleemploi.personne_id = personnes.id
	)
	GROUP BY
		infospoleemploi.personne_id,
		personnes.nir,
		infospoleemploi.identifiantpe,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai
	ORDER BY
		infospoleemploi.personne_id,
		personnes.nir,
		infospoleemploi.identifiantpe,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai;

--

INSERT INTO historiqueinscriptionspe ( informationpe_id, date, code )
SELECT
		informationspe.id,
		infospoleemploi.dateinscription,
		infospoleemploi.categoriepe
	FROM infospoleemploi
	INNER JOIN informationspe ON (
		informationspe.personne_id = infospoleemploi.personne_id
		AND informationspe.identifiantpe = infospoleemploi.identifiantpe
	)
	WHERE infospoleemploi.dateinscription IS NOT NULL
	GROUP BY
		informationspe.id,
		infospoleemploi.personne_id,
		informationspe.nir,
		infospoleemploi.identifiantpe,
		informationspe.nom,
		informationspe.prenom,
		informationspe.dtnai,
		infospoleemploi.dateinscription,
		infospoleemploi.categoriepe;

INSERT INTO historiquecessationspe ( informationpe_id, date, motif )
SELECT
		informationspe.id,
		infospoleemploi.datecessation,
		infospoleemploi.motifcessation
	FROM infospoleemploi
	INNER JOIN informationspe ON (
		informationspe.personne_id = infospoleemploi.personne_id
		AND informationspe.identifiantpe = infospoleemploi.identifiantpe
	)
	WHERE infospoleemploi.datecessation IS NOT NULL
	GROUP BY
		informationspe.id,
		infospoleemploi.personne_id,
		informationspe.nir,
		infospoleemploi.identifiantpe,
		informationspe.nom,
		informationspe.prenom,
		informationspe.dtnai,
		infospoleemploi.datecessation,
		infospoleemploi.motifcessation;

INSERT INTO historiqueradiationspe ( informationpe_id, date, motif )
SELECT
		informationspe.id,
		infospoleemploi.dateradiation,
		infospoleemploi.motifradiation
	FROM infospoleemploi
	INNER JOIN informationspe ON (
		informationspe.personne_id = infospoleemploi.personne_id
		AND informationspe.identifiantpe = infospoleemploi.identifiantpe
	)
	WHERE infospoleemploi.dateradiation IS NOT NULL
	GROUP BY
		informationspe.id,
		infospoleemploi.personne_id,
		informationspe.nir,
		infospoleemploi.identifiantpe,
		informationspe.nom,
		informationspe.prenom,
		informationspe.dtnai,
		infospoleemploi.dateradiation,
		infospoleemploi.motifradiation;

-- 2°) Lorsque la personne n'a pas encore été trouvée en base
-- a°) A partir de tempradiations
INSERT INTO informationspe ( nir, identifiantpe, nom, prenom, dtnai )
SELECT
		tempradiations.nir,
		tempradiations.identifiantpe,
		tempradiations.nom,
		tempradiations.prenom,
		tempradiations.dtnai
	FROM tempradiations
	WHERE (
		SELECT
				COUNT(*)
			FROM informationspe
			WHERE
				informationspe.identifiantpe= tempradiations.identifiantpe
				AND (
					informationspe.nir = tempradiations.nir
					OR (
						informationspe.nom = tempradiations.nom
						AND informationspe.prenom = tempradiations.prenom
						AND informationspe.dtnai = tempradiations.dtnai
					)
				)
	) = 0
	GROUP BY
		tempradiations.nir,
		tempradiations.identifiantpe,
		tempradiations.nom,
		tempradiations.prenom,
		tempradiations.dtnai;

INSERT INTO historiqueradiationspe ( informationpe_id, date, motif )
SELECT
		informationspe.id,
		tempradiations.dateradiation,
		tempradiations.motifradiation
	FROM tempradiations
		INNER JOIN informationspe ON (
			informationspe.identifiantpe= tempradiations.identifiantpe
			AND (
				informationspe.nir = tempradiations.nir
				OR (
					informationspe.nom = tempradiations.nom
					AND informationspe.prenom = tempradiations.prenom
					AND informationspe.dtnai = tempradiations.dtnai
				)
			)
		)
	WHERE (
		SELECT
				COUNT(*)
			FROM historiqueradiationspe
			WHERE
				historiqueradiationspe.informationpe_id = informationspe.id
				AND historiqueradiationspe.date = tempradiations.dateradiation
				AND historiqueradiationspe.motif = tempradiations.motifradiation
	) = 0
	GROUP BY
		informationspe.id,
		tempradiations.dateradiation,
		tempradiations.motifradiation;

-- b°) A partir de tempcessations
INSERT INTO informationspe ( nir, identifiantpe, nom, prenom, dtnai )
SELECT
		tempcessations.nir,
		tempcessations.identifiantpe,
		tempcessations.nom,
		tempcessations.prenom,
		tempcessations.dtnai
	FROM tempcessations
	WHERE (
		SELECT
				COUNT(*)
			FROM informationspe
			WHERE
				informationspe.identifiantpe= tempcessations.identifiantpe
				AND (
					informationspe.nir = tempcessations.nir
					OR (
						informationspe.nom = tempcessations.nom
						AND informationspe.prenom = tempcessations.prenom
						AND informationspe.dtnai = tempcessations.dtnai
					)
				)
	) = 0
	GROUP BY
		tempcessations.nir,
		tempcessations.identifiantpe,
		tempcessations.nom,
		tempcessations.prenom,
		tempcessations.dtnai;

INSERT INTO historiquecessationspe ( informationpe_id, date, motif )
SELECT
		informationspe.id,
		tempcessations.datecessation,
		tempcessations.motifcessation
	FROM tempcessations
		INNER JOIN informationspe ON (
			informationspe.identifiantpe= tempcessations.identifiantpe
			AND (
				informationspe.nir = tempcessations.nir
				OR (
					informationspe.nom = tempcessations.nom
					AND informationspe.prenom = tempcessations.prenom
					AND informationspe.dtnai = tempcessations.dtnai
				)
			)
		)
	WHERE (
		SELECT
				COUNT(*)
			FROM historiquecessationspe
			WHERE
				historiquecessationspe.informationpe_id = informationspe.id
				AND historiquecessationspe.date = tempcessations.datecessation
				AND historiquecessationspe.motif = tempcessations.motifcessation
	) = 0
	GROUP BY
		informationspe.id,
		tempcessations.datecessation,
		tempcessations.motifcessation;

-- c°) A partir de tempinscriptions
INSERT INTO informationspe ( nir, identifiantpe, nom, prenom, dtnai )
SELECT
		tempinscriptions.nir,
		tempinscriptions.identifiantpe,
		tempinscriptions.nom,
		tempinscriptions.prenom,
		tempinscriptions.dtnai
	FROM tempinscriptions
	WHERE (
		SELECT
				COUNT(*)
			FROM informationspe
			WHERE
				informationspe.identifiantpe= tempinscriptions.identifiantpe
				AND (
					informationspe.nir = tempinscriptions.nir
					OR (
						informationspe.nom = tempinscriptions.nom
						AND informationspe.prenom = tempinscriptions.prenom
						AND informationspe.dtnai = tempinscriptions.dtnai
					)
				)
	) = 0
	GROUP BY
		tempinscriptions.nir,
		tempinscriptions.identifiantpe,
		tempinscriptions.nom,
		tempinscriptions.prenom,
		tempinscriptions.dtnai;

INSERT INTO historiqueinscriptionspe ( informationpe_id, date, code )
SELECT
		informationspe.id,
		tempinscriptions.dateinscription,
		tempinscriptions.categoriepe
	FROM tempinscriptions
		INNER JOIN informationspe ON (
			informationspe.identifiantpe= tempinscriptions.identifiantpe
			AND (
				informationspe.nir = tempinscriptions.nir
				OR (
					informationspe.nom = tempinscriptions.nom
					AND informationspe.prenom = tempinscriptions.prenom
					AND informationspe.dtnai = tempinscriptions.dtnai
				)
			)
		)
	WHERE (
		SELECT
				COUNT(*)
			FROM historiqueinscriptionspe
			WHERE
				historiqueinscriptionspe.informationpe_id = informationspe.id
				AND historiqueinscriptionspe.date = tempinscriptions.dateinscription
				AND historiqueinscriptionspe.code = tempinscriptions.categoriepe
	) = 0
	GROUP BY
		informationspe.id,
		tempinscriptions.dateinscription,
		tempinscriptions.categoriepe;

-- Mise à jour des codes
UPDATE
	historiquecessationspe
	SET code = '90'
	WHERE code IS NULL
		AND motif = 'ABSENCE AU CONTROLE (NON REPONSE A DAM)';

UPDATE
	historiqueradiationspe
	SET code = 'CX'
	WHERE code IS NULL
		AND motif = 'REFUS ACTION INSERTION SUSPENSION DE QUINZE JOURS';

UPDATE
	historiqueradiationspe
	SET code = '92'
	WHERE code IS NULL
		AND motif = 'NON REPONSE A CONVOCATION SUSPENSION DE DEUX MOIS';

UPDATE
	historiqueradiationspe
	SET code = '8X'
	WHERE code IS NULL
		AND motif = 'INSUFFISANCE DE RECHERCHE D''EMPLOI SUSPENSION DE QUINZE JOURS';

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
