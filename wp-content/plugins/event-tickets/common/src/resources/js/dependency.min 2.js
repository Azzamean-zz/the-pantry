!function(e,n,d){"use strict";var t=e(document),i=e(window);d.selectors={dependent:".tribe-dependent",active:".tribe-active",dependency:".tribe-dependency",dependencyVerified:".tribe-dependency-verified",dependencyManualControl:"[data-dependency-manual-control]",fields:"input, select, textarea",advanced_fields:".select2-container",linked:".tribe-dependent-linked"},d.constraintConditions={condition:function(e,d){return n.isArray(d)?-1!==d.indexOf(e):e==d},not_condition:function(e,d){return n.isArray(d)?-1===d.indexOf(e):e!=d},is_not_empty:function(e){return""!=e},is_empty:function(e){return""===e},is_numeric:function(n){return e.isNumeric(n)},is_not_numeric:function(n){return!e.isNumeric(n)},is_checked:function(e,n,d){return!(!d.is(":checkbox")&&!d.is(":radio"))&&d.is(":checked")},is_not_checked:function(e,n,d){return!(!d.is(":checkbox")&&!d.is(":radio"))&&!d.is(":checked")}},d.verify=function(i){var o=e(this),c="#"+o.attr("id"),a=o.val();if(c){if(o.is(":radio")){var s=e("[name='"+o.attr("name")+"']");s.not(d.selectors.linked).on("change",function(){s.trigger("verify.dependency")}).addClass(d.selectors.linked.replace(".",""))}var r=t.find('[data-depends="'+c+'"]').not(".select2-container");0!==r.length&&(r.each(function(t,i){var c=e(i);if(c.is("[data-dependent-parent]")){var s=c.data("dependentParent"),r=c.closest(s);if(0===r.length)return void console.warn("Dependency: `data-dependent-parent` has bad selector",c);c=r.find(i)}var l={condition:!!c.is("[data-condition]")&&c.data("condition"),not_condition:!!c.is("[data-condition-not]")&&c.data("conditionNot"),is_not_empty:c.data("conditionIsNotEmpty")||c.is("[data-condition-is-not-empty]")||c.data("conditionNotEmpty")||c.is("[data-condition-not-empty]"),is_empty:c.data("conditionIsEmpty")||c.is("[data-condition-is-empty]")||c.data("conditionEmpty")||c.is("[data-condition-empty]"),is_numeric:c.data("conditionIsNumeric")||c.is("[data-condition-is-numeric]")||c.data("conditionNumeric")||c.is("[data-condition-numeric]"),is_not_numeric:c.data("conditionIsNotNumeric")||c.is("[data-condition-is-not-numeric]"),is_checked:c.data("conditionIsChecked")||c.is("[data-condition-is-checked]")||c.data("conditionChecked")||c.is("[data-condition-checked]"),is_not_checked:c.data("conditionIsNotChecked")||c.is("[data-condition-is-not-checked]")||c.data("conditionNotChecked")||c.is("[data-condition-not-checked]")},p=d.selectors.active.replace(".",""),u=c.is("[data-dependency-check-disabled]"),f=c.is("[data-dependency-always-visible]"),y=!u&&o.is(":disabled"),h=c.data("condition-relation")||"or";l=n.pick(l,function(e){return!1!==e}),("or"===h?n.reduce(l,function(e,n,t){return e||d.constraintConditions[t](a,n,o)},!1):n.reduce(l,function(e,n,t){return e&&d.constraintConditions[t](a,n,o)},!0))&&!y?(c.is(".tribe-dropdown, .tribe-ea-dropdown")?(c.select2().data("select2").$container.addClass(p),c.select2().data("select2").$container.is(":hidden")&&c.select2().data("select2").$container.show()):(c.addClass(p),c.is(":hidden")&&c.show()),f&&c.filter(d.selectors.fields).prop("disabled",!1),c.find(d.selectors.fields).not(d.selectors.dependencyManualControl).prop("disabled",!1),void 0!==e().select2&&c.find(".tribe-dropdown, .tribe-ea-dropdown").select2().prop("disabled",!1)):(c.removeClass(p),c.is(":visible")&&c.hide(),c.data("dependency-dont-disable")||c.find(d.selectors.fields).not(d.selectors.dependencyManualControl).prop("disabled",!0),void 0!==e().select2&&c.find(".tribe-dropdown, .tribe-ea-dropdown").select2().prop("disabled",!0),c.is(".tribe-dropdown, .tribe-ea-dropdown")&&c.select2().data("select2").$container.removeClass(p),f&&(c.addClass(p).show(),c.filter(d.selectors.fields).prop("disabled",!0),c.is(".tribe-dropdown, .tribe-ea-dropdown")&&c.select2().data("select2").$container.addClass(p).show()));var m=c.find(d.selectors.dependency);m.length>0&&m.trigger("change")}),o.addClass(d.selectors.dependencyVerified.className()))}},d.setup=function(n){var t=e(d.selectors.dependent);t.length&&t.dependency();var i=e(d.selectors.dependency);i.not(d.selectors.dependencyVerified).length&&i.trigger("verify.dependency")},e.fn.dependency=function(){return this.each(function(){var n=e(this),t=n.data("depends"),i=e(t);i.length&&(i.get(0).created||(i.addClass(d.selectors.dependency.replace(".","")).data("dependent",n),i.get(0).created=!0))})},t.on("setup.dependency",d.setup),t.off("change.dependency verify.dependency",d.selectors.dependency),t.on({"verify.dependency":d.verify,"change.dependency":d.verify},d.selectors.dependency),t.ready(d.setup),i.on("load",d.setup)}(jQuery,window.underscore||window._,{});