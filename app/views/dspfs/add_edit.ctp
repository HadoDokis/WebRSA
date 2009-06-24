<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout de données socio-professionnelles du foyer';
    }
    else {
        $this->pageTitle = 'Édition des données socio-professionnelles du foyer';
    }
?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Dspf', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Dspf.id', array( 'type' => 'hidden', 'value' => '' ) );
            echo $form->input( 'Dspf.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) );
            echo $form->input( 'Foyer.id', array( 'type' => 'hidden', 'value' => $foyer_id ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Dspf', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Dspf.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Dspf.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) );
            echo $form->input( 'Foyer.id', array( 'type' => 'hidden', 'value' => $foyer_id ) );
            echo '</div>';
        }
    ?>

        <?php include( '_form.ctp' ); ?>
        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>