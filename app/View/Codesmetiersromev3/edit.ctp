<?php

$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'un code métier' : 'Modification d\'un code métier' );
$this->set('title_for_layout', $title_for_layout);

if (Configure::read('debug') > 0) {
    echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
}


echo $this->Html->tag('h1', $title_for_layout);
echo $this->Xform->create(null, array('inputDefaults' => array('domain' => 'codemetierromev3')));


echo $this->Xform->inputs(
    array(
        'fieldset' => false,
        'legend' => false,
        'Codemetierromev3.id' => array('type' => 'hidden'),
        'Codemetierromev3.codedomaineproromev3_id' => array('type' => 'select', 'options' => $codesdomainesprosromev3, 'empty' => true),
        'Codemetierromev3.code' => array('type' => 'text'),
        'Codemetierromev3.name' => array('type' => 'text')
    )
);

echo $this->Html->tag(
        'div', $this->Xform->button('Enregistrer', array('type' => 'submit'))
        . $this->Xform->button('Annuler', array('type' => 'submit', 'name' => 'Cancel')), array('class' => 'submit noprint')
);

echo $this->Xform->end();
?>