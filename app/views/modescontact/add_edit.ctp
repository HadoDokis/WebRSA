<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un mode de contact';
    }
    else {
        $this->pageTitle = 'Édition des modes de contact';
        $foyer_id = $this->data['Modecontact']['foyer_id'];
    }
?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Modecontact', array( 'type' => 'post', 'url' => Router::url( null, true ) ));
        }
        else {
            echo $form->create( 'Modecontact', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Modecontact.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>
    <div>
        <?php echo $form->input( 'Modecontact.foyer_id', array( 'type' => 'hidden', 'div' => 'div', 'value' => $foyer_id ) );?>
    </div>
    <?php echo $form->input( 'Modecontact.numtel', array( 'label' =>  __( 'numtel', true ) ) );?>
    <?php echo $form->input( 'Modecontact.numposte', array( 'label' => __d( 'modecontact', 'Modecontact.numposte', true ), 'maxlength' => 4 ) );?>
    <?php echo $form->input( 'Modecontact.nattel', array( 'label' =>  __d( 'modecontact', 'Modecontact.nattel', true ), 'type' => 'select', 'options' => $options['Modecontact']['nattel'], 'empty' => true  ) );?>
    <?php echo $form->input( 'Modecontact.matetel', array( 'label' => __d( 'modecontact', 'Modecontact.matetel', true ), 'type' => 'select', 'options' => $options['Modecontact']['matetel'], 'empty' => true  ) );?>
    <?php echo $form->input( 'Modecontact.autorutitel', array( 'label' => __d( 'modecontact', 'Modecontact.autorutitel', true ), 'type' => 'select', 'options' => $options['Modecontact']['autorutitel'], 'empty' => true  ) );?>
    <?php echo $form->input( 'Modecontact.adrelec', array( 'label' => __d( 'modecontact', 'Modecontact.adrelec', true ) ) );?>
    <?php echo $form->input( 'Modecontact.autorutiadrelec', array( 'label' => __d( 'modecontact', 'Modecontact.autorutiadrelec', true ), 'type' => 'select', 'options' => $options['Modecontact']['autorutiadrelec'], 'empty' => true  ) );?>

    <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
    <?php
		echo $default->button(
			'back',
			array(
				'controller' => 'modescontact',
				'action'     => 'index',
				$foyer_id
			),
			array(
				'id' => 'Back'
			)
		);
	?>
</div>
<div class="clearer"><hr /></div>