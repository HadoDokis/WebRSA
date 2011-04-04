<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'CER';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un CER';
    }
    else {
        $this->pageTitle = 'Édition d\'un CER';
    }
?>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'Propocontratinsertioncov58ReferentId', 'Propocontratinsertioncov58StructurereferenteId' );
    });
</script>

<script type="text/javascript">
    function checkDatesToRefresh() {
        if( ( $F( 'Propocontratinsertioncov58DdCiMonth' ) ) && ( $F( 'Propocontratinsertioncov58DdCiYear' ) ) && ( $F( 'Propocontratinsertioncov58DureeEngag' ) ) ) {
            var correspondances = new Array();

            <?php
                $duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
                foreach( $$duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

            setDateIntervalCer( 'Propocontratinsertioncov58DdCi', 'Propocontratinsertioncov58DfCi', correspondances[$F( 'Propocontratinsertioncov58DureeEngag' )], false );
        }
    }

    document.observe( "dom:loaded", function() {
        Event.observe( $( 'Propocontratinsertioncov58DdCiDay' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'Propocontratinsertioncov58DdCiMonth' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'Propocontratinsertioncov58DdCiYear' ), 'change', function() {
            checkDatesToRefresh();
        } );

        Event.observe( $( 'Propocontratinsertioncov58DureeEngag' ), 'change', function() {
            checkDatesToRefresh();
//             alert($F( 'Propocontratinsertioncov58DureeEngag' ));
        } );

    });


</script>
<script type="text/javascript">
    document.observe( "dom:loaded", function() {


        <?php
        $ref_id = Set::extract( $this->data, 'Propocontratinsertioncov58.referent_id' );
            echo $ajax->remoteFunction(
                array(
                    'update' => 'StructurereferenteRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxstruct',
                            Set::extract( $this->data, 'Propocontratinsertioncov58.structurereferente_id' )
                        ),
                        true
                    )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ReferentRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxref',
                            Set::extract( $this->data, 'Propocontratinsertioncov58.referent_id' )
                        ),
                        true
                    )
                )
            ).';';
        ?>
    } );
</script>


<?php
    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }
?>
<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {

            echo $form->create( 'Propocontratinsertioncov58', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Propocontratinsertioncov58.id', array( 'type' => 'hidden', 'value' => '' ) );

            echo $form->input( 'Propocontratinsertioncov58.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
            echo $form->input( 'Propocontratinsertioncov58.rg_ci', array( 'type' => 'hidden'/*, 'value' => '' */) );
            echo '</div>';

        }
        else {
            echo $form->create( 'Propocontratinsertioncov58', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Propocontratinsertioncov58.id', array( 'type' => 'hidden' ) );

            echo $form->input( 'Propocontratinsertioncov58.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );

            echo $form->input( 'Propocontratinsertioncov58.dossiercov58_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>
<fieldset>

    <fieldset>
        <legend>RÉFÉRENT UNIQUE</legend>
        <table class="wide noborder">
            <tr>
                <td class="noborder">
                    <strong>Organisme chargé de l'instruction du dossier :</strong>
                    <?php echo $xform->input( 'Propocontratinsertioncov58.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structures, 'selected' => $struct_id, 'empty' => true ) );?>
                    <?php echo $ajax->observeField( 'Propocontratinsertioncov58StructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?> 
                </td>
                <td class="noborder">
                    <strong>Nom du référent unique :</strong>
                    <?php echo $xform->input( 'Propocontratinsertioncov58.referent_id', array('label' => false, 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $struct_id.'_'.$referent_id ) );?>
                    <?php echo $ajax->observeField( 'Propocontratinsertioncov58ReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?> 
                </td>
            </tr>
            <tr>
                <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

                <td class="wide noborder"><div id="ReferentRef"></div></td>
            </tr>
        </table>
    </fieldset>

    <fieldset>
        <legend>CARACTÉRISTIQUES DU PRÉSENT CONTRAT</legend>

        <?php echo $xform->input( 'Propocontratinsertioncov58.num_contrat', array( 'label' => 'Type de contrat' , 'type' => 'select', 'options' => $options['num_contrat'], 'empty' => true, 'value' => $tc ) );?>

        <table class="nbrCi wide noborder">
            <tr class="nbrCi">
                <td class="noborder">Nombre de renouvellements </td>
                <td class="noborder"> <?php echo $nbrCi;?> </td>
            </tr>
        </table>

        <?php echo $xform->input( 'Propocontratinsertioncov58.dd_ci', array( 'label' => __d( 'propocontratinsertioncov58', 'Propocontratinsertioncov58.dd_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => false ) );?>
        <?php echo $xform->input( 'Propocontratinsertioncov58.duree_engag', array( 'label' => __d( 'propocontratinsertioncov58', 'Propocontratinsertioncov58.duree_engag', true ), 'type' => 'select', 'options' => $duree_engag_cg58, 'empty' => true ) );?>
        <?php echo $xform->input( 'Propocontratinsertioncov58.df_ci', array( 'label' => __d( 'propocontratinsertioncov58', 'Propocontratinsertioncov58.df_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true ) ) ;?>

    </fieldset>
        <?php echo $xform->input( 'Propocontratinsertioncov58.datedemande', array( 'label' => __d( 'propocontratinsertioncov58', 'Propocontratinsertioncov58.date_saisi_ci', true ), 'type' => 'hidden'/*, 'value' => $this->data['Propocontratinsertioncov58']['dd_ci'] */ ) ) ;?>
</fieldset>

    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
<script type="text/javascript">
    Event.observe( $( 'Propocontratinsertioncov58DdCiDay' ), 'change', function( event ) {
        $( 'Propocontratinsertioncov58DatedemandeDay' ).value = $F( 'Propocontratinsertioncov58DdCiDay' );
    } );
    Event.observe( $( 'Propocontratinsertioncov58DdCiMonth' ), 'change', function( event ) {
        $( 'Propocontratinsertioncov58DatedemandeMonth' ).value = $F( 'Propocontratinsertioncov58DdCiMonth' );
    } );
    Event.observe( $( 'Propocontratinsertioncov58DdCiYear' ), 'change', function( event ) {
        $( 'Propocontratinsertioncov58DatedemandeYear' ).value = $F( 'Propocontratinsertioncov58DdCiYear' );
    } );
</script>