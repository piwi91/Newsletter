$(function() {
    $.widget('piwicms.groupList', {
        // Default options
        options: {
            dataTable: null,
            dataTableContainer: null,
            // Routing
            ajaxDatatableRoute: null,
            editGroupRoute: null,
            // Translations
            editTrans: 'Edit'
        },

        // Private variables
        _element: null,
        _dataTable: null,
        _selected: null,

        /**
         * The constructor
         *
         * @private
         */
        _create: function() {
            // Dirty workaround to preserve the widget scope
            $this = this;

            this._element = this.element;

            // Initialize dataTables
            this._initDataTable();

        },

        /**
         * Initialize dataTables and query the database /w ajax
         * @private
         */
        _initDataTable: function() {
            // Dirty workaround to preserve the widget scope
            $this = this;
            this._dataTable = $(this.options.dataTable).dataTable({
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource":  Routing.generate(this.options.ajaxDatatableRoute, { _format: 'json' }),
//                "bFilter": false,
//                "bLengthChange": false,
                "bProcessing": false,
                "aoColumnDefs": [
                    {
                        aTargets: [0],
                        mData: function(source, type, val) { return source.id; },
                        bVisible: false
                    },
                    {
                        aTargets: [1],
                        mData: function(source, type, val) { return source.name; },
                        sWidth: '60px'
                    },
                    {
                        aTargets: [2],
                        mData: function(source, type, val) {
                            return ""
                        },
                        sWidth: 'auto'
                    },
                    {
                        aTargets: [3],
                        mData: function(source, type, val) {
                            _return = '<a href="'+Routing.generate($this.options.editGroupRoute, { 'id': source.id })+'" class="table-actions editgroup"><i class="icon-pencil"></i></a> ';
                            return _return;
                        },
                        sWidth: '50px',
                        bSortable: false
                    }
                ]
            });

            /* Add a click handler to the rows - this could be used as a callback */
            $(this.options.dataTable).on('click', 'tbody', function(event) {
                /* Select rows */
                $($this._dataTable.fnSettings().aoData).each(function (){
                    $(this.nTr).removeClass('selected');
                });
                $(event.target.parentNode).addClass('selected');

            });

            $(this.options.dataTable).on('click', 'tbody tr', function(event) {
                /* Get position on dataTable */
                var aPos = $this._dataTable.fnGetPosition(this);
                /* Get session object */
                var aData = $this._dataTable.fnGetData(aPos);

                $this._selected = aData;

                this._trigger("dataTableSelect", this, aData);
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
}(jQuery));/**
 * Created with JetBrains PhpStorm.
 * Group: pimwiddershoven
 * Date: 09-09-13
 * Time: 20:25
 * To change this template use File | Settings | File Templates.
 */
