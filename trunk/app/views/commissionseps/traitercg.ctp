<h1><?php echo $this->pageTitle = '3. Traitement de la commission du '.date('d/m/Y à H:i', strtotime($commissionep['Commissionep']['dateseance'])).' par le CG '; ?></h1>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<br/>
<div id="tabbedWrapper" class="tabs">
	<?php
		echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
		echo '<div>'.$form->input( 'Commissionep.save', array( 'type' => 'hidden', 'value' => true ) ).'</div>';

		foreach( array_keys( $dossiers ) as $theme ) {
			$modeleDecision = Inflector::classify( 'Decision'.Inflector::underscore( $theme ) );
			$errorClass = ( !empty( $this->validationErrors[$modeleDecision] ) ? 'error' : '' );

			$file = sprintf( 'traitercg.%s.liste.ctp', Inflector::underscore( $theme ) );
			echo '<div id="'.$theme.'" class="'.$errorClass.'"><h2 class="title '.$errorClass.'">'.__d( 'dossierep', 'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( $file );
			}
			else {
				echo '<p class="notice">Aucun dossier à traiter pour cette thématique.</p>';
			}
			echo '</div>';
		}

		echo '<div class="submit">';
			echo $form->submit( 'Enregistrer', array( 'div' => false ) );
			if ( $commissionep['Commissionep']['etatcommissionep'] == 'decisioncg' ) {
				echo $form->submit( 'Valider', array( 'name' => 'Valider', 'div' => false ) );
			}
		echo '</div>';
		echo $form->end();

		echo $default->button(
		    'back',
		    array(
		        'controller' => 'commissionseps',
		        'action'     => 'arbitragecg'
		    ),
		    array(
		        'id' => 'Back'
		    )
		);
		
	?>
</div>

<?php
	//if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	//}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );

	function changeColspanAnnuleReporte( idColumnToChangeColspan, colspanMax, decision, idsNonRaisonpassage ) {
		if ( $F( decision ) == 'reporte' || $F( decision ) == 'annule' ) {
			$( idColumnToChangeColspan ).writeAttribute( "colspan", colspanMax );
			idsNonRaisonpassage.each( function ( id ) {
				$( id ).up(1).hide();
			});
		}
		else {
			$( idColumnToChangeColspan ).writeAttribute( "colspan", 1 );
			idsNonRaisonpassage.each( function ( id ) {
				$( id ).up(1).show();
			});
		}
	}

// 	function changeColspanRaisonNonPassage( idColumnToChangeColspan, decision, idsNonRaisonpassage, idRaisonpassage ) {
// 		if ( $F( decision ) == 'reporte' || $F( decision ) == 'annule' ) {
// 			$( idColumnToChangeColspan ).writeAttribute( "colspan", "1" );
// 		}
// 		else {
// 			$( idColumnToChangeColspan ).writeAttribute( "colspan", "2" );
// 		}
// 		afficheRaisonpassage( decision, idsNonRaisonpassage, idRaisonpassage );
// 	}
// 
// 	function afficheRaisonpassage( decision, idsNonRaisonpassage, idRaisonpassage ) {
// 		if ( $F( decision ) == 'reporte' || $F( decision ) == 'annule' ) {
// 			idsNonRaisonpassage.each( function ( id ) {
// 				$( id ).up(1).hide();
// 			});
// 			$( idRaisonpassage ).up(1).show();
// 		}
// 		else {
// 			idsNonRaisonpassage.each( function ( id ) {
// 				$( id ).up(1).show();
// 			});
// 			$( idRaisonpassage ).up(1).hide();
// 		}
// 	}
</script>
