<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Services instructeurs';?>

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

    <?php include '_form.ctp'; ?>

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