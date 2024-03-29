
function Smart_Manager_Pro() {
    Smart_Manager.apply();
}

Smart_Manager_Pro.prototype = Object.create(Smart_Manager.prototype);
Smart_Manager_Pro.prototype.constructor = Smart_Manager_Pro;
    
Smart_Manager_Pro.prototype.getDataDefaultParams = function(params) {
    Smart_Manager.prototype.getDataDefaultParams.apply(this, [params]);

    if ( typeof window.smart_manager.date_params.date_filter_params != 'undefined' ) {
        window.smart_manager.currentGetDataParams.data['date_filter_params'] = window.smart_manager.date_params.date_filter_params;
    }
    if ( typeof window.smart_manager.date_params.date_filter_query != 'undefined' ) {
        window.smart_manager.currentGetDataParams.data['date_filter_query'] = window.smart_manager.date_params.date_filter_query;
    }
}

if(typeof window.smart_manager_pro === 'undefined'){
    window.smart_manager = new Smart_Manager_Pro();
}

jQuery(document).on('smart_manager_init','#sm_editor_grid', function() {
    window.smart_manager.batchUpdateSelectActionOption = '<option value="" disabled selected>Select Action</option>';
    window.smart_manager.batchUpdateCopyFromOption = '<option value="copy_from">copy from</option>';
    window.smart_manager.date_params = {}; //params for date filter
    
})
.on('sm_top_bar_loaded', '#sm_top_bar', function() {

        jQuery(document).off('click', '.sm_date_range_container .smart-date-icon').on('click', '.sm_date_range_container .smart-date-icon', function() {

            if( jQuery('.sm_date_range_container .dropdown-menu').is(':visible') === false ){
                jQuery('.sm_date_range_container .dropdown-menu').show();
            } else {
                jQuery('.sm_date_range_container .dropdown-menu').hide();
            }

            // jQuery('.sm_date_range_container .dropdown-menu').toggle();
        });

        jQuery(document).off('click', ':not(.sm_date_range_container .dropdown-menu)').on('click', ':not(.sm_date_range_container .dropdown-menu)', function( e ){
            if ( jQuery(e.target).hasClass('smart-date-icon') === false && jQuery('.sm_date_range_container .dropdown-menu').is(':visible') === true ) {
                jQuery('.sm_date_range_container .dropdown-menu').hide();
            }
        });

        jQuery(document).off('click', '.sm_date_range_container .dropdown-menu li a').on('click', '.sm_date_range_container .dropdown-menu li a', function(e) {
            e.preventDefault();

            jQuery('.sm_date_range_container .dropdown-menu').hide();
            window.smart_manager.proSelectDate(jQuery(this).attr('data-key'));
        });

        //Code for initializing the date picker
        jQuery('.sm_date_range_container input.sm_date_selector').Zebra_DatePicker({
                                                                                                    format: 'd M Y H:i:s',
                                                                                                    // format: 'dd-mm-yy H:i:s',
                                                                                                    show_icon: false,
                                                                                                    show_select_today: false,
                                                                                                    default_position: 'below',
                                                                                                    lang_clear_date: 'Clear dates',
                                                                                                    onClear: window.smart_manager.clearDateFilter,
                                                                                                    start_date: new Date( new Date().setHours(0, 0, 0) ),
                                                                                                    onSelect: function(fdate, jsdate) {
                                                                                                        jQuery(this).change();
                                                                                                        let id = jQuery(this).attr('id'),
                                                                                                            selected_date_obj = new Date(fdate),
                                                                                                            params = {'start_date_formatted':'',
                                                                                                                        'start_date_default_format':'',
                                                                                                                        'end_date_formatted':'',
                                                                                                                        'end_date_default_format':''};

                                                                                                        if( id == 'sm_date_selector_start_date' ) { //if end_date is not set

                                                                                                            params.start_date_formatted = fdate;
                                                                                                            params.start_date_default_format = jsdate;

                                                                                                            var end_date = jQuery('#sm_date_selector_end_date').val(),
                                                                                                                end_time = '';

                                                                                                            if( end_date == '' ) {
                                                                                                                end_date_obj = new Date( selected_date_obj.getFullYear(), selected_date_obj.getMonth(), ( selected_date_obj.getDate() + 29 ) );
                                                                                                                end_time =  '23:59:59';
                                                                                                            } else {
                                                                                                                end_date_obj = new Date(end_date);
                                                                                                                end_time =  window.smart_manager.strPad(end_date_obj.getHours(), 2) + ':' + window.smart_manager.strPad(end_date_obj.getMinutes(), 2) + ':' + window.smart_manager.strPad(end_date_obj.getSeconds(), 2);
                                                                                                            }
                                                                                                            var y = end_date_obj.getFullYear() + '',
                                                                                                                m = end_date_obj.getMonth(),
                                                                                                                d = window.smart_manager.strPad(end_date_obj.getDate(), 2);
                                                                                                            
                                                                                                            params.end_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + end_time;
                                                                                                            params.end_date_default_format = y + '-' + window.smart_manager.strPad((m+1), 2) + '-' + d + ' ' + end_time;

                                                                                                            if( end_date == '' ) {
                                                                                                                end_date_datepicker = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');
                                                                                                                end_date_datepicker.set_date(params.end_date_formatted);
                                                                                                                end_date_datepicker.update({'current_date': new Date(params.end_date_default_format)});
                                                                                                            }
                                                                                                            

                                                                                                        } else if( id == 'sm_date_selector_end_date' ) { //if start_date is not set

                                                                                                            params.end_date_formatted = fdate;
                                                                                                            params.end_date_default_format = jsdate;

                                                                                                            var start_date = jQuery('#sm_date_selector_start_date').val(),
                                                                                                                start_time = '';

                                                                                                            if( start_date == '' ) {
                                                                                                                start_date_obj = new Date( selected_date_obj.getFullYear(), selected_date_obj.getMonth(), ( selected_date_obj.getDate() - 29 ) );
                                                                                                                start_time = '23:59:59';
                                                                                                            } else {
                                                                                                                start_date_obj = new Date(start_date);
                                                                                                                start_time = window.smart_manager.strPad(start_date_obj.getHours(), 2) + ':' + window.smart_manager.strPad(start_date_obj.getMinutes(), 2) + ':' + window.smart_manager.strPad(start_date_obj.getSeconds(), 2);
                                                                                                            }
                                                                                                            var y = start_date_obj.getFullYear() + '',
                                                                                                                m = start_date_obj.getMonth(),
                                                                                                                d = window.smart_manager.strPad(start_date_obj.getDate(), 2);


                                                                                                            params.start_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + start_time;
                                                                                                            params.start_date_default_format = y + '-' + window.smart_manager.strPad((m+1), 2) + '-' + d + ' ' + start_time;

                                                                                                            if( start_date == '' ) {
                                                                                                                start_date_datepicker = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker');
                                                                                                                start_date_datepicker.set_date(params.start_date_formatted);
                                                                                                                start_date_datepicker.update({'current_date': new Date(params.start_date_default_format)});
                                                                                                            }
                                                                                                        }

                                                                                                        window.smart_manager.sm_handle_date_filter(params);
                                                                                                    }
                                                                                                });

        if( typeof( window.smart_manager.date_params.date_filter_params ) != 'undefined' && window.smart_manager.isJSON( window.smart_manager.date_params.date_filter_params ) ) {

            selected_dates = JSON.parse(window.smart_manager.date_params.date_filter_params);

            start_date_datepicker = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker');
            start_date_datepicker.set_date(selected_dates.start_date_formatted);
            start_date_datepicker.update({'current_date': new Date(selected_dates.start_date_default_format)});

            end_date_datepicker = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');
            end_date_datepicker.set_date(selected_dates.end_date_formatted);
            end_date_datepicker.update({'current_date': new Date(selected_dates.end_date_default_format)});

        }

    })

.off('click','.sm_beta_batch_update_background_link').on('click','.sm_beta_batch_update_background_link',function() { //Code for enabline background updating
    window.location.reload();

    // window.smart_manager.hideNotification();
    // window.smart_manager.refresh();

    // if( jQuery('#sm_top_bar_action_btns_update #batch_update_sm_editor_grid, #sm_top_bar_action_btns_update .sm_beta_dropdown_content').hasClass('sm-ui-state-disabled') === false ) {
    //     jQuery('#sm_top_bar_action_btns_update #batch_update_sm_editor_grid, #sm_top_bar_action_btns_update .sm_beta_dropdown_content').addClass('sm-ui-state-disabled');
    // }

    // if( jQuery("#wpbody .sm_beta_pro_background_update_notice").length == 0 ) {
    //     jQuery('<div id="sm_beta_pro_background_update_notice" class="notice notice-info sm_beta_pro_background_update_notice"><p><strong>Success!</strong> '+ params.title +' initiated – Your records are being updated in the background. You will be notified on your email address <strong><code>'+window.smart_manager.sm_admin_email+'</code></strong> once the process is completed.</p></div>').insertBefore('#wpbody .wrap');
    //     // To go to start of the SM page so users can see above notice.
    //     window.scrollTo(0,0);
    // }
});

//Function to determine if background process is running or not
Smart_Manager.prototype.isBackgroundProcessRunning = function() {

    if( jQuery('#sa_sm_background_process_progress').length > 0 && jQuery('#sa_sm_background_process_progress').is(":visible") ) {
        return true;
    }

    return false;
}

//function to clear the datepicker filter
Smart_Manager.prototype.clearDateFilter = function() {
    let startDate = jQuery('#sm_date_selector_start_date').val(),
        endDate = jQuery('#sm_date_selector_end_date').val(),
        refresh = 0;

    if( startDate != '' ) {
        let startDateDatepicker = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker');
        jQuery('#sm_date_selector_start_date').val('');
        refresh = 1;
    } 
    if( endDate != '' ) {
        let endDateDatepicker = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');
        jQuery('#sm_date_selector_end_date').val('');
        refresh = 1;
    }

    if( typeof(window.smart_manager.currentGetDataParams.date_filter_params) != 'undefined' && typeof(window.smart_manager.currentGetDataParams.date_filter_query) != 'undefined'  ) {
        delete window.smart_manager.currentGetDataParams.date_filter_params;
        delete window.smart_manager.currentGetDataParams.date_filter_query;
    }

    if( window.smart_manager.date_params.hasOwnProperty('date_filter_params') ) {
        delete window.smart_manager.date_params['date_filter_params'];
    }

    if( window.smart_manager.date_params.hasOwnProperty('date_filter_query') ) {
        delete window.smart_manager.date_params['date_filter_query'];
    }

    if( refresh == 1 ) {
        window.smart_manager.refresh();
    }
}

//function to process the datepicker filter
Smart_Manager.prototype.sm_handle_date_filter = function(params) {

    let date_search_array = new Array(),
        dataParams = {};

    if( window.smart_manager.dashboard_key == 'user' ) {
        date_search_array = new Array({"key":"User Registered","value":params.start_date_default_format,"type":"date","operator":">=","table_name":window.smart_manager.wpDbPrefix+"users","col_name":"user_registered","date_filter":1},
                                    {"key":"User Registered","value":params.end_date_default_format,"type":"date","operator":"<=","table_name":window.smart_manager.wpDbPrefix+"users","col_name":"user_registered","date_filter":1});
    } else {
        date_search_array = new Array({"key":"Post Date","value":params.start_date_default_format,"type":"date","operator":">=","table_name":window.smart_manager.wpDbPrefix+"posts","col_name":"post_date","date_filter":1},
                                    {"key":"Post Date","value":params.end_date_default_format,"type":"date","operator":"<=","table_name":window.smart_manager.wpDbPrefix+"posts","col_name":"post_date","date_filter":1});
    }

    window.smart_manager.date_params['date_filter_params'] = JSON.stringify(params);
    window.smart_manager.date_params['date_filter_query'] = JSON.stringify(date_search_array);


    if( Object.getOwnPropertyNames(window.smart_manager.date_params).length > 0 ) {
        dataParams.data = window.smart_manager.date_params;
    }
    
    window.smart_manager.refresh(dataParams);
}

//function to append 0's to str
Smart_Manager.prototype.strPad = function(str, len) {

    str += '';
    while (str.length < len) str = '0' + str;
    return str;

},

Smart_Manager.prototype.proSelectDate = function (dateValue){
        
    if( dateValue == 'all' ) {
        if ( typeof (window.smart_manager.clearDateFilter) !== "undefined" && typeof (window.smart_manager.clearDateFilter) === "function" ) {
            window.smart_manager.clearDateFilter();
        }
        return;
    }

    let fromDate,
        toDate,
        from_time,
        to_time,
        from_date_formatted,
        from_date_default_format,
        to_date_formatted,
        to_date_default_format,
        now = new Date(),
        params = {'start_date_formatted':'',
                'start_date_default_format':'',
                'end_date_formatted':'',
                'end_date_default_format':''};

    switch (dateValue){

        case 'today':
        fromDate = now;
        toDate   = now;
        break;

        case 'yesterday':
        fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
        toDate   = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
        break;

        case 'this_week':
        fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - (now.getDay() - 1));
        toDate   = now;
        break;

        case 'last_week':
        fromDate = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - (now.getDay() - 1) - 7));
        toDate   = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - (now.getDay() - 1) - 1));
        break;

        case 'last_4_week':
        fromDate = new Date( now.getFullYear(), now.getMonth(), ( now.getDate() - 29 ) ); //for exactly 30 days limit
        toDate   = now;
        break;

        case 'this_month':
        fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
        toDate   = now;
        break;

        case 'last_month':
        fromDate = new Date(now.getFullYear(), now.getMonth()-1, 1);
        toDate   = new Date(now.getFullYear(), now.getMonth(), 0);
        break;

        case '3_months':
        fromDate = new Date(now.getFullYear(), now.getMonth()-2, 1);
        toDate   = now;
        break;

        case '6_months':
        fromDate = new Date(now.getFullYear(), now.getMonth()-5, 1);
        toDate   = now;
        break;

        case 'this_year':
        fromDate = new Date(now.getFullYear(), 0, 1);
        toDate   = now;
        break;

        case 'last_year':
        fromDate = new Date(now.getFullYear() - 1, 0, 1);
        toDate   = new Date(now.getFullYear(), 0, 0);
        break;

        default:
        fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
        toDate   = now;
        break;
    }

    //Code for format
    if( typeof fromDate === 'object' && fromDate instanceof Date ) {
        var y = fromDate.getFullYear() + '',
            m = fromDate.getMonth(),
            d = window.smart_manager.strPad(fromDate.getDate(), 2);

        from_time =  '00:00:00';
        params.start_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + from_time;
        params.start_date_default_format = y + '-' + window.smart_manager.strPad((m+1), 2) + '-' + d + ' ' + from_time;
    }

    if( typeof toDate === 'object' && toDate instanceof Date ) {
        var y = toDate.getFullYear() + '',
            m = toDate.getMonth(),
            d = window.smart_manager.strPad(toDate.getDate(), 2);

        to_time =  '23:59:59';
        params.end_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + to_time;
        params.end_date_default_format = y + '-' + window.smart_manager.strPad((m+1), 2) + '-' + d + ' ' + to_time;
    }

    var start_date = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker'),
        end_date = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');

    if( typeof(start_date) != 'undefined') {
        start_date.set_date(params.start_date_formatted);
        start_date.update({'current_date': new Date(params.start_date_default_format), 'start_date': new Date(params.start_date_formatted)});    
    }

    if( typeof(end_date) != 'undefined') {
        end_date.set_date(params.end_date_formatted);
        end_date.update({'current_date': new Date(params.end_date_default_format), 'start_date': new Date(params.end_date_formatted)});
    }

    window.smart_manager.sm_handle_date_filter( params );

};

var sm_beta_hide_dialog = function(IDs, gID) {
    jQuery.jgrid.hideModal("#"+IDs.themodal,{gb:"#gbox_"+gID,jqm:true, onClose: null});
    index = 0;
}

// ========================================================================
// EXPORT CSV
// ========================================================================

Smart_Manager.prototype.generateCsvExport = function() {

    let params = {
                              cmd: 'get_export_csv',
                              active_module: window.smart_manager.dashboard_key,
                              security: window.smart_manager.sm_nonce,
                              pro: true,
                              SM_IS_WOO30: window.smart_manager.sm_is_woo30,
                              sort_params: (window.smart_manager.currentDashboardModel.hasOwnProperty('sort_params') ) ? window.smart_manager.currentDashboardModel.sort_params : '',
                              table_model: (window.smart_manager.currentDashboardModel.hasOwnProperty('tables') ) ? window.smart_manager.currentDashboardModel.tables : '',
                              search_text: window.smart_manager.simpleSearchText,
                              advanced_search_query: JSON.stringify(window.smart_manager.advancedSearchQuery)
                          };
    //Code for handling views
    let viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName);
    
    if(viewSlug){
        params['is_view'] = 1;
        params['active_view'] = viewSlug;
        params['active_module'] = (window.smart_manager.viewPostTypes.hasOwnProperty(viewSlug)) ? window.smart_manager.viewPostTypes[viewSlug] : window.smart_manager.dashboard_key;
    }

    let export_url = window.smart_manager.sm_ajax_url + '&cmd='+ params['cmd'] +'&active_module='+ params['active_module'] +'&security='+ params['security'] +'&pro='+ params['pro'] +'&SM_IS_WOO30='+ params['SM_IS_WOO30'] +'&SM_IS_WOO30='+ params['SM_IS_WOO30'] +'&sort_params='+ encodeURIComponent(JSON.stringify(params['sort_params'])) +'&table_model='+ encodeURIComponent(JSON.stringify(params['table_model'])) +'&advanced_search_query='+ encodeURIComponent(JSON.stringify(window.smart_manager.advancedSearchQuery))+'&search_text='+ params['search_text'];
    export_url += ( window.smart_manager.date_params.hasOwnProperty('date_filter_params') ) ? '&date_filter_params='+ window.smart_manager.date_params['date_filter_params'] : '';
    export_url += ( window.smart_manager.date_params.hasOwnProperty('date_filter_query') ) ? '&date_filter_query='+ window.smart_manager.date_params['date_filter_query'] : '';
    
    if(viewSlug){
        export_url += '&is_view='+params['is_view']+'&active_view='+params['active_view'];
    }

    window.location = export_url;
}

// ========================================================================
// PRINT INVOICE
// ========================================================================

Smart_Manager.prototype.printInvoice = function() {

    if( window.smart_manager.duplicateStore === false && window.smart_manager.selectedRows.length == 0 && !window.smart_manager.selectAll ) {
        return;
    }

    let params = {};
        params.data = {
                        cmd: 'get_print_invoice',
                        active_module: window.smart_manager.dashboard_key,
                        security: window.smart_manager.sm_nonce,
                        pro: true,
                        selected_ids: JSON.stringify(window.smart_manager.getSelectedKeyIds()),
                        sort_params: (window.smart_manager.currentDashboardModel.hasOwnProperty('sort_params') ) ? window.smart_manager.currentDashboardModel.sort_params : '',
                        table_model: (window.smart_manager.currentDashboardModel.hasOwnProperty('tables') ) ? window.smart_manager.currentDashboardModel.tables : '',
                        SM_IS_WOO30: window.smart_manager.sm_is_woo30,
                        SM_IS_WOO22: window.smart_manager.sm_id_woo22,
                        SM_IS_WOO21: window.smart_manager.sm_is_woo21,
                        search_text: window.smart_manager.simpleSearchText
                    };

    let url = window.smart_manager.sm_ajax_url + '&cmd='+ params.data['cmd'] +'&active_module='+ params.data['active_module'] +'&security='+ params.data['security'] +'&pro='+ params.data['pro'] +'&SM_IS_WOO30='+ params.data['SM_IS_WOO30'] +'&SM_IS_WOO30='+ params.data['SM_IS_WOO30'] +'&sort_params='+ encodeURIComponent(JSON.stringify(params.data['sort_params'])) +'&table_model='+ encodeURIComponent(JSON.stringify(params.data['table_model'])) +'&advanced_search_query='+ encodeURIComponent(JSON.stringify(window.smart_manager.advancedSearchQuery)) +'&search_text='+ params.data['search_text'] + '&selected_ids=' + params.data['selected_ids'];
    params.call_url = url;
    params.data_type = 'html';

    url += ( window.smart_manager.date_params.hasOwnProperty('date_filter_params') ) ? '&date_filter_params='+ window.smart_manager.date_params['date_filter_params'] : '';
    url += ( window.smart_manager.date_params.hasOwnProperty('date_filter_query') ) ? '&date_filter_query='+ window.smart_manager.date_params['date_filter_query'] : '';

    window.smart_manager.send_request(params, function(response) {
        let win = window.open('', 'Invoice');
        win.document.write(response);
        win.document.close();
        win.print();
    });
}

// ========================================================================
// DUPLICATE RECORDS
// ========================================================================

Smart_Manager.prototype.duplicateRecords = function() {

    if( window.smart_manager.duplicateStore === false && window.smart_manager.selectedRows.length == 0 && !window.smart_manager.selectAll ) {
        return;
    }

    setTimeout( function() { 

        window.smart_manager.showProgressDialog('Duplicate Records'); 

        if( typeof (sa_sm_background_process_heartbeat) !== "undefined" && typeof (sa_sm_background_process_heartbeat) === "function" ) {
            sa_sm_background_process_heartbeat();
        }

    } ,1);
    
    let params = {};
        params.data = {
                        cmd: 'duplicate_records',
                        active_module: window.smart_manager.dashboard_key,
                        security: window.smart_manager.sm_nonce,
                        pro: true,
                        storewide_option: ( window.smart_manager.duplicateStore === true ) ? 'entire_store' : '',
                        selected_ids: JSON.stringify(window.smart_manager.getSelectedKeyIds()),
                        table_model: (window.smart_manager.currentDashboardModel.hasOwnProperty('tables') ) ? window.smart_manager.currentDashboardModel.tables : '',
                        active_module_title: window.smart_manager.dashboardName,
                        backgroundProcessRunningMessage: window.smart_manager.backgroundProcessRunningMessage,
                        SM_IS_WOO30: window.smart_manager.sm_is_woo30,
                        SM_IS_WOO22: window.smart_manager.sm_id_woo22,
                        SM_IS_WOO21: window.smart_manager.sm_is_woo21
                    };

        params.showLoader = false;

        if( window.smart_manager.simpleSearchText != '' || window.smart_manager.advancedSearchQuery.length > 0 ) {
            params.data.filteredResults = 1;
        }

    window.smart_manager.send_request(params, function(response) {

    });

    // setTimeout(function() {
    //     params = { 'func_nm' : 'duplicate_records', 'title' : 'Duplicate Records' }
    //     window.smart_manager.background_process_hearbeat( params );
    // }, 1000);
    
};

// ========================================================================
// Function to handle request for both creating & updating view
// ========================================================================
Smart_Manager.prototype.saveView = function(action = 'create') {
    let viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName);
    let activeDashboard =  (viewSlug) ? viewSlug : window.smart_manager.dashboard_key;
    let name = jQuery('#sm_view_name').val();
    let currentDashboardState = '';

    if ( typeof (window.smart_manager.getCurrentDashboardState) !== "undefined" && typeof (window.smart_manager.getCurrentDashboardState) === "function" ) {
        currentDashboardState = window.smart_manager.getCurrentDashboardState();
    }
    if(currentDashboardState){
        currentDashboardState = JSON.parse(currentDashboardState);
        currentDashboardState['search_params'] = {
                                            'isAdvanceSearch': ((window.smart_manager.advancedSearchQuery.length > 0) ? 'true' : 'false'),
                                            'params': ((window.smart_manager.advancedSearchQuery.length > 0) ? window.smart_manager.advancedSearchQuery : window.smart_manager.simpleSearchText),
        }

        //AJAX to create & save view
        let params = {};
            params.data_type = 'json';
            params.data = {
                            module: 'custom_views',
                            cmd: action,
                            active_module: activeDashboard,
                            security: window.smart_manager.sm_nonce,
                            name: name,
                            isPublic: (jQuery('#sm_view_access_public').is(":checked")) ? true : false,
                            is_view: (viewSlug) ? 1 : 0,
                            currentView: JSON.stringify(currentDashboardState)
                        };
        window.smart_manager.send_request(params, function(response){
            let ack = (response.hasOwnProperty('ACK')) ? response.ACK : ''
            let viewSlug = (response.hasOwnProperty('slug')) ? response.slug : ''

            if(ack == 'Success'){
                window.smart_manager.showNotification('Success', 'View '+String(action).capitalize()+'d successfully!')
                if(viewSlug != ''){
                    var separator = (window.location.href.indexOf("?")===-1)?"?":"&";
                    window.location.href = window.location.href + separator + "dashboard="+viewSlug+"&is_view=1";
                }
            } else {
                location.reload();
            }
        });
    }
}

// ========================================================================
// function to display confirmdialog for create & update view
// ========================================================================

Smart_Manager.prototype.createUpdateViewDialog = function(action = 'create') {
    let params = {},
        viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName);

    let isView = (viewSlug) ? 1 : 0,
        isPublicView = (viewSlug) ? 1 : 0;


    params.btnParams = {}
    params.title = 'Custom Views';
    params.width = 500;
    params.height = 350;
    params.content = '<p id="sm_view_descrip">Create a custom view to save selected columns from a dashboard. Use it for saved searches, giving specific columns access to other users, etc.</p>'+ 
                    '<input id="sm_view_name" type="text" placeholder="Give a name to this view" value="'+((isView == 1 && action != 'create') ? window.smart_manager.dashboardName : '' )+'" />'+
                    '<div id="sm_view_error_msg" style="display:none;"></div>'+
                    '<div id="sm_view_access">'+
                        '<label id="sm_view_access_public_lbl">'+
                            '<input type="checkbox" id="sm_view_access_public" style="height: 1.5em;width: 1.5em;" '+((isPublicView == 1) ? 'checked' : '')+' >'+
                                'Public'+
                        '</label>'+
                        '<p class="description">Marking this view public will make it available to all users having access to the Smart Manager.</p>'+
                    '</div>';

    if ( typeof (window.smart_manager.createUpdateView) !== "undefined" && typeof (window.smart_manager.createUpdateView) === "function" ) {
        params.btnParams.yesText = String(action).capitalize();
        params.btnParams.yesCallback = window.smart_manager.createUpdateView;
        params.btnParams.yesCallbackParams = action;
        params.btnParams.hideOnYes = false
    }
    
    window.smart_manager.showConfirmDialog(params);
}



// ========================================================================
// function to handle functionality for checking iof view exists & if not then creating or updating the same
// ========================================================================

Smart_Manager.prototype.createUpdateView = function(action = 'create') {
    let name = jQuery('#sm_view_name').val()
    // Code to validate name field
    if(!name){
        jQuery('#sm_view_error_msg').html('Please add view name').show();
        jQuery('#sm_view_name').addClass('sm_border_red')
    } else {
        jQuery('#sm_view_name').removeClass('sm_border_red');
        jQuery('#sm_view_error_msg').html('').hide();
        
        let viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName);
        let activeDashboard = (viewSlug) ? viewSlug : window.smart_manager.dashboard_key;

        if(action != 'create' && name == window.smart_manager.dashboardName){
            if ( typeof (window.smart_manager.saveView) !== "undefined" && typeof (window.smart_manager.saveView) === "function" ) {
                window.smart_manager.saveView(action);
            }
        } else {
            //Code to check if the view with same name exists
            let params = {};
            params.data_type = 'json';
            params.data = {
                            module: 'custom_views',
                            cmd: 'is_view_available',
                            active_module: activeDashboard,
                            security: window.smart_manager.sm_nonce,
                            name: name
                        };
            window.smart_manager.send_request(params, function(response){
                if(response.hasOwnProperty('is_available')){
                    let isAvailable = response.is_available;
                    if(isAvailable){
                        if ( typeof (window.smart_manager.saveView) !== "undefined" && typeof (window.smart_manager.saveView) === "function" ) {
                            window.smart_manager.saveView(action);
                        }
                    } else {
                        jQuery('#sm_view_error_msg').html('View already exists. Please try another name').show();
                        jQuery('#sm_view_name').addClass('sm_border_red')
                    }
                }
            });
        }
    }
    
    //If eists show error
    // If not exists send ajax call for saving the view
};

// ========================================================================
// DELETE VIEW
// ========================================================================

Smart_Manager.prototype.deleteView = function() {

    let viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName);

    //AJAX to create & save view
    let params = {};
        params.data_type = 'json';
        params.data = {
                        module: 'custom_views',
                        cmd: 'delete',
                        security: window.smart_manager.sm_nonce,
                        active_module: viewSlug,
                    };
    window.smart_manager.send_request(params, function(response){
        let ack = (response.hasOwnProperty('ACK')) ? response.ACK : ''
        if(ack == 'Success'){
            window.smart_manager.showNotification('Success', 'View deleted successfully!');
        }
        location.reload();
    });
}

// ========================================================================
// BATCH UPDATE
// ========================================================================


Smart_Manager.prototype.processBatchUpdate = function() {

    if( window.smart_manager.selectedRows.length == 0 && !window.smart_manager.selectAll ) {
        return;
    }

    window.smart_manager.batch_update_actions = new Array();

    setTimeout( function() { 
            window.smart_manager.showProgressDialog('Bulk Edit'); 

            if( typeof (sa_sm_background_process_heartbeat) !== "undefined" && typeof (sa_sm_background_process_heartbeat) === "function" ) {
                sa_sm_background_process_heartbeat();
            }
        }
    ,1);

    //getting the batch update form data
    jQuery('#sm_inline_dialog tr[id^=batch_update_action_row_]').each(function() {

        let row_id = jQuery(this).attr('id'),
            field_nm = jQuery(this).find('.batch_update_field').val(),
            field_type = jQuery(this).find('option:selected','.batch_update_field').attr('data-type'),
            field_editor = jQuery(this).find('option:selected','.batch_update_field').attr('data-editor'),
            field_multiSelectSeparator = jQuery(this).find('option:selected','.batch_update_field').attr('data-multiSelectSeparator'),
            field_display_text = jQuery("option:selected","#"+row_id+" .batch_update_field").text(),
            action_display_text = jQuery("option:selected","#"+row_id+" .batch_update_action").text(),
            value_display_text = jQuery("option:selected","#"+row_id+" .batch_update_value").text(),
            action = jQuery(this).find('.batch_update_action').val(),
            value = new Array(),
            table_nm = '',
            col_nm = '';
            if( typeof (field_nm) != 'undefined' ) {
                field_params = field_nm.split("/");
                if( field_params.length > 0 ) {

                    table_nm = field_params[0];

                    if( field_params.length > 2 ) {
                        field_meta = field_params[1].split("=");
                        col_nm = field_meta[1];
                    } else {
                        col_nm = field_params[1];
                    }
                }    
            }

            jQuery(this).find('.batch_update_value').each( function(){
                value.push(jQuery(this).val());
            });

            if( typeof (field_type) != 'undefined' && field_type == 'sm.image' && action != 'copy_from' ) {
                value = jQuery(this).find('.batch_update_image').attr('data-imageId');
            }

        window.smart_manager.batch_update_actions.push({'table_nm' : table_nm, 'col_nm' : col_nm, 'action' : action, 'value' : value, 'type' : field_type, 'editor' : field_editor, 'multiSelectSeparator' : field_multiSelectSeparator, 'field_display_text' : field_display_text, 'action_display_text' : action_display_text, 'value_display_text' : value_display_text });
    });

    jQuery(document).trigger("sm_batch_update_on_submit"); //trigger to make changes in batch_update_actions

    let storewide_option = jQuery('input[name=batch_update_storewide]:checked').val();

    //Ajax request to batch update the selected records
    let params = {};
        params.data = {
                        cmd: 'batch_update',
                        active_module: window.smart_manager.dashboard_key,
                        security: window.smart_manager.sm_nonce,
                        pro: true,
                        storewide_option: ( storewide_option == 'entire_store' ) ? 'entire_store' : '',
                        selected_ids: JSON.stringify(window.smart_manager.getSelectedKeyIds()),
                        batch_update_actions: JSON.stringify(window.smart_manager.batch_update_actions),
                        active_module_title: window.smart_manager.dashboardName,
                        backgroundProcessRunningMessage: window.smart_manager.backgroundProcessRunningMessage,
                        table_model: (window.smart_manager.currentDashboardModel.hasOwnProperty('tables') ) ? window.smart_manager.currentDashboardModel.tables : '',
                        SM_IS_WOO30: window.smart_manager.sm_is_woo30,
                        SM_IS_WOO22: window.smart_manager.sm_id_woo22,
                        SM_IS_WOO21: window.smart_manager.sm_is_woo21
                    };

        params.showLoader = false;

    if( window.smart_manager.simpleSearchText != '' || window.smart_manager.advancedSearchQuery.length > 0 ) {
        params.data.filteredResults = 1;
    }

    window.smart_manager.send_request(params, function(response) {

    });
}

Smart_Manager.prototype.resetBatchUpdate = function() {
    
}


Smart_Manager.prototype.displayDefaultBatchUpdateValueHandler = function( row_id ) {

    if( row_id == '' ) {
        return;
    }

    let selected_field = jQuery( "#"+row_id+" .batch_update_field option:selected" ).val(),
        type = window.smart_manager.column_names_batch_update[selected_field].type,
        editor = window.smart_manager.column_names_batch_update[selected_field].editor,
        multiSelectSeparator = window.smart_manager.column_names_batch_update[selected_field].multiSelectSeparator,
        col_val = window.smart_manager.column_names_batch_update[selected_field].values,
        allowMultiSelect = window.smart_manager.column_names_batch_update[selected_field].allowMultiSelect,
        skip_default_action = false;

    if( type == 'checkbox') {

        let checkedVal = '',
            uncheckedVal = '',
            checkedDisplayVal = '',
            uncheckedDisplayVal = '';

        if( type == 'checkbox' ) {
            checkedVal = window.smart_manager.column_names_batch_update[selected_field].checkedTemplate;
            uncheckedVal = window.smart_manager.column_names_batch_update[selected_field].uncheckedTemplate;
            
            checkedDisplayVal = checkedVal.substr(0,1).toUpperCase() + checkedVal.substr(1,checkedVal.length);
            uncheckedDisplayVal = uncheckedVal.substr(0,1).toUpperCase() + uncheckedVal.substr(1,uncheckedVal.length);
        }

        jQuery("#"+row_id+" #batch_update_value_td").empty().append('<select class="batch_update_value" style="min-width:130px !important;">'+
                                                    '<option value="'+checkedVal+'"> '+ checkedDisplayVal +' </option>'+
                                                    '<option value="'+uncheckedVal+'"> '+ uncheckedDisplayVal +' </option>'+
                                                '</select>')
        jQuery("#"+row_id+" #batch_update_value_td").find(".batch_update_value").select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });
        
    } else if (col_val != '' && type == 'dropdown') {
        
        var batch_update_value_options = '<select class="batch_update_value" style="min-width:130px !important;">',
            value_options_empty = true;

        for (var key in col_val) {
            if( typeof (col_val[key]) != 'object' && typeof (col_val[key]) != 'Array' ) {
                value_options_empty = false;
                batch_update_value_options += '<option value="'+key+'">'+ col_val[key] + '</option>';
            }
        }

        batch_update_value_options += '</select>';

        if( value_options_empty === false ) {
            jQuery("#"+row_id+" #batch_update_value_td").empty().append(batch_update_value_options);

            let args = { width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') };

            if( editor == 'select2' && allowMultiSelect ) {
                args['multiple'] = true;
            }

            jQuery("#"+row_id+" #batch_update_value_td").find('.batch_update_value').select2(args);
        }

    } else if (col_val != '' && type == 'sm.multilist') {

        let options = {},
            index = 0;

        Object.entries( col_val ).forEach(([key, value]) => {
            index = key;

            if( value.hasOwnProperty('parent') ) {
                if( value.parent > 0 ) {
                    index = value.parent + '_childs';
                    value.term = ' – '+value.term;
                } 
            }

            if( options.hasOwnProperty(index) ) {
                options[ index ] += '<option value="'+ key +'"> '+ value.term +' </option>';    
            } else {
                options[ index ] = '<option value="'+ key +'"> '+ value.term +' </option>';
            }

            
        });

        let batch_update_value_options = '<select class="batch_update_value" style="min-width:130px !important;">'+ Object.values(options).join() +'</select>';

        jQuery("#"+row_id+" #batch_update_value_td").empty().append(batch_update_value_options)
        jQuery("#"+row_id+" #batch_update_value_td").find('.batch_update_value').select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });
     
    } else if ( type == 'sm.longstring') {
        jQuery("#"+row_id+" #batch_update_value_td").empty().append('<textarea class="batch_update_value" placeholder="Enter a value..." class="FormElement ui-widget-content"></textarea>');
    } else if ( type == 'sm.image') {
        jQuery("#"+row_id+" #batch_update_value_td").empty().append('<div class="batch_update_image" style="width:15em;"><span style="color:#0073aa;cursor:pointer;font-size: 2.25em;line-height: 1;" class="dashicons dashicons-camera"></span></div>');
    } else if ( type == 'numeric') {
        jQuery("#"+row_id+" #batch_update_value_td").empty().append('<input type="number" class="batch_update_value" placeholder="Enter a value..." class="FormElement ui-widget-content" />');
    }
}


Smart_Manager.prototype.createBatchUpdateDialog = function() {

    if( window.smart_manager.selectedRows.length <= 0 && window.smart_manager.selectAll === false ) {
        return;
    }

    let allItemsOptionText = ( window.smart_manager.simpleSearchText != '' || window.smart_manager.advancedSearchQuery.length > 0 ) ? 'All Items In Search Results' : 'All Items In Store';

    let entire_store_batch_update_html = "<tr>"+
                                            ( ( window.smart_manager.selectAll === false ) ? "<td style='white-space: pre;'><input type='radio' name='batch_update_storewide' value='selected_ids' checked/>Selected Items</td>" : '' ) +
                                            "<td style='white-space: pre;'><input type='radio' name='batch_update_storewide' value='entire_store' "+ (( window.smart_manager.selectAll === true ) ? 'checked' : '') +" />"+ allItemsOptionText +"</td>"+
                                        "</tr>",
        batch_update_field_options = '<option value="" disabled selected>Select Field</option>',
        batch_update_action_options_string = '',
        batch_update_action_options_number = '',
        batch_update_action_options_datetime = '',
        batch_update_action_options_multilist = '',
        batch_update_actions_row = '',
        batch_update_dlg_content = '',
        dlgParams = {};

        if( Object.getOwnPropertyNames(window.smart_manager.column_names_batch_update).length > 0 ) {
            for (let key in window.smart_manager.column_names_batch_update) {
                batch_update_field_options += '<option value="'+key+'" data-type="'+window.smart_manager.column_names_batch_update[key].type+'" data-editor="'+window.smart_manager.column_names_batch_update[key].editor+'" data-multiSelectSeparator="'+window.smart_manager.column_names_batch_update[key].multiSelectSeparator+'">'+ window.smart_manager.column_names_batch_update[key].name +'</option>';
            };
        }
        
        //Formating options for default actions
        window.smart_manager.batch_update_action_options_default = '<option value="" disabled selected>Select Action</option>';
        window.smart_manager.batch_update_action_options_default += '<option value="set_to">set to</option>';
        window.smart_manager.batch_update_action_options_default += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for number actions
        batch_update_action_options_string = window.smart_manager.batchUpdateSelectActionOption;
        let selected = '';
        for (let key in window.smart_manager.batch_update_action_string) {
            selected = '';
            if( key == 'set_to' ) {
                selected = 'selected';
            }

            batch_update_action_options_string += '<option value="'+key+'" '+ selected +'>'+ window.smart_manager.batch_update_action_string[key] +'</option>';
        }
        batch_update_action_options_string += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for datetime actions
        batch_update_action_options_datetime = window.smart_manager.batchUpdateSelectActionOption;
        for (let key in window.smart_manager.batch_update_action_datetime) {
            selected = '';
            if( key == 'set_datetime_to' ) {
                selected = 'selected';
            }
            batch_update_action_options_datetime += '<option value="'+key+'" '+selected+'>'+ window.smart_manager.batch_update_action_datetime[key] +'</option>';
        }
        batch_update_action_options_datetime += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for multilist actions
        batch_update_action_options_multilist = window.smart_manager.batchUpdateSelectActionOption;
        for (let key in window.smart_manager.batch_update_action_multilist) {
            selected = '';
            if( key == 'set_to' ) {
                selected = 'selected';
            }
            batch_update_action_options_multilist += '<option value="'+key+'" '+selected+'>'+ window.smart_manager.batch_update_action_multilist[key] +'</option>';
        }
        batch_update_action_options_multilist += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for string actions
        batch_update_action_options_number = window.smart_manager.batchUpdateSelectActionOption;
        for (let key in window.smart_manager.batch_update_action_number) {
            selected = '';
            if( key == 'set_to' ) {
                selected = 'selected';
            }
            batch_update_action_options_number += '<option value="'+key+'" '+selected+'>'+ window.smart_manager.batch_update_action_number[key] +'</option>';
        }
        batch_update_action_options_number += window.smart_manager.batchUpdateCopyFromOption;


        batch_update_actions_row = "<td style='white-space: pre;'><select required class='batch_update_field' style='min-width:130px;width:auto !important;'>"+batch_update_field_options+"</select></td>"+
                                        "<td style='white-space: pre;'><select required class='batch_update_action' style='min-width:130px !important;'>"+window.smart_manager.batch_update_action_options_default+"</select></td>"+
                                        "<td id='batch_update_value_td' style='white-space: pre;'><input type='text' class='batch_update_value' placeholder='Enter a value...' class='FormElement ui-widget-content'></td>"+
                                        "<td id='batch_update_add_delete_row' style='float:right;'><div class='dashicons dashicons-plus' style='color:#0073aa;cursor:pointer;line-height:1.7em;'></div><div class='dashicons dashicons-trash' style='color:#FF5B5E;cursor:pointer;line-height:1.5em;'></div></td>";



        batch_update_dlg_content = "<div id='batchUpdateform' class='formdata' style='width: 100%; overflow: auto; position: relative; height: auto;'>"+
                                        "<table class='batch_update_table' width='100%'>"+
                                            "<tbody>"+
                                                entire_store_batch_update_html +
                                                "<tr id='batch_update_action_row_0'>"+
                                                    batch_update_actions_row+
                                                "</tr>"+
                                                "<tr>"+
                                                    "<td>&#160;</td>"+
                                                "</tr>"+
                                            "</tbody>"+
                                        "</table>"+
                                    "</div>";

        dlgParams.btnParams = {};
        dlgParams.btnParams.yesText = 'Update';
        if ( typeof (window.smart_manager.processBatchUpdate) !== "undefined" && typeof (window.smart_manager.processBatchUpdate) === "function" ) {
            dlgParams.btnParams.yesCallback = window.smart_manager.processBatchUpdate;
        }

        dlgParams.btnParams.noText = 'Reset';
        if ( typeof (window.smart_manager.resetBatchUpdate) !== "undefined" && typeof (window.smart_manager.resetBatchUpdate) === "function" ) {
            dlgParams.btnParams.noCallback = window.smart_manager.resetBatchUpdate;
        }

        dlgParams.title = 'Bulk Edit';
        dlgParams.content = batch_update_dlg_content;
        dlgParams.height = 300;
        dlgParams.width = 850;

        window.smart_manager.showConfirmDialog(dlgParams);

        jQuery('.batch_update_field, .batch_update_action').each(function() {
            jQuery(this).select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });
        })

        jQuery(".batch_update_action_row_0").find('#batch_update_add_delete_row .dashicons-trash').hide(); //for hiding the delete icon for the first row

        //function for handling add row in batch update dialog
        jQuery(document).off('click','#batch_update_add_delete_row .dashicons-plus').on('click','#batch_update_add_delete_row .dashicons-plus', function() {
            let count = jQuery('tr[id^=batch_update_action_row_]').length,
                current_id = 'batch_update_action_row_'+count;
            jQuery('.batch_update_table tr:last').before("<tr id="+current_id+">"+ batch_update_actions_row +"</tr>");

            jQuery("#"+current_id).find('.batch_update_field, .batch_update_action').select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });

            jQuery(this).hide();

        });

        //function for handling delete row in batch update dialog
        jQuery(document).off('click','#batch_update_add_delete_row .dashicons-trash').on('click','#batch_update_add_delete_row .dashicons-trash', function() {

            let add_row_visible = jQuery(this).closest('td').find('.dashicons-plus').is(":visible");
            jQuery(this).closest('tr').remove();

            if( add_row_visible === true ) { //condition for removing plus icon only if visible
                jQuery('tr[id^=batch_update_action_row_]:last()').find('.dashicons-plus').show();
            }

        });

        // For the time now
        Date.prototype.timeNow = function () {
             return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
        }

        jQuery(document).on('change','.batch_update_field',function(){

            let row_id = jQuery(this).closest('tr').attr('id');

            let selected_field = jQuery( "#"+row_id+" .batch_update_field option:selected" ).val(),
                type = window.smart_manager.column_names_batch_update[selected_field].type,
                editor = window.smart_manager.column_names_batch_update[selected_field].editor,
                col_val = window.smart_manager.column_names_batch_update[selected_field].values,
                skip_default_action = false;

            // Formating options for default actions
            window.smart_manager.batch_update_action_options_default = window.smart_manager.batchUpdateSelectActionOption +
                                                                        '<option value="set_to">set to</option>'+
                                                                        window.smart_manager.batchUpdateCopyFromOption;

            jQuery(document).trigger("sm_batch_update_field_on_change",[row_id, selected_field, type, col_val]);

            if( type == 'numeric' ) {
                jQuery("#"+row_id+" .batch_update_action").empty().append(batch_update_action_options_number);
            } else if (type == 'text' || type == 'sm.longstring') {
                jQuery("#"+row_id+" .batch_update_action").empty().append(batch_update_action_options_string);
            } else if ( type == 'sm.datetime' ) {
                jQuery("#"+row_id+" .batch_update_action").empty().append(batch_update_action_options_datetime);
            } else if ( type == 'sm.multilist' || ( type == 'dropdown' && editor == 'select2' ) ) {
                jQuery("#"+row_id+" .batch_update_action").empty().append(batch_update_action_options_multilist);
            } else {
                jQuery("#"+row_id+" .batch_update_action").empty().append(window.smart_manager.batch_update_action_options_default);
                jQuery("#"+row_id+" .batch_update_action").find('[value="set_to"]').attr('selected','selected');
            }

            let actionOptions = { 'batch_update_action_options_number': batch_update_action_options_number,
                                'batch_update_action_options_string': batch_update_action_options_string,
                                'batch_update_action_options_datetime': batch_update_action_options_datetime,
                                'batch_update_action_options_multilist': batch_update_action_options_multilist};

            jQuery(document).trigger("sm_batch_update_field_post_on_change",[row_id, selected_field, type, col_val, actionOptions]);

            jQuery("#"+row_id+" .batch_update_value").val('');

            jQuery("#"+row_id+" #batch_update_value_td").empty().append('<input type="text" class="batch_update_value" placeholder="Enter a value..." class="FormElement ui-widget-content" >');

            if( skip_default_action === true ) {
                return;
            }

            if (type == 'sm.date' || type == 'sm.time' || type == 'sm.datetime' ) {
                let placeholder = 'YYYY-MM-DD' + ((type == 'sm.datetime') ? ' HH:MM:SS' : '');
                placeholder = ( type == 'sm.time' ) ? 'H:i' : placeholder;

                jQuery("#"+row_id+" .batch_update_value").attr('placeholder',placeholder);

                let format = 'Y-m-d'+ ((type == 'sm.datetime') ? ' H:i:s' : '');
                format = ( type == 'sm.time' ) ? 'H:i' : format;

                jQuery("#"+row_id+" .batch_update_value").Zebra_DatePicker({ format: format,
                                                                            show_icon: false,
                                                                            show_select_today: false,
                                                                            default_position: 'below',
                                                                        });
            } else {
                 jQuery("#"+row_id+" .batch_update_value").attr('placeholder','Enter a value...');
                let datepicker = jQuery("#"+row_id+" .batch_update_value").data('Zebra_DatePicker');
            }

            if ( typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) !== "undefined" && typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) === "function" ) {
                dlgParams.btnParams.noCallback = window.smart_manager.displayDefaultBatchUpdateValueHandler( row_id );
            }
        });

        //Handling action change event only for 'datetime' type fields
        jQuery(document).on('change','.batch_update_action',function(){

            let row_id = jQuery(this).closest('tr').attr('id');

            let selected_field = jQuery( "#"+row_id+" .batch_update_field option:selected" ).val(),
                selected_action = jQuery( "#"+row_id+" .batch_update_action option:selected" ).val(),
                type = window.smart_manager.column_names_batch_update[selected_field].type;

            if( jQuery("#"+row_id+" #batch_update_value_td").find(".sm_batch_update_copy_from_ids").length > 0 || jQuery("#"+row_id+" #batch_update_value_td").find(".sm_batch_update_search_value").length > 0 ) {
                jQuery("#"+row_id+" #batch_update_value_td").empty().append('<input type="text" class="batch_update_value" placeholder="Enter a value..." class="FormElement ui-widget-content" >');
            }

            if( type == 'sm.datetime' ) {
                let placeholder = ( selected_action == 'set_datetime_to' ) ? 'YYYY-MM-DD HH:MM:SS' : ( ( selected_action == 'set_date_to' ) ? 'YYYY-MM-DD' : 'HH:MM:SS' );

                jQuery("#"+row_id+" .batch_update_value").attr('placeholder',placeholder);

                let format = ( selected_action == 'set_datetime_to' ) ? 'Y-m-d H:i:s' : ( ( selected_action == 'set_date_to' ) ? 'Y-m-d' : 'H:i:s' );
                jQuery("#"+row_id+" .batch_update_value").Zebra_DatePicker({ format: format,
                                                                                show_icon: false,
                                                                                show_select_today: false,
                                                                                default_position: 'below',
                                                                            });
            }

            //Code for handling 'copy from' functionality
            if( selected_action == 'copy_from' ) {

                let select_str = '<select class="batch_update_value sm_batch_update_copy_from_ids" style="min-width:130px !important;">'+
                                    '<option></option></select>';

                let select2Params = {
                    width: '15em',
                    dropdownCssClass: 'sm_beta_batch_update_field',
                    placeholder: 'Select '+window.smart_manager.dashboardDisplayName,
                    dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]'),
                    ajax: {
                        url:         window.smart_manager.sm_ajax_url,
                        dataType:    'json',
                        delay:       250,
                        data:        function( params ) {
                            return {
                                search_term     : params.term,
                                cmd             :'get_batch_update_copy_from_record_ids',
                                active_module   : window.smart_manager.dashboard_key,
                                security        : window.smart_manager.sm_nonce
                            };
                        },
                        processResults: function( data ) {
                            var terms = [];
                            if ( data ) {
                                jQuery.each( data, function( id, title ) {
                                    terms.push({
                                        id:   id,
                                        text: title
                                    });
                                });
                            }
                            return {
                                results: terms
                            };
                        },
                        cache: true
                    }
                }

                jQuery("#"+row_id+" #batch_update_value_td").empty().append(select_str);
                jQuery("#"+row_id+" #batch_update_value_td").find(".batch_update_value").select2(select2Params);
            } else if( selected_action == 'search_and_replace' ) {
                jQuery("#"+row_id+" #batch_update_value_td").empty().append('<input type="text" class="batch_update_value sm_batch_update_search_value" placeholder="Search for..." class="FormElement ui-widget-content" >');
                jQuery("#"+row_id+" #batch_update_value_td").append('<input type="text" style="margin-left: 1em;" class="batch_update_value sm_batch_update_replace_value" placeholder="Replace with..." class="FormElement ui-widget-content" >');
            } else {
                if ( typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) !== "undefined" && typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) === "function" ) {
                    dlgParams.btnParams.noCallback = window.smart_manager.displayDefaultBatchUpdateValueHandler( row_id );
                }
            }
        });

        jQuery(document).off('click', ".batch_update_image").on('click', ".batch_update_image", function(event){

            let row_id = jQuery(this).closest('tr').attr('id');

            let file_frame;
                                        
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
              file_frame.open();
              return;
            }
            
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
              title: jQuery( this ).data( 'uploader_title' ),
              button: {
                text: jQuery( this ).data( 'uploader_button_text' )
              },
              multiple: false  // Set to true to allow multiple files to be selected
            });

            file_frame.on( 'open', function() {
                jQuery('[aria-describedby="sm_inline_dialog"]').hide();
            });

            file_frame.on( 'close', function() {
                jQuery('[aria-describedby="sm_inline_dialog"]').show();
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
              // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();

                jQuery('#'+row_id+' .batch_update_image').attr('data-imageId',attachment['id']);
                jQuery('#'+row_id+' .batch_update_image').html('<img style="cursor:pointer;" src="'+attachment['url']+'" width="32" height="32">');
            });
            
            file_frame.open();
        });
    };

// ========================================================================

Smart_Manager.prototype.smToggleFullScreen = function (elem) {
    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (elem.requestFullScreen) {
            elem.requestFullScreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

Smart_Manager.prototype.smScreenHandler = function () {
    if( window.smart_manager.wpToolsPanelWidth === 0) {
        window.smart_manager.wpToolsPanelWidth = jQuery('#adminmenuwrap').width();
        jQuery('#adminmenuback').hide();
        jQuery('#adminmenuwrap').hide();
        jQuery('#wpadminbar').hide();
        jQuery('#wpcontent').css('margin-left', '0px');
        window.smart_manager.grid_width = window.smart_manager.grid_width + window.smart_manager.wpToolsPanelWidth;
        window.smart_manager.grid_height = document.documentElement.offsetHeight - 260;
    } else {
        jQuery('#adminmenuback').show();
        jQuery('#adminmenuwrap').show();
        jQuery('#wpadminbar').show();
        jQuery('#wpcontent').removeAttr("style");
        window.smart_manager.grid_width = window.smart_manager.grid_width - window.smart_manager.wpToolsPanelWidth;
        window.smart_manager.grid_height = document.documentElement.offsetHeight - 360;
        window.smart_manager.wpToolsPanelWidth = 0;
    }
    
    window.smart_manager.hot.updateSettings({'width':window.smart_manager.grid_width, 'height': window.smart_manager.grid_height});
    window.smart_manager.hot.render();

    jQuery('#sm_top_bar, #sm_bottom_bar').css('width',window.smart_manager.grid_width+'px');
}

if ( document.addEventListener ) {
    document.addEventListener('webkitfullscreenchange', window.smart_manager.smScreenHandler, false);
    document.addEventListener('mozfullscreenchange', window.smart_manager.smScreenHandler, false);
    document.addEventListener('fullscreenchange', window.smart_manager.smScreenHandler, false);
    document.addEventListener('MSFullscreenChange', window.smart_manager.smScreenHandler, false);
}

// Function to handle deletion of records
Smart_Manager.prototype.deleteAllRecords = function ( actionArgs ) {

    if( window.smart_manager.selectedRows.length == 0 && !window.smart_manager.selectAll ) {
        return;
    }

    setTimeout( function() { 

        window.smart_manager.showProgressDialog('Delete Records'); 

        if( typeof (sa_sm_background_process_heartbeat) !== "undefined" && typeof (sa_sm_background_process_heartbeat) === "function" ) {
            sa_sm_background_process_heartbeat();
        }

    } ,1);

    let args = ( typeof('actionArgs') != 'undefined' ) ? actionArgs : {}; 

    let params = {};
        params.data = {
                        cmd: 'delete_all',
                        active_module: window.smart_manager.dashboard_key,
                        security: window.smart_manager.sm_nonce,
                        selected_ids: JSON.stringify(window.smart_manager.getSelectedKeyIds()),
                        storewide_option: ( true === window.smart_manager.selectAll ) ? 'entire_store' : '',
                        active_module_title: window.smart_manager.dashboardName,
                        backgroundProcessRunningMessage: window.smart_manager.backgroundProcessRunningMessage,
                        deletePermanently: ( ( args.hasOwnProperty('deletePermanently') ) ? args.deletePermanently : 0 )
                    };

        params.showLoader = false;

        if( window.smart_manager.simpleSearchText != '' || window.smart_manager.advancedSearchQuery.length > 0 ) {
            params.data.filteredResults = 1;
        }

    window.smart_manager.send_request(params, function(response) {
        // if ( response ) {
        //     let no_of_records = parseInt( response );
        //     if ( no_of_records > 0 ) {
        //         if( jQuery('#sm_top_bar_action_btns_basic #del_sm_editor_grid span').hasClass('sm-ui-state-disabled') === false ) {
        //             jQuery('#sm_top_bar_action_btns_basic #del_sm_editor_grid span').addClass('sm-ui-state-disabled');
        //         }
        //         // setTimeout(function() {
        //         //     params = { 'func_nm' : 'delete_all', 'title' : 'Delete Records' }
        //         //     window.smart_manager.background_process_hearbeat( params );
        //         // }, 1000);
        //     } else {
        //         window.smart_manager.refresh();
        //         window.smart_manager.hideNotification();
        //         window.smart_manager.showNotification('', 'Cannot delete the selected records');
        //     }
        // }
    });

}
