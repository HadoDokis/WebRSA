<?php $this->pageTitle = 'Contrat d\'Engagement réciproque';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un contrat d\'Engagment réciproque';
    }
    else {
        $this->pageTitle = 'Contrat d\'Engagment réciproque ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'Contrat d\'Engagement réciproque  ';?></h1>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'forme_ci' );?></th>
                    <td><?php echo $formeci[$contratinsertion['Contratinsertion']['forme_ci']];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'lib_typo' );?></th>
                    <td><?php echo ( $tc[$contratinsertion['Contratinsertion']['typocontrat_id']] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'dd_ci' );?></th>
                    <td><?php echo date_short( $contratinsertion['Contratinsertion']['dd_ci'] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'df_ci' );?></th>
                    <td><?php echo date_short( $contratinsertion['Contratinsertion']['df_ci'] );?></td>
                </tr>
            </tbody>
        </table>
                <h2>Formation et expérience</h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'niv_etude');?></th>
                    <td>
                        <?php if( !empty( $contratinsertion['Nivetu'] ) ):?>
                            <ul>
                                <?php foreach( $contratinsertion['Nivetu'] as $nivetus ):?>
                                    <li><?php echo h( $nivetus['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'diplomes' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['diplomes'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'expr_prof' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['expr_prof'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'form_compl' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['form_compl'];?></td>
                </tr>
            </tbody>
        </table>
                <h2>Parcours d'insertion antérieur</h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'rg_ci' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['rg_ci'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'actions_prev' );?></th>
                    <td><?php echo isset( $contratinsertion['Contratinsertion']['actions_prev'] ) ? 'Oui' : 'Non';?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'obsta_renc' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['obsta_renc'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'service_soutien' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['service_soutien'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'pers_charg_suivi' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['pers_charg_suivi'];?></td>
                </tr>
            </tbody>
        </table>
                <h2>Projet et actions d'insertion</h2>
        <table>
            <tbody>
                <tr class="odd">
                    <th><?php __( 'objectifs_fixes' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['objectifs_fixes'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'engag_object' );?></th>
                    <td><?php echo $codesaction;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'sect_acti_emp' );?></th>
                    <td><?php echo isset( $sect_acti_emp[$contratinsertion['Contratinsertion']['sect_acti_emp']] ) ? $sect_acti_emp[$contratinsertion['Contratinsertion']['sect_acti_emp']] : null ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'emp_occupe' );?></th>
                    <td><?php echo isset( $emp_occupe[$contratinsertion['Contratinsertion']['emp_occupe']] ) ? $emp_occupe[$contratinsertion['Contratinsertion']['emp_occupe']] : null ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'duree_hebdo_emp' );?></th>
                    <td><?php echo isset( $duree_hebdo_emp[$contratinsertion['Contratinsertion']['duree_hebdo_emp']] ) ? $duree_hebdo_emp[$contratinsertion['Contratinsertion']['duree_hebdo_emp']] : null ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'nat_cont_trav' );?></th>
                    <td><?php echo isset( $nat_cont_trav[$contratinsertion['Contratinsertion']['nat_cont_trav']] ) ? $nat_cont_trav[$contratinsertion['Contratinsertion']['nat_cont_trav']] : null ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'duree_cdd' );?></th>
                    <td><?php echo isset( $duree_cdd[$contratinsertion['Contratinsertion']['duree_cdd']] ) ? $duree_cdd[$contratinsertion['Contratinsertion']['duree_cdd']] : null ;?></td>
                </tr>
            </tbody>
        </table>
                <h2></h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'duree_engag' );?></th>
                    <td><?php echo isset( $duree_engag[$contratinsertion['Contratinsertion']['duree_engag']] ) ? $duree_engag[$contratinsertion['Contratinsertion']['duree_engag']] : null ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'nature_projet' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['nature_projet'];?></td>
                </tr>
            </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>