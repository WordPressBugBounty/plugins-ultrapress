/**
 * Handles interactivity on the UltraPress admin settings pages.
 *
 * This script manages the media uploader for the chatbot icon and toggles
 * the visibility of API provider settings based on user selection.
 *
 * @package UltraPress
 * @version 5.0.0
 */

'use strict';

jQuery(function ($) {

    // --- 1. Media Uploader for ChatBot Icon (Runs on the Chatbot Settings page) ---
    const uploadBtn = $('#ultrapress-upload-icon-btn');
    
    if (uploadBtn.length) {
        let mediaUploader;

        const iconUrlInput = $('#ultrapress-chatbot-icon-url');
        const iconPreview = $('.ultrapress-icon-preview-wrapper img');
        const resetBtn = $('#ultrapress-reset-icon-btn');

        // Handles the click event for the upload button.
        uploadBtn.on('click', function (e) {
            e.preventDefault();

            // If the uploader object already exists, just open it.
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Create a new media uploader frame.
            mediaUploader = wp.media({
                title: ultrapressAdminData.uploaderTitle, // Title from localized script
                button: {
                    text: ultrapressAdminData.uploaderButton, // Button text from localized script
                },
                multiple: false, // User can only select one image
            });

            // Event handler for when an image is selected.
            mediaUploader.on('select', function () {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                iconUrlInput.val(attachment.url); // Set the URL in the hidden input
                iconPreview.attr('src', attachment.url); // Update the preview image
            });

            // Open the media uploader frame.
            mediaUploader.open();
        });

        // Handles the click event for the reset button.
        resetBtn.on('click', function (e) {
            e.preventDefault();
            iconUrlInput.val(''); // Clear the URL from the hidden input
            iconPreview.attr('src', ultrapressAdminData.defaultIconUrl); // Reset preview to default
        });
    }


    // --- 2. API Provider Settings Toggle (Runs on the AI Brain page) ---
    const providerSelect = $('#ultrapress_api_provider_select');

    if (providerSelect.length) {
        const toggleProviderSettings = function () {
            const selectedProvider = providerSelect.val();
            
            // This is a robust way to hide all provider-specific fields, which are inside table rows (tr).
            $('.provider-setting').closest('tr').hide();
            
            // Show only the settings for the currently selected provider.
            $('.provider-' + selectedProvider).closest('tr').show();
        };

        // Run the function on page load to set the initial correct view.
        toggleProviderSettings();
        
        // Run the function again whenever the user changes the selection.
        providerSelect.on('change', toggleProviderSettings);
    }

});