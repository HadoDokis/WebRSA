<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php require_once( 'filtre.ctp' );?>

<?php if( !empty( $this->data ) ):?>
    <?php if( empty( $cohorte ) ):?>
        <p class="notice">Tous les allocataires ont été orientés.</p>
    <?php else:?>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Commune</th>
                    <th>Nom prenom</th>
                    <th>Date demande</th>
                    <th>Date ouverture de droit</th>
                    <th>Service instructeur</th>
                    <th>PréOrientation</th>
                    <th>Orientation</th>
                    <th>Structure</th>
                    <th>Décision</th>
                    <th>Date proposition</th>
                    <th>Date dernier CI</th>
                    <th class="action">Action</th>
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
                                    <td>'.h( $personne['Adresse']['codepos'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Canton</th>
                                    <td>'.h( $personne['Adresse']['canton'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date de fin de droit</th>
                                    <td>'.h( date_short( $personne['Situationdossierrsa']['dtclorsa'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Motif de fin de droit</th>
                                    <td>'.h( $personne['Situationdossierrsa']['moticlorsa'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( $personne['Adresse']['locaadr'] ),
                                h( $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] ),
                                h( date_short( $personne['Dossier']['dtdemrsa'] ) ),
                                h( date_short( $personne['Dossier']['dtdemrsa'] ) ), // FIXME: voir flux instruction
                                h( $typeserins[$personne['Suiviinstruction']['typeserins']] ),
                                /*h(
                                    implode(
                                        ' ',
                                        array(
                                            $personne['Suiviinstruction']['numdepins'],
                                            $personne['Suiviinstruction']['typeserins'],
                                            $personne['Suiviinstruction']['numcomins'],
                                            $personne['Suiviinstruction']['numagrins']
                                        )
                                    )
                                ),*/
                                h( isset( $typesOrient[$personne['Orientstruct']['propo_algo']] ) ? $typesOrient[$personne['Orientstruct']['propo_algo']] : null),
                                h( $typesOrient[$personne['Orientstruct']['typeorient_id']] ),
                                h( $personne['Orientstruct']['Structurereferente']['lib_struc'] ),
                                h( $personne['Orientstruct']['statut_orient'] ),
                                h( date_short( $personne['Orientstruct']['date_propo'] ) ),
                                h( date_short( $personne['Contratinsertion']['dd_ci'] ) ),
                                $html->printLink(
                                    'Imprimer la notification',
                                    array( 'controller' => 'gedooos', 'action' => 'notification_structure', $personne['Personne']['id'] ),
//                                     !empty( $personne['Orientstruct']['personne_id'] ),
                                    $permissions->check( 'gedooos', 'notification_structure' )
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
    <?php endif;?>
<?php endif;?>