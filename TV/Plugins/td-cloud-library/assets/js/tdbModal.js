var $jscomp=$jscomp||{};$jscomp.scope={};$jscomp.arrayIteratorImpl=function(a){var b=0;return function(){return b<a.length?{done:!1,value:a[b++]}:{done:!0}}};$jscomp.arrayIterator=function(a){return{next:$jscomp.arrayIteratorImpl(a)}};$jscomp.makeIterator=function(a){var b="undefined"!=typeof Symbol&&Symbol.iterator&&a[Symbol.iterator];return b?b.call(a):$jscomp.arrayIterator(a)};$jscomp.arrayFromIterator=function(a){for(var b,c=[];!(b=a.next()).done;)c.push(b.value);return c};
$jscomp.arrayFromIterable=function(a){return a instanceof Array?a:$jscomp.arrayFromIterator($jscomp.makeIterator(a))};var tdbModal={};jQuery().ready(function(){tdbModal.init()});
(function(){tdbModal={items:[],item:function(){this.body=this.title=this.customClasses=this.modalObj=this.uid="";this.cancelBtnTxt=window.tdb_modal_cancel;this.confirmBtnTxt=window.tdb_modal_confirm;this.validateFormInputs=void 0;this._is_initialized=this._is_modal_open=!1},init:function(){tdbModal.items=[];this._is_running=!1},addItem:function(a){if("undefined"===typeof a.uid)throw"item.uid is not defined";tdbModal.items.push(a);tdbModal._initialize_item(a)},deleteItem:function(a){for(var b=0;b<
tdbModal.items.length;b++)if(tdbModal.items[b].uid===a)return a=jQuery("body"),a.hasClass("tdb-s-modal-prevent-scroll")&&a.removeClass("tdb-s-modal-prevent-scroll"),jQuery("#tdb_modal_"+tdbModal.items[b].uid).remove(),tdbModal.items.splice(b,1),!0;return!1},resetItems:function(){tdbModal.items=[]},buildModal:function(a){var b=jQuery("body"),c=Math.floor(65536*(1+Math.random())).toString(16).substring(1);b.prepend('<div id="tdb_modal_'+c+'" class="tdb-s-modal-wrap '+a.customClasses+'"><div class="tdb-s-modal-bg"></div><div class="tdb-s-modal"><div class="tdb-s-modal-header"><h3 class="tdb-s-modal-title">'+
a.title+'</h3><svg class="tdb-s-modal-close" mlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"><g transform="translate(-5 -5)"><path d="M0,13a1,1,0,0,1-.707-.293,1,1,0,0,1,0-1.414l12-12a1,1,0,0,1,1.414,0,1,1,0,0,1,0,1.414l-12,12A1,1,0,0,1,0,13Z" transform="translate(6 6)"/><path d="M12,13a1,1,0,0,1-.707-.293l-12-12a1,1,0,0,1,0-1.414,1,1,0,0,1,1.414,0l12,12A1,1,0,0,1,12,13Z" transform="translate(6 6)"/></g></svg></div><div class="tdb-s-modal-body">'+a.body+'</div><div class="tdb-s-modal-footer"><button class="tdb-s-btn tdb-s-btn-sm tdb-s-btn-hollow tdb-s-modal-close tdb-s-modal-cancel">'+
a.cancelBtnTxt+'</button> <button class="tdb-s-btn tdb-s-btn-sm tdb-s-modal-confirm">'+a.confirmBtnTxt+"</button></div></div></div>");b=jQuery("#tdb_modal_"+c);a.uid=c;a.modalObj=b},openModal:function(a){if(!a._is_modal_open){var b=jQuery("body");setTimeout(function(){a.modalObj.addClass("tdb-s-modal-open");a._is_modal_open=!0;b.addClass("tdb-s-modal-prevent-scroll");a.modalObj.trigger("tdbModal.open")},1)}},closeModal:function(a){if(a._is_modal_open){var b=jQuery("body");a.modalObj.removeClass("tdb-s-modal-open");
a._is_modal_open=!1;b.removeClass("tdb-s-modal-prevent-scroll");a.modalObj.trigger("tdbModal.close");setTimeout(function(){tdbModal.deleteItem(a.uid)},300)}},_initialize_item:function(a){!0!==a._is_initialized&&(tdbModal.buildModal(a),a.modalObj.on("click",".tdb-s-modal-close, .tdb-s-modal-bg",function(b){b.preventDefault();a.modalObj.trigger("tdbModal.cancel");tdbModal.closeModal(a)}),a.modalObj.on("click",".tdb-s-modal-confirm",function(b){b.preventDefault();a.modalObj.trigger("tdbModal.confirming")}),
a.modalObj.on("tdbModal.confirming",function(){var b=a.modalObj.find(".tdb-s-modal-confirm"),c=a.modalObj.find(".tdb-s-modal-cancel");b.addClass("tdb-s-btn-saving");b.prop("disabled",!0);c.prop("disabled",!0)}),a.modalObj.on("tdbModal.confirmingReset",function(){var b=a.modalObj.find(".tdb-s-modal-confirm"),c=a.modalObj.find(".tdb-s-modal-cancel");b.removeClass("tdb-s-btn-saving");b.prop("disabled",!1);c.prop("disabled",!1)}),a.modalObj.on("tdbModal.confirmed",function(){tdbModal.closeModal(a)}),
a._is_initialized=!0)},_stringToBinary:function(a){for(var b=new Uint16Array(a.length),c=0;c<b.length;c++)b[c]=a.charCodeAt(c);return btoa(String.fromCharCode.apply(String,$jscomp.arrayFromIterable(new Uint8Array(b.buffer))))},_stringFromBinary:function(a){a=atob(a);for(var b=new Uint8Array(a.length),c=0;c<b.length;c++)b[c]=a.charCodeAt(c);return String.fromCharCode.apply(String,$jscomp.arrayFromIterable(new Uint16Array(b.buffer)))},_validate_form_inputs:function(a){if("undefined"!==typeof a.validateFormInputs){var b=
!0;a.modalObj.find(".tdb-s-form-group").removeClass("tdb-s-fg-error");a.modalObj.find(".tdb-s-fg-error-msg").remove();jQuery.each(a.validateFormInputs,function(c,e){var d=a.modalObj.find("#"+e.id);if(d.length){c=d.closest(".tdb-s-form-group");e=e.rules.split("|");var f="";jQuery.each(e,function(a,b){switch(b){case "file":if(!0===d.data("no-save"))return!1;break;case "required":if(""===d.val())return f="This field is required.",!1;break;case "email":if(!d.val().match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/))return f=
"Please enter a valid email.",!1}});""!==f&&(c.addClass("tdb-s-fg-error"),c.append('<span class="tdb-s-fg-error-msg">'+f+"</span>"),b=!1)}});if(!b)return!1}return!0}}})();
