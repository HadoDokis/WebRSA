<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php require_once( 'filtre.ctp' );?>

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
                                h( $personne['Orientstruct']['propo_algo_texte'] ).
                                    $form->input( 'Orientstruct.'.$index.'.propo_algo', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Orientstruct']['propo_algo'] ) ).
                                    $form->input( 'Orientstruct.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $personne['Orientstruct']['id'] ) ),
                                $form->input( 'Orientstruct.'.$index.'.typeorient_id', array( 'label' => false, 'type' => 'select', 'options' => $typesOrient, 'value' => $personne['Orientstruct']['propo_algo'] ) ),
                                $form->input( 'Orientstruct.'.$index.'.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structuresReferentes, 'empty' => true ) ),
                                $form->input( 'Orientstruct.'.$index.'.statut_orient', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => array( 'Orienté' => 'Valider', 'En attente' => 'En attente' ), 'value' => 'Orienté' ) ),
                                h( $personne['Dossier']['statut'] ),
                                $innerTable
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