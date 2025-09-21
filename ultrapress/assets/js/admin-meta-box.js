/**
 * Handles interactivity within the UltraPress AI SEO meta box in the post editor.
 *
 * This script manages character counters, AJAX requests for AI meta generation,
 * and updates the UI with the response.
 *
 * @package UltraPress
 * @version 5.0.0
 */

'use strict';

jQuery(function ($) {
    // --- 1. Cache DOM Elements ---
    const metaBox = $('#ultrapress_seo_meta_box');
    
    // Check if the meta box exists on the page to avoid running code unnecessarily.
    if (!metaBox.length) {
        return;
    }

    const titleInput = metaBox.find('#_ultrapress_seo_title');
    const descriptionInput = metaBox.find('#_ultrapress_meta_description');
    const keywordInput = metaBox.find('#_ultrapress_focus_keyword');
    const generateBtn = metaBox.find('#ultrapress-generate-seo-btn');
    const spinner = metaBox.find('.spinner');

    // --- 2. Character Counters ---

    function updateCounter(inputElement) {
        const counterElement = metaBox.find('.ultrapress-char-counter[data-target="' + inputElement.attr('id') + '"]');
        if (counterElement.length) {
            const currentLength = inputElement.val().length;
            const limit = counterElement.data('limit');
            counterElement.find('span').text(currentLength);

            // Change color if limit is exceeded
            if (currentLength > limit) {
                counterElement.css('color', '#d63638'); // Red
            } else {
                counterElement.css('color', '#666'); // Default grey
            }
        }
    }

    // Initialize counters on page load
    updateCounter(titleInput);
    updateCounter(descriptionInput);

    // Update counters on every key press
    titleInput.on('input', () => updateCounter(titleInput));
    descriptionInput.on('input', () => updateCounter(descriptionInput));


    // --- 3. AI Generation AJAX Handler ---

    generateBtn.on('click', function () {
        const btn = $(this);
        let originalBtnText = btn.html();

        // Get post content - works with both standard and Gutenberg editors
        const postTitle = $('#title').val() || $('.editor-post-title__input').val();
        let postContent = '';
        if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/editor')) {
            postContent = wp.data.select('core/editor').getEditedPostContent();
        } else {
            postContent = $('#content').val();
        }
        
        // Sanitize content by stripping HTML tags for the AI
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = postContent;
        const strippedContent = tempDiv.textContent || tempDiv.innerText || '';

        // Show loading state
        btn.prop('disabled', true).html(ultrapressSeoData.generatingText);
        spinner.addClass('is-active');

        // Perform the AJAX request
        $.ajax({
            url: ultrapressSeoData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ultrapress_generate_seo_meta',
                nonce: ultrapressSeoData.nonce,
                title: postTitle,
                content: strippedContent.substring(0, 1500), // Send a generous snippet
                keyword: keywordInput.val(),
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Populate the fields with the AI's response
                    if (data.title) {
                        titleInput.val(data.title).trigger('input'); // Trigger input to update counter
                    }
                    if (data.description) {
                        descriptionInput.val(data.description).trigger('input');
                    }
                    // If a keyword was suggested, populate that field too
                    if (data.suggested_keyword) {
                        keywordInput.val(data.suggested_keyword);
                    }

                } else {
                    // Show an alert with the error message
                    alert('Error: ' + (response.data.message || 'An unknown error occurred.'));
                }
            },
            error: function () {
                alert('Error: A connection error occurred with the server.');
            },
            complete: function () {
                // Restore button to its original state
                btn.prop('disabled', false).html(originalBtnText);
                spinner.removeClass('is-active');
            }
        });
    });
});