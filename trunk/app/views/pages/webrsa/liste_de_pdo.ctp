<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml" lang="fr"><head>

    
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">        <title>
            Situation de l'allocataire        </title>
        
	<link rel="stylesheet" type="text/css" href="liste_de_pdo_fichiers/all_002.css" media="all">

	<link rel="stylesheet" type="text/css" href="liste_de_pdo_fichiers/all.css" media="all">

	<link rel="stylesheet" type="text/css" href="liste_de_pdo_fichiers/screen.css" media="screen,presentation">

	<link rel="stylesheet" type="text/css" href="liste_de_pdo_fichiers/print.css" media="print">

	<link rel="stylesheet" type="text/css" href="liste_de_pdo_fichiers/menu.css" media="all">
        <script type="text/javascript" src="liste_de_pdo_fichiers/prototype.js"></script><script type="text/javascript" src="liste_de_pdo_fichiers/tooltip.js"></script><script type="text/javascript" src="liste_de_pdo_fichiers/webrsa.js"></script>        <!-- TODO: à la manière de cake, dans les vues qui en ont besoin -->
        <script type="text/javascript">
            // prototype
            document.observe("dom:loaded", function() {
                window.history.forward();

                var baseUrl = 'http://localhost/mhamzaoui/webrsa/';
                make_treemenus( baseUrl );
//                 make_table_tooltips();
                make_folded_forms();
                mkTooltipTables();

                // External links
                $$('a.external').each( function ( link ) {
                    $( link ).onclick = function() {
                        window.open( $( link ).href, 'external' ); return false;
                    };
                } );
            });
        </script>
        <script type="text/javascript">
        //<![CDATA[
            function printit(){
                if (window.print) {
                window.print() ;
                } else {
                    var WebBrowser = '<object id="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>';
                    document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
                        WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";
                }
            }
        //]]>
        </script>

        <!--[if IE]>
            <style type="text/css" media="screen, presentation">
                .treemenu { position: relative; }
                .treemenu, .treemenu *, #pageMenu, #pageWrapper { zoom: 1; }
            </style>
        <![endif]-->
    </head><body>
            <div id="pageWrapper">
            <div id="pageHeader">
    <!--        <div id="whois">&nbsp;</div>
    -->
    &nbsp;
</div>            <div id="menu1Wrapper">
    <div class="menu1">
        <ul>
                                    <li class="" id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                    <a href="#">Gestion des cohortes</a>                    <ul>
                    <!-- AJOUT POUR LA GESTION DES CONTRATS D'INSERTION (Cohorte) -->
                        <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                            <a href="http://localhost/mhamzaoui/webrsa/cohortesci" title="Gestion des contrats">Contrat insertion</a>                        </li> 
                    <!-- MODIF POUR LA GESTION DES ORIENTATIONS (Cohorte) -->
                        <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                            <a href="#">Orientation</a>                                <ul>
                                    <li><a href="http://localhost/mhamzaoui/webrsa/cohortes/nouvelles" title="Nouvelles demandes">Nouvelles demandes</a></li>
                                    <li><a href="http://localhost/mhamzaoui/webrsa/cohortes/orientees" title="Demandes orientées">Demandes orientées</a></li>
                                    <li><a href="http://localhost/mhamzaoui/webrsa/cohortes/enattente" title="Demandes en attente">En attente</a></li>
                                    <!-- <li><a href="/mhamzaoui/webrsa/cohortes/exports_index" title="Fichiers exportés">Fichiers Exportés</a></li> -->
                                    <!--<li><a href="#">Liste suivant critères</a></li>
                                    <li><a href="#">Gestion des éditions</a></li> -->
                                </ul>
                        </li>
                        <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                            <a href="http://localhost/mhamzaoui/webrsa/pages/display/recherche_indus">Indus</a>                        </li> 
                    </ul>
                </li> 
                                        <li id="menu2one">
                    <a href="#">Recherche multicritères</a>                    <ul>
                        <li><a href="http://localhost/mhamzaoui/webrsa/">Par dossier / allocataire</a></li>
                        <li><a href="http://localhost/mhamzaoui/webrsa/criteres">Par Orientation</a></li>
                        <li><a href="http://localhost/mhamzaoui/webrsa/criteresci">Par Contrat insertion</a></li>
                    </ul>
                </li>
                                        <li id="menu2one">
                    <a href="#">Insertion</a>                    <ul>
                        <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/liste_appels_a_projet">Appels à projet</a></li>
			<li><a href="http://localhost/mhamzaoui/webrsa/pages/display/recherche_suivi_stagiaire">Suivi des stagiaires</a></li>
			<li><a href="http://localhost/mhamzaoui/webrsa/pages/display/demande_remboursement">Demande de remboursement</a></li>
                    </ul>
                </li>
                                        <li id="menu2one">
                    <a href="#">Tableaux de bord</a>                    <ul>
			<li><a href="http://localhost/mhamzaoui/webrsa/pages/display/indicateurs_mensuels">Indicateurs mensuels</a></li>
                    </ul>
                </li>
                                            <li id="menu3one">
                        <a href="#">Administration</a>                        <ul>
                            <li><a href="http://localhost/mhamzaoui/webrsa/droits/edit">Droits</a></li>
                            <li><a href="http://localhost/mhamzaoui/webrsa/parametrages">Paramétrage</a></li>
			    <li><a href="#">Moteur de règle</a>				<ul>
				    <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/moteur_regle_1">Orientation</a></li>
				    <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/moteur_regle_2">Recherche dossier/allocataire</a></li>
				    <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/moteur_regle_3">Recherche orientation</a></li>
				    <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/moteur_regle_4">Recherche contrat d'insertion</a></li>
				 <!--   <li><a href="/mhamzaoui/webrsa/pages/display/moteur_regle_2">Contrat d&#039;insertion</a></li> -->
				</ul>
			    </li>
			    <li><a href="#">Paiement allocation</a>				<ul>
				    <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/criteres_paiements_allocations">Listes nominatives</a></li>
				    <li><a href="http://localhost/mhamzaoui/webrsa/pages/display/criteres_mandats_paiements">Mandats mensuels</a></li>
				</ul>
			    </li>
                        <!--  <li><a href="#">Intégration flux</a></li>
                            <li><a href="#">Gestion des logs</a></li>
                            <li><a href="#">Gestion des éditions</a></li>-->
                        </ul>
                    </li>
                        <li id="menu4one"><a href="http://localhost/mhamzaoui/webrsa/users/logout">Déconnexion webrsa</a></li>
                    </ul>
    </div>
</div>


              <!--  -->

    <div id="pageCartouche">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Groupe</th>
                    <th>Service instructeur</th>
                    <!-- <th>Zones géographiques</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> Hamzaoui </td>
                    <td> Michel </td>
                    <td> Administrateurs </td>
                    <td> Service Social Départemental </td>
                    <!--<td>
                        <ul>
                                                    </ul>
                    </td>-->
                </tr>
            </tbody>
        </table>
    </div>
            <div id="pageContent">
                                

<div class="treemenu">
    <h2><a href="http://localhost/mhamzaoui/webrsa/dossiers/view">Dossier RSA </a></h2>
    <ul>
        <li><a href="http://localhost/mhamzaoui/webrsa/personnes">Composition du foyer</a>            <a class="toggler" href="#"><img alt="Étendre" src="liste_de_pdo_fichiers/bullet_toggle_plus.png"></a><ul style="display: none;">
                            </ul>
        </li>
        <!-- TODO: permissions à partir d'ici et dans les fichiers concernés -->
        <li><span>Informations foyer</span>
            <a class="toggler" href="#"><img alt="Réduire" src="liste_de_pdo_fichiers/bullet_toggle_minus.png"></a><ul style="">
                                    <li><a href="http://localhost/mhamzaoui/webrsa/adressesfoyers">Adresses</a>                                            </li>
                
                                    <li>
                        <a href="http://localhost/mhamzaoui/webrsa/modescontact">Modes de contact</a>                    </li>
                
                                    <li>
                        <a href="http://localhost/mhamzaoui/webrsa/avispcgdroitrsa">Avis PCG droit rsa</a>                    </li>
                
                                    <li>
                        <a href="http://localhost/mhamzaoui/webrsa/infosfinancieres">Informations financières</a>                    </li>
                
                                    <li><span>Situation dossier rsa</span>
                        	  <a class="toggler" href="#"><img alt="Réduire" src="liste_de_pdo_fichiers/bullet_toggle_minus.png"></a><ul style="">
					<li>
					    <a href="http://localhost/mhamzaoui/webrsa/detailsdroitsrsa">Détails du droit RSA</a>					</li>
					<li>
					  <a href="http://localhost/mhamzaoui/webrsa/pages/display/historique_du_droit">Historique du droit</a>					</li>
					<li>
					  <a href="http://localhost/mhamzaoui/webrsa/pages/display/liste_de_pdo">Consultation dossier PDO</a>					</li>
				      
				      <li>
					  <a href="http://localhost/mhamzaoui/webrsa/pages/display/liste_des_indus">Liste des indus</a>				      </li>

				  </ul>
                    </li>
                
                                   <li>
                        <a href="http://localhost/mhamzaoui/webrsa/suivisinstruction">Suivi instruction du dossier</a>                    </li>
                                                   <li>
                        <a href="http://localhost/mhamzaoui/webrsa/detailsdroitsrsa">Détails du droit RSA</a>                    </li>
                            </ul>
        </li>

                    <li><a href="http://localhost/mhamzaoui/webrsa/dspfs/view">Données socio-professionnelles</a></li>        	<li>
	    <a href="http://localhost/mhamzaoui/webrsa/pages/display/suivi_parcours_insertion">Suivi du parcours d'insertion</a>	</li>
            </ul>
</div>

<div class="with_treemenu">
    <h1>Situation du droit</h1>

            <ul class="actionMenu">

                
            <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Etat du dossier</th>
		    <th>Date de refus (le cas échéant)</th>
		    <th>Date fin de droit (le cas échéant)</th>
		    <th>Dossier PDO lié</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
		    <td>Droit ouvert et versable</td>
		    <td></td>
		    <td></td>
		    <td>Non</td>
		</tr>
            </tbody>
        </table>
    </ul></div><br><hr><br>


    <div class="with_treemenu">
	<h2>Listes des PDO</h2>

	<ul class="actionMenu">
	    <li><a href=""><img src="liste_de_pdo_fichiers/add.png" alt="">Ajouter</a>  </li>
	</ul>

            <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Numéro PDO</th>
                    <th>Nature de la PDO</th>
		    <th>Date soumission CAF</th>
		    <th>Décision du Conseil Général</th>
		    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>12</td>
		    <td>PDO d'ouverture de droits</td>
		    <td>11/02/2009</td>
		    <td>Accord CG</td>
		    <td><a href="http://localhost/mhamzaoui/webrsa/pages/display/demande_pdo" title=""><img src="liste_de_pdo_fichiers/zoom.png" alt=""> Voir</a></td><td><a href="http://localhost/mhamzaoui/webrsa/pages/display/validation_pdo" title=""><img src="liste_de_pdo_fichiers/pencil.png" alt=""> Modifier</a></td>
		</tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
		    <td style="background: rgb(230, 238, 242) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">19</td>
		    <td style="background: rgb(230, 238, 242) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">PDO de maintien</td>
		    <td style="background: rgb(230, 238, 242) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">01/06/2009</td>
		    <td style="background: rgb(230, 238, 242) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">Accord CG</td>
		    <td style="background: rgb(230, 238, 242) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;"><a href="http://localhost/mhamzaoui/webrsa/pages/display/demande_pdo" title="demande pdo"><img src="liste_de_pdo_fichiers/zoom.png" alt=""> Voir</a></td> <td style="background: rgb(230, 238, 242) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;"><a href="http://localhost/mhamzaoui/webrsa/pages/display/validation_pdo" title="validation pdo"><img src="liste_de_pdo_fichiers/pencil.png" alt=""> Modifier</a></td>
		</tr>
            </tbody>
        </table>
    </div><br>
  <div class="clearer"><hr></div>            </div>
            <div id="pageFooter">
    webrsa v. 1.0.4.398 (CakePHP v. 1.2.2.8120) - 2009@Adullact.
    Page construite en 0,37 secondes.    $LastChangedDate: 2009-07-27 17:51:26 +0200 (lun., 27 juil. 2009)$
</div>
        </div>
    <div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable0</div><div style="display: none;" class="tooltip">innerTable1</div><div style="display: none;" class="tooltip">innerTable1</div><div style="display: none;" class="tooltip">innerTable1</div><div style="display: none;" class="tooltip">innerTable1</div><div style="display: none;" class="tooltip">innerTable1</div><div style="display: none;" class="tooltip">innerTable1</div></body></html>