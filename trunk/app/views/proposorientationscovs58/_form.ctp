<?php
    $typeorient_id = null;
    if( !empty( $this->data['Structurereferente']['Typeorient']['id'] ) ) {
        $typeorient_id = $this->data['Structurereferente']['Typeorient']['id'];
    }
    $domain = 'orientstruct';
?>

<fieldset>
    <legend>Ajout d'une orientation</legend>
    <?php echo $form->input( 'Propoorientationcov58.typeorient_id', array( 'label' =>  required( __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ) ), 'type' => 'select', 'options' => $typesorients, 'empty' => true, 'value' => $typeorient_id ) );?>
    <?php
        if( $this->action == 'edit' ) {
            if( !empty( $this->data['Propoorientationcov58']['structurereferente_id'] ) ) {
                $this->data['Propoorientationcov58']['structurereferente_id'] = preg_replace( '/^[^_]+_/', '', $this->data['Propoorientationcov58']['typeorient_id'] ).'_'.$this->data['Propoorientationcov58']['structurereferente_id'];

                $this->data['Propoorientationcov58']['referent_id'] = preg_replace( '/^[^_]+_/', '', $this->data['Propoorientationcov58']['structurereferente_id'] ).'_'.$this->data['Propoorientationcov58']['referent_id'];

            }
        }
        else {
            if( !Set::check( $this->data, 'Propoorientationcov58.structurereferente_id', '' ) ) {
                $this->data = Set::insert( $this->data, 'Propoorientationcov58.structurereferente_id', '' );
            }
            if( !Set::check( $this->data, 'Propoorientationcov58.referent_id', '' ) ) {
                $this->data = Set::insert( $this->data, 'Propoorientationcov58.referent_id', '' );
            }
        }

        /// Rustine sinon 13_10_5_4
        $this->data['Propoorientationcov58']['structurereferente_id'] = preg_replace( '/^.*(?<![0-9])([0-9]+_[0-9]+)$/', '\1', $this->data['Propoorientationcov58']['structurereferente_id'] );
        $this->data['Propoorientationcov58']['referent_id'] = preg_replace( '/^.*(?<![0-9])([0-9]+_[0-9]+)$/', '\1', $this->data['Propoorientationcov58']['referent_id'] );

    ?>
    <?php
        echo $form->input( 'Propoorientationcov58.structurereferente_id', array( 'label' => required(__d( 'structurereferente', 'Structurereferente.lib_struc', true  )), 'type' => 'select', 'options' => $structuresreferentes, 'empty' => true, 'selected' => $this->data['Propoorientationcov58']['structurereferente_id'] ) );

        echo $form->input( 'Propoorientationcov58.referent_id', array(  'label' => __d( 'structurereferente', 'Structurereferente.nom_referent', true  ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $this->data['Propoorientationcov58']['referent_id'] ) );
    ?>
    <?php echo $form->input( 'Propoorientationcov58.datedemande', array(  'label' =>  required( __d( 'contratinsertion', 'Contratinsertion.date_propo', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true, 'tyoe' => 'date' ) );?>

</fieldset>
