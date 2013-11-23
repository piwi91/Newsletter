$(function() {
    $.widget('piwicms.application', {
        // Default options
        options: {

        },

        // Private variables
        _UIblocked: false,

        /**
         * The constructor
         *
         * @private
         */
        _create: function() {

        },

        /**
         * Block the UI
         *
         * @param string header
         * @param string text
         * @param string class
         */
        blockUI: function(title, message, className) {
            if(!this._UIblocked){
                this._UIblocked = true;

                var htmlMessage = $('<div></div>')
                        .addClass('content').addClass(className)
                        .append($('<p></p>').html(title).addClass('title'))
                        .append($('<p></p>').html(message).addClass('message'))
                    ;

                /* $.blockUI.defaults.css = {}; */
                $.blockUI({
                    message: htmlMessage,
                    css: { backgroundColor: 'transparent', color: '#fff', border:0, zIndex: 2001 },
                    overlayCSS:  { zIndex: 2000, backgroundColor: '#000', opacity: 0.9, cursor: 'wait' }
                });
            }
        },

        /**
         * Unblock the UI
         */
        unblockUI: function() {
            this._UIblocked = false;

            $.unblockUI();
        },

        /**
         * RenderModal
         *
         * @param target
         * @param url
         * @param cssClass
         * @param loadingMessage
         */
        renderModal: function(target, url, cssClass, loadingMessage) {
            var $this = this;

            if (cssClass == undefined) {
                cssClass = '';
            }
            if (target == "#") {
                target = "#modal"+parseInt(Math.random()*1000);
            }
            if (url.indexOf('#') == 0) {
                $(url).modal('open');
            } else {
                if (loadingMessage !== undefined) {
                    this.blockUI('', loadingMessage);
                }
                $.get(url, {targetId: target}, function(data) {
                    if (loadingMessage !== undefined) {
                        $this.unblockUI();
                    }
                    $('<div class="modal fade ' + cssClass + '" id="'+target.replace("#","")+'">' + data + '</div>')
                        .modal()
                        .on('hidden', function(){
                            $(target).remove();
                        });
                }).success(function() {
                        $('input:text:visible:first').focus();
                    });
            }
        },

        /**
         * Is called with a hash of all options that are changing always refresh when changing options
         *
         * @private
         */
        _setOptions: function() {
            // _super and _superApply handle keeping the right this-context
            this._superApply(arguments);
        },

        /**
         * Is called for each individual option that is changing
         *
         * @param key
         * @param value
         * @private
         */
        _setOption: function(key, value) {
            this._super(key, value);
        },

        /**
         * Called when created, and later when changing options
         *
         * @private
         */
        _refresh: function() {

        },

        /**
         * Events bound via _on are removed automatically
         *
         * @private
         */
        _destroy: function() {
            // Revert other modifications here
            this.element.removeClass('hotflo-clock');

            // Call the base destroy function
            $.Widget.prototype.destroy.call(this);
        }
    });
}(jQuery));

$(function() {
    /* Initialize application widget */
    $(window).application();

    /*  Better select widget
        Select 2
     */
    $('.select2').select2();

    /* DateTimepicker, Datepicker and Timepicker in Bootstrap 3 layout */
    $('.datetimepicker').datetimepicker({

    });
    $('.datepicker').datetimepicker({
        minView: 1
    });
    $('.timepicker').datetimepicker({
        maxView: 0,
        startView: 0
    });
});