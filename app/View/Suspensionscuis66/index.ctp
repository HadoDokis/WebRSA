<?php
	$this->pageTitle = 'Suspension';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'suspensioncui66', "Suspensionscuis66::{$this->action}" )
	);
?>
<ul class="actionMenu">
	<li><?php
			echo $this->Xhtml->addLink(
				'Ajouter une suspension',
				array( 'controller'=> $this->request->params['controller'], 'action'=>'add', $cui_id ),
				$this->Permissions->checkDossier( $this->request->params['controller'], 'add', $dossierMenu )
			);
		?>
	</li>
</ul>
<?php

    if( !empty( $suspensionscuis66 ) ) {
        echo '<table class="default2">';
        echo '<tr>';
        echo '<th>Date de début</th>';
        echo '<th>Date de fin</th>';
        echo '<th>Format journée</th>';
        echo '<th>Observations</th>';
        echo '<th>Motifs de suspension</th>';
        echo '<th colspan="4">Actions</th>';
        echo '</tr>';
        echo '<tbody>';

        foreach( $suspensionscuis66 as $i => $suspensioncui66 ) {
            $listMotifssuspension = Hash::get( $suspensioncui66, 'Suspensioncui66.listmotifs' );

            $differentsMotifs = '';
            if( !empty( $listMotifssuspension ) ) {
               foreach( $listMotifssuspension as $key => $motif ) {
                   if( !empty( $motif ) ) {
                       $differentsMotifs .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$motif.'</li></ul>';
                   }
               }
            }

            echo $this->Xhtml->tableCells(
                array(
                    h( date_short( Hash::get( $suspensioncui66, 'Suspensioncui66.datedebut' ) ) ),
                    h( date_short( Hash::get( $suspensioncui66, 'Suspensioncui66.datefin' ) ) ),
                    h( value( $options['Suspensioncui66']['formatjournee'], Hash::get( $suspensioncui66, 'Suspensioncui66.formatjournee' ) ) ),
                    h( Hash::get( $suspensioncui66, 'Suspensioncui66.observation' ) ),
                    $differentsMotifs,
                    $this->Default2->button(
                        'edit',
                        array( 'controller' => 'suspensionscuis66', 'action' => 'edit', $suspensioncui66['Suspensioncui66']['id'] ),
                        array(
                            'enabled' => (
                                ( $this->Permissions->checkDossier( 'suspensionscuis66', 'edit', $dossierMenu ) == 1 )
                            )
                        )
                    ),
                    $this->Default2->button(
                        'delete',
                        array( 'controller' => 'suspensionscuis66', 'action' => 'delete', $suspensioncui66['Suspensioncui66']['id'] ),
                        array(
                            'enabled' => (
                                ( $this->Permissions->checkDossier( 'suspensionscuis66', 'delete', $dossierMenu ) == 1 )
                            )
                        ),
                        'Etes-vous sûr de vouloir supprimer cette suspension ?'
                    ),
                    $this->Default2->button(
                        'filelink',
                        array( 'controller' => 'suspensionscuis66', 'action' => 'filelink',
                        $suspensioncui66['Suspensioncui66']['id'] ),
                        array(
                            'enabled' => (
                                ( $this->Permissions->checkDossier( 'suspensionscuis66', 'filelink', $dossierMenu ) == 1 )
                            )
                        )
                    ),
                    h( '('.Set::classicExtract( $suspensioncui66, 'Fichiermodule.nb_fichiers_lies' ).')' ),
                ),
                array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$i ),
                array( 'class' => 'even', 'id' => 'innerTableTrigger'.$i )
            );
        }
        echo '</tbody>';
        echo '</table>';
    }
    else{
        echo '<p class="notice">Aucune suspension présente pour ce CUI</p>';
    }
//	echo $this->Default2->index(
//		$suspensionscuis66,
//		array(
//			'Suspensioncui66.datedebut',
//			'Suspensioncui66.datefin',
//			'Suspensioncui66.observation',
//            'Suspensioncui66.formatjournee',
//            'Suspensioncui66.listmotifs'
//		),
//		array(
//			'actions' => array(
//				'Suspensionscuis66::edit' => array(
//					'disabled' => !$this->Permissions->checkDossier( $this->request->params['controller'], 'edit', $dossierMenu )
//				),
//				'Suspensionscuis66::delete' => array(
//					'disabled' => !$this->Permissions->checkDossier( $this->request->params['controller'], 'edit', $dossierMenu )
//				)
//			),
//			'options' => $options
//		)
//	);
	
		echo '<div class="aere">';
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'cuis',
			'action' => 'index',
			$personne_id
		),
		array(
			'id' => 'Back',
			'label' => 'Retour au CUI'
		)
	);
	echo '</div>';
?>
</div>