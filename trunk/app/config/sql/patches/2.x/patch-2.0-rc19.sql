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
SELECT add_missing_table_field ('public', 'membreseps', 'numvoie', 'VARCHAR(6)');
SELECT add_missing_table_field ('public', 'membreseps', 'typevoie', 'VARCHAR(4)');
SELECT add_missing_table_field ('public', 'membreseps', 'nomvoie', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'membreseps', 'compladr', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'membreseps', 'codepostal', 'CHAR(5)');
SELECT add_missing_table_field ('public', 'membreseps', 'ville', 'VARCHAR(100)');

ALTER TABLE actionscandidats ALTER COLUMN contractualisation SET NOT NULL;

SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'decisioncov', 'CHAR(10)');
SELECT add_missing_table_field ('public', 'proposcontratsinsertioncovs58', 'decisioncov', 'VARCHAR(10)');

UPDATE contratsinsertion 
    SET decision_ci = 'N'
    WHERE decision_ci IN ( 'A', 'R' );

UPDATE contratsinsertion 
    SET datevalidation_ci = null
    WHERE decision_ci IN ( 'N', 'E' );

UPDATE contratsinsertion 
    SET datevalidation_ci = dd_ci
    WHERE
        decision_ci = 'V'
        AND datevalidation_ci IS NULL;


ALTER TABLE contratsinsertion ADD CONSTRAINT contratsinsertion_decision_ci_datevalidation_ci_check CHECK(
    ( decision_ci = 'V' AND datevalidation_ci IS NOT NULL )
    OR ( decision_ci <> 'V' AND datevalidation_ci IS NULL )
);


SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'referent_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'proposorientationscovs58', 'proposorientationscovs58_referent_id_fkey', 'referents', 'referent_id');
SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'covreferent_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'proposorientationscovs58', 'proposorientationscovs58_covreferent_id_fkey', 'referents', 'covreferent_id');

SELECT add_missing_table_field ('public', 'eps_membreseps', 'suppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'eps_membreseps', 'eps_membreseps_suppleant_id_fkey', 'referents', 'suppleant_id');

SELECT add_missing_table_field ('public', 'membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE membreseps DROP COLUMN suppleant_id;
SELECT add_missing_table_field ('public', 'eps_membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE eps_membreseps DROP COLUMN suppleant_id;



-- *****************************************************************************
-- 20110517, ajout d'une valeur pour le type enum contractualisation dans
-- les fiches de candidatures 66
-- *****************************************************************************
ALTER TABLE actionscandidats ALTER COLUMN contractualisation TYPE TEXT;
DROP TYPE TYPE_CONTRACTUALISATION;
CREATE TYPE TYPE_CONTRACTUALISATION AS ENUM ( 'marche', 'subvention', 'internecg' );
ALTER TABLE actionscandidats ALTER COLUMN contractualisation TYPE TYPE_CONTRACTUALISATION USING CAST(contractualisation AS TYPE_CONTRACTUALISATION);


DROP TYPE IF EXISTS TYPE_POSITIONFICHE;
CREATE TYPE TYPE_POSITIONFICHE AS ENUM ( 'enattente', 'encours', 'nonretenue', 'sortie' );
ALTER TABLE actionscandidats_personnes ADD COLUMN positionfiche TYPE_POSITIONFICHE DEFAULT  'enattente'::type_positionfiche;

SELECT add_missing_table_field ('public', 'actionscandidats_personnes', 'issortie', 'type_booleannumber');

-- *****************************************************************************
-- 20110517, modifications pour la gestion des présences dans les eps
-- *****************************************************************************
SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'suppleant', 'INTEGER');
ALTER TABLE commissionseps_membreseps DROP COLUMN suppleant;
SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'reponsesuppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'commissionseps_membreseps', 'commissionseps_membreseps_reponsesuppleant_id_fkey', 'membreseps', 'reponsesuppleant_id');
SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'presencesuppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'commissionseps_membreseps', 'commissionseps_membreseps_presencesuppleant_id_fkey', 'membreseps', 'presencesuppleant_id');

SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE commissionseps_membreseps DROP COLUMN suppleant_id;

-- *****************************************************************************
-- 20110517, ajout du champ pour le bouton radio du cg93
-- *****************************************************************************
DROP TYPE IF EXISTS TYPE_DECISIONPCG;
CREATE TYPE TYPE_DECISIONPCG AS ENUM ( 'valide', 'enattente' );

SELECT add_missing_table_field ('public', 'decisionsnonrespectssanctionseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsnonrespectssanctionseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionsreorientationseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsreorientationseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionsnonorientationsproseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsnonorientationsproseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionsregressionsorientationseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsregressionsorientationseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionssignalementseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionssignalementseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionscontratscomplexeseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionscontratscomplexeseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;

-- *****************************************************************************
-- 20110523, ajout de champs de commentaires dans les décisions du module EP
-- pour les thématiques où elles manquent
-- *****************************************************************************

SELECT add_missing_table_field ('public', 'decisionscontratscomplexeseps93', 'commentaire', 'TEXT');
SELECT add_missing_table_field ('public', 'defautsinsertionseps66', 'commentaire', 'TEXT');


-- *******************************************************************************************************
-- 20110524, ajout de la valeur Absent pour les présences des participants à une commission EP
-- *******************************************************************************************************
ALTER TABLE commissionseps_membreseps ALTER COLUMN presence TYPE TEXT;
DROP TYPE TYPE_PRESENCESEANCEEP;
CREATE TYPE TYPE_PRESENCESEANCEEP AS ENUM ( 'present', 'excuse', 'absent', 'remplacepar' );
ALTER TABLE commissionseps_membreseps ALTER COLUMN presence TYPE TYPE_PRESENCESEANCEEP USING CAST(presence AS TYPE_PRESENCESEANCEEP);

-- *******************************************************************************************************
-- 20110524, ajout d'un état à la commission pour la figée avant de prendre les présences
-- *******************************************************************************************************
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TEXT;
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_ETATCOMMISSIONEP;
CREATE TYPE TYPE_ETATCOMMISSIONEP AS ENUM ( 'cree', 'associe', 'valide', 'presence', 'decisionep', 'traiteep', 'decisioncg', 'traite', 'annule', 'reporte' );
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TYPE_ETATCOMMISSIONEP USING CAST(etatcommissionep AS TYPE_ETATCOMMISSIONEP);
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep SET DEFAULT 'cree'::TYPE_ETATCOMMISSIONEP;

-- *******************************************************************************************************
-- 20110524, ajout de l'organisme aux membres de l'EP
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'membreseps', 'organisme', 'VARCHAR(250)');
ALTER TABLE membreseps ALTER COLUMN organisme SET DEFAULT NULL;

-- *******************************************************************************************************
-- 20110524, ajout de la date d'impression de la décision d'un passage en EP
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'passagescommissionseps', 'impressiondecision', 'DATE');
ALTER TABLE passagescommissionseps ALTER COLUMN impressiondecision SET DEFAULT NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
