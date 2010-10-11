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
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursNatureContrats/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'unfolded' : 'folded' ) ) );?>
<fieldset><legend>Critères</legend>
<?php echo $form->input( 'Statistiquesministerielle.localisation', array('disabled'=>false, 'label' => 'Localité') ); ?>
<?php echo $form->input( 'Statistiquesministerielle.service', array('disabled'=>false, 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) ); ?>
<?php echo $form->input( 'Statistiquesministerielle.date', array('disabled'=>false, 'label'=>'Année', 'type' => 'date', 'dateFormat' => 'Y', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 )); ?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>
<?php
echo $html->tag(
        'h1',
$this->pageTitle = 'Indicateurs de nature des contrats'
)
?>
<p class="p"><strong>2. Nature des actions inscrites dans les contrats RSA en cours de validité au 31 décembre de l'année.</strong></p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="2"><strong>Actions inscrites dans les contrats RSA en cours de validité au 31 décembre (*) <i>(en nombre)</i></strong></th>
		</tr>
	</thead>
	<tbody>
		<tr class="even">
			<td colspan="2"><strong>a. Nature des actions d'insertions inscrites dans les contrats d'engagement réciproque en matière d'insertion professionnelle (L262-35)</strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Activités, stages ou formation destinés à acquérir des compétences professionnelles</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][0]) ? $results['L262-35'][0] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Orientation vers le service public de l'emploi, parcours de recherche d'emploi</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][1]) ? $results['L262-35'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Mesures d'insertion par l'activité économique (IAE)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][2]) ? $results['L262-35'][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Aide à la réalisation d’un projet de création, de reprise ou de poursuite d’une activité non salariée</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][3]) ? $results['L262-35'][3] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Emploi aidé</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][4]) ? $results['L262-35'][4] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Emploi non aidé</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][5]) ? $results['L262-35'][5] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions facilitant le lien social (développement de l'autonomie sociale, activités collectives,…)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][6]) ? $results['L262-35'][6] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td class="odd" style="width: 70%;">Actions facilitant la mobilité (permis de conduire, acquisition / location de véhicule, frais de transport…)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][7]) ? $results['L262-35'][7] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions visant l'accès à un logement, relogement ou à l'amélioration de l'habitat</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][8]) ? $results['L262-35'][8] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions facilitant l'accès aux soins</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][9]) ? $results['L262-35'][9] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions visant l'autonomie financière (constitution d'un dossier de surendettement,...)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][10]) ? $results['L262-35'][10] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions visant la famille et la parentalité (soutien familiale, garde d'enfant, …)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][11]) ? $results['L262-35'][11] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Lutte contre l'illettrisme ; acquisition des savoirs de base</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][12]) ? $results['L262-35'][12] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Autres actions</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-35'][13]) ? $results['L262-35'][13] : ''; ?></strong></td>
		</tr>		
		<tr class="even">
			<td colspan="2"><strong>b. Nature des actions d'insertions inscrites dans les contrats d'engagement réciproque en matière d'insertion sociale ou professionnelle (L262-36)</strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Activités, stages ou formation destinés à acquérir des compétences professionnelles</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][0]) ? $results['L262-36'][0] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Orientation vers le service public de l'emploi, parcours de recherche d'emploi</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][1]) ? $results['L262-36'][1] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Mesures d'insertion par l'activité économique (IAE)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][2]) ? $results['L262-36'][2] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Aide à la réalisation d’un projet de création, de reprise ou de poursuite d’une activité non salariée</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][3]) ? $results['L262-36'][3] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Emploi aidé</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][4]) ? $results['L262-36'][4] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Emploi non aidé</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][5]) ? $results['L262-36'][5] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions facilitant le lien social (développement de l'autonomie sociale, activités collectives,…)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][6]) ? $results['L262-36'][6] : ''; ?></strong></td>
		</tr>		
		<tr>
			<td class="odd" style="width: 70%;">Actions facilitant la mobilité (permis de conduire, acquisition / location de véhicule, frais de transport…)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][7]) ? $results['L262-36'][7] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions visant l'accès à un logement, relogement ou à l'amélioration de l'habitat</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][8]) ? $results['L262-36'][8] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions facilitant l'accès aux soins</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][9]) ? $results['L262-36'][9] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions visant l'autonomie financière (constitution d'un dossier de surendettement,...)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][10]) ? $results['L262-36'][10] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Actions visant la famille et la parentalité (soutien familiale, garde d'enfant, …)</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][11]) ? $results['L262-36'][11] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Lutte contre l'illettrisme ; acquisition des savoirs de base</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][12]) ? $results['L262-36'][12] : ''; ?></strong></td>
		</tr>
		<tr>
			<td class="odd" style="width: 70%;">Autres actions</td>
			<td style="width: 30%;" class="number"><strong><?php echo isset($results['L262-36'][13]) ? $results['L262-36'][13] : ''; ?></strong></td>
		</tr>	
	</tbody>
</table>
<p>(*) Pour un département inscrivant plusieurs actions par contrat, un même contrat sera comptabilisé autant de fois qu’il y a d’actions.</p>