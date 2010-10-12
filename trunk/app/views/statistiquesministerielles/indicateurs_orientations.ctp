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
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursOrientations/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
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
echo $html->tag(
        'h1',
$this->pageTitle = 'Indicateurs d\'orientations'
)
?>
<p class="p"><strong>1. Bénéficiaires du RSA dans le champ des Droits et Devoirs (L262-28)
 au 31 décembre de l'année, selon le parcours d'insertion envisagé par le Conseil général.
</strong></p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="5"><strong>Orientation des personnes dans le champ des Droits et Devoirs <i>(en effectifs)</i></strong></th>
		</tr>
		<tr>
			<th rowspan="2" style="width:40%;"></th>
			<th rowspan="2"><strong>Personnes dans le champ des Droits et Devoirs L262-28</strong></th>
			<th colspan="2"><strong>Parcours d'insertion envisagé :</strong></th>
			<th rowspan="2"><strong>En attente d'orientation (*)</strong></th>
		</tr>
		<tr>
			<th><strong>Orientation à dominante professionnelle</strong></th>
			<th><strong>Orientation à dominante sociale</strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><strong>Effectif au 31 décembre de l'année :</strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
		</tr>
		<tr class="even">
			<td colspan="5">Âge :</td>
		</tr>
		<tr class="odd">
			<td>- moins de 25 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][0][0]) ? $results['age'][0][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][0][1]) ? $results['age'][0][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][0][2]) ? $results['age'][0][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][0][3]) ? $results['age'][0][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 25 à 29 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][1][0]) ? $results['age'][1][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][1][1]) ? $results['age'][1][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][1][2]) ? $results['age'][1][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][1][3]) ? $results['age'][1][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 30 à 39 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][2][0]) ? $results['age'][2][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2][1]) ? $results['age'][2][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2][2]) ? $results['age'][2][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2][3]) ? $results['age'][2][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 40 à 49 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][3][0]) ? $results['age'][3][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3][1]) ? $results['age'][3][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3][2]) ? $results['age'][3][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3][3]) ? $results['age'][3][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 50 à 59 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][4][0]) ? $results['age'][4][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4][1]) ? $results['age'][4][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4][2]) ? $results['age'][4][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4][3]) ? $results['age'][4][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 60 ans et plus</td>
			<td class="number"><strong><?php echo isset($results['age'][5][0]) ? $results['age'][5][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][5][1]) ? $results['age'][5][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][5][2]) ? $results['age'][5][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][5][3]) ? $results['age'][5][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connu</td>
			<td class="number"><strong><?php echo isset($results['age'][6][0]) ? $results['age'][6][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][6][1]) ? $results['age'][6][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][6][2]) ? $results['age'][6][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][6][3]) ? $results['age'][6][3] : ''; ?></strong></td>
		</tr>
		
		<tr class="even">
			<td colspan="5">Situation familliale :</td>
		</tr>
		<tr class="odd">
			<td>- homme seul sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][0][0]) ? $results['situation'][0][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][0][1]) ? $results['situation'][0][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][0][2]) ? $results['situation'][0][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][0][3]) ? $results['situation'][0][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme seule sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1][0]) ? $results['situation'][1][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][1][1]) ? $results['situation'][1][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][1][2]) ? $results['situation'][1][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][1][3]) ? $results['situation'][1][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme seul avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][2][0]) ? $results['situation'][2][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2][1]) ? $results['situation'][2][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2][2]) ? $results['situation'][2][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2][3]) ? $results['situation'][2][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- - dont bénéficiant du Rsa majoré</td>
			<td class="number"><strong><?php echo isset($results['situation'][3][0]) ? $results['situation'][3][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3][1]) ? $results['situation'][3][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3][2]) ? $results['situation'][3][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3][3]) ? $results['situation'][3][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme seule avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][4][0]) ? $results['situation'][4][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4][1]) ? $results['situation'][4][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4][2]) ? $results['situation'][4][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4][3]) ? $results['situation'][4][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- - dont bénéficiant du Rsa majoré</td>
			<td class="number"><strong><?php echo isset($results['situation'][5][0]) ? $results['situation'][5][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][5][1]) ? $results['situation'][5][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][5][2]) ? $results['situation'][5][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][5][3]) ? $results['situation'][5][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme en couple sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][6][0]) ? $results['situation'][6][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][6][1]) ? $results['situation'][6][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][6][2]) ? $results['situation'][6][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][6][3]) ? $results['situation'][6][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme en couple sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][7][0]) ? $results['situation'][7][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][7][1]) ? $results['situation'][7][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][7][2]) ? $results['situation'][7][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][7][3]) ? $results['situation'][7][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme en couple avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][8][0]) ? $results['situation'][8][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][8][1]) ? $results['situation'][8][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][8][2]) ? $results['situation'][8][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][8][3]) ? $results['situation'][8][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme en couple avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][9][0]) ? $results['situation'][9][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][9][1]) ? $results['situation'][9][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][9][2]) ? $results['situation'][9][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][9][3]) ? $results['situation'][9][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connue</td>
			<td class="number"><strong><?php echo isset($results['situation'][10][0]) ? $results['situation'][10][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][10][1]) ? $results['situation'][10][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][10][2]) ? $results['situation'][10][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][10][3]) ? $results['situation'][10][3] : ''; ?></strong></td>
		</tr>
		
		<tr class="even">
			<td colspan="5">Niveau de formation :</td>
		</tr>
		<tr class="odd">
			<td>- inférieur au Cap / Bep <i>(Vbis et VI)<i/></td>
			<td class="number"><strong><?php echo isset($results['formation'][0][0]) ? $results['formation'][0][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][0][1]) ? $results['formation'][0][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][0][2]) ? $results['formation'][0][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][0][3]) ? $results['formation'][0][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Cap / Bep <i>(V)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][1][0]) ? $results['formation'][1][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][1][1]) ? $results['formation'][1][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][1][2]) ? $results['formation'][1][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][1][3]) ? $results['formation'][1][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- équivalent Bac / Brevet technicien <i>(IV)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][2][0]) ? $results['formation'][2][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2][1]) ? $results['formation'][2][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2][2]) ? $results['formation'][2][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2][3]) ? $results['formation'][2][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- supérieur au Bac <i>(III, II, I)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][3][0]) ? $results['formation'][3][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3][1]) ? $results['formation'][3][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3][2]) ? $results['formation'][3][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3][3]) ? $results['formation'][3][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connu</td>
			<td class="number"><strong><?php echo isset($results['formation'][4][0]) ? $results['formation'][4][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4][1]) ? $results['formation'][4][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4][2]) ? $results['formation'][4][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4][3]) ? $results['formation'][4][3] : ''; ?></strong></td>
		</tr>
		
										
		<tr class="even">
			<td colspan="5">Ancienneté dans le dispositif, y compris anciens minima (RMI, API) (**) :</td>
		</tr>
		<tr class="odd">
			<td>- moins de 6 mois</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][0][0]) ? $results['anciennete'][0][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][0][1]) ? $results['anciennete'][0][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][0][2]) ? $results['anciennete'][0][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][0][3]) ? $results['anciennete'][0][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 6 mois à moins d'un an</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1][0]) ? $results['anciennete'][1][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1][1]) ? $results['anciennete'][1][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1][2]) ? $results['anciennete'][1][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1][3]) ? $results['anciennete'][1][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 1 an à moins de 2 ans</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2][0]) ? $results['anciennete'][2][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2][1]) ? $results['anciennete'][2][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2][2]) ? $results['anciennete'][2][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2][3]) ? $results['anciennete'][2][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 2 ans à moins de 5 ans</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3][0]) ? $results['anciennete'][3][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3][1]) ? $results['anciennete'][3][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3][2]) ? $results['anciennete'][3][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3][3]) ? $results['anciennete'][3][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 5 ans et plus</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4][0]) ? $results['anciennete'][4][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4][1]) ? $results['anciennete'][4][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4][2]) ? $results['anciennete'][4][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4][3]) ? $results['anciennete'][4][3] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connue</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][5][0]) ? $results['anciennete'][5][0] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][5][1]) ? $results['anciennete'][5][1] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][5][2]) ? $results['anciennete'][5][2] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][5][3]) ? $results['anciennete'][5][3] : ''; ?></strong></td>
		</tr>																								
	</tbody>
</table>
<p>(*) Certains bénéficiaires du Rsa peuvent être en attente
d'orientation, compte tenu d’une part, du délai de 9 mois accordé par la
loi à compter de sa date d'entrée en vigueur, pour examiner l'ensemble
des situations des anciens bénéficiaires du RMI et de l'API, ou d’autre
part, si la décision d’orientation est en attente de validation par le
président du conseil général.</p>
<p>
(**) L’ancienneté dans le dispositif est mesurée par rapport à la dernière date d’entrée dans le dispositif.
</p>
<?php endif;?>