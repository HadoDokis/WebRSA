<?php
    class WidgetHelper extends AppHelper
    {
        var $helpers = array( 'Html', 'Session', 'Form' );

        // --------------------------------------------------------------------

        function booleanRadio( $fieldName, $attributes = array() ) {
            $ret = '<fieldset class="boolean">';
            $ret .= '<legend>'.$attributes['legend'].'</legend>';
            $attributes['legend'] = false;
            $ret .= $this->Form->radio( $fieldName, array( 1 => 'Oui', 0 => 'Non' ), $attributes );
            $ret .= '</fieldset>';
            return $ret;
        }

        // --------------------------------------------------------------------

        // FIXME: changer l'existant (hack HtmlHelper) par cette fonction.
//         function button( $type, $url, $title = '', $enabled = true ) {
//             $iconFileSuffix = ( ( $enabled ) ? '' : '_disabled' ); // TODO: les autres aussi
//
//             switch( $type ) {
//                 case 'add':
//                     $icon = 'icons/add'.$iconFileSuffix.'.png';
//                     $text = 'Ajouter';
//                     break;
//                 case 'edit':
//                     $icon = 'icons/pencil'.$iconFileSuffix.'.png';
//                     $text = 'Modifier';
//                     break;
//                 case 'delete':
//                     $icon = 'icons/delete'.$iconFileSuffix.'.png';
//                     $text = 'Supprimer';
//                     break;
//                 case 'print':
//                     $icon = 'icons/printer'.$iconFileSuffix.'.png';
//                     $text = 'Imprimer';
//                     break;
//                 case 'view':
//                     $icon = 'icons/zoom'.$iconFileSuffix.'.png';
//                     $text = 'Voir';
//                     break;
//                 default:
//                     $this->cakeError( 'error500' ); // FIXME -> proprement --> $this->cakeError( 'wrongParameter' )
//             }
//
//             $content = $this->Html->image(
//                 $icon,
//                 array( 'alt' => '' )
//             ).' '.$text;
//
//             if( $enabled ) {
//                 return $this->Html->link(
//                     $content,
//                     $url,
//                     array( 'escape' => false, 'title' => $title, 'class' => 'widget button '.$type.' enabled' )
//                 );
//             }
//             else {
//                 return '<span class="widget button '.$type.' disabled" title="'.h( $title ).'">'.$content.'</span>';
//             }
//         }
    }
?>