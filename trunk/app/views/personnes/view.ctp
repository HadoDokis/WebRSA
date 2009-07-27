<?php
    $title = implode(
        ' ',
        array(
            $personne['Personne']['qual'],
            $personne['Personne']['nom'],
            ( !empty( $personne['Personne']['nomnaiss'] ) ? '( née '.$personne['Personne']['nomnaiss'].' )' : null ),
            $personne['Personne']['prenom'],
            $personne['Personne']['prenom2'],
            $personne['Personne']['prenom3']
        )
    );

    $this->pageTitle = 'Visualisation d\'une personne « '.$title.' »';

    function thead( $pct = 10 ) {
        return '<thead>
                <tr>
                    <th colspan="2" style="width: '.$pct.'%;">Grossesse</th>
                </tr>
            </thead>';
    }
?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $personne['Personne']['foyer_id'] ) );?>

<div class="with_treemenu">
    <h1><?php echo 'Visualisation d\'une personne « '.$title.' »';?></h1>

    <ul class="actionMenu">
        <?php
            if( $permissions->check( 'personnes', 'edit' ) ) {
                echo '<li>'.$html->editLink(
                    'Éditer la personne « '.$title.' »',
                    array( 'controller' => 'personnes', 'action' => 'edit', $personne['Personne']['id'] )
                ).' </li>';
            }

        ?>
    </ul>

<div id="fichePers">
    <table>
        <tbody>
            <tr class="even">
                <th><?php __( 'rolepers' );?></th>
                <td><?php echo isset( $rolepers[$personne['Prestation']['rolepers']] ) ? $rolepers[$personne['Prestation']['rolepers']] : null ;?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'qual' );?></th>
                <td><?php echo isset( $qual[$personne['Personne']['qual']] ) ? $qual[$personne['Personne']['qual']] : null ;?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'nom' );?></th>
                <td><?php echo $personne['Personne']['nom'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'prenom' );?></th>
                <td><?php echo $personne['Personne']['prenom'];?></td>
            </tr>
              <tr class="even">
                <th><?php __( 'nomnai' );?></th>
                <td><?php echo $personne['Personne']['nomnai'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'prenom2' );?></th>
                <td><?php echo $personne['Personne']['prenom2'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'prenom3' );?></th>
                <td><?php echo $personne['Personne']['prenom3'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'nomcomnai' );?></th>
                <td><?php echo $personne['Personne']['nomcomnai'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'dtnai' );?></th>
                <td><?php echo date_short( $personne['Personne']['dtnai'] );?></td>
            </tr>
             <tr class="odd">
                <th><?php __( 'rgnai' );?></th>
                <td><?php echo $personne['Personne']['rgnai'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'typedtnai' );?></th>
                <td><?php echo isset( $typedtnai[$personne['Personne']['typedtnai']] ) ? $typedtnai[$personne['Personne']['typedtnai']] : null;?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'nir' );?></th>
                <td><?php echo $personne['Personne']['nir'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'topvalec' );?></th>
                <td><?php echo ( $personne['Personne']['topvalec'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'sexe' );?></th>
                <td><?php echo $sexe[$personne['Personne']['sexe']];?></td>
            </tr>
        </tbody>
    </table>
<!--            <h2></h2>-->
    <table>
        <tbody>
            <tr class="even">
                <th><?php __( 'nati' );?></th>
                <td><?php echo isset( $nationalite[$personne['Personne']['nati']] ) ? $nationalite[$personne['Personne']['nati']] : null;?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'dtnati' );?></th>
                <td><?php echo date_short( isset( $personne['Personne']['dtnati'] ) ) ? date_short( $personne['Personne']['dtnati'] ) : null;?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'pieecpres' );?></th>
                <td><?php echo isset( $pieecpres[$personne['Personne']['pieecpres']] ) ? $pieecpres[$personne['Personne']['pieecpres']] : null;?></td>
            </tr>
        </tbody>
    </table>
    <?php if( $sexe[$personne['Personne']['sexe']] == 'Femme' ):?>
        <table>
            <?php echo thead( 10 );?>
            <tbody>
                <tr class="odd">
                    <th><?php __( 'ddgro' );?></th>
                    <td><?php echo date_short( isset( $grossesse['Grossesse']['ddgro'] ) ) ? date_short( $grossesse['Grossesse']['ddgro'] ) : null;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'dfgro' );?></th>
                    <td><?php echo date_short( isset( $grossesse['Grossesse']['dfgro'] ) ) ? date_short( $grossesse['Grossesse']['dfgro'] ) : null;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'dtdeclgro' );?></th>
                    <td><?php echo date_short( isset( $grossesse['Grossesse']['dtdeclgro'] ) ) ? date_short( $grossesse['Grossesse']['dtdeclgro'] ) : null;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'natfingro' );?></th>
                    <td><?php echo isset( $grossesse['Grossesse']['natfingro'] ) ? $grossesse['Grossesse']['natfingro'] : null;?></td>
                </tr>
            </tbody>
        </table>
    <?php endif;?>

</div>
</div>
<div class="clearer"><hr /></div>