$(function() {
    $.widget('piwicms.newsletterStatistics', {
        // Default options
        options: {
            id: null,
            chart01: null,
            chart02: null,
            chart03: null
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

            this._initHighcharts();

        },

        _initHighcharts: function() {
            var $this = this;
            var jsonHighcharts = Application.ajax({
                method: 'post',
                url: Routing.generate('piwicms_admin_newsletter_stats_highcharts', {
                    id: this.options.id,
                    _format: 'json'
                })
            });
            jsonHighcharts.error(
                function(XMLHttpRequest, textStatus, errorThrown){
                    console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
                    alert("Couldn't render example");
                }
            );
            jsonHighcharts.success(
                function(data) {
                    $($this.options.chart01).highcharts(data.chart01);
                    $($this.options.chart02).highcharts(data.chart02);
                    $($this.options.chart03).highcharts(data.chart03);
                }
            );
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