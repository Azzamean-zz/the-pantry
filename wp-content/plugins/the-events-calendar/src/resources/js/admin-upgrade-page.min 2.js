tribe.upgradePage=tribe.upgradePage||{},function(e,t){"use strict";t.setup=function(){if("upgrade"!=e("#current-settings-tab").val()){if("1"==tribe_upgrade.v2_is_enabled)return;return e(".tribe_settings > h1").append('<button id="upgrade-button">✨ '+tribe_upgrade.button_text+"</button>"),void e(document).on("click","#upgrade-button",function(e){document.location="?page=tribe-common&tab=upgrade&post_type=tribe_events"})}e("#tribeSaveSettings").hide(),e("#tribe-field-views_v2_enabled input").hide().prop("checked",!0),e(document).on("click","#tribe-upgrade-step1 button",function(t){t.preventDefault(),e("#tribe-upgrade-step1").addClass("hidden"),e("#tribe-upgrade-step2").removeClass("hidden")}),e(document).on("click","#tribe-upgrade-step2 button",function(t){t.preventDefault(),e("#tribeSaveSettings").click()})},e(document).ready(t.setup)}(jQuery,tribe.upgradePage);