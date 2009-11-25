<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout d\'APRES au comité d\'examen';?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout APRE';
    }
    else {
        $this->pageTitle = 'Édition APRE';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>

    <?php echo $xform->create( 'ApreComiteapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>APREs à traiter furant le comité</legend>
                <?php echo $xform->input( 'Comiteapre.id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>
                <?php echo $xform->input( 'Apre.Apre', array( 'label' =>  false, 'type' => 'select', 'options' => $apre, 'multiple' => 'checkbox' ) );?>
            </fieldset>
        </div>

        <?php echo $xform->submit( 'Enregistrer' );?>
    <?php echo $xform->end();?>

<div class="clearer"><hr /></div>