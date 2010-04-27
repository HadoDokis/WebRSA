<?php
//     define( 'GEDOOO_WSDL', 'http://gedooo.rsa:8080/axis2/services/OfficeService?wsdl' );
//         define( 'GEDOOO_WSDL', 'http://gedooo.services.adullact.org:8080/axis2/services/OfficeService?wsdl' );


    // new 20100420 by Francois
    define( 'GEDOOO_WSDL', 'http://gedooo.services.adullact.org:8080/gedooo/OfficeService?wsdl' ); 

    Configure::write( 'Utilisateurs.multilogin', false );
    Configure::write( 'with_parentid', false ); // true = cg66
    Configure::write( 'nom_form_ci_cg', 'cg93' );
    Configure::write( 'CG.cantons', false ); // false = cg93, true = cg66
    Configure::write( 'Zonesegeographiques.CodesInsee', true );
    //Configure::write( 'Etatliquidatif.limit', 1000 ); // INFO: commenter pour ne pas avoir de limite

    /**
    * Champs spécifique selon le CG pour le formulaire de l'APRE
    * @default: 'cg93' (pour le CG93), 'cg66' (pour le CG66)
    */
    Configure::write( 'nom_form_apre_cg', 'cg93' );

    // Limit pour le nombre de documents à éditer dans la cohorte par orientation
    Configure::write( 'nb_limit_print', 300 );// INFO: Au delà de 50, risque de cassure,


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
    Configure::write( 'Apre.suffixe', '' );

    /**
    * Paramètres à renseigner pour les APRE's forfaitaires
	* FIXME: doc
    */
    Configure::write( 'Apre.forfaitaire.montantbase', 400 );
    Configure::write( 'Apre.forfaitaire.montantenfant12', 100 );
    Configure::write( 'Apre.forfaitaire.nbenfant12max', 4 );

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
?>