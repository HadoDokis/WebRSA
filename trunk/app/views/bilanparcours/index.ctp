<?php  $this->pageTitle = 'Bilan de parcours de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
    <h1>Bilan du parcours</h1>

        <?php
            echo $default->index(
                $bilanparcours,
                array(
                    'Bilanparcours.datebilan',
                    'Structurereferente.lib_struc',
                    'Referent.nom_complet'
                ),
                array(
                    'actions' => array(
                        'Bilanparcours.edit',
                        'Bilanparcours.delete'
                    ),
                    'add' => array( 'Bilanparcours.add' => $personne_id )
                )
            )
        ?>

</div>
<div class="clearer"><hr /></div>