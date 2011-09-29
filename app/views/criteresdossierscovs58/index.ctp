<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'Recherche par Dossiers COVs';
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

        <fieldset>
            <legend>Filtrer par Dossier COV</legend>
            <?php
                $listThemes = array();
                foreach( $themes as $i => $theme ){
                    $listThemes[$i] = __d( 'dossiercov58',  'ENUM::THEMECOV::'.$themes[$i], true );
                }


                echo $default2->subform(
                    array(
                        'Dossiercov58.etapecov' => array( 'type' => 'select', 'options' => $options['etapecov'] ),
                        'Dossiercov58.themecov58_id' => array( 'type' => 'select', 'options' => $listThemes ),
                        'Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                        'Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                        'Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),

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
                    <th>'.$xpaginator->sort( __d( 'dossiercov58', 'Dossiercov58.etapecov', true ), 'Dossiercov58.etapecov').'</th>
                    <th>Action</th>
                </tr></thead><tbody>';

                foreach( $dossierscovs58 as $dossiercov58 ) {
                    echo '<tr>
                        <td>'.h( $dossiercov58['Dossier']['numdemrsa'] ).'</td>
                        <td>'.h( $dossiercov58['Personne']['nom_complet'] ).'</td>
                        <td>'.__d( 'dossiercov58',  'ENUM::THEMECOV::'.$themes[$dossiercov58['Dossiercov58']['themecov58_id']], true ).'</td>
                        <td>'.h( Set::enum( Set::classicExtract( $dossiercov58, 'Dossiercov58.etapecov' ),  $options['etapecov'] ) ).
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