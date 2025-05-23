import jQuery from 'jquery';
let $ = jQuery;

/**
 * Debounce and throttle function's decorator plugin 1.0.5
 *
 * Copyright (c) 2009 Filatov Dmitry (alpha@zforms.ru)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

(function($) {

    $.extend({

        debounce : function(fn, timeout, invokeAsap, ctx) {

            if(arguments.length == 3 && typeof invokeAsap != 'boolean') {
                ctx = invokeAsap;
                invokeAsap = false;
            }

            var timer;

            return function() {

                var args = arguments;
                ctx = ctx || this;

                invokeAsap && !timer && fn.apply(ctx, args);

                clearTimeout(timer);

                timer = setTimeout(function() {
                    invokeAsap || fn.apply(ctx, args);
                    timer = null;
                }, timeout);

            };

        },

        throttle : function(fn, timeout, ctx) {

            var timer, args, needInvoke;

            return function() {

                args = arguments;
                needInvoke = true;
                ctx = ctx || this;

                timer || (function() {
                    if(needInvoke) {
                        fn.apply(ctx, args);
                        needInvoke = false;
                        timer = setTimeout(arguments.callee, timeout);
                    }
                    else {
                        timer = null;
                    }
                })();

            };

        }

    });

})(jQuery);