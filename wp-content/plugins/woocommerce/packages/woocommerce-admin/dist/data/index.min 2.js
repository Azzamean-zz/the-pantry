this.wc=this.wc||{},this.wc.data=function(e){var t={};function r(n){if(t[n])return t[n].exports;var c=t[n]={i:n,l:!1,exports:{}};return e[n].call(c.exports,c,c.exports,r),c.l=!0,c.exports}return r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var c in e)r.d(n,c,function(t){return e[t]}.bind(null,c));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=705)}({0:function(e,t){!function(){e.exports=this.wp.element}()},13:function(e,t,r){"use strict";function n(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}r.d(t,"a",(function(){return n}))},17:function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var n=r(37);var c=r(52);function o(e){return function(e){if(Array.isArray(e))return Object(n.a)(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||Object(c.a)(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},19:function(e,t){!function(){e.exports=this.wp.data}()},2:function(e,t){!function(){e.exports=this.lodash}()},3:function(e,t){!function(){e.exports=this.wp.i18n}()},30:function(e,t){!function(){e.exports=this.wp.url}()},37:function(e,t,r){"use strict";function n(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}r.d(t,"a",(function(){return n}))},52:function(e,t,r){"use strict";r.d(t,"a",(function(){return c}));var n=r(37);function c(e,t){if(e){if("string"==typeof e)return Object(n.a)(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(r):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?Object(n.a)(e,t):void 0}}},57:function(e,t){!function(){e.exports=this.wp.dataControls}()},705:function(e,t,r){"use strict";r.r(t),r.d(t,"SETTINGS_STORE_NAME",(function(){return Q})),r.d(t,"withSettingsHydration",(function(){return Y})),r.d(t,"useSettings",(function(){return H})),r.d(t,"PLUGINS_STORE_NAME",(function(){return Te})),r.d(t,"withPluginsHydration",(function(){return ke}));var n={};r.r(n),r.d(n,"getSettingsGroupNames",(function(){return m})),r.d(n,"getSettings",(function(){return O})),r.d(n,"getDirtyKeys",(function(){return b})),r.d(n,"getIsDirty",(function(){return v})),r.d(n,"getSettingsForGroup",(function(){return j})),r.d(n,"isGetSettingsRequesting",(function(){return _})),r.d(n,"getSetting",(function(){return E})),r.d(n,"getLastSettingsErrorForGroup",(function(){return P})),r.d(n,"getSettingsError",(function(){return S}));var c={};r.r(c),r.d(c,"updateSettingsForGroup",(function(){return T})),r.d(c,"updateErrorForGroup",(function(){return k})),r.d(c,"setIsRequesting",(function(){return I})),r.d(c,"clearIsDirty",(function(){return A})),r.d(c,"updateAndPersistSettingsForGroup",(function(){return C})),r.d(c,"persistSettingsForGroup",(function(){return x})),r.d(c,"clearSettings",(function(){return U}));var o={};r.r(o),r.d(o,"getSettings",(function(){return F})),r.d(o,"getSettingsForGroup",(function(){return L}));var i={};r.r(i),r.d(i,"getActivePlugins",(function(){return X})),r.d(i,"getInstalledPlugins",(function(){return Z})),r.d(i,"isPluginsRequesting",(function(){return ee})),r.d(i,"getPluginsError",(function(){return te})),r.d(i,"isJetpackConnected",(function(){return re})),r.d(i,"getJetpackConnectUrl",(function(){return ne}));var a={};r.r(a),r.d(a,"updateActivePlugins",(function(){return ae})),r.d(a,"updateInstalledPlugins",(function(){return ue})),r.d(a,"setIsRequesting",(function(){return se})),r.d(a,"setError",(function(){return le})),r.d(a,"updateIsJetpackConnected",(function(){return pe})),r.d(a,"updateJetpackConnectUrl",(function(){return ge})),r.d(a,"installPlugin",(function(){return de})),r.d(a,"activatePlugins",(function(){return me}));var u={};r.r(u),r.d(u,"getActivePlugins",(function(){return Ee})),r.d(u,"getInstalledPlugins",(function(){return Pe})),r.d(u,"isJetpackConnected",(function(){return Se})),r.d(u,"getJetpackConnectUrl",(function(){return he}));var s=r(19),l=r(57),p="wc/admin/settings",g=r(17);function f(e,t){var r=JSON.stringify(t,Object.keys(t).sort());return"".concat(e,":").concat(r)}function d(e){var t=e.indexOf(":");return t<0?e:e.substring(0,t)}var m=function(e){var t=new Set(Object.keys(e).map((function(e){return d(e)})));return Object(g.a)(t)},O=function(e,t){var r={},n=e[t]&&e[t].data||[];return 0===n.length||n.forEach((function(n){r[n]=e[f(t,n)].data})),r},b=function(e,t){return e[t].dirty||[]},v=function(e,t){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[],n=b(e,t);return 0!==n.length&&r.some((function(e){return n.includes(e)}))},j=function(e,t,r){var n=O(e,t);return r.reduce((function(e,t){return e[t]=n[t]||{},e}),{})},_=function(e,t){return e[t]&&Boolean(e[t].isRequesting)};function E(e,t,r){var n=arguments.length>3&&void 0!==arguments[3]&&arguments[3],c=arguments.length>4&&void 0!==arguments[4]?arguments[4]:function(e){return e},o=f(t,r),i=e[o]&&e[o].data||n;return c(i,n)}var P=function(e,t){var r=e[t].data;return 0===r.length?e[t].error:Object(g.a)(r).pop().error},S=function(e,t,r){return r?e[f(t,r)].error||!1:e[t]&&e[t].error||!1},h=r(2),R={UPDATE_SETTINGS_FOR_GROUP:"UPDATE_SETTINGS_FOR_GROUP",UPDATE_ERROR_FOR_GROUP:"UPDATE_ERROR_FOR_GROUP",CLEAR_SETTINGS:"CLEAR_SETTINGS",SET_IS_REQUESTING:"SET_IS_REQUESTING",CLEAR_IS_DIRTY:"CLEAR_IS_DIRTY"},y=regeneratorRuntime.mark(C),w=regeneratorRuntime.mark(x);function T(e,t){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:new Date;return{type:R.UPDATE_SETTINGS_FOR_GROUP,group:e,data:t,time:r}}function k(e,t,r){var n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:new Date;return{type:R.UPDATE_ERROR_FOR_GROUP,group:e,data:t,error:r,time:n}}function I(e,t){return{type:R.SET_IS_REQUESTING,group:e,isRequesting:t}}function A(e){return{type:R.CLEAR_IS_DIRTY,group:e}}function C(e,t){return regeneratorRuntime.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,T(e,t);case 2:return r.delegateYield(x(e),"t0",3);case 3:case"end":return r.stop()}}),y)}function x(e){var t,r,n,c;return regeneratorRuntime.wrap((function(o){for(;;)switch(o.prev=o.next){case 0:return o.next=2,I(e,!0);case 2:return o.next=4,Object(l.select)(p,"getDirtyKeys",e);case 4:if(0!==(t=o.sent).length){o.next=9;break}return o.next=8,I(e,!1);case 8:return o.abrupt("return");case 9:return o.next=11,Object(l.select)(p,"getSettingsForGroup",e,t);case 11:return r=o.sent,n="".concat("/wc-analytics","/settings/").concat(e,"/batch"),c=t.reduce((function(e,t){var n=Object.keys(r[t]).map((function(e){return{id:e,value:r[t][e]}}));return Object(h.concat)(e,n)}),[]),o.prev=14,o.next=17,Object(l.apiFetch)({path:n,method:"POST",data:{update:c}});case 17:if(o.sent){o.next=20;break}throw new Error("settings did not update");case 20:return o.next=22,A(e);case 22:o.next=28;break;case 24:return o.prev=24,o.t0=o.catch(14),o.next=28,k(e,null,o.t0);case 28:return o.next=30,I(e,!1);case 30:case"end":return o.stop()}}),w,null,[[14,24]])}function U(){return{type:R.CLEAR_SETTINGS}}var G=r(13),D=regeneratorRuntime.mark(F),N=regeneratorRuntime.mark(L);function F(e){var t,r,n;return regeneratorRuntime.wrap((function(c){for(;;)switch(c.prev=c.next){case 0:return c.next=2,Object(l.dispatch)(p,"setIsRequesting",e,!0);case 2:return c.prev=2,t="/wc-analytics/settings/"+e,c.next=6,Object(l.apiFetch)({path:t,method:"GET"});case 6:return r=c.sent,n=r.reduce((function(e,t){return e[t.id]=t.value,e}),{}),c.abrupt("return",T(e,Object(G.a)({},e,n)));case 11:return c.prev=11,c.t0=c.catch(2),c.abrupt("return",k(e,null,c.t0.message));case 14:case"end":return c.stop()}}),D,null,[[2,11]])}function L(e){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.abrupt("return",F(e));case 1:case"end":return t.stop()}}),N)}function q(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function J(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?q(Object(r),!0).forEach((function(t){Object(G.a)(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):q(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var K=function(e,t){var r=t.group,n=t.groupIds,c=t.data,o=t.time,i=t.error;return n.forEach((function(t){e[f(r,t)]={data:c[t],lastReceived:o,error:i}})),e},W=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0,r=t.type,n=t.group,c=t.data,o=t.error,i=t.time,a=t.isRequesting,u={};switch(r){case R.SET_IS_REQUESTING:e=J({},e,Object(G.a)({},n,J({},e[n],{isRequesting:a})));break;case R.CLEAR_IS_DIRTY:e=J({},e,Object(G.a)({},n,J({},e[n],{dirty:[]})));break;case R.UPDATE_SETTINGS_FOR_GROUP:case R.UPDATE_ERROR_FOR_GROUP:var s=c?Object.keys(c):[];e=null===c?J({},e,Object(G.a)({},n,{data:e[n]?e[n].data:[],error:o,lastReceived:i})):J({},e,Object(G.a)({},n,{data:e[n]&&e[n].data?[].concat(Object(g.a)(e[n].data),Object(g.a)(s)):s,error:o,lastReceived:i,isRequesting:!1,dirty:e[n]&&e[n].dirty?Object(h.union)(e[n].dirty,s):s}),K(u,{group:n,groupIds:s,data:c,time:i,error:o}));break;case R.CLEAR_SETTINGS:e={}}return e};Object(s.registerStore)(p,{reducer:W,actions:c,controls:l.controls,selectors:n,resolvers:o});var Q=p,M=r(0),Y=function(e,t){return function(r){return function(n){var c=Object(M.useRef)(t);return Object(s.useSelect)((function(t,r){if(c.current){var n=t(p),o=n.isResolving,i=n.hasFinishedResolution,a=r.dispatch(p),u=a.startResolution,s=a.finishResolution,l=a.updateSettingsForGroup,g=a.clearIsDirty;o("getSettings",[e])||i("getSettings",[e])||(u("getSettings",[e]),l(e,c.current),g(e),s("getSettings",[e]))}}),[]),Object(M.createElement)(r,n)}}};function V(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function B(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?V(Object(r),!0).forEach((function(t){Object(G.a)(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):V(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var H=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[],r=Object(s.useSelect)((function(r){var n=r(p),c=n.getLastSettingsErrorForGroup,o=n.getSettingsForGroup,i=n.getIsDirty,a=n.isGetSettingsRequesting;return{requestedSettings:o(e,t),settingsError:Boolean(c(e)),isRequesting:a(e),isDirty:i(e,t)}}),[e,t]),n=r.requestedSettings,c=r.settingsError,o=r.isRequesting,i=r.isDirty,a=Object(s.useDispatch)(p),u=a.persistSettingsForGroup,l=a.updateAndPersistSettingsForGroup,g=a.updateSettingsForGroup,f=Object(M.useCallback)((function(t,r){g(e,Object(G.a)({},t,r))}),[e]),d=Object(M.useCallback)((function(){u(e)}),[e]),m=Object(M.useCallback)((function(t,r){l(e,Object(G.a)({},t,r))}),[e]);return B({settingsError:c,isRequesting:o,isDirty:i},n,{persistSettings:d,updateAndPersistSettings:m,updateSettings:f})},$=r(3),z={"facebook-for-woocommerce":Object($.__)("Facebook for WooCommerce",'woocommerce'),jetpack:Object($.__)("Jetpack",'woocommerce'),"klarna-checkout-for-woocommerce":Object($.__)("Klarna Checkout for WooCommerce",'woocommerce'),"klarna-payments-for-woocommerce":Object($.__)("Klarna Payments for WooCommerce",'woocommerce'),"mailchimp-for-woocommerce":Object($.__)("Mailchimp for WooCommerce",'woocommerce'),"woocommerce-gateway-paypal-express-checkout":Object($.__)("WooCommerce PayPal",'woocommerce'),"woocommerce-gateway-stripe":Object($.__)("WooCommerce Stripe",'woocommerce'),"woocommerce-payfast-gateway":Object($.__)("WooCommerce PayFast",'woocommerce'),"woocommerce-payments":Object($.__)("WooCommerce Payments",'woocommerce'),"woocommerce-services":Object($.__)("WooCommerce Services",'woocommerce'),"woocommerce-shipstation-integration":Object($.__)("WooCommerce ShipStation Gateway",'woocommerce'),"kliken-marketing-for-google":Object($.__)("Google Ads",'woocommerce')},X=function(e){return e.active||[]},Z=function(e){return e.installed||[]},ee=function(e,t){return e.requesting[t]||!1},te=function(e,t){return e.errors[t]||!1},re=function(e){return e.jetpackConnection},ne=function(e,t){return e.jetpackConnectUrls[t.redirect_url]},ce={UPDATE_ACTIVE_PLUGINS:"UPDATE_ACTIVE_PLUGINS",UPDATE_INSTALLED_PLUGINS:"UPDATE_INSTALLED_PLUGINS",SET_IS_REQUESTING:"SET_IS_REQUESTING",SET_ERROR:"SET_ERROR",UPDATE_JETPACK_CONNECTION:"UPDATE_JETPACK_CONNECTION",UPDATE_JETPACK_CONNECT_URL:"UPDATE_JETPACK_CONNECT_URL"},oe=regeneratorRuntime.mark(de),ie=regeneratorRuntime.mark(me);function ae(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return{type:ce.UPDATE_ACTIVE_PLUGINS,active:e,replace:t}}function ue(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return{type:ce.UPDATE_INSTALLED_PLUGINS,installed:e,replace:t}}function se(e,t){return{type:ce.SET_IS_REQUESTING,selector:e,isRequesting:t}}function le(e,t){return{type:ce.SET_ERROR,selector:e,error:t}}function pe(e){return{type:ce.UPDATE_JETPACK_CONNECTION,jetpackConnection:e}}function ge(e,t){return{type:ce.UPDATE_JETPACK_CONNECT_URL,jetpackConnectUrl:t,redirectUrl:e}}function fe(e,t){var r=z[t]||t;switch(e){case"install":return Object($.sprintf)(Object($.__)("There was an error installing %s. Please try again.",'woocommerce'),r);case"connect":return Object($.sprintf)(Object($.__)("There was an error connecting to %s. Please try again.",'woocommerce'),r);case"activate":default:return Object($.sprintf)(Object($.__)("There was an error activating %s. Please try again.",'woocommerce'),r)}}function de(e){var t,r;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,se("installPlugin",!0);case 2:return n.prev=2,n.next=5,Object(l.apiFetch)({path:"".concat("/wc-admin","/plugins/install"),method:"POST",data:{plugin:e}});case 5:if(!(t=n.sent)||"success"!==t.status){n.next=10;break}return n.next=9,ue(null,t.slug);case 9:return n.abrupt("return",t);case 10:throw new Error;case 13:return n.prev=13,n.t0=n.catch(2),r=fe("install",e),n.next=18,le("installPlugin",r);case 18:return n.abrupt("return",r);case 19:case"end":return n.stop()}}),oe,null,[[2,13]])}function me(e){var t;return regeneratorRuntime.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,se("activatePlugins",!0);case 2:return r.prev=2,r.next=5,Object(l.apiFetch)({path:"".concat("/wc-admin","/plugins/activate"),method:"POST",data:{plugins:e.join(",")}});case 5:if(!(t=r.sent)||"success"!==t.status){r.next=10;break}return r.next=9,ae(t.activatedPlugins);case 9:return r.abrupt("return",t);case 10:throw new Error;case 13:return r.prev=13,r.t0=r.catch(2),r.next=17,le("activatePlugins",r.t0);case 17:return r.abrupt("return",e.map((function(e){return fe("activate",e)})));case 18:case"end":return r.stop()}}),ie,null,[[2,13]])}var Oe=r(30),be=regeneratorRuntime.mark(Ee),ve=regeneratorRuntime.mark(Pe),je=regeneratorRuntime.mark(Se),_e=regeneratorRuntime.mark(he);function Ee(){var e;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,se("getActivePlugins",!0);case 2:return t.prev=2,"/wc-admin/plugins/active",t.next=6,Object(l.apiFetch)({path:"/wc-admin/plugins/active",method:"GET"});case 6:return e=t.sent,t.next=9,ae(e.plugins,!0);case 9:t.next=15;break;case 11:return t.prev=11,t.t0=t.catch(2),t.next=15,le("getActivePlugins",t.t0);case 15:case"end":return t.stop()}}),be,null,[[2,11]])}function Pe(){var e;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,se("getInstalledPlugins",!0);case 2:return t.prev=2,"/wc-admin/plugins/installed",t.next=6,Object(l.apiFetch)({path:"/wc-admin/plugins/installed",method:"GET"});case 6:return e=t.sent,t.next=9,ue(e,!0);case 9:t.next=15;break;case 11:return t.prev=11,t.t0=t.catch(2),t.next=15,le("getInstalledPlugins",t.t0);case 15:case"end":return t.stop()}}),ve,null,[[2,11]])}function Se(){var e;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,se("isJetpackConnected",!0);case 2:return t.prev=2,"/jetpack/v4/connection",t.next=6,Object(l.apiFetch)({path:"/jetpack/v4/connection",method:"GET"});case 6:return e=t.sent,t.next=9,pe(e.isActive);case 9:t.next=15;break;case 11:return t.prev=11,t.t0=t.catch(2),t.next=15,le("isJetpackConnected",t.t0);case 15:return t.next=17,se("isJetpackConnected",!1);case 17:case"end":return t.stop()}}),je,null,[[2,11]])}function he(e){var t,r;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,se("getJetpackConnectUrl",!0);case 2:return n.prev=2,t=Object(Oe.addQueryArgs)("/wc-admin/plugins/connect-jetpack",e),n.next=6,Object(l.apiFetch)({path:t,method:"GET"});case 6:return r=n.sent,n.next=9,ge(e.redirect_url,r.connectAction);case 9:n.next=15;break;case 11:return n.prev=11,n.t0=n.catch(2),n.next=15,le("getJetpackConnectUrl",n.t0);case 15:return n.next=17,se("getJetpackConnectUrl",!1);case 17:case"end":return n.stop()}}),_e,null,[[2,11]])}function Re(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function ye(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Re(Object(r),!0).forEach((function(t){Object(G.a)(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Re(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var we=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{active:[],installed:[],requesting:{},errors:{},jetpackConnectUrls:{}},t=arguments.length>1?arguments[1]:void 0,r=t.type,n=t.active,c=t.installed,o=t.selector,i=t.isRequesting,a=t.error,u=t.jetpackConnection,s=t.redirectUrl,l=t.jetpackConnectUrl,p=t.replace;switch(r){case ce.UPDATE_ACTIVE_PLUGINS:e=ye({},e,{active:p?n:Object(h.concat)(e.active,n),requesting:ye({},e.requesting,{getActivePlugins:!1,activatePlugins:!1}),errors:ye({},e.errors,{getActivePlugins:!1,activatePlugins:!1})});break;case ce.UPDATE_INSTALLED_PLUGINS:e=ye({},e,{installed:p?c:Object(h.concat)(e.installed,c),requesting:ye({},e.requesting,{getInstalledPlugins:!1,installPlugin:!1}),errors:ye({},e.errors,{getInstalledPlugins:!1,installPlugin:!1})});break;case ce.SET_IS_REQUESTING:e=ye({},e,{requesting:ye({},e.requesting,Object(G.a)({},o,i))});break;case ce.SET_ERROR:e=ye({},e,{requesting:ye({},e.requesting,Object(G.a)({},o,!1)),errors:ye({},e.errors,Object(G.a)({},o,a))});break;case ce.UPDATE_JETPACK_CONNECTION:e=ye({},e,{jetpackConnection:u});break;case ce.UPDATE_JETPACK_CONNECT_URL:e=ye({},e,{jetpackConnectUrls:ye({},e.jetpackConnectUrl,Object(G.a)({},s,l))})}return e};Object(s.registerStore)("wc/admin/plugins",{reducer:we,actions:a,controls:l.controls,selectors:i,resolvers:u});var Te="wc/admin/plugins",ke=function(e){return function(t){return function(r){var n=Object(M.useRef)(e);return Object(s.useSelect)((function(e,t){if(n.current){var r=e("wc/admin/plugins"),c=r.isResolving,o=r.hasFinishedResolution,i=t.dispatch("wc/admin/plugins"),a=i.startResolution,u=i.finishResolution,s=i.updateActivePlugins,l=i.updateInstalledPlugins,p=i.updateIsJetpackConnected;c("getActivePlugins",[])||o("getActivePlugins",[])||(a("getActivePlugins",[]),a("getInstalledPlugins",[]),a("isJetpackConnected",[]),s(n.current.activePlugins,!0),l(n.current.installedPlugins,!0),p(n.current.jetpackStatus&&n.current.jetpackStatus.isActive),u("getActivePlugins",[]),u("getInstalledPlugins",[]),u("isJetpackConnected",[]))}}),[]),Object(M.createElement)(t,r)}}}}});
