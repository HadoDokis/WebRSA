<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Comité d\'examen APRE';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    echo $xform->create( 'Comiteexamenapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

    if( $this->action == 'edit' ) {
        echo '<div>';
        echo $xform->input( 'Comiteexamenapre.id', array( 'label' => false, 'type' => 'hidden' ) );
        echo '</div>';
    }
?>

    <fieldset>
        <?php echo $xform->input( 'Comiteexamenapre.datecomite', array( 'label' => required( __( 'Date du comité', true ) ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) );?>
        <?php echo $xform->input( 'Comiteexamenapre.heurecomite', array( 'label' => required( __( 'Heure du comité', true ) ), 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5, 'hourRange' => array( 8, 19 ), 'empty' => true ) );?>
        <?php echo $xform->input( 'Comiteexamenapre.lieucomite', array( 'label' => required( __( 'Lieu du comité', true ) ), 'type' => 'text' ) );?> 
        <?php echo $xform->input( 'Comiteexamenapre.intitulecomite', array( 'label' => required( __( 'Intitulé du comité', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Comiteexamenapre.observationcomite', array( 'label' => required( __( 'Observation du comité', true ) ), 'type' => 'text' ) );?>
    </fieldset>
    <?php echo $xform->submit( 'Enregistrer' );?>
<?php echo $xform->end();?>