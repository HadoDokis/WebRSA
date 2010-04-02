<?php echo $this->element( 'dossier_menu', array( 'id' => $dossierId) ); ?>

<div class="with_treemenu">
<h1>Voir les demandes de réorientation</h1>
    <?php
        echo $default->view(
            $demandereorient,
            array(
                'Reforigine.nom_complet',
    // 			'Demandereorient.reforigine_id',
    // 			'Demandereorient.motifdemreorient_id',
                'Motifdemreorient.name',
                'Demandereorient.commentaire',
                'Demandereorient.urgent',
    // 			'Demandereorient.ep_id',
                'Ep.name',
                'Demandereorient.created',
                /*'Reforigine.nom',
                'Motifdemreorient.name',
                'Demandereorient.commentairereforigine',
                'Refaccueil.nom',
    // 			'Demandereorient.dtdemrefaccueil',
                'Demandereorient.accordrefaccueil' => array( 'type' => 'boolean' ),
                'Demandereorient.commentairerefaccueil',
                'Demandereorient.accordbenef' => array( 'type' => 'boolean' ),
                'Demandereorient.urgent' => array( 'type' => 'boolean' ),
                'Demandereorient.created',
                'Ep.name',
                'Demandereorient.decisionep',
                'Demandereorient.motifdecisionep',
                'Demandereorient.refaccueilep_id',
                'Demandereorient.decisioncg',
                'Demandereorient.motifdecisioncg',
                'Demandereorient.refaccueilcg_id',
                'Demandereorient.dateimpression',*/
            ),
            array(
                'widget' => 'table'
            )
        );

        echo $default->button(
            'back',
            array(
                'controller' => 'demandesreorient',
                'action'     => 'index',
                Set::classicExtract( $demandereorient, 'Demandereorient.personne_id' )
            ),
            array(
                'id' => 'Back'
            )
        );
    // 	debug( $demandereorient );
    ?>
</div>
<div class="clearer"><hr /> </div>