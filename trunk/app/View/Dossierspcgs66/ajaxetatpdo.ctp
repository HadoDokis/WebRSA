<?php
	if ( !empty( $etatdossierpcg ) && $etatdossierpcg != 'transmisop' ) {
		echo 'Etat du dossier : '.$this->Xhtml->tag( 'strong', __d( 'dossierpcg66', 'ENUM::ETATDOSSIERPCG::'.$etatdossierpcg ) );
	}
	else if ( !empty( $etatdossierpcg ) && $etatdossierpcg == 'transmisop' ) {
		echo 'Etat du dossier : '.$this->Xhtml->tag( 'strong', __d( 'dossierpcg66', 'ENUM::ETATDOSSIERPCG::'.$etatdossierpcg ) ).' à '.$this->Xhtml->tag( 'strong', $orgs ).' le '.$this->Xhtml->tag( 'strong', date_short( $datetransmission ) );
	}
?>
