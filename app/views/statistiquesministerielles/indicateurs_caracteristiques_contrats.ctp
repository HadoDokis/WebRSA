<?php //debug($results);?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false ); ?>
<?php
//if( is_array( $this->data ) ) {
echo '<ul class="actionMenu"><li>'.$xhtml->link(
$xhtml->image(
                'icons/application_form_magnify.png',
array( 'alt' => '' )
).' Formulaire',
            '#',
array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
).'</ul>';
//}
?>
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursCaracteristiquesContrats/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Critères</legend>
<?php echo $form->input( 'Statistiquesministerielle.localisation', array('disabled'=>true, 'label' => 'Localité') ); ?>
<?php echo $form->input( 'Statistiquesministerielle.service', array('disabled'=>true, 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) ); ?>
<?php echo $form->input( 'Statistiquesministerielle.date', array('disabled'=>false, 'label'=>'Année', 'type' => 'date', 'dateFormat' => 'Y', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 )); ?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>
<?php if( !empty( $this->data ) ):?>
<?php
echo $xhtml->tag(
        'h1',
$this->pageTitle = 'Indicateurs de caractéristiques des contrats'
)
?>
<p class="p"><strong>1. Contrats en cours de validité au 31 décembre de l'année.
</strong></p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="4"><strong>Contrats en cours de validité au 31 décembre <i>(en effectifs)</i></strong></th>
		</tr>
		<tr>
			<th style="width:40%;"></th>
			<th style="width:20%;"><strong>Total</strong></th>
			<th style="width:20%;"><strong>Dont personnes dans le champ des Droits et Devoirs (L262-28)</strong></th>
			<th style="width:20%;"><strong>Dont personnes hors du champ des Droits et Devoirs (L262-28)</strong></th>
		</tr>
	</thead>
	<tbody>
		<tr class="odd">
			<td><strong>Nombre de personnes au RSA bénéficiant encore d'un <u>contrat d'insertion RMI</u> au 31 décembre (*) :</strong></td>
			<td class="number"><strong><?php echo isset($results['R1Total'][0]) ? $results['R1Total'][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R1DD'][0]) ? $results['R1DD'][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R1DD'][1]) ? $results['R1DD'][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td><strong>Nombre de personnes au RSA bénéficiant encore d'un <u>contrat RSA expérimental</u> au 31 décembre :</strong></td>
			<td class="number"><strong><?php echo isset($results['R2Total'][0]) ? $results['R2Total'][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R2DD'][0]) ? $results['R2DD'][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R2DD'][1]) ? $results['R2DD'][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td><strong>Nombre de personnes bénéficiant d'un contrat RSA au 31 décembre :</strong></td>
			<td class="number"><strong><?php echo isset($results['R3Total'][0]) ? $results['R3Total'][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][0][0]) ? $results['R3DD'][0][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][0][1]) ? $results['R3DD'][0][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- dont PPAE</td>
			<td class="number"><strong><?php echo isset($results['R3Total'][1]) ? $results['R3Total'][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][1][0]) ? $results['R3DD'][1][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][1][1]) ? $results['R3DD'][1][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- dont contrat d'engagement réciproque en matière d'insertion professionnelle (L262-35)</td>
			<td class="number"><strong><?php echo isset($results['R3Total'][2]) ? $results['R3Total'][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][2][0]) ? $results['R3DD'][2][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][2][1]) ? $results['R3DD'][2][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- dont contrat d'engagement réciproque en matière d'insertion sociale ou professionnelle (L262-36)</td>
			<td class="number"><strong><?php echo isset($results['R3Total'][3]) ? $results['R3Total'][3] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][3][0]) ? $results['R3DD'][3][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['R3DD'][3][1]) ? $results['R3DD'][3][1] : ''; ?></strong></td>
		</tr>
	</tbody>
</table>

<table class="tooltips_oupas" style="width:60%;">
	<thead>
		<tr>
			<th style="width:40%;"></th>
			<th style="width:20%;"><strong><i>(en effectifs)</i></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr class="odd">
			<td><strong>Durée inscrite dans le contrat RSA d'engagement réciproque en matière d'insertion professionnelle (L262-35) :</strong></td>
			<td class="number">(rappel : <strong><?php echo isset($results['R3Total'][2]) ? $results['R3Total'][2] : ''; ?></strong>)</td>
		</tr>
		<tr class="odd">
			<td>- moins de 6 mois</td>
			<td class="number"><strong><?php echo isset($results['R4'][0]) ? $results['R4'][0] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 6 mois à moins d'un an</td>
			<td class="number"><strong><?php echo isset($results['R4'][1]) ? $results['R4'][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 1 an et plus</td>
			<td class="number"><strong><?php echo isset($results['R4'][2]) ? $results['R4'][2] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td><strong>Durée inscrite dans le contrat RSA d'engagement réciproque en matière d'insertion sociale ou professionnelle (L262-36) : </strong></td>
			<td class="number">(rappel : <strong><?php echo isset($results['R3Total'][3]) ? $results['R3Total'][3] : ''; ?></strong>)</td>
		</tr>
		<tr class="odd">
			<td>- moins de 6 mois</td>
			<td class="number"><strong><?php echo isset($results['R5'][0]) ? $results['R5'][0] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 6 mois à moins d'un an</td>
			<td class="number"><strong><?php echo isset($results['R5'][1]) ? $results['R5'][1] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 1 an et plus</td>
			<td class="number"><strong><?php echo isset($results['R5'][2]) ? $results['R5'][2] : ''; ?></strong></td>
		</tr>		
	</tbody>
</table>
<p>
(*) Les contrats d’insertion RMI en cours de validité au 31 mai 2009 peuvent se poursuivre au-delà du 1er juin 2009 et au maximum jusqu’au 31 mars 2010. La loi accorde en effet un délai de 9 mois à compter de sa date d’entrée en vigueur, pour examiner l’ensemble des situations des anciens bénéficiaires du RMI et de l’API.
</p>
<?php endif;?>