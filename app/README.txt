Versions de CakePHP supportées:
    * version minimale requise: 1.2.4.8284
    * version recommandée: 1.2.5

* version 1.3-rc1
	1°) Ajout d'un script bash app/majmodeles.sh qui permet de passer tous les modèles pour les éditions de .odt.default en .odt si le modèle .odt n'existe pas ou s'il est plus ancien que le fichier .odt.default
	2°) Corrections du script d'importation des données Pôle Emploi
	3°) Corrections du script d'importation des APREs forfaitaires
		- ajout du motif de rejet dans le CSV téléchargeable
		- vérification que l'allocataire n'ait pas déjà une APRE forfaitaire dans les 12 derniers mois
	4°) Améliorations pour le module PDO
	5°) Correction sur le formulaire de saisie des RDV
	6°) Corrections sur les modèles de documents (notifications APRE, Contrat d'engagement réciproque, ...)
	7°) Améliorations diverses sur l'interface de l'application
	8°) Ajout NIR et date de naissance dans le CSV d'export des orientations
	9°) Réparation: suppression des doublons à l'affichage de la liste des dossiers/allocataires dans la recherche multi-critères
	10°) Correction du problème d'enregistrement de la décision du comité APRE en cas d'accord
	11°) Correction: ajout d'une alerte lorsque les montants de l'APRE complémentaire dépassent un certain seuil au cours d'une période de temps donnée (à configurer avec Apre.montantMaxComplementaires et Apre.periodeMontantMaxComplementaires dans app/config/webrsa.inc)
	12°) Corrections: problèmes d'enregistrement et de listes de paramétrages pour les référents et les personnes chargées du suivi de l'APRE
	13°) Correction: ajout de la possibilité de supprimer des contrats d'engagement
	14°) Corrections: champs obligatoires sur le contrat d'engagement et ajout de restrictions sur le type de contrat (une seule fois 'Premier contrat', puis 'Renouvellement' ou 'Redéfinition')
	15°) Correction: ajout du caractère obligatoire de la structure et du référent pour les APRES
	16°) Documentation: ajout d'un fichier tableur contenant les champs disponibles pour les éditions liées à l'APRE app/vendors/modelesodt/Champs disponibles pour les éditions.ods

* version 1.0.10
	Évolutions importantes:
        1°) Ajout de paramètres pour le script refresh:
            -ressources
                    Doit-on recalculer la moyenne des ressources mensuelles des demandeurs et des conjoints ?
            -soumis
                    Doit-on recalculer si les demandeurs et les conjoints sont soumis à droits et devoirs ?
            -preorientation
                    Doit-on calculer et sauvegarder une préorientation pour les demandeurs et les conjoints qui ne sont pas encore orientés ni préorientés ?
            -force
                    Doit-on forcer le calcul de la préorientation même si une préorientation a déjà été calculée ?
            Exemple d'utilisation (avec les valeurs par défaut): cake/console/cake refresh -ressources true -soumis true -preorientation true -force true
        2°) Le script refresh indique maintenant le pourcentage de complétion du script au cours de l'exécution de celui-ci. À la fin du script, celui-ci donne un résumé du temps passé dans les différentes parties (calcul de la moyenne des ressources, calcul des personnes soumises à droits et devoirs, calcul de la préorientation)

	Remarques:
		* Les modèles de documents situés dans le répertoire app/vendors/modelesodt ont tous l'extension .odt.default pour éviter d'écraser vos modèles existants. Il suffit de renommer ces fichiers en .odt ou d'utiliser les votres.
		* En cas de nouvelle installation, renommer les fichiers app/config/*.default (enlever la partie ".default") et y inscrire vos valeurs. Sinon, vérifiez que vos fichiers de configuration comportent bien toutes les variables se trouvant dans les fichiers .default En cas de mise à jour, n'oubliez pas d'exécuter les patchs sql nécessaires dans app/config/sql/patches

* Ajout au 10 Février 2010 :
	Procédure de mise en place de l'application:
		- Étape 1 : Télécharger la nouvelle version sur la forge
		- Étape 2 : Sauvegarder la version actuelle de webrsa (afin de ne pas faire de fausse manipulation)
		- Étape 3 : passer les patches sql présents dans app/config/sql/patches/1.x/
		- Étape 4 : Dans cette version (1.2), nous avons mis en place un nouveau système d'édition des documents ODT pour les orientations.
				Dans la table de paramétrage (menu Administration->Paramétrages->Types d'orientation), un nouveau champ est à renseigner
				---> Modèle de notification pour cohorte
					Vous trouverez ce nouveau champ dans le patch: app/config/sql/patches/1.x/patch-version1.2.sql.
				Pour ne pas avoir de problèmes, nous avons ajouté l'extension "_cohorte" sur les documents qui seront imprimés en cohorte.

				NB: Ces documents en cohorte sont des fichiers .odt (comme les autres), mais en plus, nous avons inséré une section au document
			1. "emploi" pour les orientations vers le Pole Emploi,
			2. "pdv" pour celles vers Social ou socioprofessionnel
			3. Ainsi qu'un saut de page à la fin du document odt afin de pouvoir passer de l'un à l'autre.
			4. les modèles d'impression individuelle ne doivent pas avoir de sauts de page et de section ( ceci évite d'avoir une deuxième page blanche systématiquement)

* Ajout du 23 Février 2010 :
    - Correctifs:
        * Problème de décisions lors du comité d'examen, les décisions de Refus fonctionnent comme il faut.
        * Meilleurs paramétrages pour les éditions par Gedooo
        * Ajout de règles de validations sur certains modèles ( montant attribué lors d'un comité d'examen, , ... )
        * Mise à jour des documents ODT
        * Ajout de pagination sur les comités d'examen
        * Ajout de traduction diverses
    - Ajout:
        * Ajout de la fiche de candidature pour tests ( avec le modèle odt correspondant )

    - Pour l'intégration des informations Pôle emploi, l'ancien script integrationcsvpe.sh n'existe plus. Il faut à présent utiliser le script cake "importcsvinfope.php":
        cake/console/cake importcsvpe -type inscription ( ou radiation ou cessation ) mon_fichier.csv

