/**
 * Ultrapress Debug Module
 * Handles frontend debugging and error reporting
 */
window.UltrapressDebug = (function() {
    'use strict';

    let config = {
        debug: false,
        logToConsole: true
    };

    /**
     * Initialize the debug module
     * @param {Object} options Configuration options
     */
    function init(options = {}) {
        config = { ...config, ...options };
        
        // Set up global error handler
        window.addEventListener('error', function(event) {
            log('Uncaught error: ' + event.message, 'error', {
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                error: event.error
            });
        });

        // Monitor AJAX requests
        if (window.jQuery) {
            jQuery(document).ajaxError(function(event, jqXHR, settings, thrownError) {
                log('AJAX error: ' + thrownError, 'error', {
                    url: settings.url,
                    status: jqXHR.status,
                    response: jqXHR.responseText
                });
            });
        }
    }

    /**
     * Log a debug message
     * @param {string} message The message to log
     * @param {string} type The type of message (debug, info, warning, error)
     * @param {Object} context Additional context data
     */
    function log(message, type = 'debug', context = {}) {
        if (!config.debug && type !== 'error') {
            return;
        }

        const logEntry = {
            timestamp: new Date().toISOString(),
            type: type,
            message: message,
            context: context
        };

        // Log to console if enabled
        if (config.logToConsole) {
            const consoleMethod = type === 'error' ? 'error' 
                               : type === 'warning' ? 'warn'
                               : type === 'info' ? 'info'
                               : 'log';
            console[consoleMethod]('[Ultrapress]', message, context);
        }

        // Send to server for logging if it's an error
        if (type === 'error') {
            sendToServer(logEntry);
        }
    }

    /**
     * Send log entry to server
     * @param {Object} logEntry The log entry to send
     */
    function sendToServer(logEntry) {
        if (window.jQuery && window.ultrapress_debug && window.ultrapress_debug.ajax_url) {
            jQuery.ajax({
                url: window.ultrapress_debug.ajax_url,
                type: 'POST',
                data: {
                    action: 'ultrapress_log_error',
                    nonce: window.ultrapress_debug.nonce,
                    log_entry: logEntry
                }
            });
        }
    }

    return {
        init: init,
        log: log
    };
})();
