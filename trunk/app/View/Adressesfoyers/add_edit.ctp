<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une adresse';
	}
	else {
		$title = implode(
			' ',
			array(
				$this->request->data['Adresse']['numvoie'],
				$typevoie[$this->request->data['Adresse']['typevoie']],
				$this->request->data['Adresse']['nomvoie'] )
		);

		$this->pageTitle = 'Édition de l\'adresse « '.$title.' »';
		$foyer_id = $this->request->data['Adressefoyer']['foyer_id'];
	}
?>
<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $this->Form->create( 'Adressefoyer', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	}
	else {
		echo $this->Form->create( 'Adressefoyer', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo '<div>';
		echo $this->Form->input( 'Adresse.id', array( 'type' => 'hidden' ) );
		echo $this->Form->input( 'Adressefoyer.id', array( 'type' => 'hidden', 'value' => $this->request->data['Adressefoyer']['id'] ) );
		echo '</div>';
	}

	echo '<div>'.$this->Form->input( 'Adressefoyer.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) ).'</div>';

	echo $this->Form->input( 'Adresse.numvoie', array( 'label' =>   __d( 'adresse', 'Adresse.numvoie' ) ) );
	echo $this->Form->input( 'Adresse.typevoie', array( 'label' =>  required( __d( 'adresse', 'Adresse.typevoie' ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );
	echo $this->Form->input( 'Adresse.nomvoie', array( 'label' =>  required( __d( 'adresse', 'Adresse.nomvoie' ) ) ) );
	echo $this->Form->input( 'Adresse.complideadr', array( 'label' =>  __d( 'adresse', 'Adresse.complideadr' ) ) );
	echo $this->Form->input( 'Adresse.compladr', array( 'label' =>  __d( 'adresse', 'Adresse.compladr' ) ) );
	echo $this->Form->input( 'Adresse.lieudist', array( 'label' =>  __d( 'adresse', 'Adresse.lieudist' ) ) );
	echo $this->Form->input( 'Adresse.numcomrat', array( 'label' =>  __d( 'adresse', 'Adresse.numcomrat' ) ) );
	echo $this->Form->input( 'Adresse.numcomptt', array( 'label' =>  required( __d( 'adresse', 'Adresse.numcomptt' ) ) ) );
	echo $this->Form->input( 'Adresse.codepos', array( 'label' =>  required( __d( 'adresse', 'Adresse.codepos' ) ) ) );
	echo $this->Form->input( 'Adresse.locaadr', array( 'label' =>  required( __d( 'adresse', 'Adresse.locaadr' ) ) ) );
	echo $this->Form->input( 'Adresse.pays', array( 'label' =>  required( __d( 'adresse', 'Adresse.pays' ) ), 'type' => 'select', 'options' => $pays, 'empty' => true ) );
	echo $this->Form->input( 'Adresse.canton', array( 'label' =>  __d( 'adresse', 'Adresse.canton' ) ) );

	if( $this->name == 'Adressesfoyers' ):
		echo $this->Form->input( 'Adressefoyer.rgadr', array( 'label' => required( __d( 'adressefoyer', 'Adressefoyer.rgadr' ) ), 'type' => 'select', 'options' => $rgadr, 'empty' => true ) );
	endif;
	echo $this->Form->input( 'Adressefoyer.dtemm', array( 'label' =>  __d( 'adressefoyer', 'Adressefoyer.dtemm' ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );
	echo $this->Form->input( 'Adressefoyer.typeadr', array( 'label' => required( __d( 'adressefoyer', 'Adressefoyer.typeadr' ) ), 'type' => 'select', 'options' => $typeadr, 'empty' => true ) );
?>
<div class="submit">
	<?php
		echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );
		echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );
	?>
</div>
<?php echo $this->Form->end(); ?>