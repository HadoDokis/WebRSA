<?php $this->pageTitle = 'Préconisation d\'orientation';?>
<?php echo $this->element( 'dossier_menu', array( 'id' => $details['Dossier']['id'] ) );?>


<h1>Préconisation d'orientation</h1>

<div class="with_treemenu">



<table class="tooltips">
        <thead>
            <tr>
               <!-- <th>Role personne</th> -->
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de la demande</th>
                <th>Date d'orientation</th>
                <th>Statut de l'orientation</th>
                <th>Préconisation d'orientation</th>
                <th>Structure référente</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
//             debug($details);
                foreach( $personnes as $personne ) {

                    echo $html->tableCells(

                        array(
                            h( $personne['Personne']['nom'] ),
                            h( $personne['Personne']['prenom'] ),
                            h( $locale->date( 'Date::short', $details['Dossier']['dtdemrsa'] ) ),
                            h( $locale->date( 'Date::short', $personne['Orientstruct']['date_valid'] ) ),
                            h( $personne['Orientstruct']['statut_orient'] ),
                            h( Set::enum( Set::classicExtract( $personne, 'Structurereferente.typeorient_id' ), $typeorient ) ) ,
                            h( Set::classicExtract( $personne, 'Structurereferente.lib_struc' )  ),
                            $html->editLink(
                                'Editer l\'orientation',
                                array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personne['Personne']['id'] )
                            ),
                            $html->printLink(
                                'Imprimer la notification',
                                array( 'controller' => 'gedooos', 'action' => 'orientstruct', $personne['Orientstruct']['id'] ),
                                !empty( $personne['Orientstruct']['typeorient_id'] )
                            ),
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                }
            ?>
        </tbody>
    </table>
<div class="clearer"><hr /></div>