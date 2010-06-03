<?php echo $this->element( 'dossier_menu', array( 'id' => $dossierId) ); ?>

<div class="with_treemenu">

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
		),
		array(
			'actions' => array(
				'Demandereorient.view',
				'Demandereorient.edit',
				'Demandereorient.delete',
			),
			'add' => array( 'Demandereorient.add' => $this->params['pass'][0] ),
// 			'tooltip' => array(
// 				'Demandereorient.commentaire',
// 				'Precoreorientreferent.accord' => array( 'type' => 'boolean' ),
// 				'Precoreorientequipe.accord' => array( 'type' => 'boolean' ),
// 				'Precoreorientconseil.accord' => array( 'type' => 'boolean' ),
// 			)
		)
	);

// 	debug( $demandesreorients );
?>
</div>
<div class="clearer"><hr /></div>