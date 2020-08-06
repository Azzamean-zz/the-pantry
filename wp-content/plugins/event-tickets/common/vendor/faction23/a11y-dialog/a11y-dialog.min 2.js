!function(t){"use strict";function e(t){if(this.options=h({appendTarget:"",bodyLock:!0,closeButtonAriaLabel:"Close this dialog window",closeButtonClasses:"a11y-dialog__close-button",contentClasses:"a11y-dialog__content",effect:"none",effectSpeed:300,effectEasing:"ease-in-out",overlayClasses:"a11y-dialog__overlay",overlayClickCloses:!0,trigger:null,wrapperClasses:"a11y-dialog"},t),this._rendered=!1,this._show=this.show.bind(this),this._hide=this.hide.bind(this),this._maintainFocus=this._maintainFocus.bind(this),this._bindKeypress=this._bindKeypress.bind(this),this.trigger=a(this.options.trigger)?o(this.options.trigger,!1,document,!0)[0]:this.options.trigger,this.node=null,!this.trigger)return void console.warn("Lookup for a11y target node failed.");this._listeners={},this.create()}function i(t){var e=[],i=t.length;for(i;i--;e.unshift(t[i]));return e}function o(t,e,o,n){o||(o=document);var s=n?t:'[data-js="'+t+'"]',r=o.querySelectorAll(s);return e&&(r=i(r)),r}function n(t,e){return i((e||document).querySelectorAll(t))}function s(t){var e=d(t);e.length&&e[0].focus()}function r(t,e){e.parentNode.insertBefore(t,e.nextElementSibling)}function d(t){return n(p.join(","),t).filter(function(t){return!!(t.offsetWidth||t.offsetHeight||t.getClientRects().length)})}function a(t){return"[object String]"===Object.prototype.toString.call(t)}function h(t,e){return Object.keys(e).forEach(function(i){t[i]=e[i]}),t}function c(t,e){var i=d(t),o=i.indexOf(document.activeElement);e.shiftKey&&0===o?(i[i.length-1].focus(),e.preventDefault()):e.shiftKey||o!==i.length-1||(i[0].focus(),e.preventDefault())}function l(){g=b.scrollTop,document.body.classList.add("a11y-dialog__body-locked"),document.body.style.position="fixed",document.body.style.width="100%",document.body.style.marginTop="-"+g+"px"}function u(){document.body.style.marginTop="",document.body.style.position="",document.body.style.width="",b.scrollTop=g,document.body.classList.remove("a11y-dialog__body-locked")}var f,p=["a[href]","area[href]","input:not([disabled])","select:not([disabled])","textarea:not([disabled])","button:not([disabled])","iframe","object","embed","[contenteditable]",'[tabindex]:not([tabindex^="-"])'],y=function(){var t=/(android)/i.test(navigator.userAgent),e=!!window.chrome,i="undefined"!=typeof InstallTrigger,o=document.documentMode,n=!o&&!!window.StyleMedia,s=!!navigator.userAgent.match(/(iPod|iPhone|iPad)/i),r=!!navigator.userAgent.match(/(iPod|iPhone)/i),d=!!window.opera||navigator.userAgent.indexOf(" OPR/")>=0;return{android:t,chrome:e,edge:n,firefox:i,ie:o,ios:s,iosMobile:r,opera:d,safari:Object.prototype.toString.call(window.HTMLElement).indexOf("Constructor")>0||!e&&!d&&"undefined"!==window.webkitAudioContext,os:navigator.platform}}(),g=0,b=y.ie||y.firefox||y.chrome&&!y.edge?document.documentElement:document.body;e.prototype.create=function(){return this.shown=!1,this.trigger.addEventListener("click",this._show),this._fire("create"),this},e.prototype.render=function(t){var e=o(this.trigger.dataset.content)[0];if(!e)return this;var i=document.createElement("div");i.setAttribute("aria-hidden","true"),i.classList.add(this.options.wrapperClasses),i.innerHTML='<div data-js="a11y-overlay" tabindex="-1" class="'+this.options.overlayClasses+'"></div>\n  <div class="'+this.options.contentClasses+'" role="dialog">\n    <div role="document">\n      <button            data-js="a11y-close-button"           class="'+this.options.closeButtonClasses+'"            type="button"            aria-label="'+this.options.closeButtonAriaLabel+'"       ></button>\n      '+e.innerHTML+"    </div>\n  </div>";var n=this.trigger;return this.options.appendTarget.length&&(n=document.querySelectorAll(this.options.appendTarget)[0]||this.trigger),r(i,n),this.node=i,this.overlay=o("a11y-overlay",!1,this.node)[0],this.closeButton=o("a11y-close-button",!1,this.node)[0],this.options.overlayClickCloses&&this.overlay.addEventListener("click",this._hide),this.closeButton.addEventListener("click",this._hide),this._rendered=!0,this._fire("render",t),this},e.prototype.show=function(t){return this.shown?this:(this._rendered||this.render(t),this._rendered?(this.shown=!0,this._applyOpenEffect(),this.node.setAttribute("aria-hidden","false"),this.options.bodyLock&&l(),f=document.activeElement,s(this.node),document.body.addEventListener("focus",this._maintainFocus,!0),document.addEventListener("keydown",this._bindKeypress),this._fire("show",t),this):this)},e.prototype.hide=function(t){return this.shown?(this.shown=!1,this.node.setAttribute("aria-hidden","true"),this._applyCloseEffect(),this.options.bodyLock&&u(),f&&f.focus(),document.body.removeEventListener("focus",this._maintainFocus,!0),document.removeEventListener("keydown",this._bindKeypress),this._fire("hide",t),this):this},e.prototype.destroy=function(){return this.hide(),this.trigger.removeEventListener("click",this._show),this.options.overlayClickCloses&&this.overlay.removeEventListener("click",this._hide),this.closeButton.removeEventListener("click",this._hide),this._fire("destroy"),this._listeners={},this},e.prototype.on=function(t,e){return void 0===this._listeners[t]&&(this._listeners[t]=[]),this._listeners[t].push(e),this},e.prototype.off=function(t,e){var i=this._listeners[t].indexOf(e);return i>-1&&this._listeners[t].splice(i,1),this},e.prototype._fire=function(t,e){(this._listeners[t]||[]).forEach(function(t){t(this.node,e)}.bind(this))},e.prototype._bindKeypress=function(t){this.shown&&27===t.which&&(t.preventDefault(),this.hide()),this.shown&&9===t.which&&c(this.node,t)},e.prototype._maintainFocus=function(t){this.shown&&!this.node.contains(t.target)&&s(this.node)},e.prototype._applyOpenEffect=function(){var t=this;"fade"===this.options.effect&&(this.node.style.opacity="0",this.node.style.transition="opacity "+this.options.effectSpeed+"ms "+this.options.effectEasing,setTimeout(function(){t.node.style.opacity="1"},50))},e.prototype._applyCloseEffect=function(){var t=this;"fade"===this.options.effect&&(this.node.setAttribute("aria-hidden","false"),this.node.style.opacity="0",setTimeout(function(){t.node.style.transition="",t.node.setAttribute("aria-hidden","true")},this.options.effectSpeed))},"undefined"!=typeof module&&void 0!==module.exports?module.exports=e:"function"==typeof define&&define.amd?define("A11yDialog",[],function(){return e}):"object"==typeof t&&(t.A11yDialog=e)}("undefined"!=typeof global?global:window);