var tribe_events_pro_admin={event:{}};!function(e,t){t.init=function(){if(this.init_settings(),e("#event_venue input, #event_venue select").change(function(){var t=e("#saved_venue option:selected");if(0===t.val()){var n=e(this).closest("form"),i=n.find('[name="venue[Address]"]').val(),r=n.find('[name="venue[City]"]').val(),s=n.find('[name="venue[Country]"]').val(),a="US"===n.find('[name="venue[Country]"] option:selected').val()?n.find('[name="venue[State]"]').val():n.find('[name="venue[Province]"]').val(),o=n.find('[name="venue[Zip]"]').val();"function"==typeof codeAddress&&codeAddress(i+","+r+","+a+","+s+","+o)}else"function"==typeof codeAddress&&codeAddress(t.data("address"))}),e("#doaction, #doaction2").click(function(t){var n=e(this).attr("id").substr(2);if("edit"===e('select[name="'+n+'"]').val()&&"tribe_events"===e(".post_type_page").val()){t.preventDefault();var i=[];e("#bulk-titles div").each(function(){var t=e(this).attr("id"),n=t.replace("ttle",""),r=e("#post-"+n+" .row-title").first().text(),s=e("<div/>").append(e(this).find("a"));e(this).html("").append(s).append(r),i[t]&&e(this).remove(),i[t]=!0})}}),e("body").on("click",".ui-dialog-titlebar .ui-dialog-titlebar-close",function(){tribe_events_pro_admin.reset_submit_button()}),e('input[name="post[]"]').click(function(){var t=e(this).val();e(this).is(":checked")?e('input[name="post[]"][value="'+t+'"]').prop("checked",!0):e('input[name="post[]"][value="'+t+'"]').prop("checked",!1)}),e(".wp-list-table.posts").on("click",".tribe-split",function(){var t="";if(t=e(this).hasClass("tribe-split-all")?TribeEventsProAdmin.recurrence.splitAllMessage:TribeEventsProAdmin.recurrence.splitSingleMessage,!window.confirm(t))return!1}),e(".wp-admin.events-cal.edit-php #doaction").click(function(t){"trash"===e('[name="action"] option:selected').val()&&e('.tribe-recurring-event-parent [name="post[]"]:checked').length>0&&!confirm(TribeEventsProAdmin.recurrence.bulkDeleteConfirmationMessage)&&t.preventDefault()}),"object"==typeof TribeEventsProRecurrenceUpdate){var t=e("div.tribe-events-recurring-update-msg"),n=t.find("img"),i=t.find("div.progress"),r=t.find("div.bar"),s=Date.now();function a(e){var i=Date.now()-s;e.html&&t.html(e.html),e.progress&&c(e.progress,e.progressText),e.continue&&(i<500?setTimeout(o,500-i):o()),e.complete&&(n.replaceWith(TribeEventsProRecurrenceUpdate.completeMsg),t.removeClass("updating").addClass("completed"),setTimeout(d,1e3))}function o(){var t={event:TribeEventsProRecurrenceUpdate.eventID,check:TribeEventsProRecurrenceUpdate.check,action:"tribe_events_pro_recurrence_realtime_update"};e.post(ajaxurl,t,a,"json")}function c(e,t){(e=parseInt(e))<0||e>100||(r.css("width",e+"%"),i.attr("title",t))}function d(){t.animate({opacity:0,height:"toggle"},1e3,function(){t.remove()})}setTimeout(function(){o(),c(TribeEventsProRecurrenceUpdate.progress,TribeEventsProRecurrenceUpdate.progressText)})}e("body").on("change","#defaultCountry-select",function(){e(this);var t=e("#tribe-field-eventsDefaultState"),n=e(".tribe-settings-form-wrap #tribe-field-eventsDefaultProvince"),i=e("p.tribe-saved-state"),r=e("p.tribe-saved-province"),s=e(this).val();"US"===s||"United States"===s?(n.hide(),r.hide(),t.show(),i.show()):(t.hide(),i.hide(),n.show(),r.show())}).find("#defaultCountry-select").trigger("change")},t.init_settings=function(){var t=e('[name="hideSubsequentRecurrencesDefault"]'),n=e('[name="userToggleSubsequentRecurrences"]');if(t.length&&n.length){var i=e("#tribe-field-userToggleSubsequentRecurrences");t.is(":checked")&&(n.prop("checked",!1),i.hide()),t.on("click",function(){e(this).is(":checked")?(n.prop("checked",!1),i.hide()):i.show()})}},e(function(){t.init()})}(jQuery,tribe_events_pro_admin);