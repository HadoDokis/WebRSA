SELECT
		personnes.id,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai,
		dsps.id,
		dsps.soutdemarsoc,
		dsps_revs.id,
		dsps_revs.soutdemarsoc,
		dsps_revs.modified
	FROM personnes
		LEFT OUTER JOIN dsps ON (
			dsps.personne_id = personnes.id
			AND dsps.personne_id NOT IN (
				SELECT tmp.personne_id
					FROM dsps_revs AS tmp
					WHERE tmp.personne_id = dsps.personne_id
			)
		)
		LEFT OUTER JOIN dsps_revs ON (
			dsps_revs.personne_id = personnes.id
			AND dsps_revs.id IN (
				SELECT tmp.id
					FROM dsps_revs AS tmp
					WHERE tmp.personne_id = dsps_revs.personne_id
					ORDER BY tmp.modified DESC
					LIMIT 1
			)
		)
	WHERE
		-- On s'assure d'avoir au moins une entrée
		(
		dsps.id IS NOT NULL
		OR dsps_revs.id IS NOT NULL
	)
	-- Filtre
	AND (
		dsps.soutdemarsoc = 'O'
		OR dsps_revs.soutdemarsoc = 'O'
	)
	LIMIT 10