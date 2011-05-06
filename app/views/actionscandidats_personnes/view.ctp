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
//$this->log( $actionscandidatspersonne);
		echo $html->tag('div', $html->tag('strong', 'Action candidat Personne'));
        echo $default->view(
        	$actionscandidatspersonne,
            array(
                'Actioncandidat.name',
				'Actioncandidat.contractualisation',
				'Actioncandidat.lieuaction',
				'Actioncandidat.cantonaction',
				'Actioncandidat.ddaction',
				'Actioncandidat.dfaction',
				'Actioncandidat.nbpostedispo',
				'Actioncandidat.nbposterestant', 
				'Actioncandidat.correspondantaction',
				'Actioncandidat.hasfichecandidature',
				'Actioncandidat.codeaction' => array('type'=>'text')
			),
            array(
                'widget' => 'table',
            )
		);
		echo $html->tag('div', $html->tag('strong', ''));
        echo $default->view(
        	$actionscandidatspersonne,
            array(
            'Referent.nom',
            'Referent.prenom',
            'Referent.numero_poste',
            'Referent.email',
            'Referent.qual',
            'Referent.fonction',
			),
                array(
                'widget' => 'table',
                )
		);
		echo $html->tag('div', $html->tag('strong', ''));
        echo $default->view(
        	$actionscandidatspersonne,
            array(
                'ActioncandidatPersonne.sortiele',
				'Motifsortie.name',
			),
            array(
                'widget' => 'table',
            )
		);	

		echo $html->tag('div', $html->tag('strong', ''));
        echo $default->view(
        	$actionscandidatspersonne,
            array(
            'ActioncandidatPersonne.ddaction',
            'ActioncandidatPersonne.dfaction',
            'ActioncandidatPersonne.motifdemande',
            'ActioncandidatPersonne.enattente',
            'ActioncandidatPersonne.datesignature',
            'ActioncandidatPersonne.bilanvenu',
            'ActioncandidatPersonne.bilanretenu',
            'ActioncandidatPersonne.infocomplementaire',
            'ActioncandidatPersonne.datebilan',
            'ActioncandidatPersonne.rendezvouspartenaire',
            'ActioncandidatPersonne.mobile',
            'ActioncandidatPersonne.naturemobile',
            'ActioncandidatPersonne.typemobile',
            'ActioncandidatPersonne.bilanrecu',
            'ActioncandidatPersonne.daterecu',
            'ActioncandidatPersonne.personnerecu',
            'ActioncandidatPersonne.pieceallocataire',
            'ActioncandidatPersonne.autrepiece',
            'ActioncandidatPersonne.precisionmotif',
            'ActioncandidatPersonne.presencecontrat',
            'ActioncandidatPersonne.integrationaction',
            'ActioncandidatPersonne.horairerdvpartenaire',
            
			),
            array(
                'widget' => 'table',
            )
		);			
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