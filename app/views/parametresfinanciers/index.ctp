<?php
	$this->pageTitle = 'Paramètres financiers pour la gestion de l\'APRE';
	echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $html->tag( 'h1', $this->pageTitle );

    echo $xform->create( 'ParametreFinancier' );

	if( $permissions->check( 'parametresfinanciers', 'edit' ) ) {
		echo $html->tag(
			'ul',
			$html->tag(
				'li',
				$html->editLink(
					'Modifier les paramètres',
					array( 'controller' => 'parametresfinanciers', 'action' => 'edit' )
				)
			),
			array( 'class' => 'actionMenu' )
		);
	}

	if( !empty( $parametrefinancier ) ) {
		$rows = array();
		foreach( $parametrefinancier['Parametrefinancier'] as $field => $value ) {
			if( $field != 'id' ) {
				$rows[] = array( __d( 'apre', "Parametrefinancier.{$field}", true ), $value );
			}
		}
		echo $xhtml->details( $rows );
	}

    echo '<div class="submit">';
    echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
    echo '</div>';
    echo $xform->end();
?>