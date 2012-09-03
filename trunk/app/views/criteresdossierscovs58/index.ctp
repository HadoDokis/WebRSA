<?php
    $this->pageTitle = 'Recherche par Dossiers COVs';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    echo '<ul class="actionMenu"><li>'.$xhtml->link(
        $xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'Cov58Datecommission', $( 'Cov58DatecommissionFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php echo $xform->create( 'Criteredossiercov58', array( 'type' => 'post', 'action' => 'index', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

        <?php  echo $xform->input( 'Criteredossiercov58.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

        <?php
			echo $search->blocAllocataire();
			echo $search->blocAdresse( $mesCodesInsee, $cantons );
		?>
		<fieldset>
			<legend>Recherche par dossier</legend>
			<?php
				echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
				echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );

				$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
				echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
				echo $search->etatdosrsa($etatdosrsa);
			?>
		</fieldset>
        <fieldset>
            <legend>Filtrer par Dossier COV</legend>
            <?php
                $listThemes = array();
                foreach( $themes as $i => $theme ){
                    $listThemes[$i] = __d( 'dossiercov58',  'ENUM::THEMECOV::'.$themes[$i], true );
                }


                echo $default2->subform(
                    array(
                        'Passagecov58.etatdossiercov' => array( 'type' => 'select', 'options' => $options['etatdossiercov'] ),
                        'Dossiercov58.themecov58_id' => array( 'type' => 'select', 'options' => $listThemes )
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>

        </fieldset>
		<fieldset>
			<legend>Filtrer par Commission</legend>
			<?php echo $default2->subform(
				array(
					'Cov58.sitecov58_id' => array( 'type' => 'select', 'option' => $sitescovs58, 'empty' => true )
				)
			); ?>
		</fieldset>
			<?php echo $xform->input( 'Cov58.datecommission', array( 'label' => 'Filtrer par date de Commission', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$datecommission_from = Set::check( $this->data, 'Cov58.datecommission_from' ) ? Set::extract( $this->data, 'Cov58.datecommission_from' ) : strtotime( '-1 week' );
					$datecommission_to = Set::check( $this->data, 'Cov58.datecommission_to' ) ? Set::extract( $this->data, 'Cov58.datecommission_to' ) : strtotime( 'now' );
				?>
				<?php echo $xform->input( 'Cov58.datecommission_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datecommission_from ) );?>
				<?php echo $xform->input( 'Cov58.datecommission_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datecommission_to ) );?>
		</fieldset>
    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'Dossiercov58', $this->passedArgs ); ?>
<?php echo $pagination;?>
<?php if( isset( $dossierscovs58 ) ):?>
    <?php if( is_array( $dossierscovs58 ) && count( $dossierscovs58 ) > 0  ):?>
        <?php
            echo '<table><thead>';
                echo '<tr>
                    <th>'.$xpaginator->sort( __d( 'dossier', 'Dossier.numdemrsa', true ), 'Dossier.numdemrsa' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'personne', 'Personne.nom_complet', true ), 'Personne.nom_complet' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'dossiercov58', 'Dossiercov58.themecov58_id', true ), 'Dossiercov58.themecov58_id' ).'</th>
					<th>'.$xpaginator->sort( __d( 'cov58', 'Cov58.datecommission', true ), 'Cov58.datecommission' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'passagecov58', 'Passagecov58.etatdossiercov', true ), 'Passagecov58.etatdossiercov').'</th>
                    <th>Action</th>
                </tr></thead><tbody>';

                foreach( $dossierscovs58 as $dossiercov58 ) {
                    echo '<tr>
                        <td>'.h( $dossiercov58['Dossier']['numdemrsa'] ).'</td>
                        <td>'.h( $dossiercov58['Personne']['nom_complet'] ).'</td>
                        <td>'.__d( 'dossiercov58',  'ENUM::THEMECOV::'.$themes[$dossiercov58['Dossiercov58']['themecov58_id']], true ).'</td>
                        <td>'.h( date('d-m-Y à h:i', strtotime($dossiercov58['Cov58']['datecommission'])) ).'</td>
                        <td>'.h( Set::enum( Set::classicExtract( $dossiercov58, 'Passagecov58.etatdossiercov' ),  $options['etatdossiercov'] ) ).
                        '<td>'.$xhtml->link( 'Voir', array( 'controller' => 'personnes', 'action' => 'view', $dossiercov58['Personne']['id'] ) ).'</td>
                    </tr>';
                }
            echo '</tbody></table>';
    ?>
    <ul class="actionMenu">
        <li><?php
            echo $xhtml->printLinkJs(
                'Imprimer le tableau',
                array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
            );
        ?></li>

    </ul>
<?php echo $pagination;?>

    <?php else:?>
        <?php echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );?>
    <?php endif;?>
<?php endif;?>