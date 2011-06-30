<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Types d\'orientations';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php echo $form->create( 'Typeorient', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );?>

	<fieldset>
		<?php echo $form->input( 'Typeorient.id', array( 'type' => 'hidden' ) );?>
		<?php echo $form->input( 'Typeorient.lib_type_orient', array( 'label' => required(  __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ) ), 'type' => 'text' ) );?>
		<?php echo $form->input( 'Typeorient.parentid', array( 'label' =>  __( 'parentid', true ), 'type' => 'select', 'options' => $parentid, 'empty' => true )  );?>
		<?php echo $form->input( 'Typeorient.modele_notif', array( 'label' => required( __d( 'typeorient', 'Typeorient.modele_notif', true ) ), 'type' => 'text' )  );?>
	<?php echo $form->input( 'Typeorient.modele_notif_cohorte', array( 'label' => required( __d( 'typeorient', 'Typeorient.modele_notif_cohorte', true ) ), 'type' => 'text' ) );?>
	</fieldset>
	<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Type d'orientation</th>
			<th>Parent</th>
			<th>Modèle de notification</th>
			<th>Modèle de notification pour cohorte</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $typesorients as $typeorient ):?>
			<?php echo $xhtml->tableCells(
						array(
							h( $typeorient['Typeorient']['id'] ),
							h( $typeorient['Typeorient']['lib_type_orient'] ),
							h( $typeorient['Typeorient']['parentid'] ),
							h( $typeorient['Typeorient']['modele_notif'] ),
							h( $typeorient['Typeorient']['modele_notif_cohorte'] ),
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
			?>
		<?php endforeach;?>
		</tbody>
	</table>

	<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
