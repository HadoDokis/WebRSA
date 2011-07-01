<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une adresse';
    }
    else {
        $title = implode(
            ' ',
            array(
                $this->data['Adresse']['numvoie'],
                $typevoie[$this->data['Adresse']['typevoie']],
                $this->data['Adresse']['nomvoie'] )
        );

        $this->pageTitle = 'Édition de l\'adresse « '.$title.' »';
        $foyer_id = $this->data['Adressefoyer']['foyer_id'];
    }
?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Adressefoyer', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $form->create( 'Adressefoyer', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Adresse.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Adressefoyer.id', array( 'type' => 'hidden', 'value' => $this->data['Adressefoyer']['id'] ) );
            echo '</div>';
        }
    ?>
        <?php echo '<div>'.$form->input( 'Adressefoyer.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) ).'</div>';?>

        <?php include( '_form.ctp' );?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
     <?php
		echo $default->button(
			'back',
			array(
				'controller' => 'adressesfoyers',
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