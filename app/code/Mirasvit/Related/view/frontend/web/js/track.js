define([
    'jquery',
    'underscore',
    'jquery/jquery.cookie',
    'domReady!'
], function ($, _) {
    'use strict';
    
    $.widget('mirasvit.relatedAnalytics', {
        options: {
            callbackUrl: '',
            cookieName:  ''
        },
        
        _create: function () {
            var sessionId = this.ensureSession();
            
            var $blocks = $('[data-related-block]');
            
            _.each($blocks, function (block) {
                var $block = $(block);
                var id = $block.attr('data-related-block');
                
                $.post(this.options.callbackUrl, {
                    action:     'impression',
                    block_id:   id,
                    session_id: sessionId
                });
                
                $('a', $block).on('click', function (e) {
                    $.post(this.options.callbackUrl, {
                        action:     'click',
                        block_id:   id,
                        session_id: sessionId
                    });
                }.bind(this))
                
            }.bind(this))
        },
        
        ensureSession: function () {
            var cookieName = this.options.cookieName;
            var session = $.cookie(cookieName);
            var currentDate = new Date();
            
            if (!session) {
                session = '' + Math.floor(currentDate.getTime() / 1000) + Math.floor(Math.random() * 10000001);
            }
            
            if (session) {
                $.cookie(cookieName, session, {expires: 60 * 60 * 24 * 365, path: '/'});
            }
            
            return session;
        }
    });
    
    return $.mirasvit.relatedAnalytics;
});