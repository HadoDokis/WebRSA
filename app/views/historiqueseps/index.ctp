    <?php  echo $this->element( 'dossier_menu', array( 'dossier_id' => $dossier_id) );?>

    <div class="with_treemenu">
    <h1><?php echo $this->pageTitle = 'Historique des passages en EP';?></h1>

    <?php
        if( !empty( $decisions ) ){
            $themeep = Set::extract( $decisions, '/Dossierep/themeep' );
            foreach( $themeep as $key => $theme ){
                $modeleDecision = 'Decision'.Inflector::singularize( $theme );
            }
            echo $default2->index(
                    $decisions,
                    array(
                        'Commissionep.dateseance',
                        'Dossierep.themeep',// => array( 'type' => 'text', 'options' => $options['themeep'] ),
                        'Passagecommissionep.etatdossierep',
                        "{$modeleDecision}.0.decision" => array( 'label' => 'Avis / Décision EP' ),
                        "{$modeleDecision}.0.commentaire" => array( 'label' => 'Commentaire EP' ),
                        "{$modeleDecision}.1.decision" => array( 'label' => 'Décision CG' ),
                        "{$modeleDecision}.1.commentaire" => array( 'label' => 'Commentaire CG' )
                    ),
                    array(
                        'options' => $options
                    )
                );
        }
        else{
            echo '<p class="notice">Aucun passage en EP pour cet allocataire</p>';
        }
// debug($decisions);
?>
</div>
<div class="clearer"><hr /></div>