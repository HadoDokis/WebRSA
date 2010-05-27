<?php
    $domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
    echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe( "dom:loaded", function() {
            dependantSelect(
                'RendezvousReferentId',
                'RendezvousStructurereferenteId'
            );

        ///Bilan si personne reçue
        observeDisableFieldsOnRadioValue(
            'candidatureform',
            'data[ActioncandidatPersonne][bilanrecu]',
            [
                'ActioncandidatPersonneDaterecuDay',
                'ActioncandidatPersonneDaterecuMonth',
                'ActioncandidatPersonneDaterecuYear',
                'ActioncandidatPersonnePersonnerecu',
                'ActioncandidatPersonnePresencecontrat'
            ],
            'N',
            false
        );

        ///Bilan si personne reçue
        observeDisableFieldsOnRadioValue(
            'candidatureform',
            'data[ActioncandidatPersonne][pieceallocataire]',
            [
                'ActioncandidatPersonneAutrepiece'
            ],
            'AUT',
            true
        );


        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ActioncandidatPartenairePartenaireId',
                    'url' => Router::url( array( 'action' => 'ajaxpart', Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' ) ), true )
                )
            ).';';

            echo $ajax->remoteFunction(
                array(
                    'update' => 'ActioncandidatPersonneStructurereferente',
                    'url' => Router::url( array( 'action' => 'ajaxstruct', Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' ) ), true )
                )
            ).';';

            echo $ajax->remoteFunction(
                array(
                    'update' => 'StructureData',
                    'url' => Router::url( array( 'action' => 'ajaxreffonct', Set::extract( $this->data, 'Rendezvous.referent_id' ) ), true )
                )
            );
        ?>
    } );
</script>

<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true ),
            array(
                'class' => 'aere'
            )
        );

        echo $html->tag(
            'p',
            'La fiche de liaison est un document conventionnel partagé qui engage tous les acteurs du PDI',
            array(
                'class' => 'remarque'
            )
        );
    ?>
    <?php
        echo $xform->create( 'ActioncandidatPersonne', array( 'id' => 'candidatureform' ) );
        if( Set::check( $this->data, 'ActioncandidatPersonne.id' ) ){
            echo $xform->input( 'ActioncandidatPersonne.id', array( 'type' => 'hidden' ) );
        }
    ?>
    <fieldset class="actioncandidat">
        <legend class="actioncandidat" >Prescripteur / Référent</legend>
        <?php
            echo $default->subform(
                array(
                    'ActioncandidatPersonne.ddaction' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
                    'ActioncandidatPersonne.referent_id' => array( 'value' => $referentId ),
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            ///Ajax pour les données du référent et de l'organisme auquel il est lié
            echo $ajax->observeField( 'ActioncandidatPersonneReferentId', array( 'update' => 'ActioncandidatPersonneStructurereferente', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) );

            echo $html->tag(
                'div',
                '<b></b>',
                array(
                    'id' => 'ActioncandidatPersonneStructurereferente'
                )
            );

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.motifdemande' => array( 'domain' => $domain )
                )
            );
        ?>
    </fieldset>
    <fieldset class="actioncandidat">
        <legend class="actioncandidat" >Personne orientée / allocataire</legend>
        <?php
            ///Données propre à la Personne
            echo $default->view(
                $personne,
                array(
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Personne.dtnai'
                ),
                array(
                    'widget' => 'dl',
                    'class' => 'allocataire infos',
                    'options' => $options
                )
            );

            ///Données propre à l'adresse de la Personne
            echo $html->tag(
                'dl',
                $html->tag( 'dt', 'Adresse' ).
                $html->tag(
                    'dd',
                    $default->format( $personne, 'Adresse.numvoie' ).' '.$default->format( $personne, 'Adresse.typevoie', array( 'options' => $options ) ).' '.$default->format( $personne, 'Adresse.nomvoie' ).' '.$default->format( $personne, 'Adresse.codepos' ).' '.$default->format( $personne, 'Adresse.locaadr' )
                ),
                array(
                    'class' => 'allocataire infos'
                )
            );

            ///Données propre aux données du foyer de la personne
            echo $default->view(
                $personne,
                array(
                    'Foyer.Modecontact.0.numtel' => array( 'label' => 'N° de téléphone' ),
                    'Foyer.Modecontact.0.adrelec' => array( 'label' => 'Email' ),
                    'Detaildroitrsa.oridemrsa' => array( 'label' => 'Allocataire du ' ),
                    'Foyer.Dossier.matricule' => array( 'label' => 'Numéro allocataire ' )
                ),
                array(
                    'widget' => 'dl',
                    'class' => 'allocataire infos',
                    'options' => $options
                )
            );

            ///Données propre au Pole Emploi
            $isPoleemploi = Set::classicExtract( $personne, 'Activite.act' );
            $isInscrit = 'Non';
            $idassedic = null;
            if( $isPoleemploi == 'ANP' ) {
                $isInscrit = 'Oui';
                $idassedic = Set::classicExtract( $personne, 'Personne.idassedic' );
            }
            else {
                $isInscrit;
                $idassedic;
            }


            echo $html->tag(
                'dl',
                $html->tag( 'dt', 'Inscrit au Pole Emploi' ).
                $html->tag(
                    'dd',
                    $isInscrit
                ).
                $html->tag( 'dt', ' N° identifiant : ' ).
                $html->tag(
                    'dd',
                    $idassedic
                ),
                array(
                    'class' => 'allocataire infos'
                )
            );

            ///Données propre aux Dsps de la personne
            if( !empty( $dsp ) ) {
                echo $default->view(
                    $personne,
                    array(
                        'Dsp.nivetu' => array( 'label' => 'Niveau d\'étude', 'options' => $options['Dsp']['nivetu'] )
                    ),
                    array(
                        'widget' => 'dl',
                        'class' => 'allocataire infos',
                        'options' => $options
                    )
                );

                echo $default->view(
                    $personne,
                    array(
                        'Dsp.libautrqualipro' => array( 'label' => 'Expériences professionnelles, ou qualification, et/ou niveau de diplomes <br />' )
                    ),
                    array(
                        'widget' => 'dl',
                        'class' => 'allocataire infos',
                        'options' => $options
                    )
                );
            }
            else{
                echo '<strong>Expériences professionnelles, ou qualification, et/ou niveau de diplomes </strong>';
                echo $default->subform(
                    array(
                        'Dsp.id' => array( 'type' => 'hidden' ),
                        'Dsp.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
                        'Dsp.nivetu' => array( 'options' => $options['Dsp']['nivetu'], 'empty' => true ),
                        'Dsp.libautrqualipro' => array( 'type' => 'textarea' )
                    )
                );
            }

            ///Données propre au contrat d'engagement réciproque (CER)
            if( !empty( $contrat ) ) {
                echo $default->view(
                    $personne,
                    array(
                        'Contratinsertion.decision_ci' => array( 'label' => 'Contrat d\'engagement : ', 'options' => $options['Contratinsertion']['decision_ci'] ),
                        'Contratinsertion.datevalidation_ci'=> array( 'label' => false )
                    ),
                    array(
                        'widget' => 'dl',
                        'class' => 'allocataire infos',
                        'options' => $options
                    )
                );
            }
            else{
                echo '<strong>Contrat d\'engagement : </strong>';
                echo $html->tag(
                    'p',
                    'Aucun contrat présent pour cette personne',
                    array( 'class' => 'notice' )
                );
            }
        ?>
    </fieldset>

    <fieldset class="actioncandidat">
        <legend class="actioncandidat" >Partenaire / Prestataire</legend>
        <?php
            echo $default->subform(
                array(
                    'Rendezvous.id' => array( 'type' => 'hidden' ),
                    'Rendezvous.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
                    'Rendezvous.daterdv' => array( 'label' =>  'Rendez-vous fixé le ', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true ),
                    'Rendezvous.heurerdv' => array( 'label' => 'A ', 'type' => 'time', 'timeFormat' => '24', 'minuteInterval' => 5,  'empty' => true, 'hourRange' => array( 8, 19 ) )
                ),
                array(
                    'options' => $options,
                    'domain' => $domain
                )
            );


            echo $xform->input( 'Rendezvous.structurereferente_id', array( 'label' =>  required( __( 'Nom de l\'organisme', true ) ), 'type' => 'select', 'options' => $structs, 'empty' => true ) );

            echo $xform->input( 'Rendezvous.referent_id', array( 'label' =>  ( 'Nom de l\'agent / du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) );


            ///Ajax pour les données du référent et de l'organisme auquel il est lié
            echo $ajax->observeField( 'RendezvousReferentId', array( 'update' => 'StructureData', 'url' => Router::url( array( 'action' => 'ajaxreffonct' ), true ) ) );

            echo $html->tag(
                'div',
                '<b></b>',
                array(
                    'id' => 'StructureData'
                )
            );

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.pieceallocataire' => array( 'legend' => 'L\'allocataire est invité à se munir: ', 'type' => 'radio', 'separator' => '<br />', 'options' => $options['ActioncandidatPersonne']['pieceallocataire'] ),
                    'ActioncandidatPersonne.autrepiece' => array( 'label' => false )
                )
            );

        ?>
    </fieldset>


    <fieldset class="actioncandidat">
        <legend class="actioncandidat" >Résultats d'orientation</legend>
        <?php
            echo $default->subform(
                array(
                    'ActioncandidatPersonne.bilanvenu' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => 'La personne s\'est présentée' ),
                    'ActioncandidatPersonne.bilanrecu' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => 'La personne a été reçue' ),
                    'ActioncandidatPersonne.daterecu' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true ),
                    'ActioncandidatPersonne.personnerecu',
                    'ActioncandidatPersonne.presencecontrat' => array( 'type' => 'select', 'label' => 'Avec son contrat d\'Engagement Réciproque' ),
                    'ActioncandidatPersonne.bilanretenu' => array( 'type' => 'radio', 'separator' => '<br />', 'legend' => 'La personne a été retenue' )
                ),
                array(
                    'options' => $options,
                    'domain' => $domain
                )
            );

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
                    'ActioncandidatPersonne.actioncandidat_id'
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            ///Ajax pour les données de l'action entreprise et de son partenaire lié
            echo $ajax->observeField( 'ActioncandidatPersonneActioncandidatId', array( 'update' => 'ActioncandidatPartenairePartenaireId', 'url' => Router::url( array( 'action' => 'ajaxpart' ), true ) ) );
            echo $html->tag(
                'div',
                '<b></b>',
                array(
                    'id' => 'ActioncandidatPartenairePartenaireId',
                    'class' => 'aere'
                )
            );

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.integrationaction' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => 'La personne souhaite intégrer l\'action' ),
                    'ActioncandidatPersonne.precisionmotif',
                    'ActioncandidatPersonne.dfaction' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

        ?>
    </fieldset>


    <fieldset class="loici">
        <p>
            <strong>Engagement:</strong><br />
            <em>Je m’engage à me rendre disponible afin d’être présent à la prestation ou au rendez vous qui me sera fixé. En cas de force majeure, je m’engage à prévenir le conseiller d’insertion ou l’assistante sociale chargé de mon suivi.<br />
            Nous vous rappelons que dans le cas où vous ne donneriez pas suite à ce rendez vous sans motif valable, vous seriez convoqué(e) par l'Equipe Pluridisciplinaire Locale (Commission Audition), pour non respect de vos obligations dans le cadre de votre contrat.<br />
            </em>
        </p>
        <?php
            echo $default->subform(
                array(
                    'ActioncandidatPersonne.datesignature' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => false )
                ),
                array(
                    'domain' => $domain
                )
            );
        ?>
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