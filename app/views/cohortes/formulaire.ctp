<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php require_once( 'filtre.ctp' );?>

<?php if( !empty( $this->data ) ):?>
    <?php if( empty( $cohorte ) ):?>
        <p class="notice">Aucune demande dans la cohorte.</p>
    <?php else:?>
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
                        <th class="action">Structure</th>
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
                                        <td>'.h( $personne['Adresse']['codepos'] ).'</td>
                                    </tr>
                                    <tr>
                                        <th>Canton</th>
                                        <td>'.h( $personne['Adresse']['canton'] ).'</td>
                                    </tr>
                                    <tr>
                                        <th>Date de fin de droit</th>
                                        <td>'.h( $personne['Situationdossierrsa']['dtclorsa'] ).'</td>
                                    </tr>
                                    <tr>
                                        <th>Motif de fin de droit</th>
                                        <td>'.h( $personne['Situationdossierrsa']['moticlorsa'] ).'</td>
                                    </tr>
                                </tbody>
                            </table>';

                            $statut_orient = Set::extract( $this->data, 'Orientstruct.'.$index.'.statut_orient' );
                            echo $html->tableCells(
                                array(
                                    h( $personne['Adresse']['locaadr'] ),
                                    h( date_short( $personne['Dossier']['dtdemrsa'] ) ),
                                    h( date_short( $personne['Dossier']['dtdemrsa'] ) ), // FIXME: voir flux instruction
//                                     h( date_short( $personne['Dossier']['Situationdossierrsa']['dtclorsa'] ) ),
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
                                    h( $personne['Orientstruct']['propo_algo_texte'] ).
                                        $form->input( 'Orientstruct.'.$index.'.propo_algo', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Orientstruct']['propo_algo'] ) ).
                                        $form->input( 'Orientstruct.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Orientstruct']['id'] ) ).
                                        $form->input( 'Orientstruct.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Foyer']['dossier_rsa_id'] ) ),
                                    $form->input( 'Orientstruct.'.$index.'.typeorient_id', array( 'label' => false, 'type' => 'select', 'options' => $typesOrient, 'value' => $personne['Orientstruct']['propo_algo'] ) ),
                                    $form->input( 'Orientstruct.'.$index.'.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structuresReferentes, 'empty' => true ) ),
                                    $form->input( 'Orientstruct.'.$index.'.statut_orient', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => array( 'Orienté' => 'A valider', 'En attente' => 'En attente' ), 'value' => ( !empty( $statut_orient ) ? $statut_orient : 'Orienté' ) ) ),
                                    h( $personne['Dossier']['statut'] ),
                                    array( $innerTable, array( 'class' => 'innerTableCell' ) ),
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
    <?php endif;?>
<?php endif;?>