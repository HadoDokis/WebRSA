<?php
    $domain = 'periodeimmersion';
    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id, 'personne_id' => $personne_id ) );
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'periodeimmersion', "Periodesimmersion::{$this->action}", true )
        );

    ?>
    <?php
        echo $xform->create( 'Periodeimmersion', array( 'id' => 'periodeimmersionform' ) );
        if( Set::check( $this->data, 'Periodeimmersion.id' ) ) {
            echo '<div>'.$xform->input( 'Periodeimmersion.id', array( 'type' => 'hidden' ) ).'</div>';
        }
    ?>

        <fieldset>
            <legend>LE CONTRAT CONCERNÉ</legend>
            <?php
                echo $default->view(
                    $cui,
                    array(
                        'Cui.secteur' => array( 'options' => $options['secteur'] ),
                        'Cui.convention' => array( 'options' => $options['convention'] ),
                    ),
                    array(
                        'widget' => 'table',
                        'id' => 'infosCui',
                        'options' => $options
                    )
                );
            ?>
        </fieldset>

        <fieldset>
            <legend>L'EMPLOYEUR</legend>
            <?php
                echo $default->view(
                    $cui,
                    array(
                        'Cui.nomemployeur',
                        'Cui.numvoieemployeur',
                        'Cui.typevoieemployeur' => array( 'options' => $options['typevoie'] ),
                        'Cui.nomvoieemployeur',
                        'Cui.compladremployeur',
                        'Cui.numtelemployeur',
                        'Cui.emailemployeur',
                        'Cui.codepostalemployeur',
                        'Cui.villeemployeur',
                        'Cui.siret',
                    ),
                    array(
                        'widget' => 'table',
                        'id' => 'infosCui',
                        'options' => $options
                    )
                );

            ?>
        </fieldset>

        <fieldset>
            <legend>LE SALARIÉ</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">
                        <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
                        <br />
                        <?php if(  Set::classicExtract( $personne, 'Personne.qual') != 'MR' ):?>
                            <strong>Pour les femmes, nom patronymique : </strong><?php echo Set::classicExtract( $personne, 'Personne.nomnai' );?>
                        <?php endif;?>
                        <br />
                        <strong>Né(e) le : </strong>
                            <?php
                                echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) ).' <strong>à</strong>  '.Set::classicExtract( $personne, 'Personne.nomcomnai' );
                            ?>
                        <br />
                        <strong>Adresse : </strong><br />
                            <?php
                                echo Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $options['typevoie'], Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $personne, 'Adresse.compladr' ).'<br /> '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' );
                            ?>
                        <br />
                        <!-- Si on n'autorise pas la diffusion de l'email, on n'affiche rien -->
                        <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.autorutiadrelec' ) == 'A' ):?>
                            <strong>Adresse électronique : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.adrelec' );?>
                        <?php endif;?>
                    </td>
                    <td class="mediumSize noborder">
                        <strong>Prénoms : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
                        <br />
                        <strong>NIR : </strong><?php echo Set::classicExtract( $personne, 'Personne.nir');?>
                        <br />
                        <strong>Si bénéficiaire RSA, n° allocataire : </strong>
                        <?php
                            echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' ).'  <strong><br />relève de : </strong> '.Set::classicExtract( $personne, 'Foyer.Dossier.fonorg' );
                        ?>
                        <br />
                        <!-- Si on n'autorise aps la diffusion du téléphone, on n'affiche rien -->
                        <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.autorutitel' ) == 'A' ):?>
                            <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.numtel' );?>
                            <br />
                            <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.Modecontact.numtel' );?>
                        <?php endif;?>
                    </td>
                </tr>

            </table>
        </fieldset>

        <fieldset id="periodeimmersion" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Periodeimmersion.cui_id' => array( 'value' => $cui_id, 'type' => 'hidden' )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>

            <fieldset>
                <legend>L'ENTREPRISE D'ACCUEIL</legend>
                <?php
                    echo $default->subform(
                        array(
                            'Periodeimmersion.nomentaccueil',
                            'Periodeimmersion.numvoieentaccueil',
                            'Periodeimmersion.typevoieentaccueil' => array( 'options' => $options['typevoie'] ),
                            'Periodeimmersion.nomvoieentaccueil',
                            'Periodeimmersion.compladrentaccueil',
                            'Periodeimmersion.codepostalentaccueil',
                            'Periodeimmersion.villeentaccueil',
                            'Periodeimmersion.numtelentaccueil',
                            'Periodeimmersion.emailentaccueil',
                            'Periodeimmersion.activiteentaccueil',
                            'Periodeimmersion.siretentaccueil'
                        ),
                        array(
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
            <fieldset>
                <legend>PÉRIODE D'IMMERSION</legend>
                <?php
                    echo $default->subform(
                        array(
                            'Periodeimmersion.datedebperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')-2, 'empty' => false ),
                            'Periodeimmersion.datefinperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')-2, 'empty' => false ),
                            'Periodeimmersion.nbjourperiode',
                            'Periodeimmersion.codeposteaffectation',
                            'Periodeimmersion.objectifimmersion' => array( 'type' => 'radio', 'separator' => '<br />', 'options' => $options['objectifimmersion'] ),
                            'Periodeimmersion.datesignatureimmersion' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')-2, 'empty' => false )
                        ),
                        array(
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
        </fieldset>
    <div class="submit">
        <?php
            echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
            echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end();?>
</div>

<div class="clearer"><hr /></div>