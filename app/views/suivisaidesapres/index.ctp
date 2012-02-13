<?php echo $xform->create( 'Suiviaideapre' );?>
<div>

<h1><?php echo $this->pageTitle = 'Paramétrage des personnes chargés du suivi des Aides APREs';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'suivisaidesapres', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<?php if( empty( $suivisaidesapres ) ):?>
		<p class="notice">Aucune personne présente pour le moment.</p>
	<?php else:?>
	<div>
		<h2>Table des Personnes chargés du suivi des Aides APREs</h2>
		<?php
			echo $default->index(
				$suivisaidesapres,
				array(
					'Suiviaideapre.qual' => array( 'options' => $qual ),
					'Suiviaideapre.nom',
					'Suiviaideapre.prenom',
					'Suiviaideapre.numtel' => array( 'type' => 'phone' ),
				),
				array(
					'actions' => array(
						'Suiviaideapre.edit',
						'Suiviaideapre.delete'
					)
				)
			);
		?>
	</div>
	<?php endif;?>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
</div>
<div class="clearer"><hr /></div>
<?php echo $form->end();?>