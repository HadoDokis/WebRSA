<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">

        <?php
            echo $html->tag(
                'h1',
                $this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}", true )
            );

        ?>
<br />
    <div id="tabbedWrapper" class="tabs">

        <div id="entretiens">
            <h2 class="title">Entretiens</h2>
            <?php
                echo $default->index(
                    $entretiens,
                    array(
                        'Entretien.dateentretien',
                        'Structurereferente.lib_struc',
                        'Referent.nom_complet'
                    ),
                    array(
                        'actions' => array(
                            'Entretien.view',
                            'Entretien.edit',
                            'Entretien.delete'
                        ),
                        'add' => array( 'Entretien.add' => $personne_id )
                    )
                );
            ?>
        </div><!-- Fin de div entretiens -->

<!-- INFO : Fin de l'affichage des Entretiens -->
<? if( false ):?>
        <div id="dsporigine">
            <h2 class="title">DSP d'origine</h2>
            <?php if( !empty( $dsps ) ):?>
                <?php

                    echo $form->input(
                        'Dsp.hideempty',
                        array(
                            'type' => 'checkbox',
                            'label' => 'Cacher les questions sans réponse',
                            'onclick' => 'if( $( \'DspHideempty\' ).checked ) {
                                $$( \'.empty\' ).each( function( elmt ) { elmt.hide() } );
                            } else { $$( \'.empty\' ).each( function( elmt ) { elmt.show() } ); }'
                        )
                    );

                    echo $default->view(
                        $dsps,
                        array(
                            'Dsp.sitpersdemrsa',
                            'Dsp.topisogroouenf',
                            'Dsp.topdrorsarmiant',
                            'Dsp.drorsarmianta2',
                            'Dsp.topcouvsoc',
                            'Dsp.accosocfam',
                            'Dsp.libcooraccosocfam',
                            'Dsp.accosocindi',
                            'Dsp.libcooraccosocindi',
                            'Dsp.soutdemarsoc',
                            'Dsp.nivetu',
                            'Dsp.nivdipmaxobt',
                            'Dsp.annobtnivdipmax',
                            'Dsp.topqualipro',
                            'Dsp.libautrqualipro',
                            'Dsp.topcompeextrapro',
                            'Dsp.libcompeextrapro',
                            'Dsp.topengdemarechemploi',
                            'Dsp.hispro',
                            'Dsp.libderact',
                            'Dsp.libsecactderact',
                            'Dsp.cessderact',
                            'Dsp.topdomideract',
                            'Dsp.libactdomi',
                            'Dsp.libsecactdomi',
                            'Dsp.duractdomi',
                            'Dsp.inscdememploi',
                            'Dsp.topisogrorechemploi',
                            'Dsp.accoemploi',
                            'Dsp.libcooraccoemploi',
                            'Dsp.topprojpro',
                            'Dsp.libemploirech',
                            'Dsp.libsecactrech',
                            'Dsp.topcreareprientre',
                            'Dsp.concoformqualiemploi',
                            'Dsp.topmoyloco',
                            'Dsp.toppermicondub',
                            'Dsp.topautrpermicondu',
                            'Dsp.libautrpermicondu',
                            'Dsp.natlog',
                            'Dsp.demarlog'
                        ),
                        array(
                            'options' => $options
                        )
                    );
                ?>
                <?php else:?>
                    <ul class="actionMenu">
                        <?php
                            echo '<li>'.$html->addLink(
                                'Ajouter des Dsps',
                                array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
                            ).' </li>';

                        ?>
                    </ul>
                    <p class="notice">Cette personne ne possède pas encore de données socio-professionnelles.</p>
            <?php endif;?>
        </div>

<!-- INFO : Fin de l'affichage des DSP d'Origine -->

        <div id="dspcg">
            <h2 class="title">DSP CG</h2>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                            'Ajouter des Dsps',
                            array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
                        ).' </li>';

                    ?>
                </ul>
                <?php if( !empty( $dsps ) ):?>
                <?php

                    echo $default->view(
                        $dsps,
                        array(
                            'Dsp.nivetu',
                            'Dsp.duractdomi'
                        ),
                        array(
                            'options' => $options
                        )
                    );

                ?>
                <?php else:?>
                    <p class="notice">Cette personne ne possède pas encore de données CG.</p>
            <?php endif;?>
        </div>
        <? endif;?>
    </div> <!-- Fin de div tabbedWrapper -->

</div>
<div class="clearer"><hr /></div>

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
</script>