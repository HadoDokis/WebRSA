<?php $this->pageTitle = 'Préconisation d\'orientation';?>
<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>


<h1>Préconisation d'orientation</h1>

<div class="with_treemenu">

</div>


<table class="tooltips">
        <thead>
            <tr>
               <!-- <th>Role personne</th> -->
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de la demande</th>
                <th>Date d'orientation</th>
                <th>Préconisation d'orientation</th>
                <th>Structure référente</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $dossier['Foyer']['Personne'] as $personne ) {
                    echo $html->tableCells(
                        array(
//                             h( $personne['rolepers'] ),
                            h( $personne['nom'] ),
                            h( $personne['prenom'] ),
                            h( date_short( $dossier['Dossier']['dtdemrsa'] ) ),
                            h( date_short( $personne['Orientstruct']['date_valid'] ) ),
                            h( isset( $personne['Structurereferente']['Typeorient']['lib_type_orient'] ) ? $personne['Structurereferente']['Typeorient']['lib_type_orient']  : null ) ,
                            h( isset( $personne['Structurereferente']['lib_struc'] ) ? $personne['Structurereferente']['lib_struc'] : null ),

                            $html->editLink(
                                'Editer l\'orientation',
                                array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personne['id'] )
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