<?php
// 开发, 测试, demo 功能3合1
namespace App\development\Contr;
use \Main\Core\Controller;
//use \Main\Core\F;
defined('IN_SYS') || exit('ACC Denied');
    
class indexContr extends Controller\HttpController {
    public function indexDo() {
        
        
//        obj('Main/Core/Minifier')->minify($js);
        $js = <<<JS
(function( factory ){
	if ( typeof define === 'function' && define.amd ) {
		// AMD
		define( ['jquery', 'datatables.net'], function ( $ ) {
			return factory( $, window, document );
		} );
	}
	else if ( typeof exports === 'object' ) {
		// CommonJS
		module.exports = function (root, $) {
			if ( ! root ) {
				root = window;
			}

			if ( ! $ || ! $.fn.dataTable ) {
				// Require DataTables, which attaches to jQuery, including
				// jQuery if needed and have a $ property so we can access the
				// jQuery object that is used
				$ = require('datatables.net')(root, $).$;
			}

			return factory( $, root, root.document );
		};
	}
	else {
		// Browser
		factory( jQuery, window, document );
	}
}(function( $, window, document, undefined ) {
'use strict';
var DataTable = $.fn.dataTable;


/* Set the defaults for DataTables initialisation */
$.extend( true, DataTable.defaults, {
	dom:
		"<'row uk-grid'<'uk-width-1-2'l><'uk-width-1-2'f>>" +
		"<'row uk-grid dt-merge-grid'<'uk-width-1-1'tr>>" +
		"<'row uk-grid dt-merge-grid'<'uk-width-2-5'i><'uk-width-3-5'p>>",
	renderer: 'uikit'
} );


/* Default class modification */
$.extend( DataTable.ext.classes, {
	sWrapper:      "dataTables_wrapper uk-form dt-uikit",
	sFilterInput:  "uk-form-small",
	sLengthSelect: "uk-form-small",
	sProcessing:   "dataTables_processing uk-panel"
} );


/* UIkit paging button renderer */
DataTable.ext.renderer.pageButton.uikit = function ( settings, host, idx, buttons, page, pages ) {
	var api     = new DataTable.Api( settings );
	var classes = settings.oClasses;
	var lang    = settings.oLanguage.oPaginate;
	var aria = settings.oLanguage.oAria.paginate || {};
	var btnDisplay, btnClass, counter=0;

	var attach = function( container, buttons ) {
		var i, ien, node, button;
		var clickHandler = function ( e ) {
			e.preventDefault();
			if ( !$(e.currentTarget).hasClass('disabled') && api.page() != e.data.action ) {
				api.page( e.data.action ).draw( 'page' );
			}
		};

		for ( i=0, ien=buttons.length ; i<ien ; i++ ) {
			button = buttons[i];

			if ( $.isArray( button ) ) {
				attach( container, button );
			}
			else {
				btnDisplay = '';
				btnClass = '';

				switch ( button ) {
					case 'ellipsis':
						btnDisplay = '<i class="uk-icon-ellipsis-h"></i>';
						btnClass = 'uk-disabled disabled';
						break;

					case 'first':
						btnDisplay = '<i class="uk-icon-angle-double-left"></i> ' + lang.sFirst;
						btnClass = (page > 0 ?
							'' : ' uk-disabled disabled');
						break;

					case 'previous':
						btnDisplay = '<i class="uk-icon-angle-left"></i> ' + lang.sPrevious;
						btnClass = (page > 0 ?
							'' : 'uk-disabled disabled');
						break;

					case 'next':
						btnDisplay = lang.sNext + ' <i class="uk-icon-angle-right"></i>';
						btnClass = (page < pages-1 ?
							'' : 'uk-disabled disabled');
						break;

					case 'last':
						btnDisplay = lang.sLast + ' <i class="uk-icon-angle-double-right"></i>';
						btnClass = (page < pages-1 ?
							'' : ' uk-disabled disabled');
						break;

					default:
						btnDisplay = button + 1;
						btnClass = page === button ?
							'uk-active' : '';
						break;
				}

				if ( btnDisplay ) {
					node = $('<li>', {
							'class': classes.sPageButton+' '+btnClass,
							'id': idx === 0 && typeof button === 'string' ?
								settings.sTableId +'_'+ button :
								null
						} )
						.append( $(( -1 != btnClass.indexOf('disabled') || -1 != btnClass.indexOf('active') ) ? '<span>' : '<a>', {
								'href': '#',
								'aria-controls': settings.sTableId,
								'aria-label': aria[ button ],
								'data-dt-idx': counter,
								'tabindex': settings.iTabIndex
							} )
							.html( btnDisplay )
						)
						.appendTo( container );

					settings.oApi._fnBindAction(
						node, {action: button}, clickHandler
					);

					counter++;
				}
			}
		}
	};

	// IE9 throws an 'unknown error' if document.activeElement is used
	// inside an iframe or frame. 
	var activeEl;

	try {
		// Because this approach is destroying and recreating the paging
		// elements, focus is lost on the select button which is bad for
		// accessibility. So we want to restore focus once the draw has
		// completed
		activeEl = $(host).find(document.activeElement).data('dt-idx');
	}
	catch (e) {}

	attach(
		$(host).empty().html('<ul class="uk-pagination uk-pagination-right"/>').children('ul'),
		buttons
	);

	if ( activeEl ) {
		$(host).find( '[data-dt-idx='+activeEl+']' ).focus();
	}
};


return DataTable;
}));
JS;
        var_dump($js);
        echo '<hr><br>None true<hr>';
        var_dump(( new \Main\Core\JavaScriptPacker($js, 'None', false, true))->pack());
        
        echo '<hr><br>Numeric false<hr>';
        var_dump(( new \Main\Core\JavaScriptPacker($js, 'Numeric', false, false))->pack());
        
        echo '<hr><br>Numeric <hr>';
        var_dump(( new \Main\Core\JavaScriptPacker($js, 'Numeric', false, true))->pack());
        
        echo '<hr><br>Normal true <hr>';
        var_dump(( new \Main\Core\JavaScriptPacker($js, 'Normal', false, true))->pack());
        
        echo '<hr><br>Normal false <hr>';
        var_dump(( new \Main\Core\JavaScriptPacker($js, 'Normal', false, false))->pack());
        
        
       $content = <<<RRR
(function(b){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(a){return b(a,window,document)}):"object"===typeof exports?module.exports=function(a,c){a||(a=window);if(!c||!c.fn.dataTable)c=require("datatables.net")(a,c).$;return b(c,a,a.document)}:b(jQuery,window,document)})(function(b,a,c){var g=b.fn.dataTable;b.extend(!0,g.defaults,{dom:"<'row uk-grid'<'uk-width-1-2'l><'uk-width-1-2'f>><'row uk-grid dt-merge-grid'<'uk-width-1-1'tr>><'row uk-grid dt-merge-grid'<'uk-width-2-5'i><'uk-width-3-5'p>>",
renderer:"uikit"});b.extend(g.ext.classes,{sWrapper:"dataTables_wrapper uk-form dt-uikit",sFilterInput:"uk-form-small",sLengthSelect:"uk-form-small",sProcessing:"dataTables_processing uk-panel"});g.ext.renderer.pageButton.uikit=function(a,h,r,m,j,n){var o=new g.Api(a),s=a.oClasses,k=a.oLanguage.oPaginate,t=a.oLanguage.oAria.paginate||{},f,d,p=0,q=function(c,g){var l,h,i,e,m=function(a){a.preventDefault();!b(a.currentTarget).hasClass("disabled")&&o.page()!=a.data.action&&o.page(a.data.action).draw("page")};
l=0;for(h=g.length;l<h;l++)if(e=g[l],b.isArray(e))q(c,e);else{d=f="";switch(e){case "ellipsis":f='<i class="uk-icon-ellipsis-h"></i>';d="uk-disabled disabled";break;case "first":f='<i class="uk-icon-angle-double-left"></i> '+k.sFirst;d=0<j?"":" uk-disabled disabled";break;case "previous":f='<i class="uk-icon-angle-left"></i> '+k.sPrevious;d=0<j?"":"uk-disabled disabled";break;case "next":f=k.sNext+' <i class="uk-icon-angle-right"></i>';d=j<n-1?"":"uk-disabled disabled";break;case "last":f=k.sLast+
' <i class="uk-icon-angle-double-right"></i>';d=j<n-1?"":" uk-disabled disabled";break;default:f=e+1,d=j===e?"uk-active":""}f&&(i=b("<li>",{"class":s.sPageButton+" "+d,id:0===r&&"string"===typeof e?a.sTableId+"_"+e:null}).append(b(-1!=d.indexOf("disabled")||-1!=d.indexOf("active")?"<span>":"<a>",{href:"#","aria-controls":a.sTableId,"aria-label":t[e],"data-dt-idx":p,tabindex:a.iTabIndex}).html(f)).appendTo(c),a.oApi._fnBindAction(i,{action:e},m),p++)}},i;try{i=b(h).find(c.activeElement).data("dt-idx")}catch(u){}q(b(h).empty().html('<ul class="uk-pagination uk-pagination-right"/>').children("ul"),
m);i&&b(h).find("[data-dt-idx="+i+"]").focus()};return g});
RRR;
        echo '<hr><br>第三方 <hr>';
        var_dump($content);
       
        
       
        $this->display();
    }
    
    public function test($request){
        // 'account' => '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',
        var_dump($request->get);
        var_dump($this->get());
        exit;
        exit('test');
        
    }
    public function tt(){
        echo 'qweqwe';
    }


    public function __destruct() {
        \statistic();
    }
}
