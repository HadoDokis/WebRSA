<?php
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'traitementpdo', "Traitementspdos::{$this->action}", true )
        );

    ?>
    <?php
        echo $xform->create( 'Traitementpdo', array( 'id' => 'traitementpdoform' ) );
        if( Set::check( $this->data, 'Traitementpdo.id' ) ){
            echo $xform->input( 'Traitementpdo.id', array( 'type' => 'hidden' ) );

        }

//         echo $default->view(
//             $propopdo,
//             array(
//                 'Propopdo.user_id'
//             ),
//             array(
//                 'widget' => 'table',
//                 'id' => 'dossierInfosOrganisme',
//                 'options' => $gestionnaire
//             )
//         );
        echo $default->form(
            array(
                'Traitementpdo.propopdo_id' => array( 'type' => 'hidden', 'value' => $propopdo_id ),
                'Traitementpdo.descriptionpdo_id',
                'Traitementpdo.datereception' => array( 'empty' => false, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 ),
                'Traitementpdo.datedepart' => array( 'empty' => false, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 ),
                'Traitementpdo.traitementtypepdo_id',
                'Traitementpdo.hascourrier' => array( 'type' => 'radio' ),
                'Traitementpdo.hasrevenu' => array( 'type' => 'radio' ),
                'Traitementpdo.hasficheanalyse' => array( 'type' => 'radio' ),
                'Traitementpdo.haspiecejointe' => array( 'type' => 'radio' )
            ),
            array(
                'options' => $options
            )
        );

?>
</div>
<div class="clearer"><hr /></div>