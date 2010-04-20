<!--/************************************************************************/ -->
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect(
                'Aideapre66Typeaideapre66Id',
                'Aideapre66Themeapre66Id'
            );

            observeDisableFieldsOnValue(
                'Aideapre66VersementTIE',
                [
                    'Aideapre66AutorisationversO',
                    'Aideapre66AutorisationversN'
                ],
                'TIE',
                false
            );

            observeDisableFieldsOnValue(
                'Aideapre66VersementDEM',
                [
                    'Aideapre66AutorisationversO',
                    'Aideapre66AutorisationversN'
                ],
                'DEM',
                true
            );

            observeDisableFieldsetOnRadioValue(
                'Apre',
                'data[Aideapre66][autorisationvers]',
                $( 'Soussigne' ),
                'O',
                false,
                true
            );


        });
    </script>

<!--/************************************************************************/ -->


<fieldset>
    <legend><strong>Aide demandée</strong></legend>
    <?php

        $Aideapre66Id = Set::classicExtract( $this->data, 'Aideapre66.id' );
        $Fraisdeplacement66Id = Set::classicExtract( $this->data, 'Fraisdeplacement66.id' );
        $ApreId = Set::classicExtract( $this->data, "{$this->modelClass}.id" );


        if( $this->action == 'edit' && !empty( $Aideapre66Id ) ) {
            echo $form->input( 'Aideapre66.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Fraisdeplacement66.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Aideapre66.apre_id', array( 'type' => 'hidden', 'value' => $ApreId ) );
            echo $form->input( 'Fraisdeplacement66.aideapre66_id', array( 'type' => 'hidden', 'value' => $Aideapre66Id ) );

        }

        echo $default->subform(
            array(
                //'Aideapre66.apre_id' => array( 'type' => 'hidden', 'value' => Set::classicExtract( $this->data, "{$this->modelClass}.id" ) ),
                'Aideapre66.themeapre66_id' => array( 'options' => $themes ),
                'Aideapre66.typeaideapre66_id' => array( 'options' => $typesaides ),
                'Aideapre66.motivdem',
                'Aideapre66.montantaide' => array( 'type' => 'text' ),
                'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces à fournir', 'multiple' => 'checkbox' , 'options' => $pieceliste, 'empty' => false ),
                'Aideapre66.virement' => array( 'domain' => 'aideapre66', 'type' => 'radio', 'options' => $options['virement'], 'separator' => '<br />' ),
                'Aideapre66.versement' => array( 'domain' => 'aideapre66', 'type' => 'radio', 'options' => $options['versement'], 'separator' => '<br />' ),
                'Aideapre66.autorisationvers' => array( 'legend' => 'Autorisation de paiement au tiers', 'domain' => 'aideapre66', 'options' => $options['autorisationvers'], 'type' => 'radio', 'separator' => '<br />' )
            ),
            array(
                'options' => $options
            )
        );

        echo $html->tag(
            'fieldset',
            'Je soussigné '. '<strong>'.Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'].'</strong>'.' souhaite que mon aide ( si elle est acceptée ) soit versée sur le compte du '.$default->subform( 'Aideapre66.creancier', array( 'rows' => 2 ) ),
            array( 'id' => 'Soussigne' )
        );

        echo $default->subform(
            array(
                'Aideapre66.datedemande'
            )
        );
    ?>
</fieldset>
<?php
    $apresPassees = Set::extract( $listApres, '/Aideapre66' );
//     debug($listApres);
?>
<fieldset>
    <legend>Attributions antérieures de l'APRE</legend>
    <?php if( !empty( $apresPassees ) ):?>
        <table>
            <thead>
                <tr>
                    <th>Date de demande de l'APRE</th>
                    <th>Thème de l'aide</th>
                    <th>Type d'aide</th>
                    <th>Montant accordé</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $listApres as $i => $liste ){
                        echo $html->tableCells(
                            array(
                                h( date_short( Set::classicExtract( $liste, "{$this->modelClass}.datedemandeapre" ) ) ),
                                h( Set::enum( Set::classicExtract( $liste, 'Aideapre66.themeapre66_id' ), $themes ) ),
                                h( Set::enum( Set::classicExtract( $liste, 'Aideapre66.typeaideapre66_id' ), $nomsTypeaide ) ),
                                h( $locale->money( Set::classicExtract( $liste, 'Aideapre66.montantaide' ) ) ),
                            )
                        );
                    }
                ?>
            </tbody>
        </table>
    <?php else:?>
        <p class="notice">Aucune APRE antérieure présente pour cette personne</p>
    <?php endif;?>
</fieldset>
<fieldset>
    <legend><strong>Calcul des frais de déplacements, d'hébergement et de restauration</strong></legend>
    <?php
        echo $default->subform(
            array(
                'Fraisdeplacement66.lieuresidence',
                'Fraisdeplacement66.destination',
            )
        );
    ?>

    <table>
        <thead>
            <tr>
                <th colspan="2">Véhicule personnel</th>
                <th colspan="2">Transport public</th>
                <th colspan="2">Hébergement</th>
                <th colspan="2">Repas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="fraisdepct">Nb km </td>
                <td class="fraisdepct"> <?php echo $xform->input( 'Fraisdeplacement66.nbkmvoiture', array( 'label' => false ) );?></td>
                <td class="fraisdepct">Nb trajet</td>
                <td class="fraisdepct"> <?php echo $form->input( 'Fraisdeplacement66.nbtrajettranspub', array( 'label' => false ) );?></td>
                <td class="fraisdepct">Nb nuitées</td>
                <td class="fraisdepct"> <?php echo $form->input( 'Fraisdeplacement66.nbnuithebergt', array( 'label' => false ) );?></td>
                <td class="fraisdepct">Nb repas</td>
                <td class="fraisdepct"> <?php echo $form->input( 'Fraisdeplacement66.nbrepas', array( 'label' => false ) );?></td>
            </tr>
            <tr>
                <td class="fraisdepct"> Nb trajet </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbtrajetvoiture', array( 'label' => false ) );?></td>
                <td class="fraisdepct"> Prix billet </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.prixbillettranspub', array( 'label' => false ) );?></td>
                <td class="fraisdepct"> Forfait "nuitées"</td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.forfaithebergt', array( 'label' => false/*, 'value' => '23'*/ ) );?></td>
                <td class="fraisdepct"> Forfait "Repas"</td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.forfaitrepas', array( 'label' => false/*, 'value' => '3.81'*/ ) );?></td>
            </tr>
            <tr>
                <td class="fraisdepct"> Nb total km </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbtotalkm', array( 'label' => false ) );?></td>
                <td class="fraisdepct"> Total </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.totaltranspub', array( 'label' => false ) );?></td>
                <td class="fraisdepct"> Total </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.totalhebergt', array( 'label' => false ) );?></td>
                <td class="fraisdepct"> Total </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.totalrepas', array( 'label' => false ) );?></td>
            </tr>
            <tr>
                <td class="fraisdepct"> Forfait "Km" </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.forfaitvehicule', array( 'label' => false/*, 'value' => '0.20'*/ ) );?></td>
            </tr>
            <tr>
                <td class="fraisdepct"> Total </td>
                <td class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.totalvehicule', array( 'label' => false ) ); ?> </td>
            </tr>
        </tbody>
    </table>
</fieldset>

<fieldset class="aere">
    <legend><strong>Observations du référent</strong></legend>
    <?php
        echo $xform->input(  "{$this->modelClass}.avistechreferent", array( 'domain' => 'apre', 'label' => false ) );
    ?>
</fieldset>
<fieldset>
    <?php
        echo $default->subform(
            array(
                'Aideapre66.motifrejet'
            )
        );
    ?>
</fieldset>
<fieldset>
    <legend><strong>Administration</strong></legend>
    <fieldset>
        <?php
            echo $default->subform(
                array(
                    'Aideapre66.montantpropose' => array( 'type' => 'text' ),
                    'Aideapre66.datemontantpropose'
                )
            );
        ?>
    </fieldset>
    <fieldset>
        <legend><strong>Décision et engagement financier de l'équipe de direction</strong></legend>
        <?php
            echo $default->subform(
                array(
                    'Aideapre66.decisionapre' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['decisionapre'], 'separator' => '<br />' ),
                    'Aideapre66.montantaccorde' => array( 'type' => 'text' ),
                    'Aideapre66.datemontantaccorde'
                ),
                array(
                    'class' => 'fraisdepct'
                )
            );
        ?>
    </fieldset>
</fieldset>
<fieldset class="loici">
    <p>
        Un formulaire de demande par type d'aide demandée. Il doit être établi par un référent, pour Pôle Emploi en son absence par un prescripteur habilité.
    </p>
</fieldset>