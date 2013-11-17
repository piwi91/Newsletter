$(function() {
    $.widget('piwicms.newsletterStep2', {
        // Default options
        options: {
            template: null
        },

        // Private variables
        _element: null,

        /**
         * The constructor
         *
         * @private
         */
        _create: function() {
            // Dirty workaround to preserve the widget scope
            var $this = this;

            this._element = this.element;

            $('#piwicms_newsletter_step2_text').css({'height': '400px'});

            this._initExample();

        },

        _initExample: function() {
            var $this = this;

            var renderExampleBtn = $('#renderExample');

            $(renderExampleBtn).on('click', function() {
                $this._renderExample();
            });
        },

        _renderExample: function() {
            var exampleObject = $('#example');
            var blocks = [];
            $('.mailingblock').each(function() {
                blocks.push({
                    'id': $(this).data('id'),
                    'index': $(this).data('index'),
                    'block': $(this).data('block'),
                    'html': $(this).html()
                });
            });
            $.ajax({
                method: 'post',
                url: Routing.generate('piwicms_admin_newsletter_render_example', {
                    _format: 'json'
                }),
                data: {
                    template: this.options.template,
                    blocks: blocks
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
                    alert("Couldn't render example");
                },
                success: function(data) {
                    $(exampleObject).html(data.view);
                },
                dataType: 'json'
            });
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