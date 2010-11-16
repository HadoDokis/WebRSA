<?php $this->pageTitle = 'CER';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un CER';
    }
    else {
        $this->pageTitle = 'CER ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'CER ';?></h1>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.forme_ci' );?></th>
                    <td><?php echo $formeci[$contratinsertion['Contratinsertion']['forme_ci']];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'lib_typo' );?></th>
                    <td><?php echo Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ), $options['num_contrat'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.dd_ci' );?></th>
                    <td><?php echo date_short( $contratinsertion['Contratinsertion']['dd_ci'] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.df_ci' );?></th>
                    <td><?php echo date_short( $contratinsertion['Contratinsertion']['df_ci'] );?></td>
                </tr>
            </tbody>
        </table>
                <h2>Formation et expérience</h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.niv_etude');?></th>
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
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.diplomes' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['diplomes'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.expr_prof' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['expr_prof'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.form_compl' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['form_compl'];?></td>
                </tr>
            </tbody>
        </table>
                <h2>Type d'orientation</h2>
        <table>
            <tbody>
                <tr class="odd">
                    <th><?php __( 'Type d\'orientation' );?></th>
                    <td><?php echo $contratinsertion['Typeorient']['lib_type_orient'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Structure référente' );?></th>
                    <td><?php echo $contratinsertion['Structurereferente']['lib_struc'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Référent' );?></th>
                    <td><?php echo $contratinsertion['Referent']['nom_complet'];?></td>
                </tr>
            </tbody>
        </table>
                <h2>Parcours d'insertion antérieur</h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.rg_ci' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['rg_ci'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.actions_prev' );?></th>
                    <td><?php echo isset( $contratinsertion['Contratinsertion']['actions_prev'] ) ? 'Oui' : 'Non';?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.obsta_renc' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['obsta_renc'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.service_soutien' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['service_soutien'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.pers_charg_suivi' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['pers_charg_suivi'];?></td>
                </tr>
            </tbody>
        </table>
                <h2>Projet et actions d'insertion</h2>
        <table>
            <tbody>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.objectifs_fixes' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['objectifs_fixes'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.engag_object' );?></th>
                    <td><?php echo $contratinsertion['Action']['libelle'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.sect_acti_emp' );?></th>
                    <td><?php echo isset( $sect_acti_emp[$contratinsertion['Contratinsertion']['sect_acti_emp']] ) ? $sect_acti_emp[$contratinsertion['Contratinsertion']['sect_acti_emp']] : null ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.emp_occupe' );?></th>
                    <td><?php echo isset( $emp_occupe[$contratinsertion['Contratinsertion']['emp_occupe']] ) ? $emp_occupe[$contratinsertion['Contratinsertion']['emp_occupe']] : null ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.duree_hebdo_emp' );?></th>
                    <td><?php echo isset( $duree_hebdo_emp[$contratinsertion['Contratinsertion']['duree_hebdo_emp']] ) ? $duree_hebdo_emp[$contratinsertion['Contratinsertion']['duree_hebdo_emp']] : null ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.nat_cont_trav' );?></th>
                    <td><?php echo isset( $nat_cont_trav[$contratinsertion['Contratinsertion']['nat_cont_trav']] ) ? $nat_cont_trav[$contratinsertion['Contratinsertion']['nat_cont_trav']] : null ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.duree_cdd' );?></th>
                    <td><?php echo isset( $duree_cdd[$contratinsertion['Contratinsertion']['duree_cdd']] ) ? $duree_cdd[$contratinsertion['Contratinsertion']['duree_cdd']] : null ;?></td>
                </tr>
            </tbody>
        </table>
                <h2></h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.duree_engag' );?></th>
                    <td><?php echo isset( $duree_engag[$contratinsertion['Contratinsertion']['duree_engag']] ) ? $duree_engag[$contratinsertion['Contratinsertion']['duree_engag']] : null ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'contratinsertion', 'Contratinsertion.nature_projet' );?></th>
                    <td><?php echo $contratinsertion['Contratinsertion']['nature_projet'];?></td>
                </tr>
            </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>