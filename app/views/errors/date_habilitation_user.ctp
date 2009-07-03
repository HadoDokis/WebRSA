<h1><?php echo $this->pageTitle = 'Erreur:  mauvaises dates d\'habilitation de l\'utilisateur';?></h1>
<p>Votre période d'habilitation n'est pas valide.</p>
<p>Veuillez vous reconnecter ultérieurement</p>

<!--
<?php //date_default_timezone_set( 'Europe/Paris' );
    $date_deb_hab = $this->viewVars['params']['habilitations']['date_deb_hab'];

    debug( array( $date_deb_hab, "2009-07-04" ) );
    debug( strcmp( $date_deb_hab, "2009-07-04" ) );

    //debug( strftime( "%d/%m/%Y", strtotime( $date_deb_hab ) ) );
    //debug( date_short( "2009-07-04" ) );
?> -->
<table>
    <tbody>
        <tr>
            <th>Date de début d'habilitation</th>
            <td><?php echo '<b>'.$this->viewVars['params']['habilitations']['date_deb_hab'].'</b>';?></td>
        </tr>
        <tr>
            <th>Date de fin d'habilitation</th>
            <td><?php echo '<b>'.$this->viewVars['params']['habilitations']['date_fin_hab'].'</b>';?></td>
        </tr>
    </tbody>
</table>
