<?php
	define( 'GEDOOO_WSDL', 'http://gedooo.rsa:8080/axis2/services/OfficeService?wsdl' );
//         define( 'GEDOOO_WSDL', 'http://gedooo.services.adullact.org:8080/axis2/services/OfficeService?wsdl' );


    Configure::write( 'Utilisateurs.multilogin', false );
    Configure::write( 'with_parentid', false ); // true = cg66
    Configure::write( 'nom_form_ci_cg', 'cg66' );
    Configure::write( 'CG.cantons', false ); // false = cg93, true = cg66
    Configure::write( 'Zonesegeographiques.CodesInsee', true );
    //Configure::write( 'Etatliquidatif.limit', 1000 ); // INFO: commenter pour ne pas avoir de limite

    /**
    * Champs spécifique selon le CG pour le formulaire de l'APRE
    * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
    */
    Configure::write( 'nom_form_apre_cg', 'cg66' );

    // Limit pour le nombre de documents à éditer dans la cohorte par orientation
    Configure::write( 'nb_limit_print', 2000 );


	/**
	* Permet à l'administrateur d'accéder à toutes les parties de l'application
	* normalement bloquées aux seules parties de paramétrage renseignées.
	* Voir AppController::_isAdminAction().
	* @default false
	*/
    Configure::write( 'Admin.unlockall', false );

	/**
	* Montant maximal des apres complémentaires pour une personne au cours
	* de la période de temps définie par Apre.periodeMontantMaxComplementaires.
	* @default 2600
    * cg66 -> 3000
	*/
	Configure::write( 'Apre.montantMaxComplementaires', 2600 );

	/**
	* Période (en nombre de mois) utilisée pour la calcul du montant maximal
	* des apres complémentaires pour une personne.
	* @default 24
	*/
	Configure::write( 'Apre.periodeMontantMaxComplementaires', 24 );

    /**
    * Paramètre à renseigner pour l'utilisation du bon MVC
    * @default: vide (pour le CG93), sinon '66' pour le CG66
    */
    Configure::write( 'Apre.suffixe', '66' );

    /**
    * Paramètres à renseigner pour les APRE's forfaitaires
	* FIXME: doc
    */
    Configure::write( 'Apre.forfaitaire.montantbase', 400 );
    Configure::write( 'Apre.forfaitaire.montantenfant12', 100 );
    Configure::write( 'Apre.forfaitaire.nbenfant12max', 4 );

	/**
	* FIXME: vérifier l'existance et les droits
	* FIXME: accès concurrents ?
	*/
    Configure::write( 'Cohorte.dossierTmpPdfs', APP.'tmp/files/pdf' );

    /**
    *   Paramètres à renseigner pour les montants des
    *   forfaits de déplacements des APREs pour le CG66
    *   @default:   0.20€ pour les forfaits au Km
    *               23€ pour les frais d'hebergement
    *               3.81€ pour les frais de repas
    */
    Configure::write( 'Fraisdeplacement66.forfaitvehicule', 0.20 );
    Configure::write( 'Fraisdeplacement66.forfaithebergt', 23 );
    Configure::write( 'Fraisdeplacement66.forfaitrepas', 3.81 );

    /**
    * Permet à l'administrateur d'ajouter une adresse pour l'utilisateur
    * connecté à l'application.
    * Besoin pour le CG66 lors de l'impression des courriers de rendez-vous
    * @default false
    */
    Configure::write( 'User.adresse', false ); // true = cg66, false = cg93


    /**
    * Paramètre à renseigner pour l'affichage de la bonne fiche de candidature
    * @default: 'cg93' (pour le CG93), sinon 'cg66' pour le CG66
    */
    Configure::write( 'ActioncandidatPersonne.suffixe', 'cg66' );

    /**
    *   Paramètre à renseigner dans le cas d'un affichage plus large du menu du dossier
    *   @default: false
    */
    Configure::write( 'UI.menu.large', true );


    /**
    *   Paramètre à renseigner pour le CG58 pour le lien pointant sur leur application
    */
    Configure::write( 'UI.menu.lienDemandeur',
    'http://intranet.cg58.fr/applications/xbase/xbase_list.php?DBNAME=xbase_rsa&APPCODE=ALLOCATAIRE&LIST=Fichier RSA&NAV=NO-L&DISPLAYSTYLE=DISPLAYSTD&nCurPage=0&FILTER=;;;;;%s;;;;' );


    /**
    *   Paramètre à renseigner pour déterminer la plage horaire que l'on dispose pour pouvoir accéder
    *   aux différents boutons possédant ce paramètre.
    *   On met 48, pour 48H car la plage de date va de minuit à minuit et donc un formulaire saisi
    *   un jour à 18h ne serait plus modifiable/validable/supprimable le lendemain.
    *   @default: 48 --> nombre d'heures pendant lesquelles on pourra accéder au bouton
    */
    Configure::write( 'Periode.modifiable.nbheure', 48 );



    /**
    * Champs spécifique selon le CG pour le formulaire des PDOs
    * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
    */
    Configure::write( 'nom_form_pdo_cg', 'cg66' );

    /**
    * Champs spécifique selon le CG pour le formulaire du bilan de parcours / Fiche de saisine
    * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
    */
    Configure::write( 'nom_form_bilan_cg', 'cg66' );

    /**
    *   Paramètre pour connaître le pourcentage lors d'un versement en 2 fois
    *   pour les apres présentent dans un état liquidatif
    *   @default: 60 ---> avant 40 %
    */
    Configure::write( 'Apre.pourcentage.montantversement', 60 );
?>