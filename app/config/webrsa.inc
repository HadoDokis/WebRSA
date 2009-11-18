<?php
    define( 'GEDOOO_WSDL', 'http://gedooo.services.adullact.org:8080/axis2/services/OfficeService?wsdl' );
    Configure::write(  'Utilisateurs.multilogin', false );
    Configure::write( 'with_parentid', false );
    Configure::write( 'nom_form_ci_cg', 'cg66' );
    Configure::write( 'CG.cantons', false );
    Configure::write( 'Zonesegeographiques.CodesInsee', true );
?>