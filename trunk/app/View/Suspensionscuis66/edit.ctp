<?php
$this->pageTitle = __d( 'suspensioncui66', "Suspensioncui66::{$this->action}" );

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
            echo $this->Xform->create( 'Suspensioncui66', array( 'id' => 'suspensioncui66form' ) );
            if( Set::check( $this->request->data, 'Suspensioncui66.id' ) ){
                echo $this->Xform->input( 'Suspensioncui66.id', array( 'type' => 'hidden' ) );
            }

            echo $this->Form->input( 'Suspensioncui66.cui_id', array( 'type' => 'hidden', 'value' => $cui_id ) );
            echo $this->Xform->input( 'Suspensioncui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

            echo $this->Xform->input( 'Suspensioncui66.observation', array( 'label' => __d( 'suspensioncui66', 'Suspensioncui66.observation' ), 'type' => 'textarea' )  );
            echo $this->Xform->input( 'Suspensioncui66.datedebut', array( 'label' => required( __d( 'suspensioncui66', 'Suspensioncui66.datedebut' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );
            echo $this->Xform->input( 'Suspensioncui66.datefin', array( 'label' => required( __d( 'suspensioncui66', 'Suspensioncui66.datefin' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );

            echo $this->Xform->input( 'Motifsuspensioncui66.Motifsuspensioncui66', array( 'type' => 'select', 'label' => 'Motifs de suspension', 'multiple' => 'checkbox', 'empty' => false, 'options' => $listeMotifssuspensioncuis66 ) );

            echo $this->Xform->input( 'Suspensioncui66.formatjournee', array( 'label' => __d( 'suspensioncui66', 'Suspensioncui66.formatjournee' ), 'type' => 'select', 'options' => $options['Suspensioncui66']['formatjournee'], 'empty' => true ) );
        ?>
    </fieldset>

    <div class="submit">
        <?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
<?php echo $this->Form->end();?>