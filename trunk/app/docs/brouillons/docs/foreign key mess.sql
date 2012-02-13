-- Tables ayant un champ _id qui n'est pas une foreign key:
/*
1°) cg93_20111109_v21
	acos	parent_id
	apres_comitesapres	comite_pcd_id
	aros	parent_id
	aros_acos	aro_id
	aros_acos	aco_id
	decisionsparcours	parcoursdetecte_id
	dossiers	detaildroitrsa_id
	dossiers	avispcgdroitrsa_id
	dossiers	organisme_id
	dsps_revs	dsp_id
	groups	parent_id
	histoaprecomplementaires	personne_id
	jetonsfonctions	user_id
	precosreorients	demandereorient_id
	tmporientsstructs	personne_id
	tmporientsstructs	typeorient_id
	tmporientsstructs	structurereferente_id
	traitementspdos	personne_id

	18 ligne(s)

	Temps d'exécution total : 1,180,923.723 ms <=> 20 minutes
*/
SELECT
		table_name AS table,
		column_name AS column
	FROM information_schema.columns
	WHERE
		column_name ~ E'_id$'
		AND table_schema = 'public'
		AND column_name NOT IN (
			SELECT
				kcu.column_name
			FROM information_schema.table_constraints tc
				LEFT JOIN information_schema.key_column_usage kcu ON (
					tc.constraint_catalog = kcu.constraint_catalog
					AND tc.constraint_schema = kcu.constraint_schema
					AND tc.constraint_name = kcu.constraint_name
				)
				LEFT JOIN information_schema.referential_constraints rc ON (
					tc.constraint_catalog = rc.constraint_catalog
					AND tc.constraint_schema = rc.constraint_schema
					AND tc.constraint_name = rc.constraint_name
				)
				LEFT JOIN information_schema.constraint_column_usage ccu ON (
					rc.unique_constraint_catalog = ccu.constraint_catalog
					AND rc.unique_constraint_schema = ccu.constraint_schema
					AND rc.unique_constraint_name = ccu.constraint_name
				)
			WHERE
				tc.constraint_type = 'FOREIGN KEY'
				AND kcu.table_name = information_schema.columns.table_name
				AND kcu.table_schema = 'public'
				AND ccu.table_schema = 'public'
		);

-- Tables ayant un champ personne_id et une clé étrangère vers une de ces mêmes tables:
SELECT
	kcu.table_name AS referencing_table,
	kcu.column_name AS referencing_column,
	ccu.table_name AS referenced_table,
	ccu.column_name AS referenced_column
FROM information_schema.table_constraints tc
	LEFT JOIN information_schema.key_column_usage kcu ON (
		tc.constraint_catalog = kcu.constraint_catalog
		AND tc.constraint_schema = kcu.constraint_schema
		AND tc.constraint_name = kcu.constraint_name
	)
	LEFT JOIN information_schema.referential_constraints rc ON (
		tc.constraint_catalog = rc.constraint_catalog
		AND tc.constraint_schema = rc.constraint_schema
		AND tc.constraint_name = rc.constraint_name
	)
	LEFT JOIN information_schema.constraint_column_usage ccu ON (
		rc.unique_constraint_catalog = ccu.constraint_catalog
		AND rc.unique_constraint_schema = ccu.constraint_schema
		AND rc.unique_constraint_name = ccu.constraint_name
	)
WHERE
	tc.constraint_type = 'FOREIGN KEY'
	AND ccu.table_name <> 'personnes'
	AND ccu.table_name <> kcu.table_name
	AND ccu.table_name IN (
		SELECT
				DISTINCT table_name AS name
			FROM information_schema.columns
			WHERE
				column_name = 'personne_id'
				AND table_schema = 'public'
	)
	AND kcu.table_name IN (
		SELECT
				DISTINCT table_name AS name
			FROM information_schema.columns
			WHERE
				column_name = 'personne_id'
				AND table_schema = 'public'
	)
ORDER BY
	kcu.table_name ASC,
	kcu.column_name ASC,
	ccu.table_name ASC,
	ccu.column_name ASC;

/*
	CG 93 (cg93_20111109_v21), CG 66 (cg66_20111110_v21), CG 58 (cg58_20110426_v21):
	referencing_table	referencing_column	referenced_table	referenced_column
	=============================================================================
	bilanparcours		rendezvous_id		rendezvous			id
	dsps_revs			dsp_id				dsps				id
	entretiens			nv_dsp_id			dsps				id
	entretiens			rendezvous_id		rendezvous			id
	entretiens			vx_dsp_id			dsps				id
	traitementspdos		propopdo_id			propospdos			id

	Ex.: au 66, avec dsps_revs: 01001209066, 01002328066, 01004611066, 01003437066, 01003660066, 01003460066, 00018529066, 01004382066, 01003706066, 01004049066
*/

/*
	FIXME: bilanparcours66 <=> contratinsertion_id, orientstruct_id
	SELECT COUNT(*) FROM bilansparcours66 WHERE contratinsertion_id IS NOT NULL AND orientstruct_id IS NOT NULL; -> 0
*/