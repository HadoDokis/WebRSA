-- *****************************************************************************
-- Améliorations des performances
-- INFO: http://archives.postgresql.org/pgsql-performance/2008-10/msg00029.php
-- INFO: pour que les différents VACUUM / REINDEX soient pris en compte sans erreur,
--       il faut passer ce script à partir de la console (par opposition à "à partir
--       de phpPgAdmin".
-- *****************************************************************************

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

DROP INDEX IF EXISTS personnes_upper_nom_idx;
DROP INDEX IF EXISTS personnes_upper_prenom_idx;
DROP INDEX IF EXISTS personnes_upper_nomnai_idx;
/*ALTER TABLE personnes ALTER COLUMN nom SET STATISTICS 100;
ALTER TABLE personnes ALTER COLUMN prenom SET STATISTICS 100;
ALTER TABLE personnes ALTER COLUMN nomnai SET STATISTICS 100;*/
CREATE INDEX personnes_upper_nom_idx ON personnes USING btree (upper(nom::text) varchar_pattern_ops);
CREATE INDEX personnes_upper_prenom_idx ON personnes USING btree (upper(prenom::text) varchar_pattern_ops);
CREATE INDEX personnes_upper_nomnai_idx ON personnes USING btree (upper(nomnai::text) varchar_pattern_ops);

DROP INDEX IF EXISTS dossierscaf_personne_id_idx;
CREATE INDEX dossierscaf_personne_id_idx ON dossierscaf USING btree (personne_id);

DROP INDEX IF EXISTS dsps_personne_id_idx;
CREATE INDEX dsps_personne_id_idx ON dsps USING btree (personne_id);

DROP INDEX IF EXISTS infosfinancieres_dossier_id_idx;
DROP INDEX IF EXISTS infosfinancieres_type_allocation_idx;
DROP INDEX IF EXISTS infosfinancieres_dossier_id_type_allocation_idx;
CREATE INDEX infosfinancieres_dossier_id_idx ON infosfinancieres USING btree (dossier_id);
CREATE INDEX infosfinancieres_type_allocation_idx ON infosfinancieres USING btree (type_allocation);
CREATE INDEX infosfinancieres_dossier_id_type_allocation_idx ON infosfinancieres USING btree (dossier_id,type_allocation);

DROP INDEX IF EXISTS dossiers_detaildroitrsa_id_idx;
CREATE INDEX dossiers_detaildroitrsa_id_idx ON dossiers (detaildroitrsa_id);

DROP INDEX IF EXISTS rattachements_personne_id_idx;
CREATE INDEX rattachements_personne_id_idx ON rattachements (personne_id);

DROP INDEX IF EXISTS contratsinsertion_forme_ci_idx;
DROP INDEX IF EXISTS contratsinsertion_simple_valide_idx;
CREATE INDEX contratsinsertion_forme_ci_idx ON contratsinsertion (forme_ci);
CREATE INDEX contratsinsertion_simple_valide_idx ON contratsinsertion (decision_ci, forme_ci)
	WHERE decision_ci IS NOT NULL
		AND decision_ci <> 'V'
		AND forme_ci = 'S';

DROP INDEX IF EXISTS situationsdossiersrsa_etatdosrsa_ouvert_idx;
CREATE INDEX situationsdossiersrsa_etatdosrsa_ouvert_idx ON situationsdossiersrsa (etatdosrsa) WHERE etatdosrsa IN ( 'Z','2','3','4' );

DROP INDEX IF EXISTS modescontact_foyer_id_idx;
CREATE INDEX modescontact_foyer_id_idx ON modescontact (foyer_id);

DROP INDEX IF EXISTS suivisinstruction_dossier_id_idx;
CREATE INDEX suivisinstruction_dossier_id_idx ON suivisinstruction (dossier_id);

DROP INDEX IF EXISTS dossiers_dtdemrsa_year_idx;
CREATE INDEX dossiers_dtdemrsa_year_idx ON dossiers (DATE_PART('year',dtdemrsa));

DROP INDEX IF EXISTS dossiers_dtdemrsa_month_idx;
CREATE INDEX dossiers_dtdemrsa_month_idx ON dossiers (DATE_PART('month',dtdemrsa));

-- -----------------------------------------------------------------------------

DROP INDEX IF EXISTS actions_typeaction_id_idx;
CREATE INDEX actions_typeaction_id_idx ON actions (typeaction_id);

DROP INDEX IF EXISTS actionsinsertion_contratinsertion_id_idx;
CREATE INDEX actionsinsertion_contratinsertion_id_idx ON actionsinsertion (contratinsertion_id);

DROP INDEX IF EXISTS aidesagricoles_infoagricole_id_idx;
CREATE INDEX aidesagricoles_infoagricole_id_idx ON aidesagricoles (infoagricole_id);

DROP INDEX IF EXISTS aidesdirectes_actioninsertion_id_idx;
CREATE INDEX aidesdirectes_actioninsertion_id_idx ON aidesdirectes (actioninsertion_id);

DROP INDEX IF EXISTS avispcgdroitsrsa_dossier_id_idx;
CREATE INDEX avispcgdroitsrsa_dossier_id_idx ON avispcgdroitsrsa (dossier_id);

DROP INDEX IF EXISTS avispcgpersonnes_personne_id_idx;
CREATE INDEX avispcgpersonnes_personne_id_idx ON avispcgpersonnes (personne_id);

DROP INDEX IF EXISTS bilanparcours_nvparcours_referent_id_idx;
CREATE INDEX bilanparcours_nvparcours_referent_id_idx ON bilanparcours (nvparcours_referent_id);

DROP INDEX IF EXISTS bilanparcours_nvsansep_referent_id_idx;
CREATE INDEX bilanparcours_nvsansep_referent_id_idx ON bilanparcours (nvsansep_referent_id);

DROP INDEX IF EXISTS bilanparcours_personne_id_idx;
CREATE INDEX bilanparcours_personne_id_idx ON bilanparcours (personne_id);

DROP INDEX IF EXISTS bilanparcours_referent_id_idx;
CREATE INDEX bilanparcours_referent_id_idx ON bilanparcours (referent_id);

DROP INDEX IF EXISTS bilanparcours_rendezvous_id_idx;
CREATE INDEX bilanparcours_rendezvous_id_idx ON bilanparcours (rendezvous_id);

DROP INDEX IF EXISTS bilanparcours_structurereferente_id_idx;
CREATE INDEX bilanparcours_structurereferente_id_idx ON bilanparcours (structurereferente_id);

DROP INDEX IF EXISTS bilansparcours66_contratinsertion_id_idx;
CREATE INDEX bilansparcours66_contratinsertion_id_idx ON bilansparcours66 (contratinsertion_id);

DROP INDEX IF EXISTS bilansparcours66_orientstruct_id_idx;
CREATE INDEX bilansparcours66_orientstruct_id_idx ON bilansparcours66 (orientstruct_id);

DROP INDEX IF EXISTS bilansparcours66_referent_id_idx;
CREATE INDEX bilansparcours66_referent_id_idx ON bilansparcours66 (referent_id);

DROP INDEX IF EXISTS connections_user_id_idx;
CREATE INDEX connections_user_id_idx ON connections (user_id);

DROP INDEX IF EXISTS contactspartenaires_partenaire_id_idx;
CREATE INDEX contactspartenaires_partenaire_id_idx ON contactspartenaires (partenaire_id);

DROP INDEX IF EXISTS cuis_personne_id_idx;
CREATE INDEX cuis_personne_id_idx ON cuis (personne_id);

DROP INDEX IF EXISTS cuis_referent_id_idx;
CREATE INDEX cuis_referent_id_idx ON cuis (referent_id);

DROP INDEX IF EXISTS cuis_structurereferente_id_idx;
CREATE INDEX cuis_structurereferente_id_idx ON cuis (structurereferente_id);

DROP INDEX IF EXISTS decisionspropospdos_decisionpdo_id_idx;
CREATE INDEX decisionspropospdos_decisionpdo_id_idx ON decisionspropospdos (decisionpdo_id);

DROP INDEX IF EXISTS decisionspropospdos_propopdo_id_idx;
CREATE INDEX decisionspropospdos_propopdo_id_idx ON decisionspropospdos (propopdo_id);

DROP INDEX IF EXISTS detailsressourcesmensuelles_ressourcesmensuelles_detailressourcemensuelle_id_idx;
CREATE INDEX detailsressourcesmensuelles_ressourcesmensuelles_detailressourcemensuelle_id_idx ON detailsressourcesmensuelles_ressourcesmensuelles (detailressourcemensuelle_id);

DROP INDEX IF EXISTS detailsressourcesmensuelles_ressourcesmensuelles_ressourcemensuelle_id_idx;
CREATE INDEX detailsressourcesmensuelles_ressourcesmensuelles_ressourcemensuelle_id_idx ON detailsressourcesmensuelles_ressourcesmensuelles (ressourcemensuelle_id);

DROP INDEX IF EXISTS grossesses_personne_id_idx;
CREATE INDEX grossesses_personne_id_idx ON grossesses (personne_id);

DROP INDEX IF EXISTS groups_parent_id_idx;
CREATE INDEX groups_parent_id_idx ON groups (parent_id);

DROP INDEX IF EXISTS informationseti_personne_id_idx;
CREATE INDEX informationseti_personne_id_idx ON informationseti (personne_id);

DROP INDEX IF EXISTS infosagricoles_personne_id_idx;
CREATE INDEX infosagricoles_personne_id_idx ON infosagricoles (personne_id);

DROP INDEX IF EXISTS jetons_dossier_id_idx;
CREATE INDEX jetons_dossier_id_idx ON jetons (dossier_id);

DROP INDEX IF EXISTS jetons_user_id_idx;
CREATE INDEX jetons_user_id_idx ON jetons (user_id);

DROP INDEX IF EXISTS memos_personne_id_idx;
CREATE INDEX memos_personne_id_idx ON memos (personne_id);

DROP INDEX IF EXISTS orientations_personne_id_idx;
CREATE INDEX orientations_personne_id_idx ON orientations (personne_id);

DROP INDEX IF EXISTS orientsstructs_servicesinstructeurs_orientstruct_id_idx;
CREATE INDEX orientsstructs_servicesinstructeurs_orientstruct_id_idx ON orientsstructs_servicesinstructeurs (orientstruct_id);

DROP INDEX IF EXISTS orientsstructs_servicesinstructeurs_serviceinstructeur_id_idx;
CREATE INDEX orientsstructs_servicesinstructeurs_serviceinstructeur_id_idx ON orientsstructs_servicesinstructeurs (serviceinstructeur_id);

DROP INDEX IF EXISTS paiementsfoyers_foyer_id_idx;
CREATE INDEX paiementsfoyers_foyer_id_idx ON paiementsfoyers (foyer_id);

DROP INDEX IF EXISTS parcours_personne_id_idx;
CREATE INDEX parcours_personne_id_idx ON parcours (personne_id);

DROP INDEX IF EXISTS prestsform_actioninsertion_id_idx;
CREATE INDEX prestsform_actioninsertion_id_idx ON prestsform (actioninsertion_id);

DROP INDEX IF EXISTS prestsform_refpresta_id_idx;
CREATE INDEX prestsform_refpresta_id_idx ON prestsform (refpresta_id);

DROP INDEX IF EXISTS propospdos_originepdo_id_idx;
CREATE INDEX propospdos_originepdo_id_idx ON propospdos (originepdo_id);

DROP INDEX IF EXISTS propospdos_personne_id_idx;
CREATE INDEX propospdos_personne_id_idx ON propospdos (personne_id);

DROP INDEX IF EXISTS propospdos_referent_id_idx;
CREATE INDEX propospdos_referent_id_idx ON propospdos (referent_id);

DROP INDEX IF EXISTS propospdos_serviceinstructeur_id_idx;
CREATE INDEX propospdos_serviceinstructeur_id_idx ON propospdos (serviceinstructeur_id);

DROP INDEX IF EXISTS propospdos_structurereferente_id_idx;
CREATE INDEX propospdos_structurereferente_id_idx ON propospdos (structurereferente_id);

DROP INDEX IF EXISTS propospdos_typenotifpdo_id_idx;
CREATE INDEX propospdos_typenotifpdo_id_idx ON propospdos (typenotifpdo_id);

DROP INDEX IF EXISTS propospdos_typepdo_id_idx;
CREATE INDEX propospdos_typepdo_id_idx ON propospdos (typepdo_id);

DROP INDEX IF EXISTS propospdos_user_id_idx;
CREATE INDEX propospdos_user_id_idx ON propospdos (user_id);

DROP INDEX IF EXISTS referents_structurereferente_id_idx;
CREATE INDEX referents_structurereferente_id_idx ON referents (structurereferente_id);

DROP INDEX IF EXISTS regroupementszonesgeo_zonesgeographiques_regroupementzonegeo_id_idx;
CREATE INDEX regroupementszonesgeo_zonesgeographiques_regroupementzonegeo_id_idx ON regroupementszonesgeo_zonesgeographiques (regroupementzonegeo_id);

DROP INDEX IF EXISTS regroupementszonesgeo_zonesgeographiques_zonegeographique_id_idx;
CREATE INDEX regroupementszonesgeo_zonesgeographiques_zonegeographique_id_idx ON regroupementszonesgeo_zonesgeographiques (zonegeographique_id);

DROP INDEX IF EXISTS ressources_ressourcesmensuelles_ressource_id_idx;
CREATE INDEX ressources_ressourcesmensuelles_ressource_id_idx ON ressources_ressourcesmensuelles (ressource_id);

DROP INDEX IF EXISTS ressources_ressourcesmensuelles_ressourcemensuelle_id_idx;
CREATE INDEX ressources_ressourcesmensuelles_ressourcemensuelle_id_idx ON ressources_ressourcesmensuelles (ressourcemensuelle_id);

DROP INDEX IF EXISTS suivisaidesaprestypesaides_suiviaideapre_id_idx;
CREATE INDEX suivisaidesaprestypesaides_suiviaideapre_id_idx ON suivisaidesaprestypesaides (suiviaideapre_id);

DROP INDEX IF EXISTS suivisappuisorientation_personne_id_idx;
CREATE INDEX suivisappuisorientation_personne_id_idx ON suivisappuisorientation (personne_id);

DROP INDEX IF EXISTS titressejour_personne_id_idx;
CREATE INDEX titressejour_personne_id_idx ON titressejour (personne_id);

-- DROP INDEX IF EXISTS tmporientsstructs_personne_id_idx;
-- CREATE INDEX tmporientsstructs_personne_id_idx ON tmporientsstructs (personne_id);

-- DROP INDEX IF EXISTS tmporientsstructs_structurereferente_id_idx;
-- CREATE INDEX tmporientsstructs_structurereferente_id_idx ON tmporientsstructs (structurereferente_id);

-- DROP INDEX IF EXISTS tmporientsstructs_typeorient_id_idx;
-- CREATE INDEX tmporientsstructs_typeorient_id_idx ON tmporientsstructs (typeorient_id);

DROP INDEX IF EXISTS totalisationsacomptes_identificationflux_id_idx;
CREATE INDEX totalisationsacomptes_identificationflux_id_idx ON totalisationsacomptes (identificationflux_id);

DROP INDEX IF EXISTS traitementspdos_descriptionpdo_id_idx;
CREATE INDEX traitementspdos_descriptionpdo_id_idx ON traitementspdos (descriptionpdo_id);

DROP INDEX IF EXISTS traitementspdos_personne_id_idx;
CREATE INDEX traitementspdos_personne_id_idx ON traitementspdos (personne_id);

DROP INDEX IF EXISTS traitementspdos_propopdo_id_idx;
CREATE INDEX traitementspdos_propopdo_id_idx ON traitementspdos (propopdo_id);

DROP INDEX IF EXISTS traitementspdos_traitementtypepdo_id_idx;
CREATE INDEX traitementspdos_traitementtypepdo_id_idx ON traitementspdos (traitementtypepdo_id);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************

-- INFO: pour que les différents VACUUM / REINDEX soient pris en compte sans erreur,
--       il faut passer ce script à partir de la console (par opposition à "à partir
--       de phpPgAdmin".

/*VACUUM FULL actions;
REINDEX TABLE actions;

VACUUM FULL actionsinsertion;
REINDEX TABLE actionsinsertion;

VACUUM FULL aidesagricoles;
REINDEX TABLE aidesagricoles;

VACUUM FULL aidesdirectes;
REINDEX TABLE aidesdirectes;

VACUUM FULL avispcgdroitsrsa;
REINDEX TABLE avispcgdroitsrsa;

VACUUM FULL avispcgpersonnes;
REINDEX TABLE avispcgpersonnes;

VACUUM FULL bilanparcours;
REINDEX TABLE bilanparcours;

VACUUM FULL bilansparcours66;
REINDEX TABLE bilansparcours66;

VACUUM FULL connections;
REINDEX TABLE connections;

VACUUM FULL contactspartenaires;
REINDEX TABLE contactspartenaires;

VACUUM FULL contratsinsertion;
REINDEX TABLE contratsinsertion;

VACUUM FULL cuis;
REINDEX TABLE cuis;

VACUUM FULL decisionspropospdos;
REINDEX TABLE decisionspropospdos;

VACUUM FULL detailsressourcesmensuelles_ressourcesmensuelles;
REINDEX TABLE detailsressourcesmensuelles_ressourcesmensuelles;

VACUUM FULL dossiers;
REINDEX TABLE dossiers;

VACUUM FULL dossierscaf;
REINDEX TABLE dossierscaf;

VACUUM FULL dsps;
REINDEX TABLE dsps;

VACUUM FULL grossesses;
REINDEX TABLE grossesses;

VACUUM FULL groups;
REINDEX TABLE groups;

VACUUM FULL informationseti;
REINDEX TABLE informationseti;

VACUUM FULL infosagricoles;
REINDEX TABLE infosagricoles;

VACUUM FULL infosfinancieres;
REINDEX TABLE infosfinancieres;

VACUUM FULL jetons;
REINDEX TABLE jetons;

VACUUM FULL memos;
REINDEX TABLE memos;

VACUUM FULL modescontact;
REINDEX TABLE modescontact;

VACUUM FULL orientations;
REINDEX TABLE orientations;

VACUUM FULL orientsstructs_servicesinstructeurs;
REINDEX TABLE orientsstructs_servicesinstructeurs;

VACUUM FULL paiementsfoyers;
REINDEX TABLE paiementsfoyers;

VACUUM FULL parcours;
REINDEX TABLE parcours;

VACUUM FULL personnes;
REINDEX TABLE personnes;

VACUUM FULL prestsform;
REINDEX TABLE prestsform;

VACUUM FULL propospdos;
REINDEX TABLE propospdos;

VACUUM FULL rattachements;
REINDEX TABLE rattachements;

VACUUM FULL referents;
REINDEX TABLE referents;

VACUUM FULL regroupementszonesgeo_zonesgeographiques;
REINDEX TABLE regroupementszonesgeo_zonesgeographiques;

VACUUM FULL ressources_ressourcesmensuelles;
REINDEX TABLE ressources_ressourcesmensuelles;

VACUUM FULL situationsdossiersrsa;
REINDEX TABLE situationsdossiersrsa;

VACUUM FULL suivisaidesaprestypesaides;
REINDEX TABLE suivisaidesaprestypesaides;

VACUUM FULL suivisappuisorientation;
REINDEX TABLE suivisappuisorientation;

VACUUM FULL suivisinstruction;
REINDEX TABLE suivisinstruction;

VACUUM FULL titressejour;
REINDEX TABLE titressejour;

-- VACUUM FULL tmporientsstructs;
-- REINDEX TABLE tmporientsstructs;

VACUUM FULL totalisationsacomptes;
REINDEX TABLE totalisationsacomptes;

VACUUM FULL traitementspdos;
REINDEX TABLE traitementspdos;*/

-- *****************************************************************************