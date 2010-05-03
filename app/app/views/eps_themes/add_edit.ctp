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
<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout de thèmes';
    }
    else {
        $this->pageTitle = 'Édition de thèmes';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>
<?php
    ///
    echo $html->tag(
        'ul',
        implode(
            '',
            array(
                $html->tag( 'li', $html->link( 'Tout sélectionner', '#', array( 'onclick' => 'allCheckboxes( true ); return false;' ) ) ),
                $html->tag( 'li', $html->link( 'Tout désélectionner', '#', array( 'onclick' => 'allCheckboxes( false ); return false;' ) ) ),
            )
        )
    );
?>
    <?php echo $xform->create( 'EpTheme', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Thème 1: Demande de réorientation</legend>
                <?php
//                     echo $xform->input( 'Ep.id', array( 'label' => false, 'type' => 'hidden' ) );
                ?>
                <?php if (!empty( $demandereorient )):?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Motif de demande de réorientation</th>
                            <th>Commentaire</th>
                            <th>Urgent</th>
                            <th>Accord avec le bénéficiaire</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            foreach( $demandereorient as $i => $theme ) {
                                /*$pePe = Set::extract( $this->data, 'Demandereorient.Demandereorient' );
                                if( empty( $pePe ) ) {
                                    $pePe = array();
                                }*/
// debug($this->data);

                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $theme, 'Personne.qual' ).' '.Set::classicExtract( $theme, 'Personne.nom' ).' '.Set::classicExtract( $theme, 'Personne.prenom' ) ),
                                        h( Set::enum( Set::classicExtract( $theme, 'Demandereorient.motifdemreorient_id' ), $motifdemreorient ) ),
                                        h( Set::classicExtract( $theme, 'Demandereorient.commentaire' ) ),
                                        h( $html->boolean( Set::classicExtract( $theme, 'Demandereorient.urgent' ), false ) ),
                                        h( $html->boolean( Set::classicExtract( $theme, 'Demandereorient.accordbenef' ), false ) ),

                                        $xform->input( "Demandereorient.{$i}.id", array( 'type' => 'hidden', 'value' => $theme['Demandereorient']['id'] ) ).
                                        $xform->checkbox (
                                            //'Parcoursdetecte.Parcoursdetecte.'.$i,
                                            "Demandereorient.{$i}.ep_id",
                                            array(
                                                'value' => $this->params['pass'][0]
//                                                 'value' => $parcours['Parcoursdetecte']['id'],
//                                                 'id' => 'ParcoursdetecteParcoursdetecte'.$parcours['Parcoursdetecte']['id'],
//                                                 'checked' => in_array( $parcours['Parcoursdetecte']['id'], $paPa ),
//                                                 'class' => 'checkbox'
                                            )
                                        )

                                        /*$xform->checkbox(
                                            'Demandereorient.Demandereorient.'.$i,
                                            array(
                                                'value' => $theme['Demandereorient']['id'],
                                                'id' => 'PartepPartep'.$theme['Demandereorient']['id'],
                                                'checked' => in_array( $theme['Demandereorient']['id'], $pePe ),
                                                'class' => 'checkbox'
                                            )
                                        )*/
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }

                        ?>
                    </tbody>
                </table>
                <?php endif;?>
            </fieldset>
            <fieldset>
                <legend>Thème 2: Parcours détectés</legend>

                <?php if (!empty( $parcoursdetecte )):?>
                <table>
                    <thead>
                        <tr>
                            <th>Parcours créé</th>
                            <th>Date du transfert</th>
                            <th>Signalé</th>
                            <th>Commentaire</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            foreach( $parcoursdetecte as $i => $parcours ) {
                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $parcours, 'Orientstruct.Personne.qual' ).' '.Set::classicExtract( $parcours, 'Orientstruct.Personne.nom' ).' '.Set::classicExtract( $parcours, 'Orientstruct.Personne.prenom' ) ),
                                        h( Set::classicExtract( $parcours, 'Parcoursdetecte.datetransref' ) ),
                                        h( $html->boolean( Set::classicExtract( $parcours, 'Parcoursdetecte.signale' ), false ) ),
                                        h( Set::classicExtract( $parcours, 'Parcoursdetecte.commentaire' ) ),

                                        $xform->input( "Parcoursdetecte.{$i}.id", array( 'type' => 'hidden', 'value' => $parcours['Parcoursdetecte']['id'] ) ).
                                        $xform->checkbox (
                                            "Parcoursdetecte.{$i}.ep_id",
                                            array(
                                                'value' => $this->params['pass'][0]
                                            )
                                        )
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }

                        ?>
                    </tbody>
                </table>
                <?php endif;?>
            </fieldset>
        </div>

        <?php echo $xform->submit( 'Enregistrer' );?>
    <?php echo $xform->end();?>
    <?php echo $default->button(
            'back',
            array(
                'controller' => 'eps',
                'action'     => 'ordre',
                $epId
            ),
            array(
                'id' => 'Back'
            )
        );
?>