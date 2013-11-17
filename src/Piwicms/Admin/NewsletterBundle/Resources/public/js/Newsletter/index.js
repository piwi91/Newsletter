$(function() {
    $.widget('piwicms.newsletterIndex', {
        // Default options
        options: {
            dataTable: null,
            dataTableContainer: null,
            // Routing
            ajaxDatatableRoute: null,
            editNewsletterRoute: null,
            deleteNewsletterRoute: null,
            previewNewsletterRoute: null,
            sendNewsletterRoute: null,
            statsNewsletterRoute: null,
            // Translations
            editTrans: 'Edit',
            deleteTrans: 'Delete'
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
                "aaSorting": [[2, 'desc' ]],
                "aoColumnDefs": [
                    {
                        aTargets: [0],
                        mData: function(source, type, val) { return source.id; },
                        bVisible: false,
                        sName: 'id'
                    },
                    {
                        aTargets: [1],
                        mData: function(source, type, val) { return source.title; },
                        sWidth: 'auto',
                        sName: 'title'
                    },
                    {
                        aTargets: [2],
                        mData: function(source, type, val) {
                            return Date.create(source.datetime.date)
                                .format('{Weekday} {dd} {Month} {yyyy} at {24hr}:{mm}');
                        },
                        sWidth: '150px',
                        sName: 'datetime'
                    },
                    {
                        aTargets: [3],
                        mData: function(source, type, val) { return source.createdBy },
                        sWidth: '200px',
                        sName: 'createdBy'
                    },
                    {
                        aTargets: [4],
                        mData: function(source, type, val) {
                            _return = '<a href="'+Routing.generate($this.options.editNewsletterRoute, { 'id': source.id })+'" class="table-actions editnewsletter"><i class="icon-pencil"></i></a> ';
                            _return += '<a href="'+Routing.generate($this.options.deleteNewsletterRoute, { 'id': source.id })+'" class="table-actions deletenewsletter"><i class="icon-trash"></i></a> ';
                            _return += '<a href="'+Routing.generate($this.options.previewNewsletterRoute, { 'id': source.id })+'" class="table-actions previewnewsletter"><i class="icon-eye-open"></i></a> ';
                            _return += '<a href="'+Routing.generate($this.options.sendNewsletterRoute, { 'id': source.id })+'" class="table-actions sendnewsletter"><i class="icon-envelope"></i></a> ';
                            _return += '<a href="'+Routing.generate($this.options.statsNewsletterRoute, { 'id': source.id })+'" class="table-actions"><i class="icon-bar-chart"></i></a> ';
                            return _return;
                        },
                        sWidth: '200px',
                        bSortable: false,
                        bSearchable: false,
                        sName: ''
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

//                this._trigger("dataTableSelect", this, aData);
            });

            this._on(this.document, {
                'click.editnewsletter': function(event) {
                    event.preventDefault();
                    alert('This button isn\'t yet available');
                },
                'click.deletenewsletter': function(event) {
                    event.preventDefault();
                    var location = $(event.currentTarget).attr('href');
                    bootbox.confirm("Are you sure?", function(result) {
                        if (result !== false) {
                            window.location.replace(location);
                        }
                    });
                },
                'click.sendnewsletter': function(event) {
                    event.preventDefault();
                    var location = $(event.currentTarget).attr('href');
                    bootbox.confirm("Are you sure?", function(result) {
                        if (result !== false) {
                            window.location.replace(location);
                        }
                    });
                },
                'click.previewnewsletter': function(event) {
                    event.preventDefault();
                    var location = $(event.currentTarget).attr('href');
                    $(window).application('renderModal',
                        '#',
                        location,
                        'previewModal',
                        'Loading preview'
                    );
                }
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