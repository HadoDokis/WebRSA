<?php
	$this->pageTitle = 'Services instructeurs';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	};
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Serviceinstructeur', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo '<div>';
		echo $form->input( 'Serviceinstructeur.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
	else {
		echo $form->create( 'Serviceinstructeur', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo '<div>';
		echo $form->input( 'Serviceinstructeur.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
?>

	<fieldset>
		<?php
			echo $default->subform(
				array(
					'Serviceinstructeur.lib_service' => array( 'label' => ( __( 'lib_service', true ) ) ),
					'Serviceinstructeur.num_rue' => array( 'label' =>  __( 'num_rue', true ) ),
					'Serviceinstructeur.type_voie' => array( 'label' =>  ( __( 'type_voie', true ) ), 'options' => $typevoie ),
					'Serviceinstructeur.nom_rue' => array( 'label' =>  __( 'nom_rue', true ) ),
					'Serviceinstructeur.complement_adr' => array( 'label' =>  __( 'complement_adr', true ) ),
					'Serviceinstructeur.code_insee' => array( 'label' =>  ( __( 'code_insee', true ) ) ),
					'Serviceinstructeur.code_postal' => array( 'label' =>  __( 'code_postal', true ) ),
					'Serviceinstructeur.ville' => array( 'label' =>  __( 'ville', true ) ),
				)
			);
		?>
	</fieldset>
	<fieldset>
		<?php
			echo $default->subform(
				array(
					'Serviceinstructeur.numdepins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.numdepins', true ) ) ),
					'Serviceinstructeur.typeserins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.typeserins', true ) ), 'empty' => true ),
					'Serviceinstructeur.numcomins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.numcomins', true ) ) ),
					'Serviceinstructeur.numagrins' => array( 'label' => ( __d( 'suiviinstruction', 'Suiviinstruction.numagrins', true ) ), 'maxlength' => 2 ),
				)
			);
		?>
	</fieldset>
	<?php if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ):?>
	<fieldset>
		<?php
			echo $default->subform(
				array(
					'Serviceinstructeur.sqrecherche' => array( 'rows' => 40 ),
				)
			);
		?>
	</fieldset>
	<?php endif;?>

	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $form->end();?>

<?php
	if( isset( $sqlErrors ) && !empty( $sqlErrors ) ) {
		echo '<h2>Erreurs SQL dans les moteurs de recherche</h2>';
		foreach( $sqlErrors as $key => $error ) {
			echo "<div class=\"query\">";
			echo "<h3>".__d( Inflector::underscore( $key ), sprintf( "%s::index", Inflector::pluralize( $key ) ), true )."</h3>";
			echo "<div class=\"errormessage\">".nl2br( $error['error'] )."</div>";
			echo "<div class=\"sql\">".nl2br( str_replace( "\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $error['sql'] ) )."</div>";
			echo "</div>";
		}
	}
?>