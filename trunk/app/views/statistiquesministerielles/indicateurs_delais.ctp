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
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursDelais/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
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
echo $html->tag(
        'h1',
$this->pageTitle = 'Indicateurs de délais'
)
?>
<p class="p"><strong>3. Délais entre les différentes étapes de l'orientation au cours de l'année.</strong></p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="2"><strong>Délais entre les différentes étapes de l'orientation <i>(en jours)</i></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr class="even">
			<td style="width: 70%;" ><strong>a. Délai moyen entre la date d'ouverture de droit, tel qu'enregistré par les organismes chargés du service de l'allocation (Caf, Msa), et la décision d'orientation validée par le président du conseil général (*):</strong></td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Amoy']) ? $results['Amoy'] : ''; ?></strong></td>
		</tr>
		
		<tr class="even">
			<td style="width: 70%;" ><strong>b. Délai moyen entre la décision d'orientation et la signature d'un contrat :</strong></td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Bmoy'][0]) ? $results['Bmoy'][0] : ''; ?></strong></td>
		</tr>
					
		<tr>
			<td class="odd" style="width: 70%;"><strong>Délai moyen pour la signature d'un PPAE (L262-34) (**) :</strong></td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Bmoy'][1]) ? $results['Bmoy'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Nombre total de ces contrats signés au cours de l'année :</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot'][0][0]) ? $results['Btot'][0][0] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés dans le mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot'][0][1]) ? $results['Btot'][0][1] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés entre 1 mois et moins de 3 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot'][0][2]) ? $results['Btot'][0][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés plus de 3 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot'][0][3]) ? $results['Btot'][0][3] : ''; ?></strong></td>
		</tr>
						
		<tr>
			<td class="odd" style="width: 70%;"><strong>Délai moyen pour la signature d'un contrat d'engagement réciproque en matière d'insertion professionnelle (L262-35) :</strong></td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Bmoy'][2]) ? $results['Bmoy'][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Nombre total de ces contrats signés au cours de l'année :</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-35'][0]) ? $results['Btot']['L262-35'][0] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés dans le mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-35'][1]) ? $results['Btot']['L262-35'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés entre 1 mois et moins de 3 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-35'][2]) ? $results['Btot']['L262-35'][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés plus de 3 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-35'][3]) ? $results['Btot']['L262-35'][3] : ''; ?></strong></td>
		</tr>
		
		<tr>
			<td class="odd" style="width: 70%;"><strong>Délai moyen pour la signature d'un contrat d'engagement réciproque en matière d'insertion sociale ou professionnelle (L262-36) :</strong></td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Bmoy'][3]) ? $results['Bmoy'][3] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Nombre total de ces contrats signés au cours de l'année :</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-36'][0]) ? $results['Btot']['L262-36'][0] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés dans les 2 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-36'][1]) ? $results['Btot']['L262-36'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés entre 2 mois et 4 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-36'][2]) ? $results['Btot']['L262-36'][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">&nbsp;-&nbsp;dont contrats signés plus de 4 mois après la décision d'orientation</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['Btot']['L262-36'][3]) ? $results['Btot']['L262-36'][3] : ''; ?></strong></td>
		</tr>
							
	</tbody>
</table>
<p>
(*) On considère que la date d’ouverture de droit correspond à la date de dépôt de la demande, c’est-à-dire le premier jour du mois du dépôt de la demande.
</p>
<p>
(**) Il serait souhaitable, qu’à terme, les flux d’échanges entre Pôle emploi et les Conseils généraux permettent de recueillir ces informations sur le PPAE.
</p>
<?php endif;?>