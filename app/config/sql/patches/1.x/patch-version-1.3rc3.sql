-- Suppression des doublons APREs forfaitaires dans un même apres_etatsliquidatifs

DELETE FROM apres_etatsliquidatifs
	WHERE apres_etatsliquidatifs.id IN(
		SELECT a1.id
			FROM
				apres_etatsliquidatifs AS a1,
				apres_etatsliquidatifs AS a2,
				etatsliquidatifs as e1,
				etatsliquidatifs as e2
			WHERE a1.apre_id = a2.apre_id
				AND a1.etatliquidatif_id = a2.etatliquidatif_id
				AND a1.id < a2.id
				AND a1.etatliquidatif_id = e1.id
				AND a2.etatliquidatif_id = e2.id
				AND e1.typeapre = 'forfaitaire'
				AND e2.typeapre = 'forfaitaire'
	);

/**
* Suppression de la plus ancienne des adresses et adresses_foyers de rang
* '01' en doublon
*/

DELETE FROM adresses_foyers
	WHERE adresses_foyers.id IN (
		SELECT a1.id
			FROM
				adresses_foyers AS a1,
				adresses_foyers AS a2,
				adresses
			WHERE
				a1.foyer_id = a2.foyer_id
				AND a1.rgadr = '01'
				AND a2.rgadr = '01'
				AND a1.id < a2.id
				AND a1.adresse_id = adresses.id
	);