Versions de CakePHP supportées:
    * version minimale requise / version recommandée: 1.2.9

* Principales modifications 2.0rc16 -> 2.0rc17
	1- Ajout d'un filtre, dans toutes les recherches multi-critères, afin de n'afficher que les dernières demandes de RSA pour un même allocataire. (Décocher la case afin de gagner en performance car la requête est coûteuse en temps)
	2- Réparation de l'ajout/édition d'une orientation suite à la mise en place de la GED Alffresco -> le fichier app/config/cmis.php a été corrigé.
	3- Renommage de l'ensemble des tables (et des attributs liés) du module EP, notamment "seanceseps" qui devient "commissionseps"
	4- Réparation de l'ajout et de l'affichage des informations d'une commission d'EP
	5- Corrections diverses sur la COV suite aux divers retours du CG58
	6- Réparation de la gestion des droits sur les différents boutons des tableaux de résultats (modifier, supprimer, ...)
	7- Ajout d'une recherche multi-critères dans la table de paramétrages des utilisateurs
	8- Ajout d'une recherche multi-critères pour les Entretiens
	9- Ajout d'une cohorte de validation des APREs pour le CG66
	10- Mise en place de la pagination progressive pour l'ensemble des formulaires de recherche (à paramétrer dans webrsa.inc, Configure::write( 'Optimisations.progressivePaginate', true );) afin de gagner en performance
	11- Correction: lors de la sélection des dossiers pour une commission d'EP, on ne voit plus les dossiers déjà associés à d'autres commissions d'EP
	12- Amélioration: il n'est plus nécessaire de finaliser une commission d'EP au niveau CG si aucun des thèmes traité par la séance ne prend de décision au niveau CG (CG 58)

* Version 1.3-rc5
    - Correctifs:
        * Nouvelle gestion des éditions de courrier pour les orientations ( édition simple ou en cohorte ). Lors de l'ajout ou de la modification d'une orientation, le PDF est généré directement et stocké en base en base, ce qui permet de ne plus solliciter le serveur gedooo qu'une seule fois.
		Pour cela, nous avons créé une nouvelle table "pdfs" présente dans le patch app/config/sql/patches/1.x/patch-version-1.3rc5.sql.
		ATTENTION:
			1°) dans la partie Cohortes -> Orientation -> Demandes orientées, n'apparaîtront que les demandes qui possèdent leur PDF en base.
			2°) Lors du parcours d'un dossier d'allocataire, dans le module Orientation, dans le tableau des orientations d'une personne, le lien permettant d'imprimer une notification d'orientation sera grisé tant que le PDF pour cette orientation ne sera pas stocké en base.
			3°) Lorsqu'on réalise une nouvelle orientation ou que l'on modifie une orientation existante, PDF sera enregistré pour cette orientation.
			4°) Pour sauvegarder les PDFs des orientations déjà présentes en base, il existe un script cake.
			Ce script accepte 3 arguments: -help, -limit et -order. -help vous permettra d'avoir l'aide la plus à jour concernant ce script, ainsi que la valeur par défaut pour les arguments -limit et -order.
			Par exemple, pour créer les PDFs des 100 dernières orientations enregistrées qui ne possèdent pas encore de PDf en base: cake/console/cake cohjortepdfs -limit 100 -order desc
        * Réparation des données présentes dans l'état liquidatif: les valeurs du fichier Hopeyra et du fichier Pdf renvoient le même résultat (la même somme ).
        * Modification des relations des tables "evenements" et "creances" qui sont passées de m-n à 0-n.

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
	16°) Documentation: ajout d'un fichier tableur contenant les champs disponibles pour les éditions liées à l'APRE app/Vendor/modelesodt/Champs disponibles pour les éditions.ods

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
		* Les modèles de documents situés dans le répertoire app/Vendor/modelesodt ont tous l'extension .odt.default pour éviter d'écraser vos modèles existants. Il suffit de renommer ces fichiers en .odt ou d'utiliser les votres.
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