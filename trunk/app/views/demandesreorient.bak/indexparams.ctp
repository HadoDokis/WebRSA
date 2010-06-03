<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'demandereorient', "Demandesreorient::{$this->action}", true )
    )
?>
<?php
    echo $default->index(
        $demandesreorients,
        array(
            'Reforigine.nom_complet' => array( 'domain' => 'referent' ), // FIXME
            'Motifdemreorient.name',
            'Demandereorient.urgent' => array( 'type' => 'boolean' ),
            'Demandereorient.created',
            'Ep.name',
        )
//         array(
//             'actions' => array(
//                 'Demandereorient.view',
//                 'Demandereorient.edit',
//                 'Demandereorient.delete',
//             ),
//             'add' => 'Demandereorient.add',
//             'tooltip' => array(
//                 'Demandereorient.commentaire',
//                 'Precoreorientreferent.accord' => array( 'type' => 'boolean' ),
//                 'Precoreorientequipe.accord' => array( 'type' => 'boolean' ),
//                 'Precoreorientconseil.accord' => array( 'type' => 'boolean' ),
//             )
//         )
    );

//  debug( $demandesreorients );
?>