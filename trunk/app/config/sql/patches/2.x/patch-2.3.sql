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




-- *****************************************************************************
COMMIT;
-- *****************************************************************************