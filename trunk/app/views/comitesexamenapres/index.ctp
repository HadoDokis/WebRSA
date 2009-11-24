<?php  $this->pageTitle = 'Comité d\'examen APRE';?>


    <h1>Comité d'examen APRE</h1>

        <?php if( empty( $comitesexamenapres ) ):?>
            <p class="notice">Il n'existe pas encore de comité.</p>
        <?php endif;?>

        <?php if( $permissions->check( 'comitesexamenapres', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter Comité',
                        array( 'controller' => 'comitesexamenapres', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <?php if( !empty( $comitesexamenapres ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>N° APRE</th>
                <th>Nom/Prénom Allocataire</th>
                <th>Type de demande APRE</th>
                <th>Référent APRE</th>
                <th>Date demande APRE</th>
                <th>Natures de la demande</th>
                <th>Etat du dossier</th>
                <th colspan="5" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $comitesexamenapres as $comiteexamenapre ) {

                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $comiteexamenapre, 'Apre.numeroapre' ) ),
                            h( $comiteexamenapre['Personne']['nom'].' '.$comiteexamenapre['Personne']['prenom'] ),
                            h( Set::enum( Set::classicExtract( $comiteexamenapre, 'Apre.typedemandeapre' ), $options['typedemandeapre'] ) ),
                            h( Set::enum( Set::classicExtract( $comiteexamenapre, 'Apre.referentapre_id' ), $refsapre ) ),
                            h( date_short( Set::classicExtract( $comiteexamenapre, 'Apre.datedemandeapre' ) ) ),
                            ( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ),
                            h(  Set::enum( Set::classicExtract( $comiteexamenapre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] ) ),
                            $html->viewLink(
                                'Voir la demande APRE',
                                array( 'controller' => 'comitesexamenapres', 'action' => 'view', $comiteexamenapre['Apre']['id'] ),
                                $permissions->check( 'comitesexamenapres', 'view' )
                            ),
                            $html->editLink(
                                'Editer la demande APRE',
                                array( 'controller' => 'comitesexamenapres', 'action' => 'edit', $comiteexamenapre['Apre']['id'] ),
                                $permissions->check( 'comitesexamenapres', 'edit' )
                            ),
                            $html->printLink(
                                'Imprimer la demande APRE',
                                array( 'controller' => 'gedooos', 'action' => 'apre', $comiteexamenapre['Apre']['id'] ),
                                $permissions->check( 'gedooos', 'apre' )
                            )
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                }
            ?>
        </tbody>
    </table>
    <?php  endif;?>

<div class="clearer"><hr /></div>