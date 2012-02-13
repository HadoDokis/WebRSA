<?php
    $domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
    echo $this->element( 'dossier_menu', array( 'id' => $dossierId ) );
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<div class="with_treemenu">
    <?php
        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
        );
    ?>
<?php 
	echo $xform->create( 'ActioncandidatPersonne', array( 'id' => 'viewForm' ) );


		if( ( $actionscandidatspersonne['ActioncandidatPersonne']['positionfiche'] == 'annule' ) ){
			
			echo $html->tag('div', $html->tag('strong', 'Raison de l\'annulation'));
			echo $default->view(
				$actionscandidatspersonne,
				array(
					'ActioncandidatPersonne.motifannulation' => array( 'type' => 'text' )
				),
				array(
					'widget' => 'table',
					'class' => 'aere'
				)
			);

		}
		echo $html->tag('div', $html->tag('strong', 'Action engagée'));
        echo $default->view(
        	$actionscandidatspersonne,
            array(
                'Actioncandidat.name',
				'Actioncandidat.contractualisation',
				'Actioncandidat.lieuaction',
				'Actioncandidat.cantonaction',
				'Actioncandidat.ddaction',
				'Actioncandidat.dfaction',
				'Actioncandidat.nbpostedispo'=> array( 'type'=>'text' ),
				'Actioncandidat.referent_id' => array('type'=>'text' ),
				'Actioncandidat.nbposterestant',
				'Actioncandidat.codeaction' => array('type'=>'text')
			),
            array(
                'widget' => 'table',
                'class' => 'aere'
            )
		);
		echo $html->tag('div', $html->tag('strong', 'Nom du prescripteur de la fiche' ) );
        echo $default->view(
        	$actionscandidatspersonne,
            array(
            'Referent.qual',
            'Referent.nom',
            'Referent.prenom',
            'Referent.numero_poste',
            'Referent.email',
            'Referent.fonction',
			),
			array(
			'widget' => 'table',
			'class' => 'aere'
			)
		);


		$naturemobile = Set::enum( $actionscandidatspersonne['ActioncandidatPersonne']['naturemobile'], $options['ActioncandidatPersonne']['naturemobile'] );

		echo $html->tag('div', $html->tag('strong', 'Fiche descriptive de la demande'));
        echo $default2->view(
        	$actionscandidatspersonne,
            array(
				'ActioncandidatPersonne.motifdemande',
				'ActioncandidatPersonne.mobile' => array( 'type' => 'boolean' ),
				'ActioncandidatPersonne.naturemobile' => array( 'type'=>'text', 'value' => $naturemobile ),
				'ActioncandidatPersonne.typemobile',
				'ActioncandidatPersonne.rendezvouspartenaire' => array( 'type' => 'boolean' ),
				'ActioncandidatPersonne.horairerdvpartenaire'
			),
            array(
                'widget' => 'table',
                'domain' => $domain,
                'class' => 'aere'
            )
		);	

		$venu = Set::enum( $actionscandidatspersonne['ActioncandidatPersonne']['bilanvenu'], $options['ActioncandidatPersonne']['bilanvenu'] );
		$retenu = Set::enum( $actionscandidatspersonne['ActioncandidatPersonne']['bilanretenu'], $options['ActioncandidatPersonne']['bilanretenu'] );
		
		echo $html->tag('div', $html->tag('strong', 'Bilan du rendez-vous'));
        echo $default2->view(
        	$actionscandidatspersonne,
            array(
                'ActioncandidatPersonne.bilanvenu' => array( 'type'=>'text', 'value' => $venu ),
                'ActioncandidatPersonne.bilanretenu' => array( 'type'=>'text', 'value' => $retenu ),
                'ActioncandidatPersonne.infocomplementaire',
				'ActioncandidatPersonne.datebilan'
			),
            array(
                'widget' => 'table',
                'class' => 'aere'
            )
		);

		echo $html->tag('div', $html->tag('strong', 'Sortie'));
        echo $default->view(
        	$actionscandidatspersonne,
            array(
                'ActioncandidatPersonne.sortiele',
				'Motifsortie.name',
			),
            array(
                'widget' => 'table',
                'class' => 'aere'
            )
		);

?>
<?php
		echo "<h2>Pièces déjà présentes</h2>";
		echo $fileuploader->results( Set::classicExtract( $actionscandidatspersonne, 'Fichiermodule' ) );
	?>
</div>
    <div class="submit">
        <?php

            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>