-- Trouve toutes les personnes ayant les mêmes nom de naissance, prenom, date de naissance, range de naissance et foyer

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

SELECT
	count(Personne.id)
FROM
	personnes as Personne,
	personnes as Personne2,
	INNER JOIN foyers as Foyer ON
		( Personne.foyer_id = Foyer.id AND Personne2.foyer_id = Foyer.id )
WHERE
		( Personne.nomnai LIKE Personne2.nomnai
		OR Personne.nom LIKE Personne2.nom )
	AND Personne.prenom LIKE Personne2.prenom
	AND Personne.dtnai = Personne2.dtnai
	AND Personne2.id < Personne.id
;