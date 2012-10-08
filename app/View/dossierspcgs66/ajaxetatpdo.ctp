<?php
	if ( !empty( $etatdossierpcg ) && $etatdossierpcg != 'transmisop' ) {
		echo 'Etat du dossier : '.$xhtml->tag( 'strong', __d( 'dossierpcg66', 'ENUM::ETATDOSSIERPCG::'.$etatdossierpcg, true ) );
	}
	else if ( !empty( $etatdossierpcg ) && $etatdossierpcg == 'transmisop' ) {
		echo 'Etat du dossier : '.$xhtml->tag( 'strong', __d( 'dossierpcg66', 'ENUM::ETATDOSSIERPCG::'.$etatdossierpcg, true ) ).' le '.$xhtml->tag( 'strong', date_short( $datetransmission ) );
	}
?>
