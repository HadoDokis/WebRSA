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

		echo '<div>'.$form->input( 'Adressefoyer.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) ).'</div>';

		echo $form->input( 'Adresse.numvoie', array( 'label' =>   __d( 'adresse', 'Adresse.numvoie', true ) ) );
		echo $form->input( 'Adresse.typevoie', array( 'label' =>  required( __d( 'adresse', 'Adresse.typevoie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );
		echo $form->input( 'Adresse.nomvoie', array( 'label' =>  required( __d( 'adresse', 'Adresse.nomvoie', true ) ) ) );
		echo $form->input( 'Adresse.complideadr', array( 'label' =>  __d( 'adresse', 'Adresse.complideadr', true ) ) );
		echo $form->input( 'Adresse.compladr', array( 'label' =>  __d( 'adresse', 'Adresse.compladr', true ) ) );
		echo $form->input( 'Adresse.lieudist', array( 'label' =>  __d( 'adresse', 'Adresse.lieudist', true ) ) );
		echo $form->input( 'Adresse.numcomrat', array( 'label' =>  __d( 'adresse', 'Adresse.numcomrat', true ) ) );
		echo $form->input( 'Adresse.numcomptt', array( 'label' =>  required( __d( 'adresse', 'Adresse.numcomptt', true ) ) ) );
		echo $form->input( 'Adresse.codepos', array( 'label' =>  required( __d( 'adresse', 'Adresse.codepos', true ) ) ) );
		echo $form->input( 'Adresse.locaadr', array( 'label' =>  required( __d( 'adresse', 'Adresse.locaadr', true ) ) ) );
		echo $form->input( 'Adresse.pays', array( 'label' =>  required( __d( 'adresse', 'Adresse.pays', true ) ), 'type' => 'select', 'options' => $pays, 'empty' => true ) );
		echo $form->input( 'Adresse.canton', array( 'label' =>  __d( 'adresse', 'Adresse.canton', true ) ) );

		if( $this->name == 'Adressefoyers' ):
			echo $form->input( 'Adressefoyer.rgadr', array( 'label' => required( __d( 'adressefoyer', 'Adressefoyer.rgadr', true ) ), 'type' => 'select', 'options' => $rgadr, 'empty' => true ) );
		endif;
		echo $form->input( 'Adressefoyer.dtemm', array( 'label' =>  __d( 'adressefoyer', 'Adressefoyer.dtemm', true ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );
		echo $form->input( 'Adressefoyer.typeadr', array( 'label' => required( __d( 'adressefoyer', 'Adressefoyer.typeadr', true ) ), 'type' => 'select', 'options' => $typeadr, 'empty' => true ) );

		echo $form->submit( 'Enregistrer' );
		echo $form->end();

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