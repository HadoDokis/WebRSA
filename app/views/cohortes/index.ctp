<h1>Gestion des nouvelles demandes</h1>

<?php echo $form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
    <table class="tooltips_oupas">
        <thead>
            <tr>
                <th>Commune</th>
                <th>Date demande</th>
                <th>Date ouverture de droit</th>
                <th>Nom prenom</th>
                <th>Service instructeur</th>
                <th>PréOrientation</th>
                <th class="action">Orientation</th>
                <th class="action">Décision</th>
                <th>Statut</th>
                <th class="innerTableHeader">Informations complémentaires</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $cohorte as $index => $personne ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>N° de dossier</th>
                                <td>'.h( $personne['Dossier']['numdemrsa'] ).'</td>
                            </tr>
                            <tr>
                                <th>Date naissance</th>
                                <td>'.h( date_short( $personne['Personne']['dtnai'] ) ).'</td>
                            </tr>
                            <tr>
                                <th>Numéro CAF</th>
                                <td>'.h( $personne['Dossier']['matricule'] ).'</td>
                            </tr>
                            <tr>
                                <th>NIR</th>
                                <td>'.h( $personne['Personne']['nir'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code postal</th>
                                <td>'.h( $personne['Foyer']['Adresse']['codepos'] ).'</td>
                            </tr>
                            <tr>
                                <th>Canton</th>
                                <td>'.h( $personne['Foyer']['Adresse']['canton'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';

                    echo $html->tableCells(
                        array(
                            h( $personne['Foyer']['Adresse']['locaadr'] ),
                            h( date_short( $personne['Dossier']['dtdemrsa'] ) ),
                            h( date_short( $personne['Dossier']['dtdemrsa'] ) ), // FIXME: voir flux instruction
                            h( $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] ),
                            h(
                                implode(
                                    ' ',
                                    array(
                                        $personne['Suiviinstruction']['numdepins'],
                                        $personne['Suiviinstruction']['typeserins'],
                                        $personne['Suiviinstruction']['numcomins'],
                                        $personne['Suiviinstruction']['numagrins']
                                    )
                                )
                            ),
                            h( $personne['Orientstruct']['propo_algo'] ),
                            $form->input( 'Orientation.'.$index.'.id', array( 'label' => false, 'type' => 'select', 'options' => $services, 'value' => $personne['Dossier']['preorientation_id'] ) ),
                            $form->input( 'Orientation.'.$index.'.decision', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => array( 'valider' => 'Valider', 'attente' => 'En attente' ), 'value' => 'valider' ) ),
                            h( $personne['Dossier']['statut'] ),
                            $innerTable
//                             $html->editLink(
//                                 'Éditer le dossier',
//                                 array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] )
//                             ),
//                             $html->printLink(
//                                 'Imprimer le dossier',
//                                 array( 'controller' => 'gedooos', 'action' => 'notification_structure', $dossier['Contratinsertion']['id'] )
//                             )
                        ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>

    <?php echo $form->submit( 'Validation de la liste' );?>
<?php echo $form->end();?>