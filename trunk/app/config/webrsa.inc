<?php
    define( 'GEDOOO_WSDL', 'http://gedooo.rsa:8080/axis2/services/OfficeService?wsdl' );
//         define( 'GEDOOO_WSDL', 'http://gedooo.services.adullact.org:8080/axis2/services/OfficeService?wsdl' );

    Configure::write( 'Utilisateurs.multilogin', false );
    Configure::write( 'with_parentid', false ); // true = cg66
    Configure::write( 'nom_form_ci_cg', 'cg66' );
    Configure::write( 'CG.cantons', false ); // false = cg93, true = cg66
    Configure::write( 'Zonesegeographiques.CodesInsee', true );
    //Configure::write( 'Etatliquidatif.limit', 1000 ); // INFO: commenter pour ne pas avoir de limite
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
?>