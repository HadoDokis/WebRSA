(function($){$.extend({create:function(element,attributes,children,root){if(typeof(root)=='undefined'){root=document}var elem=$(root.createElement(element));if(typeof(attributes)=='object'){for(key in attributes){elem.attr(key,attributes[key]);}}if(typeof(children)=='object'){for(i=0;i<children.length;i++){elem.append(children[i]);}}else if(typeof(children)!='undefined'&&children!=null){elem.text(children.toString());}return elem;}});})(jQuery);