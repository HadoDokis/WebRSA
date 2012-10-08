<?php
	$this->pageTitle = 'Indicateurs motifs de réorientations';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php echo $this->Xhtml->tag( 'h1', $this->pageTitle ); ?>
<?php
	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
	$this->Xhtml->image(
					'icons/application_form_magnify.png',
	array( 'alt' => '' )
	).' Formulaire',
				'#',
	array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</ul>';
?>
<?php echo $this->Form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursMotifsReorientation/', 'id' => 'Search', 'class' => ( !empty( $this->request->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Critères</legend>
<?php echo $this->Form->input( 'Statistiquesministerielle.localisation', array('disabled'=>true, 'label' => 'Localité') ); ?>
<?php echo $this->Form->input( 'Statistiquesministerielle.service', array('disabled'=>true, 'label' => __( 'lib_service' ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) ); ?>
<?php echo $this->Form->input( 'Statistiquesministerielle.date', array('disabled'=>false, 'label'=>'Année', 'type' => 'date', 'dateFormat' => 'Y', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 )); ?>
</fieldset>
<div class="submit noprint"><?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $this->Form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $this->Form->end();?>
<?php if( !empty( $this->request->data ) ):?>
<p class="p"><strong>4a. Motifs des réorientations vers une dominante sociale effectuées au cours de l'année.</strong>
</p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="2"><strong>Motifs des réorientations vers le social <i>(en effectifs)</i></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="even" style="width: 70%;"><strong>Nombre de personnes réorientées <u>vers une dominante sociale</u> au cours de l'année :</strong></td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1'][0]) ? $results['tab1'][0] : ''; ?></strong></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Motifs de réorientation vers une dominante sociale (*) :</strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Orientation initiale inadaptée</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1'][1]) ? $results['tab1'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Changement de situation de la personne (difficultés nouvelles de logement, santé, garde d'enfants, famille, ...)</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1'][2]) ? $results['tab1'][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Autres :</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1'][3]) ? $results['tab1'][3] : ''; ?></strong></td>
		</tr>
	</tbody>
</table>
<p>
(*) Si une personne a été réorientée plusieurs fois au cours de l'année, indiquer uniquement le motif de sa dernière réorientation.

</p>
<p class="p"><strong>4b. Recours à l'article L262-31</strong>
</p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="3"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="even" style="width: 70%;"><strong>Nombre de personnes dont le dossier a été examiné par l'équipe pluridisciplinaire dans le cadre de l'article L262-31 (à l'issue du délai de 6 à 12 mois sans réorientation professionnelle) au cours de l'année :</strong></td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab2'][0]) ? $results['tab2'][0] : ''; ?></strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Dont maintien en orientation à dominante sociale</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab2'][1]) ? $results['tab2'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Dont réorientation vers une dominante professionnelle</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab2'][2]) ? $results['tab2'][2] : ''; ?></strong></td>
		</tr>
	</tbody>
</table>
<?php endif;?>