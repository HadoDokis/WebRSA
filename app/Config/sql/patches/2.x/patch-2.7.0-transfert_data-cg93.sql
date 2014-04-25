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

-- INFO: A passer après l'import du shell de 2013
-- Insertion du catalogue Hors PDI de 2013
INSERT INTO thematiquesfps93 ( type, name, created, modified ) VALUES
	( 'horspdi', 'Prescription professionnelle', NOW(), NOW() ),
	( 'horspdi', 'Prescription socioprofessionnelle', NOW(), NOW() ),
	( 'horspdi', 'Prescription Pôle Emploi', NOW(), NOW() ),
	( 'horspdi', 'Prescription sociale', NOW(), NOW() ),
	( 'horspdi', 'Autres', NOW(), NOW() );

INSERT INTO categoriesfps93 ( thematiquefp93_id, name, created, modified ) VALUES
	-- Prescription professionnelle hors PDI
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription professionnelle' LIMIT 1 ), 'Non définie', NOW(), NOW() ),
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription professionnelle' LIMIT 1 ), 'maison de l’emploi ou SE autre que PE', NOW(), NOW() ),
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription professionnelle' LIMIT 1 ), 'Plie', NOW(), NOW() ),
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription professionnelle' LIMIT 1 ), 'Formation de Droit Commun Région, AFPA, ...)', NOW(), NOW() ),
	-- Prescription socioprofessionnelle hors PDI
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription socioprofessionnelle' LIMIT 1 ), 'Non définie', NOW(), NOW() ),
	-- Prescription Pôle Emploi hors PDI
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription Pôle Emploi' LIMIT 1 ), 'Prestation pôle emploi', NOW(), NOW() ),
	-- Prescription sociale hors PDI
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Prescription sociale' LIMIT 1 ), 'Prestation sociale', NOW(), NOW() ),
	-- Autres hors PDI
	( ( SELECT id FROM thematiquesfps93 WHERE type = 'horspdi' AND name = 'Autres' LIMIT 1 ), 'Non définie', NOW(), NOW() );

INSERT INTO filieresfps93 ( categoriefp93_id, name, created, modified )
	SELECT
			categoriesfps93.id AS categoriefp93_id,
			'Non définie' AS name,
			NOW() AS created,
			NOW() AS modified
		FROM categoriesfps93
			INNER JOIN thematiquesfps93 ON ( categoriesfps93.thematiquefp93_id = thematiquesfps93.id )
		WHERE thematiquesfps93.type = 'horspdi';

-- Fonctions utilitaires
CREATE OR REPLACE FUNCTION select_filierefp93_id( TEXT, TEXT, TEXT, TEXT ) RETURNS INT AS
$$
	SELECT
			filieresfps93.id
		FROM filieresfps93
			INNER JOIN categoriesfps93 ON ( categoriesfps93.id = filieresfps93.categoriefp93_id )
			INNER JOIN thematiquesfps93 ON ( thematiquesfps93.id = categoriesfps93.thematiquefp93_id )
		WHERE
			NOACCENTS_UPPER( thematiquesfps93.type ) = NOACCENTS_UPPER( $1 )
			AND NOACCENTS_UPPER( thematiquesfps93.name ) = NOACCENTS_UPPER( $2 )
			AND NOACCENTS_UPPER( categoriesfps93.name ) = NOACCENTS_UPPER( $3 )
			AND NOACCENTS_UPPER( filieresfps93.name ) = NOACCENTS_UPPER( $4 )
		LIMIT 1
$$
LANGUAGE 'sql';

CREATE OR REPLACE FUNCTION select_actionfp93_id( TEXT, TEXT, TEXT, TEXT, TEXT ) RETURNS INT AS
$$
	SELECT
			actionsfps93.id
		FROM actionsfps93
			INNER JOIN filieresfps93 ON ( filieresfps93.id = actionsfps93.filierefp93_id )
			INNER JOIN categoriesfps93 ON ( categoriesfps93.id = filieresfps93.categoriefp93_id )
			INNER JOIN thematiquesfps93 ON ( thematiquesfps93.id = categoriesfps93.thematiquefp93_id )
		WHERE
			NOACCENTS_UPPER( thematiquesfps93.type ) = NOACCENTS_UPPER( $1 )
			AND NOACCENTS_UPPER( thematiquesfps93.name ) = NOACCENTS_UPPER( $2 )
			AND NOACCENTS_UPPER( categoriesfps93.name ) = NOACCENTS_UPPER( $3 )
			AND NOACCENTS_UPPER( filieresfps93.name ) = NOACCENTS_UPPER( $4 )
			AND NOACCENTS_UPPER( actionsfps93.name ) = NOACCENTS_UPPER( $5 )
		LIMIT 1
$$
LANGUAGE 'sql';

-- INSERT 0 2940

SELECT add_missing_table_field( 'public', 'fichesprescriptions93', 'actioncandidat_personne_id', 'INTEGER' );

INSERT INTO fichesprescriptions93 (
	actioncandidat_personne_id,
	personne_id,
	statut,
	referent_id,
	objet,
	rdvprestataire_date,
	filierefp93_id,
	actionfp93_id,
	actionfp93,
	prestatairefp93_id,
	documentbeneffp93_autre,
	date_signature,
	date_transmission,
	date_retour,
	benef_retour_presente,
	retour_nom_partenaire,
	personne_recue,
	motifnonreceptionfp93_id,
	personne_nonrecue_autre,
	personne_retenue,
	motifnonretenuefp93_id,
	personne_nonretenue_autre,
	personne_a_integre,
	motifnonintegrationfp93_id,
	personne_nonintegre_autre,
	motif_annulation,
	created,
	modified
)
SELECT
		actionscandidats_personnes.id,
		actionscandidats_personnes.personne_id,
		(
			CASE
				WHEN (
					actionscandidats_personnes.positionfiche = 'enattente'
					AND actionscandidats_personnes.bilanretenu IS NULL
					AND actionscandidats_personnes.datesignature IS NULL
				) THEN '05suivi_renseigne'
				WHEN (
					actionscandidats_personnes.positionfiche = 'enattente'
					AND actionscandidats_personnes.bilanretenu IS NULL
					AND actionscandidats_personnes.datesignature IS NOT NULL
				) THEN '02signee'
				WHEN (
					actionscandidats_personnes.positionfiche = 'enattente'
					AND actionscandidats_personnes.bilanretenu IS NOT NULL
					AND actionscandidats_personnes.datesignature IS NOT NULL
				) THEN '05suivi_renseigne'
				WHEN (
					actionscandidats_personnes.positionfiche = 'encours'
					AND actionscandidats_personnes.bilanretenu = 'RET'
				) THEN '05suivi_renseigne'
				WHEN (
					actionscandidats_personnes.positionfiche = 'nonretenue'
					AND actionscandidats_personnes.bilanretenu = 'NRE'
				) THEN '05suivi_renseigne'
				WHEN (
					actionscandidats_personnes.positionfiche = 'annule'
				) THEN '99annulee'
			END
		) AS statut,
		actionscandidats_personnes.referent_id,
		actionscandidats_personnes.motifdemande AS objet,
		actionscandidats_personnes.horairerdvpartenaire AS rdvprestataire_date,
		(
			CASE
				-- Prescriptions PDI
				WHEN actionscandidats.name LIKE '10 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'Non définie', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '11 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'formation pré qualifiante', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '12 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'formation qualifiante', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '13 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'SIAE Entreprise d insertion', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '14 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'Action du CUCS', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '15 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'Accompagnement à la création d''activité', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '16 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription professionnelle', 'FDIF/APRE', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '30 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription socio professionnelle', 'Non définie', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '31 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription socio professionnelle', 'Action du CUCS', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '32 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription socio professionnelle', 'Remise à niveau', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '33 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription socio professionnelle', 'linguistique', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '70 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription vers les acteurs de la santé', 'Accompagnement santé', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '80 %' THEN (
					select_filierefp93_id( 'pdi', 'Prescription culture loisirs vacances', 'Projet loisirs vacances', 'Non définie' )
				)
				-- Prescriptions hors PDI
				WHEN actionscandidats.name LIKE '20 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription professionnelle', 'Non définie', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '21 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription professionnelle', 'maison de l’emploi ou SE autre que PE', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '22 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription professionnelle', 'Plie', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '23 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription professionnelle', 'Formation de Droit Commun Région, AFPA, ...)', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '40 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription socioprofessionnelle', 'Non définie', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '50 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription Pôle Emploi', 'Prestation pôle emploi', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '60 %' THEN (
					select_filierefp93_id( 'horspdi', 'Prescription sociale', 'Prestation sociale', 'Non définie' )
				)
				WHEN actionscandidats.name LIKE '90 %' THEN (
					select_filierefp93_id( 'horspdi', 'Autres', 'Non définie', 'Non définie' )
				)
				ELSE NULL
			END
		) AS filierefp93_id,
		(
			CASE
				-- Prescriptions PDI
				WHEN actionscandidats.name LIKE '10 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'Non définie', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '11 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'formation pré qualifiante', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '12 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'formation qualifiante', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '13 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'SIAE Entreprise d insertion', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '14 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'Action du CUCS', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '15 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'Accompagnement à la création d''activité', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '16 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription professionnelle', 'FDIF/APRE', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '30 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription socio professionnelle', 'Non définie', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '31 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription socio professionnelle', 'Action du CUCS', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '32 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription socio professionnelle', 'Remise à niveau', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '33 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription socio professionnelle', 'linguistique', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '70 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription vers les acteurs de la santé', 'Accompagnement santé', 'Non définie', 'Non défini' )
				)
				WHEN actionscandidats.name LIKE '80 %' THEN (
					select_actionfp93_id( 'pdi', 'Prescription culture loisirs vacances', 'Projet loisirs vacances', 'Non définie', 'Non défini' )
				)
				-- Prescriptions hors PDI
				ELSE NULL
			END
		) AS actionfp93_id,
		(
			CASE
				-- Prescriptions hors PDI
				WHEN (
					actionscandidats.name LIKE '20 %'
					OR actionscandidats.name LIKE '21 %'
					OR actionscandidats.name LIKE '22 %'
					OR actionscandidats.name LIKE '23 %'
					OR actionscandidats.name LIKE '40 %'
					OR actionscandidats.name LIKE '50 %'
					OR actionscandidats.name LIKE '60 %'
					OR actionscandidats.name LIKE '90 %'
				) THEN 'Non définie'
				ELSE NULL
			END
		) AS actionfp93,
		( SELECT prestatairesfps93.id FROM prestatairesfps93 WHERE prestatairesfps93.name = 'Non defini' LIMIT 1 ) AS prestatairefp93_id,
		actionscandidats_personnes.autrepiece AS documentbeneffp93_autre,
		actionscandidats_personnes.datesignature AS date_signature,
		actionscandidats_personnes.ddaction AS date_transmission,
		actionscandidats_personnes.dfaction AS date_retour,
		( CASE WHEN actionscandidats_personnes.bilanvenu = 'VEN' THEN 'oui' WHEN actionscandidats_personnes.bilanvenu = 'NVE' THEN 'non' ELSE NULL END ),
		actionscandidats_personnes.personnerecu,
		( CASE WHEN actionscandidats_personnes.bilanrecu = 'O' THEN '1' WHEN actionscandidats_personnes.bilanrecu = 'N' THEN '0' ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanrecu = 'N' THEN ( SELECT id FROM motifsnonreceptionsfps93 WHERE autre = '1' LIMIT 1 ) ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanrecu = 'N' THEN actionscandidats_personnes.precisionmotif ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanretenu = 'RET' THEN '1' WHEN actionscandidats_personnes.bilanretenu = 'NRE' THEN '0' ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanretenu = 'NRE' THEN ( SELECT id FROM motifsnonretenuesfps93 WHERE autre = '1' LIMIT 1 ) ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanretenu = 'NRE' THEN actionscandidats_personnes.precisionmotif ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.integrationaction = 'O' THEN '1' WHEN actionscandidats_personnes.integrationaction = 'N' THEN '0' ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.integrationaction = 'N' THEN ( SELECT id FROM motifsnonintegrationsfps93 WHERE autre = '1' LIMIT 1 ) ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.integrationaction = 'N' THEN actionscandidats_personnes.precisionmotif ELSE NULL END ),
		actionscandidats_personnes.motifannulation,
		LEAST( actionscandidats_personnes.ddaction, actionscandidats_personnes.dfaction, actionscandidats_personnes.datesignature, actionscandidats_personnes.datebilan, actionscandidats_personnes.daterecu, actionscandidats_personnes.sortiele ),
		GREATEST( actionscandidats_personnes.ddaction, actionscandidats_personnes.dfaction, actionscandidats_personnes.datesignature, actionscandidats_personnes.datebilan, actionscandidats_personnes.daterecu, actionscandidats_personnes.sortiele )
	FROM actionscandidats_personnes
		INNER JOIN actionscandidats ON ( actionscandidats_personnes.actioncandidat_id = actionscandidats.id );

DROP FUNCTION select_filierefp93_id( TEXT, TEXT, TEXT, TEXT );
DROP FUNCTION select_actionfp93_id( TEXT, TEXT, TEXT, TEXT, TEXT );

INSERT INTO instantanesdonneesfps93 (
	ficheprescription93_id,
	referent_fonction,
	structure_name,
	structure_num_voie,
	structure_type_voie,
	structure_nom_voie,
	structure_code_postal,
	structure_ville,
	structure_tel,
	structure_fax,
	referent_email,
	benef_qual,
	benef_nom,
	benef_prenom,
	benef_dtnai,
	benef_numvoie,
	benef_typevoie,
	benef_nomvoie,
	benef_complideadr,
	benef_compladr,
	benef_numcomptt,
	benef_numcomrat,
	benef_codepos,
	benef_locaadr,
	benef_tel_fixe,
	benef_tel_port,
	benef_email,
	benef_identifiantpe,
	benef_inscritpe,
	benef_matricule,
	benef_natpf_socle,
	benef_natpf_majore,
	benef_natpf_activite,
	benef_nivetu,
	benef_etatdosrsa,
	benef_toppersdrodevorsa,
	benef_dd_ci,
	benef_df_ci,
	benef_positioncer,
	created,
	modified
)
SELECT
		fichesprescriptions93.id,
		referents.fonction,
		structuresreferentes.lib_struc,
		structuresreferentes.num_voie,
		structuresreferentes.type_voie,
		structuresreferentes.nom_voie,
		structuresreferentes.code_postal,
		structuresreferentes.ville,
		structuresreferentes.numtel,
		structuresreferentes.numfax,
		referents.email,
		personnes.qual,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.numvoie
				ELSE adresses.numvoie
			END
		) AS benef_numvoie,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.typevoie
				ELSE adresses.typevoie
			END
		) AS benef_typevoie,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.nomvoie
				ELSE adresses.nomvoie
			END
		) AS benef_nomvoie,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.complideadr
				ELSE adresses.complideadr
			END
		) AS benef_complideadr,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.compladr
				ELSE adresses.compladr
			END
		) AS benef_compladr,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.numcomptt
				ELSE adresses.numcomptt
			END
		) AS benef_numcomptt,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.numcomrat
				ELSE adresses.numcomrat
			END
		) AS benef_numcomrat,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.codepos
				ELSE adresses.codepos
			END
		) AS benef_codepos,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.locaadr
				ELSE adresses.locaadr
			END
		) AS benef_locaadr,
		personnes.numfixe,
		personnes.numport,
		personnes.email,
		historiqueetatspe.identifiantpe AS benef_identifiantpe,
		( CASE WHEN historiqueetatspe.etat = 'inscription' THEN '1' ELSE '0' END ) AS benef_inscritpe,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.matricule
				ELSE dossiers.matricule
			END
		) AS benef_matricule,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.natpf_socle
				ELSE ( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RSD', 'RSI', 'RSU', 'RSB', 'RSJ') ) THEN '1' ELSE '0' END )
			END
		) AS benef_natpf_socle,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.natpf_majore
				ELSE ( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RSI', 'RCI') ) THEN '1' ELSE '0' END )
			END
		) AS benef_natpf_majore,
		(
			CASE
				WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.natpf_activite
				ELSE ( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RCD', 'RCI', 'RCU', 'RCB', 'RCJ') ) THEN '1' ELSE '0' END )
			END
		) AS benef_natpf_activite,
		(
			CASE
				WHEN dsps_revs.id IS NOT NULL THEN dsps_revs.nivetu
				WHEN dsps.id IS NOT NULL THEN dsps.nivetu
				else null
			END
		) AS benef_nivetu,
		(
			CASE
				WHEN historiquesdroits.id IS NOT NULL THEN historiquesdroits.etatdosrsa
				ELSE situationsdossiersrsa.etatdosrsa
			END
		) AS benef_etatdosrsa,
		(
			CASE
				WHEN historiquesdroits.id IS NOT NULL THEN historiquesdroits.toppersdrodevorsa
				ELSE calculsdroitsrsa.toppersdrodevorsa::TEXT
			END
		) AS benef_toppersdrodevorsa,
		contratsinsertion.dd_ci,
		contratsinsertion.df_ci,
		(
			CASE
				WHEN contratsinsertion.decision_ci = 'V' THEN 'valide'
				WHEN cers93.positioncer IN ( '00enregistre', '01signe', '02attdecisioncpdv' ) THEN 'validationpdv'
				WHEN cers93.positioncer IN ( '03attdecisioncg', '04premierelecture', '05secondelecture', '07attavisep' ) THEN 'validationcg'
				ELSE 'aucun'
			END
		) AS benef_positioncer,
		fichesprescriptions93.created,
		fichesprescriptions93.created
	FROM fichesprescriptions93
		INNER JOIN actionscandidats_personnes ON ( fichesprescriptions93.actioncandidat_personne_id = actionscandidats_personnes.id )
		INNER JOIN referents ON ( fichesprescriptions93.referent_id = referents.id )
		INNER JOIN structuresreferentes ON ( referents.structurereferente_id = structuresreferentes.id )
		INNER JOIN personnes ON ( fichesprescriptions93.personne_id = personnes.id )
		INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
		INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
		LEFT OUTER JOIN detailsdroitsrsa ON ( dossiers.id = detailsdroitsrsa.dossier_id )
		LEFT OUTER JOIN situationsallocataires ON (
			situationsallocataires.personne_id = personnes.id
			AND situationsallocataires.id IN (
				SELECT dernierssituationsallocataires.id
					FROM situationsallocataires AS dernierssituationsallocataires
					WHERE
						dernierssituationsallocataires.personne_id = personnes.id
						-- Avant la date de création de la fiche de prescription
						AND dernierssituationsallocataires.modified <= fichesprescriptions93.created
					ORDER BY dernierssituationsallocataires.modified DESC
					LIMIT 1
			)
		)
		LEFT OUTER JOIN contratsinsertion ON (
			contratsinsertion.personne_id = personnes.id
			AND contratsinsertion.id IN (
				SELECT
					dernierscontratsinsertion.id
				FROM contratsinsertion AS dernierscontratsinsertion
				WHERE
					dernierscontratsinsertion.personne_id = personnes.id
					AND dernierscontratsinsertion.decision_ci IN ('E', 'V')
					AND dernierscontratsinsertion.dd_ci <= LEAST( actionscandidats_personnes.ddaction, actionscandidats_personnes.dfaction, actionscandidats_personnes.datesignature, actionscandidats_personnes.datebilan, actionscandidats_personnes.daterecu, actionscandidats_personnes.sortiele )
				ORDER BY dernierscontratsinsertion.dd_ci DESC
				LIMIT 1
			)
		)
		LEFT OUTER JOIN cers93 ON ( cers93.contratinsertion_id = contratsinsertion.id )
		LEFT OUTER JOIN informationspe ON (
			(
				(
					informationspe.nir IS NOT NULL
					AND  SUBSTRING( informationspe.nir FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM personnes.nir ) FROM 1 FOR 13 )
					AND  informationspe.dtnai = personnes.dtnai
				)
				OR
				(
					personnes.nom IS NOT NULL
					AND  personnes.prenom IS NOT NULL
					AND  personnes.dtnai IS NOT NULL
					AND  TRIM( BOTH ' ' FROM informationspe.nom ) = TRIM( BOTH ' ' FROM personnes.nom )
					AND  TRIM( BOTH ' ' FROM informationspe.prenom ) = TRIM( BOTH ' ' FROM personnes.prenom )
					AND  informationspe.dtnai = personnes.dtnai
				)
			)
			AND informationspe.id IN (
				SELECT derniereinformationspe.i__id FROM (
					SELECT i.id AS i__id, h.date AS h__date
						FROM informationspe AS i
							INNER JOIN historiqueetatspe AS h ON (h.informationpe_id = i.id)
						WHERE
						(
							(
								(
									(i.nir IS NOT NULL)
									AND  (personnes.nir IS NOT NULL)
									AND  (TRIM( BOTH ' ' FROM i.nir ) <> '')
									AND  (TRIM( BOTH ' ' FROM personnes.nir ) <> '')
									AND  (SUBSTRING( i.nir FROM 1 FOR 13 ) = SUBSTRING( personnes.nir FROM 1 FOR 13 ))
									AND  (i.dtnai = personnes.dtnai)
								)
							)
							OR
							(
								(
									(i.nom IS NOT NULL)
									AND  (personnes.nom IS NOT NULL)
									AND  (i.prenom IS NOT NULL)
									AND  (personnes.prenom IS NOT NULL)
									AND  (TRIM( BOTH ' ' FROM i.nom ) <> '')
									AND  (TRIM( BOTH ' ' FROM i.prenom ) <> '')
									AND  (TRIM( BOTH ' ' FROM personnes.nom ) <> '')
									AND  (TRIM( BOTH ' ' FROM personnes.prenom ) <> '')
									AND  (TRIM( BOTH ' ' FROM i.nom ) = personnes.nom)
									AND  (TRIM( BOTH ' ' FROM i.prenom ) = personnes.prenom)
									AND  (i.dtnai = personnes.dtnai)
								)
							)
						)
						AND h.id IN (
							SELECT dernierhistoriqueetatspe.id AS dernierhistoriqueetatspe__id
							FROM historiqueetatspe AS dernierhistoriqueetatspe
							WHERE
								dernierhistoriqueetatspe.informationpe_id = i.id
								-- Avant la date de création de la fiche de prescription
								AND dernierhistoriqueetatspe.date <= fichesprescriptions93.created
							ORDER BY dernierhistoriqueetatspe.date DESC, dernierhistoriqueetatspe.id DESC
							LIMIT 1
						)
				) AS derniereinformationspe
				ORDER BY derniereinformationspe.h__date DESC
				LIMIT 1
			)
		)
		LEFT OUTER JOIN historiqueetatspe ON (
			historiqueetatspe.informationpe_id = informationspe.id
			AND historiqueetatspe.id IN (
				SELECT h.id
					FROM historiqueetatspe AS h
					WHERE h.informationpe_id = informationspe.id
					ORDER BY h.date DESC
					LIMIT 1
			)
		)
		LEFT OUTER JOIN adressesfoyers ON (
			adressesfoyers.foyer_id = foyers.id
			AND adressesfoyers.id IN (
				SELECT af.id
					FROM adressesfoyers AS af
					WHERE
						af.foyer_id = foyers.id
						-- Avant la date de création de la fiche de prescription
						AND af.dtemm <= fichesprescriptions93.created
						ORDER BY af.dtemm DESC
					LIMIT 1
			)
		)
		LEFT OUTER JOIN adresses ON ( adressesfoyers.adresse_id = adresses.id )
		LEFT OUTER JOIN dsps ON (
			dsps.personne_id = personnes.id
			AND dsps.id IN (
				SELECT dernieresdsps.id
					FROM dsps AS dernieresdsps
					WHERE dernieresdsps.personne_id = personnes.id
					ORDER BY dernieresdsps.id DESC
					LIMIT 1
			)
		)
		LEFT OUTER JOIN dsps_revs ON (
			dsps_revs.personne_id = personnes.id
			AND dsps_revs.id IN (
				SELECT dernieresdsps_revs.id
					FROM dsps_revs AS dernieresdsps_revs
					WHERE
						dernieresdsps_revs.personne_id = personnes.id
						-- Avant la date de création de la fiche de prescription
						AND dernieresdsps_revs.modified <= fichesprescriptions93.created
					ORDER BY dernieresdsps_revs.modified DESC
					LIMIT 1
			)
		)
		LEFT OUTER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_id = dossiers.id )
		LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
		LEFT OUTER JOIN historiquesdroits ON (
			historiquesdroits.personne_id = personnes.id
			AND historiquesdroits.id IN (
				SELECT derniershistoriquesdroits.id
					FROM historiquesdroits AS derniershistoriquesdroits
					WHERE
						derniershistoriquesdroits.personne_id = personnes.id
						-- Avant la date de création de la fiche de prescription
						AND derniershistoriquesdroits.created <= fichesprescriptions93.created
					ORDER BY derniershistoriquesdroits.modified DESC
					LIMIT 1
			)
		)
	WHERE
		fichesprescriptions93.actioncandidat_personne_id IS NOT NULL
;

INSERT INTO documentsbenefsfps93_fichesprescriptions93
(
	documentbeneffp93_id,
	ficheprescription93_id,
	created,
	modified
)
SELECT
		(
			CASE
				WHEN actionscandidats_personnes.pieceallocataire = 'CER' THEN (
					SELECT documentsbenefsfps93.id
						FROM documentsbenefsfps93
						WHERE documentsbenefsfps93.name = 'CER'
						LIMIT 1
				)
				WHEN actionscandidats_personnes.pieceallocataire = 'NCA' THEN (
					SELECT documentsbenefsfps93.id
						FROM documentsbenefsfps93
						WHERE documentsbenefsfps93.name = 'Notification CAF'
						LIMIT 1
				)
				WHEN actionscandidats_personnes.pieceallocataire = 'CV' THEN (
					SELECT documentsbenefsfps93.id
						FROM documentsbenefsfps93
						WHERE documentsbenefsfps93.name = 'CV'
						LIMIT 1
				)
				WHEN actionscandidats_personnes.pieceallocataire = 'AUT' THEN (
					SELECT documentsbenefsfps93.id
						FROM documentsbenefsfps93
						WHERE documentsbenefsfps93.name = 'Autre'
						LIMIT 1
				)
				ELSE NULL
			END
		) AS documentbeneffp93_id,
		fichesprescriptions93.id AS ficheprescription93_id,
		fichesprescriptions93.created AS created,
		fichesprescriptions93.modified AS modified
	FROM fichesprescriptions93
		INNER JOIN actionscandidats_personnes ON ( fichesprescriptions93.actioncandidat_personne_id = actionscandidats_personnes.id )
WHERE
	fichesprescriptions93.actioncandidat_personne_id IS NOT NULL
	AND actionscandidats_personnes.pieceallocataire IS NOT NULL;


ALTER TABLE fichesprescriptions93 DROP COLUMN actioncandidat_personne_id;

/*
SELECT
		historiqueetatspe.identifiantpe AS benef_identifiantpe,
		( CASE WHEN historiqueetatspe.etat = 'inscription' THEN '1' ELSE '0' END ) AS benef_inscritpe,
		historiqueetatspe.date
	FROM personnes
		LEFT OUTER JOIN informationspe ON (
			(
				(
					informationspe.nir IS NOT NULL
					AND  SUBSTRING( informationspe.nir FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM personnes.nir ) FROM 1 FOR 13 )
					AND  informationspe.dtnai = personnes.dtnai
				)
				OR
				(
					personnes.nom IS NOT NULL
					AND  personnes.prenom IS NOT NULL
					AND  personnes.dtnai IS NOT NULL
					AND  TRIM( BOTH ' ' FROM informationspe.nom ) = TRIM( BOTH ' ' FROM personnes.nom )
					AND  TRIM( BOTH ' ' FROM informationspe.prenom ) = TRIM( BOTH ' ' FROM personnes.prenom )
					AND  informationspe.dtnai = personnes.dtnai
				)
			)
			AND informationspe.id IN (
				SELECT derniereinformationspe.i__id FROM (
					SELECT i.id AS i__id, h.date AS h__date
						FROM informationspe AS i
							INNER JOIN historiqueetatspe AS h ON (h.informationpe_id = i.id)
						WHERE
						(
							(
								(
									(i.nir IS NOT NULL)
									AND  (personnes.nir IS NOT NULL)
									AND  (TRIM( BOTH ' ' FROM i.nir ) <> '')
									AND  (TRIM( BOTH ' ' FROM personnes.nir ) <> '')
									AND  (SUBSTRING( i.nir FROM 1 FOR 13 ) = SUBSTRING( personnes.nir FROM 1 FOR 13 ))
									AND  (i.dtnai = personnes.dtnai)
								)
							)
							OR
							(
								(
									(i.nom IS NOT NULL)
									AND  (personnes.nom IS NOT NULL)
									AND  (i.prenom IS NOT NULL)
									AND  (personnes.prenom IS NOT NULL)
									AND  (TRIM( BOTH ' ' FROM i.nom ) <> '')
									AND  (TRIM( BOTH ' ' FROM i.prenom ) <> '')
									AND  (TRIM( BOTH ' ' FROM personnes.nom ) <> '')
									AND  (TRIM( BOTH ' ' FROM personnes.prenom ) <> '')
									AND  (TRIM( BOTH ' ' FROM i.nom ) = personnes.nom)
									AND  (TRIM( BOTH ' ' FROM i.prenom ) = personnes.prenom)
									AND  (i.dtnai = personnes.dtnai)
								)
							)
						)
						AND h.id IN (
							SELECT dernierhistoriqueetatspe.id AS dernierhistoriqueetatspe__id
							FROM historiqueetatspe AS dernierhistoriqueetatspe
							WHERE
								dernierhistoriqueetatspe.informationpe_id = i.id
								-- Avant la date de création de la fiche de prescription
-- 								AND dernierhistoriqueetatspe.date <= fichesprescriptions93.created
							ORDER BY dernierhistoriqueetatspe.date DESC, dernierhistoriqueetatspe.id DESC
							LIMIT 1
						)
				) AS derniereinformationspe
				ORDER BY derniereinformationspe.h__date DESC
				LIMIT 1
			)
		)
		LEFT OUTER JOIN historiqueetatspe ON (
			historiqueetatspe.informationpe_id = informationspe.id
			AND historiqueetatspe.id IN (
				SELECT h.id
					FROM historiqueetatspe AS h
					WHERE h.informationpe_id = informationspe.id
					ORDER BY h.date DESC
					LIMIT 1
			)
		)
	WHERE
		-- Test
		historiqueetatspe.identifiantpe IS NOT NULL;
*/

/*
SELECT
		adresses.numvoie AS benef_numvoie,
		adresses.typevoie AS benef_typevoie,
		adresses.nomvoie AS benef_nomvoie,
		adresses.complideadr AS benef_complideadr,
		adresses.compladr AS benef_compladr,
		adresses.numcomptt AS benef_numcomptt,
		adresses.numcomrat AS benef_numcomrat,
		adresses.codepos AS benef_codepos,
		adresses.locaadr AS benef_locaadr
	FROM foyers
		LEFT OUTER JOIN adressesfoyers ON (
			adressesfoyers.foyer_id = foyers.id
			AND adressesfoyers.id IN (
				SELECT af.id
					FROM adressesfoyers AS af
					WHERE
						af.foyer_id = foyers.id
						-- Avant la date de création de la fiche de prescription
						AND af.dtemm <= fichesprescriptions93.created
						ORDER BY af.dtemm DESC
					LIMIT 1
			)
		)
		LEFT OUTER JOIN adresses ON ( adressesfoyers.adresse_id = adresses.id )
	-- Test
	LIMIT 10;
*/

/*
	SELECT
			(
				CASE
					WHEN dsps_revs.id IS NOT NULL THEN dsps_revs.nivetu
					WHEN dsps.id IS NOT NULL THEN dsps.nivetu
					else null
				END
			) AS benef_nivetu,
			dsps.*,
			dsps_revs.*
		FROM personnes
			LEFT OUTER JOIN dsps ON (
				dsps.personne_id = personnes.id
				AND dsps.id IN (
					SELECT dernieresdsps.id
						FROM dsps AS dernieresdsps
						WHERE dernieresdsps.personne_id = personnes.id
						ORDER BY dernieresdsps.id DESC
						LIMIT 1
				)
			)
			LEFT OUTER JOIN dsps_revs ON (
				dsps_revs.personne_id = personnes.id
				AND dsps_revs.id IN (
					SELECT dernieresdsps_revs.id
						FROM dsps_revs AS dernieresdsps_revs
						WHERE dernieresdsps_revs.personne_id = personnes.id
						ORDER BY dernieresdsps_revs.modified DESC
						LIMIT 1
				)
			)
		WHERE
			personnes.id IN (
				SELECT fichesprescriptions93.personne_id
					FROM fichesprescriptions93
					WHERE fichesprescriptions93.personne_id = personnes.id
			)
		LIMIT 1
*/

-- benef_etatdosrsa, --historiquesdroits
-- benef_toppersdrodevorsa, --historiquesdroits
/*
	SELECT
			(
				CASE
					WHEN historiquesdroits.id IS NOT NULL THEN historiquesdroits.etatdosrsa
					ELSE situationsdossiersrsa.etatdosrsa
				END
			) AS benef_etatdosrsa,
			(
				CASE
					WHEN historiquesdroits.id IS NOT NULL THEN historiquesdroits.toppersdrodevorsa
					ELSE calculsdroitsrsa.toppersdrodevorsa::TEXT
				END
			) AS benef_toppersdrodevorsa,
			historiquesdroits.*,
			fichesprescriptions93.created
		FROM personnes
			INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
			INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
			INNER JOIN fichesprescriptions93 ON ( fichesprescriptions93.personne_id = personnes.id )
			LEFT OUTER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_id = dossiers.id )
			LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
			LEFT OUTER JOIN historiquesdroits ON (
				historiquesdroits.personne_id = personnes.id
				AND historiquesdroits.id IN (
					SELECT derniershistoriquesdroits.id
						FROM historiquesdroits AS derniershistoriquesdroits
						WHERE
							derniershistoriquesdroits.personne_id = personnes.id
							-- Avant la date de création de la fiche de prescription
							AND derniershistoriquesdroits.created <= fichesprescriptions93.created
						ORDER BY derniershistoriquesdroits.modified DESC
						LIMIT 1
				)
			)
*/

/*
	SELECT
			(
				CASE
					WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.natpf_socle
					ELSE ( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RSD', 'RSI', 'RSU', 'RSB', 'RSJ') ) THEN '1' ELSE '0' END )
				END
			) AS benef_natpf_socle,
			(
				CASE
					WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.natpf_majore
					ELSE ( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RSI', 'RCI') ) THEN '1' ELSE '0' END )
				END
			) AS benef_natpf_majore,
			(
				CASE
					WHEN situationsallocataires.id IS NOT NULL THEN situationsallocataires.natpf_activite
					ELSE ( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RCD', 'RCI', 'RCU', 'RCB', 'RCJ') ) THEN '1' ELSE '0' END )
				END
			) AS benef_natpf_activite,
			situationsallocataires.*,
			( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RSD', 'RSI', 'RSU', 'RSB', 'RSJ') ) THEN '1' ELSE '0' END ) AS benef_natpf_socle,
			( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RSI', 'RCI') ) THEN '1' ELSE '0' END ) AS benef_natpf_majore,
			( CASE WHEN EXISTS( SELECT detailscalculsdroitsrsa.id FROM detailscalculsdroitsrsa WHERE detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id AND detailscalculsdroitsrsa.natpf IN ('RCD', 'RCI', 'RCU', 'RCB', 'RCJ') ) THEN '1' ELSE '0' END ) AS benef_natpf_activite,
			fichesprescriptions93.created
		FROM personnes
			INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
			INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
			INNER JOIN fichesprescriptions93 ON ( fichesprescriptions93.personne_id = personnes.id )
			LEFT OUTER JOIN detailsdroitsrsa ON ( dossiers.id = detailsdroitsrsa.dossier_id )
			LEFT OUTER JOIN situationsallocataires ON (
				situationsallocataires.personne_id = personnes.id
				AND situationsallocataires.id IN (
					SELECT dernierssituationsallocataires.id
						FROM situationsallocataires AS dernierssituationsallocataires
						WHERE
							dernierssituationsallocataires.personne_id = personnes.id
							-- Avant la date de création de la fiche de prescription
							AND dernierssituationsallocataires.modified <= fichesprescriptions93.created
						ORDER BY dernierssituationsallocataires.modified DESC
						LIMIT 1
				)
			)
*/

-- *****************************************************************************
COMMIT;
-- *****************************************************************************