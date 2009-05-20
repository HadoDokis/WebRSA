<h1>Gestion des nouvelles demandes</h1>

<?php echo $form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
    <table class="tooltips_oupas">
        <thead>
            <tr>
                <th>Date de demande</th>
                <th>Nom / Prénom</th>
                <th>N° CAF</th>
                <th>Pré-orientation</th>
                <th class="action">Orientation</th>
                <th>Structures référentes</th>
                <th class="action">Décision</th>
                <th colspan="2" class="action">Actions</th>
                <th class="innerTableHeader">Informations complémentaires</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $dossiers as $index => $dossier ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>N° de dossier</th>
                                <td>'.h( $dossier['Dossier']['numdemrsa'] ).'</td>
                            </tr>
                            <tr>
                                <th>Date naissance</th>
                                <td>'.h( date_short( $dossier['Personne']['dtnai'] ) ).'</td>
                            </tr>
                            <tr>
                                <th>NIR</th>
                                <td>'.h( $dossier['Personne']['nir'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code postal</th>
                                <td>'.h( $dossier['Adresse']['codepos'] ).'</td>
                            </tr>
                            <tr>
                                <th>Ville</th>
                                <td>'.h( $dossier['Adresse']['locaadr'] ).'</td>
                            </tr>
                            <tr>
                                <th>Canton</th>
                                <td>'.h( $dossier['Adresse']['canton'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';

                    echo $html->tableCells(
                        array(
                            h( strftime( '%d/%m/%Y', strtotime( $dossier['Dossier']['dtdemrsa'] ) ) ),

                            h( $dossier['Personne']['nom'].' '.$dossier['Personne']['prenom'] ),

                            h( $dossier['Dossier']['matricule'] ),

                            h( $dossier['Dossier']['preorientation'] ),

                            $form->input( 'Orientation.'.$index.'.id', array( 'label' => false, 'type' => 'select', 'options' => $services, 'value' => $dossier['Dossier']['preorientation_id'] ) ),

                            //h( $dossier['Structurereferente']['lib_struc'] ),
                            $form->input( 'Structurereferente.lib_struc', array( 'label' => false, 'type' => 'select', 'options' => $options2, /*'value' => $dossier['Structurereferente']['lib_struc']*/ ) ),

                            $form->input( 'Orientation.'.$index.'.decision', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => array( 'valider' => 'Valider', 'attente' => 'En attente' ), 'value' => 'valider' ) ),

                            $html->editLink(
                                'Éditer le dossier',
                                array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] )
                            ),
                            $html->printLink(
                                'Imprimer le dossier',
                                array( 'controller' => 'gedooos', 'action' => 'notification_structure', $dossier['Contratinsertion']['id'] )
                            ),
                            array( $innerTable, array( 'class' => 'innerTableCell' ) ),
                        ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
<!--
    [Dossier] => Array
        (
            [id] => 1
            [numdemrsa] => AJ8ID907T5
            [dtdemrsa] => 2009-03-15
            [etatdosrsa] => 0
            [dtrefursa] =>
            [motisusversrsa] =>
            [ddsusversrsa] =>
            [details_droits_rsa_id] =>
            [avis_pcg_id] =>
            [organisme_id] =>
            [acompte_rsa_id] =>
        )

    [Foyer] => Array
        (
            [id] => 1
            [dossier_rsa_id] => 1
            [sitfam] => CEL
            [ddsitfam] => 1979-01-24
            [typeocclog] => HGP
            [mtvallocterr] => 0
            [mtvalloclog] => 0
            [contefichliairsa] =>
        )

)
-->