var tribe_timepickers=tribe_timepickers||{};!function(e,t){"use strict";t.selector={container:".tribe-datetime-block",timepicker:".tribe-timepicker",all_day:"#allDayCheckbox",timezone:".tribe-field-timezone",input:"select, input"},t.timepicker={opts:{forceRoundTime:!1,step:30}},t.timezone={link:_.template('<a href="#" class="tribe-change-timezone"><%= label %> <%= timezone %></a>')},t.$={},t.container=function(i,n){var r=e(n),c=r.find(t.selector.all_day),o=r.find(t.selector.timepicker),a=r.find(t.selector.timezone).not(t.selector.input),l=r.find(t.selector.timezone).filter(t.selector.input),m=e(t.timezone.link({label:l.data("timezoneLabel"),timezone:l.data("timezoneValue")}));c.on("change",function(){!0===c.prop("checked")?o.hide():o.show()}).trigger("change"),t.setup_timepickers(o),m.on("click",function(e){a=r.find(t.selector.timezone).filter(".select2-container"),e.preventDefault(),m.hide(),a.show()}),l.before(m)},t.init=function(){t.$.containers=e(t.selector.container),t.$.containers.each(t.container)},t.setup_timepickers=function(i){i.each(function(){var i=e(this),n=e.extend({},t.timepicker.opts);i.data("format")&&(n.timeFormat=i.data("format")),i.data("step")&&(n.step=i.data("step"));var r=i.data("round");r&&0!=r&&"false"!==r&&(n.forceRoundTime=!0),void 0!==e.fn.tribeTimepicker?i.tribeTimepicker(n).trigger("change"):i.timepicker(n).trigger("change")})},e(document).ready(t.init)}(jQuery,tribe_timepickers);