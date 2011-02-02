-- patch-1.0-STAGING_RSA-migration-IRSA-V6.sql
-- *** VERSIONS  ***
-- *** webrsa 2.0rc10
-- *** iRSA v. 6 : INSTRUCTION 2.0
-- *** Cristal v. 29 min : BENEFICIAIRE/FINANCIER 2.0
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

/*******************************************************************************
	Mise à jour du schéma pour @RSA V6
*******************************************************************************/

-- staging.rib
ALTER TABLE staging.rib ALTER COLUMN clerib TYPE character varying(2);
ALTER TABLE staging.rib ADD COLUMN numdebiban character varying(4);
ALTER TABLE staging.rib ADD COLUMN numfiniban character varying(7);
ALTER TABLE staging.rib ADD COLUMN bic character varying(11);

-- staging.conditionactiviteprealable
CREATE TABLE staging.conditionactiviteprealable (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddcondactprea character varying(10),
    dfcondactprea character varying(10),
    topcondactprea character varying(10),
    nbheuacttot character varying(10)
);
ALTER TABLE staging.conditionactiviteprealable OWNER TO webrsa;

-- elementaire.paiementsfoyers
ALTER TABLE elementaire.paiementsfoyers ALTER COLUMN clerib TYPE character varying(2);
ALTER TABLE elementaire.paiementsfoyers ADD COLUMN numdebiban character varying(4);
ALTER TABLE elementaire.paiementsfoyers ADD COLUMN numfiniban character varying(7);
ALTER TABLE elementaire.paiementsfoyers ADD COLUMN bic character varying(11);

-- elementaire.conditionsactivitesprealables
CREATE TABLE conditionsactivitesprealables (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddcondactprea date,
    dfcondactprea date,
    topcondactprea type_booleannumber NOT NULL,
    nbheuacttot integer
);
ALTER TABLE elementaire.conditionsactivitesprealables OWNER TO webrsa;

-- administration.statintegrationinstruction
ALTER TABLE administration.statintegrationinstruction ADD elem_conditionsactivitesprealables integer AFTER elem_allocationssoutienfamilial;
ALTER TABLE administration.statintegrationinstruction ADD webav_conditionsactivitesprealables integer AFTER webav_allocationssoutienfamilial;
ALTER TABLE administration.statintegrationinstruction ADD webap_conditionsactivitesprealables integer AFTER webap_allocationssoutienfamilial;
ALTER TABLE administration.statintegrationinstruction ADD flux_conditionsactivitesprealables integer AFTER flux_allocationssoutienfamilial;

/*******************************************************************************
	Mise à Jour des Triggers de gestion de doublon de dossiers et de personnes
*******************************************************************************/

-- FUNCTION clean_old_dossier_instruction
DROP FUNCTION staging.clean_old_dossier_instruction() CASCADE;
CREATE FUNCTION staging.clean_old_dossier_instruction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
	dossier integer;
BEGIN
select infodemandersa into dossier from staging.demandersa where numdemrsa = NEW.numdemrsa;
if (dossier is not null)
then
	delete from staging.adresse where infodemandersa = dossier;
	delete from staging.demandersa where infodemandersa = dossier;
	delete from staging.destinataire where infodemandersa = dossier;
	delete from staging.electiondomicile where infodemandersa = dossier;
	delete from staging.ficheliaison where infodemandersa = dossier;
	delete from staging.logement where infodemandersa = dossier;
	delete from staging.modescontacts where infodemandersa = dossier;	
	delete from staging.organisme where infodemandersa = dossier;
	delete from staging.organismecedant where infodemandersa = dossier;
	delete from staging.partenaire where infodemandersa = dossier;
	delete from staging.prestationrsa where infodemandersa = dossier;
	delete from staging.rib where infodemandersa = dossier;
	delete from staging.situationfamille where infodemandersa = dossier;
	delete from staging.suivi_instruction where infodemandersa = dossier;
	delete from staging.activite where infodemandersa = dossier;
	delete from staging.activiteeti where infodemandersa = dossier;
	delete from staging.aidesagricoles where infodemandersa = dossier;
	delete from staging.asf where infodemandersa = dossier;
	delete from staging.benefices where infodemandersa = dossier;
	delete from staging.chiffreaffaire where infodemandersa = dossier;
	delete from staging.commundifficultelogement where infodemandersa = dossier;
	delete from staging.communmobilite where infodemandersa = dossier;
	delete from staging.communsituationsociale where infodemandersa = dossier;
	delete from staging.conditionactiviteprealable where infodemandersa = dossier;
	delete from staging.creancealimentaire where infodemandersa = dossier;
	delete from staging.detailaccompagnementsocialfamilial where infodemandersa = dossier;
	delete from staging.detailaccosocindividuel where infodemandersa = dossier;
	delete from staging.detaildifficultedisponibilite where infodemandersa = dossier;
	delete from staging.detaildifficultelogement where infodemandersa = dossier;
	delete from staging.detaildifficultesituationsociale where infodemandersa = dossier;
	delete from staging.detailmobilite where infodemandersa = dossier;
	delete from staging.detailressourcesmensuelles where infodemandersa = dossier;
	delete from staging.determinationparcours where infodemandersa = dossier;
	delete from staging.disponibilteemploi where infodemandersa = dossier;
	delete from staging.dossiercaf where infodemandersa = dossier;
	delete from staging.dossierpoleemploi where infodemandersa = dossier;
	delete from staging.elementsfiscaux where infodemandersa = dossier;
	delete from staging.employes where infodemandersa = dossier;
	delete from staging.generalitedspp where infodemandersa = dossier;
	delete from staging.generaliteressourcesmensuelles where infodemandersa = dossier;
	delete from staging.generaliteressourcestrimestre where infodemandersa = dossier;
	delete from staging.grossesse where infodemandersa = dossier;
	delete from staging.identification where infodemandersa = dossier;
	delete from staging.nationalite where infodemandersa = dossier;
	delete from staging.niveauetude where infodemandersa = dossier;
	delete from staging.organismedecisionorientation where infodemandersa = dossier;
	delete from staging.organismereferentorientation where infodemandersa = dossier;
	delete from staging.prestations where infodemandersa = dossier;
	delete from staging.rattachement where infodemandersa = dossier;
	delete from staging.situationprofessionnelle where infodemandersa = dossier;
	delete from staging.suiviappuiorientation where infodemandersa = dossier;
	delete from staging.titresejour where infodemandersa = dossier;
end if;
return new;
end;
$$;

ALTER FUNCTION staging.clean_old_dossier_instruction() OWNER TO postgres;

-- FUNCTION clean_old_personne_instruction
DROP FUNCTION staging.clean_old_dossier_instruction() CASCADE;
CREATE FUNCTION staging.clean_old_personne_instruction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE 
	dossier INTEGER;
	personne INTEGER;
BEGIN
	SELECT infodemandersa, clepersonne INTO dossier, personne FROM staging.identification 
	WHERE infodemandersa = NEW.infodemandersa
		AND COALESCE(nomnai,'') = COALESCE(NEW.nomnai,'')
		AND COALESCE(prenom,'') = COALESCE(NEW.prenom,'')
		AND COALESCE(dtnai,'') = COALESCE(NEW.dtnai,'')
		AND COALESCE(rgnai,'') = COALESCE(NEW.rgnai,'');

	IF (dossier IS NOT NULL AND personne IS NOT NULL)
	THEN
		DELETE FROM staging.activite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.activiteeti WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.aidesagricoles WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.asf WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.benefices WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.chiffreaffaire WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.commundifficultelogement WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.communmobilite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.communsituationsociale WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.conditionactiviteprealable WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.creancealimentaire WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailaccompagnementsocialfamilial WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailaccosocindividuel WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detaildifficultedisponibilite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detaildifficultelogement WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detaildifficultesituationsociale WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailmobilite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailressourcesmensuelles WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.determinationparcours WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.disponibilteemploi WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.dossiercaf WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.dossierpoleemploi WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.elementsfiscaux WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.employes WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.generalitedspp WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.generaliteressourcesmensuelles WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.generaliteressourcestrimestre WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.grossesse WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.identification WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.nationalite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.niveauetude WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.organismedecisionorientation WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.organismereferentorientation WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.prestations WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.rattachement WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.situationprofessionnelle WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.suiviappuiorientation WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.titresejour WHERE infodemandersa = dossier AND clepersonne = personne;
	END IF;
	RETURN new;
END;
$$;

ALTER FUNCTION staging.clean_old_personne_instruction() OWNER TO postgres;

-- trigger
CREATE TRIGGER demandersa_i_clean
    BEFORE INSERT ON demandersa
    FOR EACH ROW
    EXECUTE PROCEDURE clean_old_dossier_instruction();

CREATE TRIGGER identification_i_clean
    BEFORE INSERT ON identification
    FOR EACH ROW
    EXECUTE PROCEDURE clean_old_personne_instruction();

-- *****************************************************************************
COMMIT;
-- *****************************************************************************