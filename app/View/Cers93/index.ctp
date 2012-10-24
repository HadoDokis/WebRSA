<?php
	$title_for_layout = 'Contrats d\'engagement réciproque';
	$this->set( 'title_for_layout', $title_for_layout );

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<?php echo $this->Html->tag( 'h1', $title_for_layout );?>

	<ul class="actionMenu">
		<li><?php echo $this->Xhtml->addLink( 'Ajouter', array( 'action' => 'add', $personne_id ) );?></li>
	</ul>

	<?php
		echo $this->Default2->index(
			$cers93,
			array(
				'Cer93.positioncer' => array( 'domain' => 'cer93' ),
				'Cer93.formeci' => array( 'domain' => 'cer93' ),
				'Contratinsertion.dd_ci',
				'Contratinsertion.df_ci',
				'Contratinsertion.rg_ci',
				'Contratinsertion.decision_ci',
				'Fichiermodule.nb_fichiers_lies' => array( 'label' => 'Nb fichiers liés', 'type' => 'text' )
			),
			array(
				'actions' => array(
					'Cers93::view' => array( 'url' => array( 'action' => 'view', '#Contratinsertion.id#' ) ),
					'Cers93::edit' => array(
						'url' => array( 'action' => 'edit', '#Contratinsertion.id#' ),
						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'cers93', 'edit' ), $disabledLinks['Cers93::edit'] )
					),
					'Cers93::signature' => array(
						'url' => array( 'action' => 'signature', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'cers93', 'signature' ), $disabledLinks['Cers93::signature'] )
					),
					'Histoschoixcers93::attdecisioncpdv' => array(
						'url' => array( 'action' => 'attdecisioncpdv', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'attdecisioncpdv' ), $disabledLinks['Histoschoixcers93::attdecisioncpdv'] )
					),
					'Histoschoixcers93::attdecisioncg' => array(
						'url' => array( 'action' => 'attdecisioncg', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'attdecisioncg' ), $disabledLinks['Histoschoixcers93::attdecisioncg'] )
					),
					'Histoschoixcers93::premierelecture' => array(
						'url' => array( 'action' => 'premierelecture', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'premierelecture' ), $disabledLinks['Histoschoixcers93::premierelecture'] )
					),
					'Histoschoixcers93::secondelecture' => array(
						'url' => array( 'action' => 'secondelecture', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'secondelecture' ), $disabledLinks['Histoschoixcers93::secondelecture'] )
					),
					'Histoschoixcers93::aviscadre' => array(
						'url' => array( 'action' => 'aviscadre', '#Contratinsertion.id#' ),
 						'disabled' => str_replace( '%permission%', $this->Permissions->check( 'histoschoixcers93', 'aviscadre' ), $disabledLinks['Histoschoixcers93::aviscadre'] )
					),
					'Contratsinsertion::impression' => array( 'url' => array( 'action' => 'impression', '#Contratinsertion.id#' ) ),
					'Contratsinsertion::filelink' => array( 'url' => array( 'action' => 'filelink', '#Contratinsertion.id#' ) )
				),
				'options' => $options
			)
		);

	?>
</div>
<div class="clearer"><hr /></div>