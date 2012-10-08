<fieldset>
	<?php echo $this->Form->input( 'User.nom', array( 'label' =>  required( __d( 'personne', 'Personne.nom' ) ), 'type' => 'text' ) );?>
	<?php echo $this->Form->input( 'User.prenom', array( 'label' =>  required( __d( 'personne', 'Personne.prenom' ) ), 'type' => 'text' ) );?>
	<?php echo $this->Form->input( 'User.username', array( 'label' =>  required( __( 'username' ) ), 'type' => 'text' ) );?>
	<?php echo $this->Form->input( 'User.passwd', array( 'label' =>  required( __( 'password' ) ), 'type' => 'password', 'value' => '' ) );?>
	<?php
		echo $this->Form->input( 'User.numtel', array( 'label' =>  required( __( 'numtel' ) ), 'type' => 'text', 'maxlength' => 15 ) );

		if( Configure::read( 'User.adresse' ) ) {
			echo $this->Form->input( 'User.numvoie', array( 'label' =>  __d( 'adresse', 'Adresse.numvoie' ), 'type' => 'text' ) );
			echo $this->Form->input( 'User.typevoie', array( 'label' =>  __d( 'adresse', 'Adresse.typevoie' ), 'type' => 'select', 'options' => $typevoie, 'empty' => true  ) );
			echo $this->Form->input( 'User.nomvoie', array( 'label' =>  __d( 'adresse', 'Adresse.nomvoie' ), 'type' => 'text' ) );
			echo $this->Form->input( 'User.compladr', array( 'label' =>  __d( 'adresse', 'Adresse.compladr' ), 'type' => 'text' ) );
			echo $this->Form->input( 'User.codepos', array( 'label' =>  __d( 'adresse', 'Adresse.codepos' ), 'type' => 'text', 'maxlength' => 5 ) );
			echo $this->Form->input( 'User.ville', array( 'label' =>  __( 'ville' ), 'type' => 'text' ) );
		}
	?>
	<?php echo $this->Form->input( 'User.date_naissance', array( 'label' =>  __( 'date_naissance' ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') - 80 , 'empty' => true ) ) ;?>
	<?php echo $this->Form->input( 'User.date_deb_hab', array( 'label' => required(  __( 'date_deb_hab' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10 , 'empty' => true ) );?>
	<?php echo $this->Form->input( 'User.date_fin_hab', array( 'label' => required(  __( 'date_fin_hab' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10, 'empty' => true ) ) ;?>
</fieldset>
<div><?php echo $this->Form->input( 'User.filtre_zone_geo', array( 'label' => 'Restreindre les zones géographiques', 'type' => 'checkbox' ) );?></div>
<fieldset class="col2" id="filtres_zone_geo">
	<legend>Zones géographiques</legend>
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			observeDisableFieldsetOnCheckbox( 'UserFiltreZoneGeo', 'filtres_zone_geo', false );
		});
	</script>
	<?php echo $this->Form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>
	<?php echo $this->Form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>

	<?php echo $this->Form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo required( 'Groupe d\'utilisateur' );?></legend>
	<?php echo $this->Form->input( 'User.group_id', array( 'label' => false, 'type' => 'select' , 'options' => $gp, 'empty' => true ) );?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo required( 'Service instructeur' );?></legend>
	<?php echo $this->Form->input( 'User.serviceinstructeur_id', array( 'label' => false, 'type' => 'select' , 'options' => $si, 'empty' => true ) );?>
</fieldset>
<fieldset class="col2">
	<legend>L'utilisateur est un CPDV ou secrétaire PDV</legend>
	<?php
		echo $this->Form->input( 'User.structurereferente_id', array( 'type' => 'hidden', 'value' => '', 'id' => false ) );
		echo $this->Form->input( 'User.structurereferente_id', array( 'label' => false, 'type' => 'select' , 'options' => $structuresreferentes, 'empty' => true ) );
	?>
</fieldset>
<fieldset class="col2">
	<legend>L'utilisateur est un chargé d'insertion d'un PDV</legend>
	<?php
		echo $this->Form->input( 'User.referent_id', array( 'type' => 'hidden', 'value' => '', 'id' => false ) );
		echo $this->Form->input( 'User.referent_id', array( 'label' => false, 'type' => 'select' , 'options' => $referents, 'empty' => true ) );
	?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo required( 'Est-il gestionnaire, notamment pour les PDOs ? ' );?></legend>
	<?php echo $this->Xform->input( 'User.isgestionnaire', array( 'legend' => false, 'type' => 'radio', 'options' => $options['isgestionnaire'] ) );?>
</fieldset>
<fieldset class="col2">
	<legend><?php echo required( 'Peut-il accéder aux données sensibles ? ' );?></legend>
	<?php echo $this->Xform->input( 'User.sensibilite', array( 'legend' => false, 'type' => 'radio', 'options' => $options['sensibilite'] ) );?>
</fieldset>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue( 'UserStructurereferenteId', [ 'UserReferentId' ], '', false );
		observeDisableFieldsOnValue( 'UserReferentId', [ 'UserStructurereferenteId' ], '', false );
	} );
</script>