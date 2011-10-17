<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $isRecours = Set::classicExtract( $this->params, 'named.recours' );

    if( $isRecours ) {
        $this->pageTitle = 'Modification de la liste des APREs en Recours pour le comité d\'examen';
    }
    else {
        $this->pageTitle = 'Modification de la liste des APREs pour le comité d\'examen';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>
    <?php
        ///
        echo $xhtml->tag(
            'ul',
            implode(
                '',
                array(
                    $xhtml->tag( 'li', $xhtml->link( 'Tout sélectionner', '#', array( 'onclick' => 'allCheckboxes( true ); return false;' ) ) ),
                    $xhtml->tag( 'li', $xhtml->link( 'Tout désélectionner', '#', array( 'onclick' => 'allCheckboxes( false ); return false;' ) ) ),
                )
            )
        );
    ?>
    <?php if( empty( $apres ) ):?>
        <p class="notice">Aucune demande d'APRE <?php echo ( Set::classicExtract( $this->params, 'named.recours' ) ? 'en Recours ' : '' );?>présente.</p>
    <?php else:?>
    <?php echo $xform->create( 'ApreComiteapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>APREs à traiter durant le comité</legend>
                    <?php echo $xform->input( 'Comiteapre.id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>
                <table>
                    <thead>
                        <tr>
                            <th>N° APRE</th>
                            <th>Nom/Prénom</th>
                            <th>Date demande APRE</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
// debug($apres);

                            foreach( $apres as $i => $apre ) {
// debug($apre);
                                $apreApre = Set::extract( $this->data, 'Apre.Apre' );
                                if( empty( $apreApre ) ) {
                                    $apreApre = array();
                                }

                                echo $xhtml->tableCells(
                                    array(
                                        h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                                        h( Set::classicExtract( $apre, 'Personne.qual' ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
                                        h( $locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ) ),

                                        $xform->checkbox( 'Apre.Apre.'.$i, array( 'value' => Set::classicExtract( $apre, 'Apre.id' ), 'id' => 'ApreApre'.Set::classicExtract( $apre, 'Apre.id' ), 'checked' => in_array( Set::classicExtract( $apre, 'Apre.id' ), $apreApre ), 'class' => 'checkbox' ) ),
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }

                        ?>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <?php echo $xform->submit( 'Enregistrer' );?>
    <?php echo $xform->end();?>


        <?php endif;?>
	<?php
		echo $default->button(
		    'back',
		    array(
		        'controller' => 'comitesapres',
		        'action'     => 'view',
		        $comiteapre_id
		    ),
		    array(
		        'id' => 'Back'
		    )
		);
	?>        
<script type="text/javascript">
//<![CDATA[
    function allCheckboxes( checked ) {
        $$('input.checkbox').each( function ( checkbox ) {
            $( checkbox ).checked = checked;
        } );
        return false;
    }
//]]>
</script>
<div class="clearer"><hr /></div>