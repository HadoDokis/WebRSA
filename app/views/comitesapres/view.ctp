<?php $this->pageTitle = 'Comité d\'examen pour l\'APRE';?>
<h1>Détails Comité d'examen</h1>
<?php if( $permissions->check( 'comitesapres', 'add' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->editLink(
                'Modifier Comité',
                array( 'controller' => 'comitesapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) )
            ).' </li>';
        ?>
    </ul>
<?php endif;?>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'Date du comité');?></th>
                    <td><?php echo date_short( Set::classicExtract( $comiteapre, 'Comiteapre.datecomite' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Heure du comité' );?></th>
                    <td><?php echo $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'Comiteapre.heurecomite' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Lieu du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteapre, 'Comiteapre.lieucomite' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Intitulé du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteapre, 'Comiteapre.intitulecomite' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Observations du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteapre, 'Comiteapre.observationcomite' );?></td>
                </tr>
            </tbody>
        </table>
</div>

<br />

<?php if( isset( $comiteapre['Participantcomite'] ) ):?>
    <h1>Liste des participants</h1>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->editLink(
                'Modifier Participant',
                array( 'controller' => 'participantscomites', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    
    
    
    <!-- <?php echo $xform->input( 'freu', array( 'label' => 'freu', 'type' => 'select', 'options' => $participants, 'multiple' => 'checkbox' ) ) ?> -->
    
    
    
    <?php if( is_array( $comiteapre['Participantcomite'] ) && count( $comiteapre['Participantcomite'] ) > 0  ):?>
    <div>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom/Prénom</th>
                    <th>Fonction</th>
                    <th>Organisme</th>
                    <th>N° Téléphone</th>
                    <th class="action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $comiteapre['Participantcomite'] as $participant ) {

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $participant, 'qual' ).' '.Set::classicExtract( $participant, 'nom' ).' '.Set::classicExtract( $participant, 'prenom' ) ),
                                h( Set::classicExtract( $participant, 'fonction' ) ),
                                h( Set::classicExtract( $participant, 'organisme' ) ),
                                h( Set::classicExtract( $participant, 'numtel' ) ),
                                $html->viewLink(
                                    'Voir le comité',
                                    array( 'controller' => 'comitesapres', 'action' => 'index', Set::classicExtract( $participant, 'id' ) ),
                                    $permissions->check( 'comitesapres', 'index' )
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
<?php endif;?>
<div class="clearer"><hr /></div>