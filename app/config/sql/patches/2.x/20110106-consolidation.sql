--cf. patch-2.0rc15.sql

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

-- FIXME: problèmes de minuscules et d'accents dans la table personnes --> mettre une contrainte ?
-- FIXME: problèmes de nom / prenom vides (pas NULL mais vides) dans la table personnes -> contrainte ?

/*
	-- Entrées non en majuscules sans accents dans personnes
	SELECT
		nom,
		prenom,
		nomnai,
		prenom2,
		prenom3
	FROM personnes
	WHERE
		nom !~ '^([A-Z]|\-| |'')+$'
		OR prenom !~ '^([A-Z]|\-| |'')+$'
		OR ( nomnai IS NOT NULL AND CHAR_LENGTH( TRIM( BOTH ' ' FROM nomnai ) ) > 0 AND nomnai !~ '^([A-Z]|\-| |'')+$' )
		OR ( prenom2 IS NOT NULL AND CHAR_LENGTH( TRIM( BOTH ' ' FROM prenom2 ) ) > 0 AND prenom2 !~ '^([A-Z]|\-| |'')+$' )
		OR ( prenom3 IS NOT NULL AND CHAR_LENGTH( TRIM( BOTH ' ' FROM prenom3 ) ) > 0 AND prenom3 !~ '^([A-Z]|\-| |'')+$' );
*/


-- "Doublons" --> 672
/*SELECT
		i.*
	FROM (
		SELECT
				COUNT(informationspe.id) AS count,
		-- 		informationspe.personne_id,
				informationspe.nir,
				informationspe.nom,
				informationspe.prenom,
				informationspe.dtnai
			FROM informationspe
			GROUP BY
		-- 		informationspe.personne_id,
				informationspe.nir,
				informationspe.nom,
				informationspe.prenom,
				informationspe.dtnai
	) AS i
	WHERE i.count > 1
	ORDER BY i.count DESC

-- FIXME: 3 x avec le rôle DEM RSA
-- FIXME: 2 dossiers différents: 1 en droit 6 (clos/FIXME), 2 personnes DEM pour l'autre dossier
SELECT
		informationspe.*,
		prestations.*,
		situationsdossiersrsa.*
	FROM informationspe
		INNER JOIN prestations ON (
			prestations.personne_id = informationspe.personne_id
			AND prestations.natprest = 'RSA'
		)
		INNER JOIN personnes ON (
			personnes.id = informationspe.personne_id
		)
		INNER JOIN foyers ON (
			personnes.foyer_id = foyers.id
		)
		INNER JOIN dossiers ON (
			foyers.dossier_id = dossiers.id
		)
		INNER JOIN situationsdossiersrsa ON (
			situationsdossiersrsa.dossier_id = dossiers.id
		)
	WHERE
		informationspe.nom = ( SELECT nom FROM personnes WHERE personnes.id = 49646 )
		AND informationspe.prenom = ( SELECT prenom FROM personnes WHERE personnes.id = 49646 )
		AND informationspe.dtnai = ( SELECT dtnai FROM personnes WHERE personnes.id = 49646 )

-- EXEMPLE: dernière information du parcours PE d'un allocataire
SELECT
		historiqueetatspe.identifiantpe,
		historiqueetatspe.date,
		historiqueetatspe.etat,
		historiqueetatspe.code,
		historiqueetatspe.motif
	FROM historiqueetatspe
	WHERE historiqueetatspe.informationpe_id IN (
		SELECT
				informationspe.id
			FROM informationspe
			WHERE
				informationspe.nom = ( SELECT nom FROM personnes WHERE personnes.id = 49646 )
				AND informationspe.prenom = ( SELECT prenom FROM personnes WHERE personnes.id = 49646 )
				AND informationspe.dtnai = ( SELECT dtnai FROM personnes WHERE personnes.id = 49646 )
	)
	GROUP BY
		historiqueetatspe.identifiantpe,
		historiqueetatspe.date,
		historiqueetatspe.etat,
		historiqueetatspe.code,
		historiqueetatspe.motif
	ORDER BY historiqueetatspe.date DESC
	LIMIT 1

-- EXEMPLE: dernière information venant de Pôle Emploi pour les allocataires
SELECT
-- 		COUNT(*),
		informationspe.nir,
		informationspe.nom,
		informationspe.prenom,
		informationspe.dtnai,
		historiqueetatspe.date,
		historiqueetatspe.etat
	FROM informationspe
		INNER JOIN historiqueetatspe ON (
			historiqueetatspe.informationpe_id = informationspe.id
		)
		INNER JOIN personnes ON (
			(
				informationspe.nir IS NOT NULL
				AND personnes.nir IS NOT NULL
				AND informationspe.nir ~* '^[0-9]{15}$'
				AND personnes.nir ~* '^[0-9]{13}$'
				AND informationspe.nir = personnes.nir || calcul_cle_nir( personnes.nir )
			)
			OR (
				informationspe.nom = personnes.nom
				AND informationspe.prenom = personnes.prenom
				AND informationspe.dtnai = personnes.dtnai
			)
		)
		INNER JOIN prestations ON (
			personnes.id = prestations.personne_id
			AND prestations.natprest = 'RSA'
			AND prestations.rolepers IN ( 'DEM', 'CJT' )
		)
	WHERE
		historiqueetatspe.id IN (
			SELECT h.id
				FROM historiqueetatspe AS h
				WHERE h.informationpe_id = informationspe.id
				ORDER BY h.date DESC
				LIMIT 1
		)
	GROUP BY
		informationspe.nir,
		informationspe.nom,
		informationspe.prenom,
		informationspe.dtnai,
		historiqueetatspe.date,
		historiqueetatspe.etat
	ORDER BY
		historiqueetatspe.date DESC
	LIMIT 10
*/