<?php
	$this->pageTitle = 'Rendez-vous de la personne';
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );
?>

<div class="with_treemenu">
    <h1>Rendez-vous</h1>
    <?php
        echo $default2->index(
            $rdvs,
            array(
                'Personne.nom_complet' => array( 'type' => 'string' ),
                'Structurereferente.lib_struc',
                'Referent.nom_complet' => array( 'type' => 'string' ),
                'Permanence.libpermanence',
                'Typerdv.libelle',
                'Statutrdv.libelle',
                'Rendezvous.daterdv',
                'Rendezvous.heurerdv',
                'Rendezvous.objetrdv',
                'Rendezvous.commentairerdv'
            ),
            array(
                'actions' => array(
                    'Rendezvous::view',
                    'Rendezvous::edit',
                    'Rendezvous::print' => array( 'label' => 'Imprimer', 'url' => array( 'action' => 'gedooo' ) ),
                    'Rendezvous::delete'
                ),
                'add' => array( 'Rendezvous.add' => array( 'controller'=>'rendezvous', 'action'=>'add', $personne_id ) ),
//                 'options' => $options
            )
        );
//         echo $default->index(
//             $rdvs,
//             array(
//                 'Personne.nom_complet',
//                 'Structurereferente.lib_struc',
//                 'Referent.nom_complet',
//                 'Permanence.libpermanence',
//                 'Typerdv.libelle',
//                 'Statutrdv.libelle',
//                 'Rendezvous.daterdv',
//                 'Rendezvous.heurerdv',
//                 'Rendezvous.objetrdv',
//                 'Rendezvous.commentairerdv'
//             ),
//             array(
//                 'actions' => array(
//                     'Rendezvous.view',
//                     'Rendezvous.edit',
//                     'Rendezvous.gedooo'/* => array( 'controller' => 'gedooos', 'action' => 'rendezvous' )*/,
//                     'Rendezvous.delete'
//                 ),
//                 'add' => array( 'Rendezvous.add' => $personne_id )
//             )
//         );
    ?>
</div>
<div class="clearer"><hr /></div>