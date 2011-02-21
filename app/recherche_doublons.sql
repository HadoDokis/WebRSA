SELECT
	Personne.id,
	Personne.nomnai,
	Personne.prenom,
	Personne.dtnai,
	Personne.rgnai,
	Personne.foyer_id
FROM
	personnes as Personne,
	personnes as Personne2
WHERE
	Personne.nomnai LIKE Personne2.nomnai
	AND Personne.prenom LIKE Personne2.prenom
	AND Personne.dtnai = Personne2.dtnai
	AND Personne.rgnai = Personne2.rgnai
	AND Personne.foyer_id = Personne2.foyer_id
	AND Personne2.id < Personne.id
;

--------------------------------------------------------------------------------

SELECT
	Personne.id,
	Personne.nomnai,
	Personne.prenom,
	Personne.dtnai,
	Personne.foyer_id
FROM
	personnes as Personne,
	personnes as Personne2
WHERE
	Personne.nomnai LIKE Personne2.nomnai
	AND Personne.prenom LIKE Personne2.prenom
	AND Personne.dtnai = Personne2.dtnai
	AND Personne.foyer_id = Personne2.foyer_id
	AND Personne2.id < Personne.id
;

--------------------------------------------------------------------------------

SELECT
	count(Personne.id)
FROM
	personnes as Personne,
	personnes as Personne2,
	foyers as Foyer
WHERE
	(
		Personne.nomnai LIKE Personne2.nomnai
	OR
		Personne.nom LIKE Personne2.nom
	)
	AND Personne.prenom LIKE Personne2.prenom
	AND Personne.dtnai = Personne2.dtnai
	AND Personne2.id < Personne.id
	AND Personne.foyer_id = Foyer.id
	AND Personne2.foyer_id = Foyer.id
;

--------------------------------------------------------------------------------

SELECT
	count(Personne.id)
FROM
	personnes as Personne,
	personnes as Personne2,
	foyers as Foyer,
	prestations as Prestation
WHERE
	(
		Personne.nomnai LIKE Personne2.nomnai
	OR
		Personne.nom LIKE Personne2.nom
	)
	AND Personne.prenom LIKE Personne2.prenom
	AND Personne.dtnai = Personne2.dtnai
	AND Personne.id < Personne2.id
	AND Personne.foyer_id = Foyer.id
	AND Personne2.foyer_id = Foyer.id
	(
		Prestation.personne_id = Personne.id
	OR
		Prestation.personne_id = Personne2.id
	)
	AND Prestation.natprest LIKE 'RSA'
	AND Prestation.rolepers IN ( 'DEM', 'CJT' )
;

--------------------------------------------------------------------------------

SELECT
	Personne.id,
	Personne2.id,
	Personne.nomnai,
	Personne2.nomnai,
	Personne.nom,
	Personne2.nom,
	Personne.prenom,
	Personne2.prenom,
	Personne.dtnai,
	Personne2.dtnai,
	Personne.foyer_id,
	Prestation.natprest,
	Prestation2.natprest,
	Prestation.rolepers,
	Prestation2.rolepers
FROM
	personnes as Personne INNER JOIN prestations as Prestation ON ( Personne.id = Prestation.personne_id ),
	personnes as Personne2 INNER JOIN prestations as Prestation2 ON ( Personne2.id = Prestation2.personne_id )
WHERE
	(
		Personne.nom LIKE Personne2.nom
	OR
		Personne.nomnai LIKE Personne2.nomnai
	OR
		Personne.prenom LIKE Personne2.prenom
	)
	AND Personne.dtnai = Personne2.dtnai
	AND Personne.id < Personne2.id
	AND Prestation.personne_id < Prestation2.personne_id
	AND Personne.foyer_id = Personne2.foyer_id
	AND Prestation.natprest = 'RSA'
	AND Prestation.rolepers NOT IN ( 'DEM', 'CJT' )
	AND Prestation2.natprest = 'RSA'
;

-- Recherche des doublons dans les dossiers non clos, pour les personnes ayant un droit ouvert et versable
-- pour les prestations RSA dans un même foyer.
SELECT
	COUNT(*)
FROM
	personnes as Personne
		INNER JOIN prestations as Prestation ON ( Personne.id = Prestation.personne_id )
		INNER JOIN calculsdroitsrsa as Calculdroitrsa ON ( Personne.id = Calculdroitrsa.personne_id )
		INNER JOIN foyers as Foyer ON ( Personne.foyer_id = Foyer.id )
		INNER JOIN dossiers as Dossier ON ( Dossier.id = Foyer.dossier_id )
		INNER JOIN situationsdossiersrsa as Situationdossierrsa ON ( Dossier.id = Situationdossierrsa.dossier_id ),
	personnes as Personne2 INNER JOIN prestations as Prestation2 ON ( Personne2.id = Prestation2.personne_id )
WHERE
	(
		Personne.nom LIKE Personne2.nom
	OR
		Personne.nomnai LIKE Personne2.nomnai
	OR
		Personne.prenom LIKE Personne2.prenom
	)
	AND Personne.dtnai = Personne2.dtnai
	AND
	(
		(
			Personne.rgnai IS NULL
		AND
			Personne2.rgnai IS NULL
		)
	OR
		Personne.rgnai = Personne2.rgnai
	)
	AND Personne.id < Personne2.id
	AND Personne.foyer_id = Personne2.foyer_id
	AND Prestation.natprest = 'RSA'
	AND Prestation2.natprest = 'RSA'
	AND Calculdroitrsa.toppersdrodevorsa = '1'
	AND Situationdossierrsa.etatdosrsa IN ( 'Z', '2', '3', '4' )
;

--------------------------------------------------------------------------------

SELECT
	COUNT(*)
FROM
	personnes as Personne
		INNER JOIN prestations as Prestation ON ( Personne.id = Prestation.personne_id )
		INNER JOIN calculsdroitsrsa as Calculdroitrsa ON ( Personne.id = Calculdroitrsa.personne_id )
		INNER JOIN foyers as Foyer ON ( Personne.foyer_id = Foyer.id )
		INNER JOIN dossiers as Dossier ON ( Dossier.id = Foyer.dossier_id )
		INNER JOIN situationsdossiersrsa as Situationdossierrsa ON ( Dossier.id = Situationdossierrsa.dossier_id ),
	personnes as Personne2
		INNER JOIN prestations as Prestation2 ON ( Personne2.id = Prestation2.personne_id )
		INNER JOIN calculsdroitsrsa as Calculdroitrsa2 ON ( Personne2.id = Calculdroitrsa2.personne_id )
		INNER JOIN foyers as Foyer2 ON ( Personne2.foyer_id = Foyer2.id )
		INNER JOIN dossiers as Dossier2 ON ( Dossier2.id = Foyer2.dossier_id )
		INNER JOIN situationsdossiersrsa as Situationdossierrsa2 ON ( Dossier2.id = Situationdossierrsa2.dossier_id )
WHERE
	(
		Personne.nom LIKE Personne2.nom
	OR
		Personne.nomnai LIKE Personne2.nomnai
	OR
		Personne.prenom LIKE Personne2.prenom
	)
	AND Personne.dtnai = Personne2.dtnai
	AND
	(
		(
			Personne.rgnai IS NULL
		AND
			Personne2.rgnai IS NULL
		)
	OR
		Personne.rgnai = Personne2.rgnai
	)
	AND Personne.id < Personne2.id
	AND Personne.foyer_id = Personne2.foyer_id
	AND Prestation.natprest = 'RSA'
	AND Prestation2.natprest = 'RSA'
	AND Calculdroitrsa.toppersdrodevorsa = '1'
	AND Situationdossierrsa.etatdosrsa IN ( 'Z', '2', '3', '4' )
	AND Calculdroitrsa2.toppersdrodevorsa = '1'
	AND Situationdossierrsa2.etatdosrsa IN ( 'Z', '2', '3', '4' )
;

-- Obtenir le nom de toutes les tables de la base de données

SELECT tablename FROM pg_tables where tablename not like 'pg_%' and tablename not like 'sql_%'

-- Obtenir le nom des colonnes pour une table donnée

SELECT column_name FROM information_schema.columns WHERE table_name = 'users';

-- Nom de colonnes finissant par _id pour toutes les tables de la base

SELECT column_name
FROM information_schema.columns
WHERE table_name IN (
	SELECT tablename FROM pg_tables where tablename not like 'pg_%' and tablename not like 'sql_%'
)
AND
	column_name LIKE '%_id';