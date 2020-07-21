// Site-Specific Scripts

var site = {
    init: function() {
        // Site-specific functions
        site.introPane();
        site.eventListing();
        site.eventPopups();
        site.rewardPopups();
        site.popupForms();
        site.saveFormValues();
        site.cartSetup();
        site.checkoutSetup();
        site.emailSignup();

        // Run jQuery plugins
        $('.inplace').autoHideLabels();
    },

    // LocalStorage test
    localStorageSupport: (function() {
        try {
            return 'localStorage' in window && window['localStorage'] !== null;
        } catch (e) {
            return false;
        }
    })(),

    introPane: function() {
        var $pane = $('#home-pane');

        if (
            $pane.length &&
            $(window).width() > 800 &&
            $.cookie('seen_intro') != 1
        ) {
            $('body').addClass('show-pane');

            var expiry = new Date();
            expiry.setTime(expiry.getTime() + 60 * 60 * 1000);
            $.cookie('seen_intro', 1, { expires: expiry });

            $pane.on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('show-pane hide-pane');
            });
        }
    },

    eventListing: function() {
        var $container = $('#events');
        if ($container.length === 0) return;

        var $allMonths = $container.children(),
            currentMonthIndex = 0,
            monthCount = $allMonths.length;

        // Event handlers
        function moveNext() {
            if (currentMonthIndex < monthCount) {
                currentMonthIndex++;
                updateDisplay(true);
            }
        }

        function movePrev() {
            if (currentMonthIndex > 0) {
                currentMonthIndex--;
                updateDisplay(true);
            }
        }

        function updateDisplay(updateHash) {
            $allMonths
                .removeClass('prevMonth currentMonth nextMonth')
                .find('.monthName')
                .off('click');

            var $newMonth = $allMonths
                    .eq(currentMonthIndex)
                    .addClass('currentMonth'),
                newMonthId = $newMonth.attr('id');

            // Update URL
            if (updateHash && history.replaceState) {
                window.history.replaceState(null, '', '#' + newMonthId);
            }

            if (currentMonthIndex > 0) {
                $allMonths
                    .eq(currentMonthIndex - 1)
                    .addClass('prevMonth')
                    .find('.monthName')
                    .one('click', movePrev);
            }

            if (currentMonthIndex < monthCount) {
                $allMonths
                    .eq(currentMonthIndex + 1)
                    .addClass('nextMonth')
                    .find('.monthName')
                    .one('click', moveNext);
            }
        }

        function restoreState() {
            if (window.location.hash !== '') {
                currentMonthIndex = $(window.location.hash).index();
                updateDisplay();
                $(window).scrollTop(0);
            }
        }

        updateDisplay();

        window.onpopstate = restoreState;
        restoreState();
    },

    eventPopups: function() {
        var currentRequest,
            $lightbox = $('<div class="lightbox"/>');

        $('body').on('click', 'a[data-popup]', function(e) {
            if ($(window).width() < 640) return;

            e.preventDefault();

            // Clean up previous state
            $lightbox.trigger('close');
            if (currentRequest) currentRequest.abort();

            // Set loading animation
            var href = $(this).attr('href'),
                $event = $(this)
                    .parent()
                    .addClass('loading');

            // Retrieve the page from the server
            currentRequest = $.ajax({
                url: href,
                cache: false,
            })
                .done(function(data) {
                    var $page = $('<div>').append($.parseHTML(data)),
                        $content = $page.find('#event');

                    $lightbox
                        .html($content)
                        .prepend('<a href="#" class="close">&times;</a>')
                        .lightbox_me({
                            centered: true,
                            overlayCSS: { background: 'black', opacity: 0.8 },
                        });

                    $lightbox.find('.inplace').autoHideLabels();
                    site.saveFormValues($lightbox);

                    // Track in GA
                    if (typeof ga !== 'undefined') {
                        var pageTitle = $page.find('title').text();
                        ga('send', 'pageview', {
                            page: href,
                            title: pageTitle,
                        });
                    }
                })
                .fail(function() {
                    alert('Could not load the event details.');
                })
                .always(function() {
                    $event.removeClass('loading');
                });
        });
    },

    rewardPopups: function() {
        var currentRequest,
            $lightbox = $('<div class="lightbox"/>');

        $('body').on('click', 'a[data-popup-inline]', function(e) {
            e.preventDefault();

            // Clean up previous state
            $lightbox.trigger('close');

            // Get the lightbox content from a hidden div
            var $content = $($(this).attr('href')).clone();

            $lightbox
                .html($content)
                .prepend('<a href="#" class="close">&times;</a>')
                .lightbox_me({
                    centered: true,
                    overlayCSS: { background: 'black', opacity: 0.8 },
                })
                .find('form')
                .parsley();
        });
    },

    popupForms: function() {
        var bodyClickHandler;

        function showForm(e) {
            var $popupForm = $($(this).attr('href'));

            $popupForm.addClass('open').parsley();

            bodyClickHandler = $('body').one('click', hideForm);

            $popupForm.filter('[data-ajax-submit]').submit(function(e) {
                e.preventDefault();

                var action = $popupForm.attr('action'),
                    data = $popupForm.serialize();

                $.post(action, data, function(data) {
                    alert(data.error || $popupForm.attr('data-ajax-submit'));
                });

                hideForm();
            });

            setTimeout(function() {
                $popupForm
                    .find('input[type!="hidden"]')
                    .eq(0)
                    .focus();
            }, 10);

            e.preventDefault();
        }

        function hideForm() {
            $('.popup-form').removeClass('open');
            $('body').off('click', hideForm);
        }

        $('body').on('click', '[data-popup-form]', showForm);

        // Stop propagation to keep the event from reaching the body element
        $('body').on('click', '.popup-form', function(e) {
            e.stopPropagation();
        });
    },

    saveFormValues: function($container) {
        if (!site.localStorageSupport) return;

        if (!$container) {
            $container = $('body');
        }

        $('input[data-save-value]', $container).each(function() {
            // Restore previous values
            if (this.value == '') {
                this.value = localStorage.getItem(this.name);
                $(this).trigger('change');
            }

            $(this).on('blur', function() {
                localStorage.setItem(this.name, this.value);
            });
        });
    },

    cartSetup: function() {
        var $cart = $('#cart');
        if (!$cart.length) return;

        // Update the total price for an event
        function updateEventTotal($row) {
            // Update this row
            var $total = $row.find('.price-total'),
                count = $row.find('.attendee').length,
                price = $row.data('price'),
                total = (count * price).toFixed(2);

            $row.data('total', total);
            $total.text('$' + total);

            // Update grand total
            var $rows = $cart.find('tr[data-total]'),
                $grandTotal = $cart.find('.price-grandtotal'),
                grandTotal = 0;

            $rows.each(function() {
                grandTotal += parseInt($(this).data('total'), 10);
            });
            grandTotal = grandTotal.toFixed(2);

            $grandTotal.text('$' + grandTotal);
        }

        // Add new attendee button
        function addAttendee() {
            var $newRegistration = $('#newRegistration');

            $newRegistration.find('input[name="entryId"]').val(
                $(this)
                    .parent()
                    .data('entryid')
            );

            $newRegistration.addClass('hiding').insertBefore($(this));

            setTimeout(function() {
                $newRegistration.removeClass('hiding');
                $newRegistration.find('.edit').click();
            }, 10);

            updateEventTotal($row);
        }
        // $cart.on('click', '.add-attendee', addAttendee);

        // Remove attendee button
        function removeAttendee() {
            var $registration = $(this).parent(),
                $row = $registration.closest('tr');

            if ($registration.siblings('.attendee').length === 0) {
                $row.fadeOut('fast', function() {
                    $(this).remove();
                });
            } else {
                $registration.addClass('hiding');
                setTimeout(function() {
                    $registration.remove();
                    updateEventTotal($row);
                }, 400);
            }
        }
        // $cart.on('click', '.btn-remove', removeAttendee);

        // Edit a person's contact info
        function editAttendee() {
            var $registration = $(this).closest('.attendee'),
                $editAttendeeDialog = $registration.find('.edit-attendee');
            validator = $editAttendeeDialog.parsley();

            $registration.addClass('editing');

            function hideDialog() {
                $registration.removeClass('editing');
            }

            $editAttendeeDialog.on('click', '.cancel', hideDialog);
        }
        $cart.on('click', '.btn-edit', editAttendee);

        // Make sure we have contact info for each event
        $('#btn-checkout').on('click', function(e) {
            // Validate
            if ($('[data-incomplete]').length) {
                alert(
                    'You must enter contact information for at least one person who will be attending each event.'
                );
                return false;
            }
        });

        // Refresh the page as events expire
        var $attendees = $('[data-expires]'),
            messageShown = false;
        setInterval(function() {
            var now = Math.round(Date.now() / 1000) + 10;
            $attendees.each(function() {
                var $row = $(this);
                var expires = $row.data('expires');
                if (expires && expires < now) {
                    $attendees = $attendees.not($row);
                    $('#messages').html(
                        '<div class="message">An item in your cart expired and is being removed.</div>'
                    );
                    $.post(
                        '/actions/registrar/registration/remove',
                        { id: $row.data('id') },
                        function() {
                            $('#main').load('/cart');
                            messageShown = false;
                        }
                    );
                } else if (expires <= now + 20 && !messageShown) {
                    $('#messages').html(
                        '<div class="message">An item in your cart is about to expire. This page will refresh in a few seconds.</div>'
                    );
                    messageShown = true;
                }
            });
        }, 2000);
    },

    checkoutSetup: function() {
        var $form = $('#checkout-form');

        if (
            $('#cc-number').length &&
            typeof Accept !== 'undefined' &&
            typeof window.authData !== 'undefined'
        ) {
            $('#cc-number').payment('formatCardNumber');
            $('#cc-exp').payment('formatCardExpiry');
            $('#cc-cvc').payment('formatCardCVC');

            // Validate data and generate a Stripe token
            $form.parsley();
            $form.parsley().on('form:success', function(formInstance) {
                // Cancel the form submission
                formInstance.submitEvent.preventDefault();

                // Disable the submit button to prevent repeated clicks
                $form.find('button').prop('disabled', true);

                // Create a Stripe token
                var ccNum = $('#cc-number')
                    .val()
                    .replace(/\s+/g, '');
                var expiry = $('#cc-exp').payment('cardExpiryVal');
                var month =
                    expiry.month < 10 ? '0' + expiry.month : expiry.month;
                var year = expiry.year.toString().substr(-2);

                var secureData = {
                    authData: window.authData,
                    cardData: {
                        cardNumber: ccNum,
                        month: month,
                        year: year,
                        cardCode: $('#cc-cvc').val(),
                        zip: $('#zip').val(),
                        fullName:
                            $('#firstname-input').val() +
                            ' ' +
                            $('#lastname-input').val(),
                    },
                };

                Accept.dispatchData(secureData, responseHandler);
            });
        }

        function responseHandler(response) {
            if (response.messages.resultCode === 'Error') {
                var msg = '';
                response.messages.message.forEach(function(error) {
                    msg = error.text + '\n';
                });
                alert(msg);
                $form.find('button').prop('disabled', false);
            } else {
                $form.append(
                    $('<input type="hidden" name="token" />').val(
                        response.opaqueData.dataValue
                    )
                );
                $form.get(0).submit();
            }
        }
    },

    emailSignup: function() {
        $('#signup-form').on('submit', function(e) {
            e.preventDefault();

            $.post('/', $(this).serialize(), function(data) {
                if (!data.success) {
                    // there was an error, do something with data
                    alert(data.message);
                } else {
                    // Success
                    alert('Thanks for your interest!');
                }
            });
        });
    },
};

// Add additional Parsley validators
window.Parsley.addValidator(
    'cc',
    function(value, requirement) {
        return $.payment.validateCardNumber(value);
    },
    32
).addMessage('en', 'cc', 'Please enter a valid credit card number.');
window.Parsley.addValidator(
    'expiry',
    function(value, requirement) {
        value = $.payment.cardExpiryVal(value);
        return $.payment.validateCardExpiry(value);
    },
    32
).addMessage('en', 'expiry', 'This is not a valid expiration date.');
window.Parsley.addValidator(
    'cvc',
    function(value, requirement) {
        return $.payment.validateCardCVC(value);
    },
    32
).addMessage(
    'en',
    'cvc',
    'The 3- or 4-digit security code on the back of your card.'
);

// Start things off
$(document).ready(site.init);
