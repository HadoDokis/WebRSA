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


SELECT add_missing_table_field ('public', 'rendezvous', 'isadomicile', 'TYPE_BOOLEANNUMBER');
ALTER TABLE rendezvous ALTER COLUMN isadomicile SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE rendezvous SET isadomicile = '0'::TYPE_BOOLEANNUMBER WHERE isadomicile IS NULL;
ALTER TABLE rendezvous ALTER COLUMN isadomicile SET NOT NULL;



DROP TABLE IF EXISTS histoaprecomplementaires;

CREATE TABLE histoaprecomplementaires (
    id SERIAL NOT NULL PRIMARY KEY,
    nom character varying(255),
    prenom character varying(255),
    num_apre_beneficiaire character varying(255),
    date_reception_sis character varying(255),
    caf character varying(15),
    id_dossier_provisoire integer,
    type_aide character varying(255),
    sexe character varying(1),
    referent character varying(255),
    sexe_referent character varying(1),
    ville character varying(255),
    date_comite character varying(255),
    montant_demande character varying(25),
    montant_accorde character varying(25),
    date_renvoie_dossier character varying(255),
    decision character varying(255),
    commentaire text,
    no_voie character varying(25),
    type_voie character varying(255),
    nom_rue character varying(255),
    code_postale character varying(5),
    ville_beneficiaire character varying(255),
    complement_adresse character varying(255),
    code_banque_benef character varying(5),
    num_compte_benef character varying(11),
    cle_rib_benef character varying(2),
    code_guichet_benef character varying(5),
    sexe_titulaire_rib character varying(1),
    nom_titulaire_rib character varying(255),
    prenom_titulaire_rib character varying(255),
    nom_banque_benef character varying(255),
    nom_rue_banc character varying(255),
    num_rue_banc character varying(255),
    type_rue_banc character varying(255),
    ville_banc character varying(255),
    code_postal_banc integer,
    libelle_organisme character varying(255),
    libelle_action_organisme text,
    nom_responsable_organisme character varying(255),
    prenom_responsable_organisme character varying(255),
    sexe_orga character varying(1),
    num_voie_orga character varying(255),
    type_voie_orga character varying(255),
    nom_rue_orga character varying(255),
    cp_orga integer,
    ville_orga character varying(255),
    complement_adr_orga character varying(255),
    nom_banque_orga character varying(255),
    code_banque_orga character varying(255),
    code_guichet_orga character varying(255),
    num_compte_orga character varying(255),
    cle_orga character varying(255),
    nom_referent character varying(255),
    prenom_referent character varying(255),
    nom_structure_referent character varying(255),
    num_voie_structure_referent character varying(255),
    type_voie_structure_referent character varying(255),
    nom_rue_structure_referent character varying(255),
    code_postale_structure_referent character varying(255),
    adr_total_referent text,
    adr_total_benef text,
    adr_total_orga text,
    ville_structure_referent character varying(255),
    nb_paiements integer,
    entr timestamp(0) without time zone DEFAULT now(),
    date_debut_formation character varying(255),
    date_fin_formation character varying(255),
    imprimey character varying(11) DEFAULT 'non imprimé'::character varying,
    nom_agent character varying(255),
    prenom_agent character varying(255),
    tel_agent character varying(20),
    "Signataire" character varying(2),
    personne_id integer
);


-------------------------------------------------------------------------------------------------------------
-- 20120716 : Ajout d'un champ supplémentaire dans la table actionscandidats pour le CG93
-------------------------------------------------------------------------------------------------------------

SELECT add_missing_table_field ('public', 'actionscandidats', 'contractualisation93', 'VARCHAR(250)');


-------------------------------------------------------------------------------------------------------------
-- 20120719: ajout des clés étrangères pour nouvelles orientations suite aux passages en EP.
-------------------------------------------------------------------------------------------------------------

SELECT add_missing_table_field ( 'public', 'defautsinsertionseps66', 'nvorientstruct_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'defautsinsertionseps66', 'defautsinsertionseps66_nvorientstruct_id_fkey', 'orientsstructs', 'nvorientstruct_id', false );

-- On rapatrie les données implicites
/*CREATE OR REPLACE FUNCTION public.update_orientsstructs_decisionsdefautsinsertionseps66() RETURNS VOID AS
$$
	DECLARE
		v_row   record;
		v_query text;
	BEGIN
		FOR v_row IN
			SELECT
					defautsinsertionseps66.id AS thematique_id,
					orientsstructs.id AS orientstruct_id
				FROM dossierseps
					INNER JOIN defautsinsertionseps66 ON ( defautsinsertionseps66.dossierep_id = dossierseps.id )
					INNER JOIN passagescommissionseps ON ( passagescommissionseps.dossierep_id = dossierseps.id )
					INNER JOIN commissionseps ON ( passagescommissionseps.commissionep_id = commissionseps.id )
					INNER JOIN decisionsdefautsinsertionseps66 ON ( decisionsdefautsinsertionseps66.passagecommissionep_id = passagescommissionseps.id )
					INNER JOIN orientsstructs ON ( orientsstructs.personne_id = dossierseps.personne_id )
				WHERE
					dossierseps.themeep = 'defautsinsertionseps66'
					AND passagescommissionseps.etatdossierep = 'traite'
					AND commissionseps.etatcommissionep = 'traite'
					AND (
						decisionsdefautsinsertionseps66.decision = 'reorientation'
						OR (
							decisionsdefautsinsertionseps66.decision = 'maintien'
							AND NOT (
								decisionsdefautsinsertionseps66.changementrefparcours = 'N'
								AND decisionsdefautsinsertionseps66.typeorientprincipale_id IN (
									SELECT id FROM typesorients WHERE lib_type_orient LIKE 'Emploi%'
								)
							)
						)
					)
					AND passagescommissionseps.id IN (
						SELECT
								p.id
							FROM passagescommissionseps AS p
								INNER JOIN commissionseps AS c ON ( p.commissionep_id = c.id )
							WHERE dossierseps.id = p.dossierep_id
							ORDER BY c.dateseance DESC
							LIMIT 1
					)
					AND decisionsdefautsinsertionseps66.id IN (
						SELECT
								d.id
							FROM decisionsdefautsinsertionseps66 AS d
							WHERE passagescommissionseps.id = d.passagecommissionep_id
							ORDER BY ( CASE WHEN d.etape = 'ep' THEN 1 WHEN etape = 'cg' THEN 2 ELSE 0 END ) DESC -- cg, ep
							LIMIT 1
					)
					-- Jointure sur les orientations
					AND orientsstructs.typeorient_id = decisionsdefautsinsertionseps66.typeorient_id
					AND orientsstructs.structurereferente_id = decisionsdefautsinsertionseps66.structurereferente_id
					AND orientsstructs.date_propo = DATE_TRUNC('day', decisionsdefautsinsertionseps66.modified )
					AND orientsstructs.date_valid = DATE_TRUNC('day', decisionsdefautsinsertionseps66.modified )
					AND orientsstructs.user_id = decisionsdefautsinsertionseps66.user_id
					AND orientsstructs.statut_orient = 'Orienté'
					AND orientsstructs.id NOT IN ( SELECT defautsinsertionseps66.nvorientstruct_id FROM defautsinsertionseps66 WHERE defautsinsertionseps66.nvorientstruct_id IS NOT NULL )
				ORDER BY decisionsdefautsinsertionseps66.modified ASC
		LOOP
			-- Mise à jour dans la table defautsinsertionseps66
			v_query := 'UPDATE defautsinsertionseps66 SET nvorientstruct_id = ' || v_row.orientstruct_id || ' WHERE id = ' || v_row.thematique_id || ';';
			RAISE NOTICE  '%', v_query;
			EXECUTE v_query;
		END LOOP;
	END;
$$
LANGUAGE plpgsql;

SELECT public.update_orientsstructs_decisionsdefautsinsertionseps66();
DROP FUNCTION public.update_orientsstructs_decisionsdefautsinsertionseps66();

CREATE UNIQUE INDEX defautsinsertionseps66_nvorientstruct_id_idx ON defautsinsertionseps66 (nvorientstruct_id);*/



-- *****************************************************************************
COMMIT;
-- *****************************************************************************