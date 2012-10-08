<?php
	$this->pageTitle = 'Indicateurs d\'orientations';
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
			<td class="number"><strong><?php echo isset($results['age'][1]['0 - 24']) ? $results['age'][1]['0 - 24'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['0 - 24']) ? $results['age'][2]['0 - 24'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['0 - 24']) ? $results['age'][3]['0 - 24'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['0 - 24']) ? $results['age'][4]['0 - 24'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 25 à 29 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][1]['25 - 29']) ? $results['age'][1]['25 - 29'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['25 - 29']) ? $results['age'][2]['25 - 29'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['25 - 29']) ? $results['age'][3]['25 - 29'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['25 - 29']) ? $results['age'][4]['25 - 29'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 30 à 39 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][1]['30 - 39']) ? $results['age'][1]['30 - 39'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['30 - 39']) ? $results['age'][2]['30 - 39'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['30 - 39']) ? $results['age'][3]['30 - 39'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['30 - 39']) ? $results['age'][4]['30 - 39'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 40 à 49 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][1]['40 - 49']) ? $results['age'][1]['40 - 49'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['40 - 49']) ? $results['age'][2]['40 - 49'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['40 - 49']) ? $results['age'][3]['40 - 49'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['40 - 49']) ? $results['age'][4]['40 - 49'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- de 50 à 59 ans</td>
			<td class="number"><strong><?php echo isset($results['age'][1]['50 - 59']) ? $results['age'][1]['50 - 59'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['50 - 59']) ? $results['age'][2]['50 - 59'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['50 - 59']) ? $results['age'][3]['50 - 59'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['50 - 59']) ? $results['age'][4]['50 - 59'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 60 ans et plus</td>
			<td class="number"><strong><?php echo isset($results['age'][1]['>= 60']) ? $results['age'][1]['>= 60'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['>= 60']) ? $results['age'][2]['>= 60'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['>= 60']) ? $results['age'][3]['>= 60'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['>= 60']) ? $results['age'][4]['>= 60'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connu</td>
			<td class="number"><strong><?php echo isset($results['age'][1]['NC']) ? $results['age'][1]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][2]['NC']) ? $results['age'][2]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][3]['NC']) ? $results['age'][3]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['age'][4]['NC']) ? $results['age'][4]['NC'] : ''; ?></strong></td>
		</tr>

		<tr class="even">
			<td colspan="5">Situation familliale :</td>
		</tr>
		<tr class="odd">
			<td>- homme seul sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['01 - Homme seul sans enfant']) ? $results['situation'][1]['01 - Homme seul sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['01 - Homme seul sans enfant']) ? $results['situation'][2]['01 - Homme seul sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['01 - Homme seul sans enfant']) ? $results['situation'][3]['01 - Homme seul sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['01 - Homme seul sans enfant']) ? $results['situation'][4]['01 - Homme seul sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme seule sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['02 - Femme seule sans enfant']) ? $results['situation'][1]['02 - Femme seule sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['02 - Femme seule sans enfant']) ? $results['situation'][2]['02 - Femme seule sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['02 - Femme seule sans enfant']) ? $results['situation'][3]['02 - Femme seule sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['02 - Femme seule sans enfant']) ? $results['situation'][4]['02 - Femme seule sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme seul avec enfant</td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][1]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][1]['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][1]['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation'][1]['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][2]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][2]['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][2]['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation'][2]['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][3]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][3]['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][3]['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation'][3]['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][4]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][4]['03 - Homme seul avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][4]['04 - Homme seul avec enfant, RSA non majoré']) ? $results['situation'][4]['04 - Homme seul avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
		</tr>
		<tr class="odd">
			<td>- - dont bénéficiant du Rsa majoré</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][1]['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][2]['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][3]['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['03 - Homme seul avec enfant, RSA majoré']) ? $results['situation'][4]['03 - Homme seul avec enfant, RSA majoré'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme seule avec enfant</td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][1]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][1]['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][1]['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation'][1]['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][2]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][2]['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][2]['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation'][2]['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][3]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][3]['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][3]['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation'][3]['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
			<td class="number"><strong><?php
				$sommeTous = isset($results['situation'][4]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][4]['05 - Femme seule avec enfant, RSA majoré'] : 0;
				$sommeTous+= isset($results['situation'][4]['06 - Femme seule avec enfant, RSA non majoré']) ? $results['situation'][4]['06 - Femme seule avec enfant, RSA non majoré'] : 0;
				echo ($sommeTous != 0) ? $sommeTous : '';
			?></strong></td>
		</tr>
		<tr class="odd">
			<td>- - dont bénéficiant du Rsa majoré</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][1]['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][2]['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][3]['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['05 - Femme seule avec enfant, RSA majoré']) ? $results['situation'][4]['05 - Femme seule avec enfant, RSA majoré'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme en couple sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['07 - Homme en couple sans enfant']) ? $results['situation'][1]['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['07 - Homme en couple sans enfant']) ? $results['situation'][2]['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['07 - Homme en couple sans enfant']) ? $results['situation'][3]['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['07 - Homme en couple sans enfant']) ? $results['situation'][4]['07 - Homme en couple sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme en couple sans enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['08 - Femme en couple sans enfant']) ? $results['situation'][1]['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['08 - Femme en couple sans enfant']) ? $results['situation'][2]['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['08 - Femme en couple sans enfant']) ? $results['situation'][3]['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['08 - Femme en couple sans enfant']) ? $results['situation'][4]['08 - Femme en couple sans enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- homme en couple avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['09 - Homme en couple avec enfant']) ? $results['situation'][1]['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['09 - Homme en couple avec enfant']) ? $results['situation'][2]['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['09 - Homme en couple avec enfant']) ? $results['situation'][3]['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['09 - Homme en couple avec enfant']) ? $results['situation'][4]['09 - Homme en couple avec enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- femme en couple avec enfant</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['10 - Femme en couple avec enfant']) ? $results['situation'][1]['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['10 - Femme en couple avec enfant']) ? $results['situation'][2]['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['10 - Femme en couple avec enfant']) ? $results['situation'][3]['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['10 - Femme en couple avec enfant']) ? $results['situation'][4]['10 - Femme en couple avec enfant'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connue</td>
			<td class="number"><strong><?php echo isset($results['situation'][1]['11 - Non connue']) ? $results['situation'][1]['11 - Non connue'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][2]['11 - Non connue']) ? $results['situation'][2]['11 - Non connue'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][3]['11 - Non connue']) ? $results['situation'][3]['11 - Non connue'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['situation'][4]['11 - Non connue']) ? $results['situation'][4]['11 - Non connue'] : ''; ?></strong></td>
		</tr>

		<tr class="even">
			<td colspan="5">Niveau de formation :</td>
		</tr>
		<tr class="odd">
			<td>- inférieur au Cap / Bep <i>(Vbis et VI)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][1]['Vbis et VI']) ? $results['formation'][1]['Vbis et VI'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2]['Vbis et VI']) ? $results['formation'][2]['Vbis et VI'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3]['Vbis et VI']) ? $results['formation'][3]['Vbis et VI'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4]['Vbis et VI']) ? $results['formation'][4]['Vbis et VI'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- Cap / Bep <i>(V)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][1]['V']) ? $results['formation'][1]['V'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2]['V']) ? $results['formation'][2]['V'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3]['V']) ? $results['formation'][3]['V'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4]['V']) ? $results['formation'][4]['V'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- équivalent Bac / Brevet technicien <i>(IV)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][1]['IV']) ? $results['formation'][1]['IV'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2]['IV']) ? $results['formation'][2]['IV'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3]['IV']) ? $results['formation'][3]['IV'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4]['IV']) ? $results['formation'][4]['IV'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- supérieur au Bac <i>(III, II, I)</i></td>
			<td class="number"><strong><?php echo isset($results['formation'][1]['III, II, I']) ? $results['formation'][1]['III, II, I'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2]['III, II, I']) ? $results['formation'][2]['III, II, I'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3]['III, II, I']) ? $results['formation'][3]['III, II, I'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4]['III, II, I']) ? $results['formation'][4]['III, II, I'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connu</td>
			<td class="number"><strong><?php echo isset($results['formation'][1]['NC']) ? $results['formation'][1]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][2]['NC']) ? $results['formation'][2]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][3]['NC']) ? $results['formation'][3]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['formation'][4]['NC']) ? $results['formation'][4]['NC'] : ''; ?></strong></td>
		</tr>


		<tr class="even">
			<td colspan="5">Ancienneté dans le dispositif, y compris anciens minima (RMI, API) (**) :</td>
		</tr>
		<tr class="odd">
			<td>- moins de 6 mois</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1]['moins de 6 mois']) ? $results['anciennete'][1]['moins de 6 mois'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2]['moins de 6 mois']) ? $results['anciennete'][2]['moins de 6 mois'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3]['moins de 6 mois']) ? $results['anciennete'][3]['moins de 6 mois'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['moins de 6 mois']) ? $results['anciennete'][4]['moins de 6 mois'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 6 mois à moins d'un an</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1]['6 mois et moins 1 an']) ? $results['anciennete'][1]['6 mois et moins 1 an'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2]['6 mois et moins 1 an']) ? $results['anciennete'][2]['6 mois et moins 1 an'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3]['6 mois et moins 1 an']) ? $results['anciennete'][3]['6 mois et moins 1 an'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['6 mois et moins 1 an']) ? $results['anciennete'][4]['6 mois et moins 1 an'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 1 an à moins de 2 ans</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1]['1 an et moins de 2 ans']) ? $results['anciennete'][1]['1 an et moins de 2 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2]['1 an et moins de 2 ans']) ? $results['anciennete'][2]['1 an et moins de 2 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3]['1 an et moins de 2 ans']) ? $results['anciennete'][3]['1 an et moins de 2 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['1 an et moins de 2 ans']) ? $results['anciennete'][4]['1 an et moins de 2 ans'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 2 ans à moins de 5 ans</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1]['2 ans et moins de 5 ans']) ? $results['anciennete'][1]['2 ans et moins de 5 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2]['2 ans et moins de 5 ans']) ? $results['anciennete'][2]['2 ans et moins de 5 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3]['2 ans et moins de 5 ans']) ? $results['anciennete'][3]['2 ans et moins de 5 ans'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['2 ans et moins de 5 ans']) ? $results['anciennete'][4]['2 ans et moins de 5 ans'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- 5 ans et plus</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1]['5 ans et plus']) ? $results['anciennete'][1]['5 ans et plus'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['5 ans et plus']) ? $results['anciennete'][2]['5 ans et plus'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3]['5 ans et plus']) ? $results['anciennete'][3]['5 ans et plus'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['5 ans et plus']) ? $results['anciennete'][4]['5 ans et plus'] : ''; ?></strong></td>
		</tr>
		<tr class="odd">
			<td>- non connue</td>
			<td class="number"><strong><?php echo isset($results['anciennete'][1]['NC']) ? $results['anciennete'][1]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][2]['NC']) ? $results['anciennete'][2]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][3]['NC']) ? $results['anciennete'][3]['NC'] : ''; ?></strong></td>
			<td class="number"><strong><?php echo isset($results['anciennete'][4]['NC']) ? $results['anciennete'][4]['NC'] : ''; ?></strong></td>
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