var tribe_attendees_list={};!function(t,e,i,s){"use strict";s.selector={container:".tribe-attendees-list-container",title:".tribe-attendees-list-title",list:".tribe-attendees-list",items:".tribe-attendees-list-item",hidden:".tribe-attendees-list-hidden",shown:".tribe-attendees-list-shown",showall:".tribe-attendees-list-showall"},s.init=function(){i(s.selector.showall).on("click",s.toggle_items)},s.toggle_items=function(t){t.preventDefault(),i(this).parents(s.selector.container).toggleClass("tribe-attendees-list-showjs")},i(e).ready(s.init)}(window,document,jQuery,tribe_attendees_list);