<?php
	$this->pageTitle = 'Indicateurs de réorientations';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php echo $xhtml->tag( 'h1', $this->pageTitle ); ?>
<?php
echo '<ul class="actionMenu"><li>'.$xhtml->link(
$xhtml->image(
				'icons/application_form_magnify.png',
array( 'alt' => '' )
).' Formulaire',
			'#',
array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
).'</ul>';
?>
<?php echo $form->create( 'Statistiquesministerielle', array( 'type' => 'post', 'action' => '/indicateursReorientations/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
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
<p class="p"><strong>4. Bénéficiaires du RSA réorientées au cours de l'année.
</strong></p>
<table class="tooltips_oupas">
	<thead>
		<tr>
			<th colspan="4"><strong>Personnes réorientées au cours de l'année <i>(en effectifs)</i></strong></th>
		</tr>
		<tr>
			<th style="width:40%;"></th>
			<th><strong>Ensemble des personnes réorientées (*)</strong></th>
			<th><strong>Orientation à dominante professionnelle vers orientation à dominante sociale</strong></th>
			<th><strong>Orientation à dominante sociale vers orientation à dominante professionnelle</strong></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><strong>Effectif au 31 décembre de l'année :</strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
			<td class="number"><strong><?php //echo isset($results['x']) ? $results['x'] : ''; ?></strong></td>
		</tr>
		<tr class="even">
			<td colspan="5">Âge :</td>
		</tr>
		<tr class="odd">
			<td>- moins de 25 ans</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['0 - 24']) ? $results['age']['tous']['0 - 24'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['0 - 24']) ? $results['age']['versSocial']['0 - 24'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['0 - 24']) ? $results['age']['versPro']['0 - 24'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 25 à 29 ans</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['25 - 29']) ? $results['age']['tous']['25 - 29'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['25 - 29']) ? $results['age']['versSocial']['25 - 29'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['25 - 29']) ? $results['age']['versPro']['25 - 29'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 30 à 39 ans</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['30 - 39']) ? $results['age']['tous']['30 - 39'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['30 - 39']) ? $results['age']['versSocial']['30 - 39'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['30 - 39']) ? $results['age']['versPro']['30 - 39'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 40 à 49 ans</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['40 - 49']) ? $results['age']['tous']['40 - 49'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['40 - 49']) ? $results['age']['versSocial']['40 - 49'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['40 - 49']) ? $results['age']['versPro']['40 - 49'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 50 à 59 ans</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['50 - 59']) ? $results['age']['tous']['50 - 59'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['50 - 59']) ? $results['age']['versSocial']['50 - 59'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['50 - 59']) ? $results['age']['versPro']['50 - 59'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 60 ans et plus</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['>= 60']) ? $results['age']['tous']['>= 60'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['>= 60']) ? $results['age']['versSocial']['>= 60'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['>= 60']) ? $results['age']['versPro']['>= 60'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connu</td>
			<td class="number"><strong><?php echo isset($results['age']['tous']['NC']) ? $results['age']['tous']['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versSocial']['NC']) ? $results['age']['versSocial']['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age']['versPro']['NC']) ? $results['age']['versPro']['NC'] : ''; ?></strong></td>
		</tr>

		<tr class="even">
			<td colspan="5">Situation familliale :</td>
		</tr>
		<tr class="odd">
			<td>- homme seul sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['01 - Homme seul sans enfant']) ? $results['situation']['tous']['01 - Homme seul sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['01 - Homme seul sans enfant']) ? $results['situation']['versSocial']['01 - Homme seul sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['01 - Homme seul sans enfant']) ? $results['situation']['versPro']['01 - Homme seul sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme seule sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['02 - Femme seule sans enfant']) ? $results['situation']['tous']['02 - Femme seule sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['02 - Femme seule sans enfant']) ? $results['situation']['versSocial']['02 - Femme seule sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['02 - Femme seule sans enfant']) ? $results['situation']['versPro']['02 - Femme seule sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme seul avec enfant</td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation']['tous']['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation']['tous']['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation']['tous']['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation']['tous']['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : ''; ?></strong></td>
			<td class="number"><strong><?php
				$sommeVersSocial = isset($results['situation']['versSocial']['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation']['versSocial']['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeVersSocial+= isset($results['situation']['versSocial']['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation']['versSocial']['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeVersSocial != 0) ? $sommeVersSocial : ''; ?></strong></td>
			<td class="number"><strong><?php
				$sommeVersPro = isset($results['situation']['versPro']['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation']['versPro']['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeVersPro+= isset($results['situation']['versPro']['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation']['versPro']['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeVersPro != 0) ? $sommeVersPro : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- - dont bénéficiant du Rsa majoré</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation']['tous']['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation']['versSocial']['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation']['versPro']['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme seule avec enfant</td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation']['tous']['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation']['tous']['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation']['tous']['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation']['tous']['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : ''; ?></strong></td>
			<td class="number"><strong><?php
				$sommeVersSocial = isset($results['situation']['versSocial']['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation']['versSocial']['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeVersSocial+= isset($results['situation']['versSocial']['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation']['versSocial']['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeVersSocial != 0) ? $sommeVersSocial : ''; ?></strong></td>
			<td class="number"><strong><?php
				$sommeVersPro = isset($results['situation']['versPro']['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation']['versPro']['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeVersPro+= isset($results['situation']['versPro']['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation']['versPro']['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeVersPro != 0) ? $sommeVersPro : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- - dont bénéficiant du Rsa majoré</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation']['tous']['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation']['versSocial']['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation']['versPro']['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme en couple sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['07 - Homme en couple sans enfant']) ? $results['situation']['tous']['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['07 - Homme en couple sans enfant']) ? $results['situation']['versSocial']['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['07 - Homme en couple sans enfant']) ? $results['situation']['versPro']['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme en couple sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['08 - Femme en couple sans enfant']) ? $results['situation']['tous']['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['08 - Femme en couple sans enfant']) ? $results['situation']['versSocial']['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['08 - Femme en couple sans enfant']) ? $results['situation']['versPro']['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme en couple avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['09 - Homme en couple avec enfant']) ? $results['situation']['tous']['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['09 - Homme en couple avec enfant']) ? $results['situation']['versSocial']['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['09 - Homme en couple avec enfant']) ? $results['situation']['versPro']['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme en couple avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['10 - Femme en couple avec enfant']) ? $results['situation']['tous']['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['10 - Femme en couple avec enfant']) ? $results['situation']['versSocial']['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['10 - Femme en couple avec enfant']) ? $results['situation']['versPro']['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connue</td>
			<td class="number"><strong><?php echo isset($results['situation']['tous']['11 - Non connue']) ? $results['situation']['tous']['11 - Non connue'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versSocial']['11 - Non connue']) ? $results['situation']['versSocial']['11 - Non connue'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation']['versPro']['11 - Non connue']) ? $results['situation']['versPro']['11 - Non connue'] : ''; ?></strong></td>
		</tr>

		<tr class="even">
			<td colspan="5">Niveau de formation :</td>
		</tr>
		<tr class="odd">
			<td>- inférieur au Cap / Bep <i>(Vbis et VI)</i></td>
			<td class="number"><strong><?php echo isset($results['formation']['tous']['Vbis et VI']) ? $results['formation']['tous']['Vbis et VI'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versSocial']['Vbis et VI']) ? $results['formation']['versSocial']['Vbis et VI'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versPro']['Vbis et VI']) ? $results['formation']['versPro']['Vbis et VI'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Cap / Bep <i>(V)</i></td>
			<td class="number"><strong><?php echo isset($results['formation']['tous']['V']) ? $results['formation']['tous']['V'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versSocial']['V']) ? $results['formation']['versSocial']['V'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versPro']['V']) ? $results['formation']['versPro']['V'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- équivalent Bac / Brevet technicien <i>(IV)</i></td>
			<td class="number"><strong><?php echo isset($results['formation']['tous']['IV']) ? $results['formation']['tous']['IV'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versSocial']['IV']) ? $results['formation']['versSocial']['IV'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versPro']['IV']) ? $results['formation']['versPro']['IV'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- supérieur au Bac <i>(III, II, I)</i></td>
			<td class="number"><strong><?php echo isset($results['formation']['tous']['III, II, I']) ? $results['formation']['tous']['III, II, I'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versSocial']['III, II, I']) ? $results['formation']['versSocial']['III, II, I'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versPro']['III, II, I']) ? $results['formation']['versPro']['III, II, I'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connu</td>
			<td class="number"><strong><?php echo isset($results['formation']['tous']['NC']) ? $results['formation']['tous']['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versSocial']['NC']) ? $results['formation']['versSocial']['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation']['versPro']['NC']) ? $results['formation']['versPro']['NC'] : ''; ?></strong></td>
		</tr>


		<tr class="even">
			<td colspan="5">Ancienneté dans le dispositif, y compris anciens minima (RMI, API) (**) :</td>
		</tr>
		<tr class="odd">
			<td>- moins de 6 mois</td>
			<td class="number"><strong><?php echo isset($results['anciennete']['tous']['moins de 6 mois']) ? $results['anciennete']['tous']['moins de 6 mois'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versSocial']['moins de 6 mois']) ? $results['anciennete']['versSocial']['moins de 6 mois'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versPro']['moins de 6 mois']) ? $results['anciennete']['versPro']['moins de 6 mois'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 6 mois à moins d'un an</td>
			<td class="number"><strong><?php echo isset($results['anciennete']['tous']['6 mois et moins 1 an']) ? $results['anciennete']['tous']['6 mois et moins 1 an'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versSocial']['6 mois et moins 1 an']) ? $results['anciennete']['versSocial']['6 mois et moins 1 an'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versPro']['6 mois et moins 1 an']) ? $results['anciennete']['versPro']['6 mois et moins 1 an'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 1 an à moins de 2 ans</td>
			<td class="number"><strong><?php echo isset($results['anciennete']['tous']['1 an et moins de 2 ans']) ? $results['anciennete']['tous']['1 an et moins de 2 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versSocial']['1 an et moins de 2 ans']) ? $results['anciennete']['versSocial']['1 an et moins de 2 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versPro']['1 an et moins de 2 ans']) ? $results['anciennete']['versPro']['1 an et moins de 2 ans'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 2 ans à moins de 5 ans</td>
			<td class="number"><strong><?php echo isset($results['anciennete']['tous']['2 ans et moins de 5 ans']) ? $results['anciennete']['tous']['2 ans et moins de 5 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versSocial']['2 ans et moins de 5 ans']) ? $results['anciennete']['versSocial']['2 ans et moins de 5 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versPro']['2 ans et moins de 5 ans']) ? $results['anciennete']['versPro']['2 ans et moins de 5 ans'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 5 ans et plus</td>
			<td class="number"><strong><?php echo isset($results['anciennete']['tous']['5 ans et plus']) ? $results['anciennete']['tous']['5 ans et plus'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versSocial']['5 ans et plus']) ? $results['anciennete']['versSocial']['5 ans et plus'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versPro']['5 ans et plus']) ? $results['anciennete']['versPro']['5 ans et plus'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connue</td>
			<td class="number"><strong><?php echo isset($results['anciennete']['tous']['NC']) ? $results['anciennete']['tous']['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versSocial']['NC']) ? $results['anciennete']['versSocial']['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete']['versPro']['NC']) ? $results['anciennete']['versPro']['NC'] : ''; ?></strong></td>
		</tr>
	</tbody>
</table>
<p>
(*) On entend par réorientation, le passage d’une « orientation à dominante sociale » vers une « orientation à dominante professionnelle » ou réciproquement. Un changement d’organisme au sein d’une même orientation  (exemple de Pôle emploi vers un organisme privé de placement) n’est pas considéré comme une réorientation.
</p>
<?php endif;?>