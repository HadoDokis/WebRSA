<?php
$this->pageTitle = __d( 'rupturecui66', "Rupturecui66::{$this->action}" );

if( Configure::read( 'debug' ) > 0 ) {
    echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
}
?>
<?php
    echo $this->Xhtml->tag( 'h1', $this->pageTitle );
?>

    <fieldset>
        <legend></legend>
        <?php
        echo $this->Xform->create( 'Rupturecui66', array( 'id' => 'rupturecui66form' ) );
        if( Set::check( $this->request->data, 'Rupturecui66.id' ) ){
            echo $this->Xform->input( 'Rupturecui66.id', array( 'type' => 'hidden' ) );
        }

        echo $this->Form->input( 'Rupturecui66.cui_id', array( 'type' => 'hidden', 'value' => $cui_id ) );
        echo $this->Xform->input( 'Rupturecui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

        echo $this->Xform->input( 'Rupturecui66.observation', array( 'label' => __d( 'rupturecui66', 'Rupturecui66.observation' ), 'type' => 'textarea' )  );
        echo $this->Xform->input( 'Rupturecui66.daterupturecui', array( 'label' => required( __d( 'rupturecui66', 'Rupturecui66.daterupturecui' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );
        echo $this->Xform->input( 'Rupturecui66.dateenregistrementrupture', array( 'label' => required( __d( 'rupturecui66', 'Rupturecui66.dateenregistrementrupture' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );


        echo $this->Xform->input( 'Motifrupturecui66.Motifrupturecui66', array( 'type' => 'select', 'label' => 'Motifs de rupture', 'multiple' => 'checkbox', 'empty' => false, 'options' => $listeMotifsrupturescuis66 ) );
        ?>
    </fieldset>

    <div class="submit">
        <?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
<?php echo $this->Form->end();?>