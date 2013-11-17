$(function() {
    $.widget('piwicms.privatemessageList', {
        // Default options
        options: {
            dataTable: null,
            dataTableContainer: null,
            showPrivateMessageContainer: null,
            newPrivateMessageContainer: null,
            showTitle: null,
            replyTitle: null,
            showBody: null,
            form: null,
            replyOn: null,
            newPrivateMessageButton: null,
            // Routing
            ajaxDatatableRoute: null,
            ajaxShowPrivateMessageRoute: null,
            ajaxDeletePrivateMessageRoute: null,
            deletePrivateMessageRoute: null,
            // Translations
            editTrans: 'Edit',
            deleteTrans: 'Delete',
            replyPrivateMessageTrans: 'Reply on',
            getPrivateMessageTrans: 'Retrieving private message',
            deleteConfirmationTrans: 'Are you sure you want to delete this private message?'
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

            this._on(this.options.newPrivateMessageButton, {
                click: function(e) {
                    e.preventDefault();
                    this._newPrivateMessage();
                }
            });

        },

        _getPrivateMessage: function(id, title) {
            // Dirty workaround to preserve the widget scope
            var $this = this;

            $(window).application('blockUI', [this.options.getPrivateMessageTrans, title]);
            $.ajax({
                url: Routing.generate(this.options.ajaxShowPrivateMessageRoute, { _format: 'json', id: id }),
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    $(window).application('unblockUI');
                    console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
                },
                success: function(data) {
                    console.log($($this.options.showPrivateMessageContainer));
                    $($this.options.showPrivateMessageContainer).removeClass('hide');
                    $($this.options.showTitle).html(
                        '<i class="icon-list"></i> ' +
                        data.title +
                        '<div class="pull-right">' +
                            Date.create(data.created.date).format('{weekday} {d} {month} {yyyy} {24hr}:{mm}') +
                        '</div>'
                    );
                    $($this.options.showBody).html(
                        data.text
                    );
                    $($this.options.replyTitle).html(
                        '(' + $this.options.replyPrivateMessageTrans + ' ' + data.title + ')'
                    );
                    $($this.options.replyOn).val(data.id);
                    tinyMCE.activeEditor.setContent('<p></p><blockquote>' + data.text + '</blockquote>');
                    //$('.wysiwyg').html('<p></p><blockquote>' + data.text + '</blockquote>');
                    $(window).application('unblockUI');
                },
                dataType: 'json'
            });
        },

        _newPrivateMessage: function() {
            $(this.options.showPrivateMessageContainer).addClass('hide');
            $(this.options.replyTitle).html('');
            tinyMCE.activeEditor.setContent('');
            //$('.wysiwyg').html('');
            $(this.options.replyOn).val(0);
        },

        _deletePrivateMessage: function(id) {
            // Dirty workaround to preserve the widget scope
            var $this = this;

            $.ajax({
                url: Routing.generate(this.options.ajaxDeletePrivateMessageRoute, { _format: 'json', id: id }),
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
                        mData: function(source, type, val) { return Date.create(source.created.date).relative() },
                        sWidth: '200px'
                    },
                    {
                        aTargets: [3],
                        mData: function(source, type, val) {
                            user = "";
                            if (source.fromUser.firstname != null) {
                                user += source.fromUser.firstname + " ";
                            }
                            if (source.fromUser.middlename != null) {
                                user += source.fromUser.middlename + " ";
                            }
                            if (source.fromUser.surname != null) {
                                user += source.fromUser.surname + " ";
                            }
                            return user;
                        },
                        sWidth: '200px'
                    },
                    {
                        aTargets: [4],
                        mData: function(source, type, val) {
                            _return = '<a href="#" class="table-actions showprivatemessage"><i class="icon-eye-open"></i></a> ';
                            _return += '<a href="#" class="table-actions deleteprivatemessage"><i class="icon-trash"></i></a> ';
                            return _return;
                        },
                        sWidth: '100px',
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
                'click.showprivatemessage': function(e) {
                    e.preventDefault();
                    $this._getPrivateMessage($this._selected.id);
                },
                'click.deleteprivatemessage': function(e) {
                    e.preventDefault();
                    bootbox.confirm($this.options.deleteConfirmationTrans, function(result) {
                        if (result) {
                            $this._deletePrivateMessage($this._selected.id);
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