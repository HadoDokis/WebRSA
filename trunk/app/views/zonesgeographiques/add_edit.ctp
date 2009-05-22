<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Zones géographiques';?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Zonegeographique', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
        }
        else {
            echo $form->create( 'Zonegeographique', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
        }
    ?>

<?php include '_form.ctp'; ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>