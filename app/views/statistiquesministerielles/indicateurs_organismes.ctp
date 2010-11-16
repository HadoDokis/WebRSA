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
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursOrganismes/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Critères</legend>
<?php echo $form->input( 'Statistiquesministerielle.localisation', array('disabled'=>false, 'label' => 'Localité') ); ?>
<?php echo $form->input( 'Statistiquesministerielle.service', array('disabled'=>false, 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) ); ?>
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
$this->pageTitle = 'Indicateurs d\'organismes'
)
?>
<p class="p"><strong>2. Bénéficiaires du Rsa dans le champ des Droits et
Devoirs (L262-28) au 31 décembre de l'année selon l'organisme de prise
en charge où a été désigné le référent unique.</strong></p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="2"><strong>Organismes de prise en charge, où le référent
			unique est désigné <i>(en effectifs)</i></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="width: 70%;"><strong>Nombre de personnes dans le champ des
			Droits et Devoirs (L262-28) au 31 décembre de l'année :</strong></td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['DroitsEtDevoirs']) ? $results['DroitsEtDevoirs'] : ''; ?></strong></td>
		</tr>
		<tr class="even">
			<td colspan="2">Dont le référent appartient à :</td>
		</tr>
		<tr class="odd">
			<td>- Pôle Emploi (PE)</td>
			<td class="number"><strong><?php echo isset($results['PE']) ? $results['PE'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Organisme public de placement professionnel autre que PE
			(maison de l'emploi, PLIE, mission locale,…)</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Entreprise de travail temporaire, agence privée de placement</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Organisme d'appui à la création et au développement
			d'entreprise</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Insertion par l'activité économique (IAE) <i>(uniquement si le
			référent appartient à l'IAE)</i></td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Autre organisme de placement professionnel</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Service du département</td>
			<td class="number"><strong><?php echo isset($results['SSD']) ? $results['SSD'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>&nbsp;&nbsp;&nbsp;- dont orientation à dominante professionnelle</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>&nbsp;&nbsp;&nbsp;- dont orientation à dominante sociale</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- CAF, MSA</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- CCAS, CIAS</td>
			<td class="number"><strong><?php echo isset($results['SP']) ? $results['SP'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Autres organismes d'insertion</td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
		<tr>
			<td><strong>Nombre de personnes en attente d'orientation (*) :</strong></td>
			<td class="number"><strong><?php echo isset($results['']) ? $results[''] : ''; ?></strong></td>
		</tr>
	</tbody>
</table>
<p>(*) Certains bénéficiaires du Rsa peuvent être en attente
d'orientation, compte tenu d’une part, du délai de 9 mois accordé par la
loi à compter de sa date d'entrée en vigueur, pour examiner l'ensemble
des situations des anciens bénéficiaires du RMI et de l'API, ou d’autre
part, si la décision d’orientation est en attente de validation par le
président du conseil général.</p>
<?php endif;?>