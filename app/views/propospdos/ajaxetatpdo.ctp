<?php

	if ( !empty( $etatdossierpdo ) ) {
		echo 'Etat du dossier : '.$xhtml->tag( 'strong', __d( 'propopdo', 'ENUM::ETATDOSSIERPDO::'.$etatdossierpdo, true ) );
	}

?>
