$(function() {
    $.widget('piwicms.taskList', {
        // Default options
        options: {
            dataTable: null,
            dataTableContainer: null,
            showTaskContainer: null,
            newTaskContainer: null,
            showTitle: null,
            editTitle: null,
            showBody: null,
            form: null,
            newTaskButton: null,
            // Routing
            ajaxDatatableRoute: null,
            ajaxShowTaskRoute: null,
            ajaxDeleteTaskRoute: null,
            deleteTaskRoute: null,
            // Translations
            editTrans: 'Edit',
            deleteTrans: 'Delete',
            newTaskTrans: 'New task',
            editTaskTrans: 'Edit task',
            getTaskTrans: 'Retrieving task',
            deleteConfirmationTrans: 'Are you sure you want to delete this task?'
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
            var $this = this;

            this._element = this.element;

            // Initialize dataTables
            this._initDataTable();

            this._on(this.options.newTaskButton, {
                click: function(e) {
                    e.preventDefault();
                    this._newTask();
                }
            });

        },

        _getTask: function(id, title) {
            // Dirty workaround to preserve the widget scope
            var $this = this;

            $(window).application('blockUI', [this.options.getTaskTrans, title]);
            $.ajax({
                url: Routing.generate(this.options.ajaxShowTaskRoute, { _format: 'json', id: id }),
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    $(window).application('unblockUI');
                    console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
                },
                success: function(data) {
                    console.log($($this.options.showTaskContainer));
                    $($this.options.showTaskContainer).removeClass('hide');
                    $($this.options.showTitle).html(
                        '<i class="icon-list"></i> ' +
                        data.title +
                        '<div class="pull-right">' +
                            Date.create(data.created.date).format('{weekday} {d} {month} {yyyy} {24hr}:{mm}') +
                        '</div>'
                    );
                    $($this.options.showBody).html(
                        data.description
                    );
                    $(window).application('unblockUI');
                },
                dataType: 'json'
            });
        },

        _newTask: function() {
            $(this.options.showTaskContainer).addClass('hide');
            $($this.options.editTitle).html(
                $this.options.newTaskTrans
            );
            $('#_method').val('POST');
            $('#piwicms_task_title').val('');
            $('#piwicms_task_date').val('');
            tinyMCE.activeEditor.setContent('');
        },

        _editTask: function(id, title) {
            // Dirty workaround to preserve the widget scope
            var $this = this;

            $(this.options.showTaskContainer).addClass('hide');
            $(window).application('blockUI', [this.options.getTaskTrans, title]);
            $.ajax({
                url: Routing.generate(this.options.ajaxShowTaskRoute, { _format: 'json', id: id }),
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    $(window).application('unblockUI');
                    console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
                },
                success: function(data) {
                    console.log($($this.options.showTaskContainer));
                    $($this.options.editTitle).html(
                        $this.options.editTaskTrans + ': ' + data.title
                    );
                    $('#_method').val('PUT');
                    $('#piwicms_task_title').val(data.title);
                    $('#piwicms_task_date').val(Date.create(data.date).format('{dd}-{MM}-{yyyy} {24hr}:{mm}'));
                    $(window).application('unblockUI');
                    tinyMCE.activeEditor.setContent(data.description);
                },
                dataType: 'json'
            });
        },

        _deleteTask: function(id) {
            // Dirty workaround to preserve the widget scope
            var $this = this;

            $.ajax({
                url: Routing.generate(this.options.ajaxDeleteTaskRoute, { _format: 'json', id: id }),
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    $(window).application('unblockUI');
                    console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
                },
                success: function(data) {
                    $this._dataTable.fnDraw();
                },
                dataType: 'json'
            });
        },

        /**
         * Initialize dataTables and query the database /w ajax
         * @private
         */
        _initDataTable: function() {
            // Dirty workaround to preserve the widget scope
            var $this = this;
            this._dataTable = $(this.options.dataTable).dataTable({
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource":  Routing.generate(this.options.ajaxDatatableRoute, { _format: 'json' }),
//                "bFilter": false,
//                "bLengthChange": false,
                "bProcessing": false,
                "bSort": false,
                "aoColumnDefs": [
                    {
                        aTargets: [0],
                        mData: function(source, type, val) { return source.id; },
                        bVisible: false
                    },
                    {
                        aTargets: [1],
                        mData: function(source, type, val) {
                            var title = '';
                            if (source.unread) {
                                title += '<div class="label label-info">New</div> ';
                            }
                            return title + source.title; },
                        sWidth: 'auto'
                    },
                    {
                        aTargets: [2],
                        mData: function(source, type, val) { return Date.create(source.date.date).format('{d} {month} {yyyy} {24hr}:{mm}') },
                        sWidth: '200px'
                    },
                    {
                        aTargets: [3],
                        mData: function(source, type, val) { return source.createdBy },
                        sWidth: '125px'
                    },
                    {
                        aTargets: [4],
                        mData: function(source, type, val) {
                            _return = '<a href="#" class="table-actions showtask"><i class="icon-eye-open"></i></a> ';
                            _return += '<a href="#" class="table-actions edittask"><i class="icon-pencil"></i></a> ';
                            _return += '<a href="#" class="table-actions deletetask"><i class="icon-trash"></i></a> ';
                            return _return;
                        },
                        sWidth: '125px',
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

                $this._trigger("dataTableSelect", this, aData);
            });

            this._on(this.document, {
                'click.showtask': function(e) {
                    e.preventDefault();
                    $this._getTask($this._selected.id, $this._selected.title);
                },
                'click.edittask': function(e) {
                    e.preventDefault();
                    $this._editTask($this._selected.id, $this._selected.title);
                },
                'click.deletetask': function(e) {
                    e.preventDefault();
                    bootbox.confirm($this.options.deleteConfirmationTrans, function(result) {
                        if (result) {
                            $this._deleteTask($this._selected.id);
                        }
                    });
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