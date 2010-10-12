<?php //debug($results);?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false ); ?>
<?php
//if( is_array( $this->data ) ) {
echo '<ul class="actionMenu"><li>'.$html->link(
$html->image(
                'icons/application_form_magnify.png',
array( 'alt' => '' )
).' Formulaire',
            '#',
array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
).'</ul>';
//}
?>
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursMotifsReorientations/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'unfolded' : 'folded' ) ) );?>
<fieldset><legend>Critères</legend>
<?php echo $form->input( 'Statistiquesministerielle.localisation', array('disabled'=>true, 'label' => 'Localité') ); ?>
<?php echo $form->input( 'Statistiquesministerielle.service', array('disabled'=>true, 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) ); ?>
<?php echo $form->input( 'Statistiquesministerielle.date', array('disabled'=>false, 'label'=>'Année', 'type' => 'date', 'dateFormat' => 'Y', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 )); ?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>
<?php
echo $html->tag(
        'h1',
$this->pageTitle = 'Indicateurs motifs de réorientations'
)
?>
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
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1']) ? $results['tab1'] : ''; ?></strong></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Motifs de réorientation vers une dominante sociale (*) :</strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Orientation initiale inadaptée</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1']) ? $results['tab1'] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td style="width: 70%;">Changement de situation de la personne (difficultés nouvelles de logement, santé, garde d'enfants, famille, ...)</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1']) ? $results['tab1'] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td style="width: 70%;">Autres :</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab1']) ? $results['tab1'] : ''; ?></strong></td>
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
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="even" style="width: 70%;"><strong>Nombre de personnes dont le dossier a été examiné par l'équipe pluridisciplinaire dans le cadre de l'article L262-31 (à l'issue du délai de 6 à 12 mois sans réorientation professionnelle) au cours de l'année :</strong></td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab2']) ? $results['tab2'] : ''; ?></strong></td>
		</tr>
		<tr>
			<td style="width: 70%;">Dont maintien en orientation à dominante sociale</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab2']) ? $results['tab2'] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td style="width: 70%;">Dont réorientation vers une dominante professionnelle</td>
			<td class="number" style="width: 30%;"><strong><?php echo isset($results['tab2']) ? $results['tab2'] : ''; ?></strong></td>
		</tr>		
	</tbody>
</table>