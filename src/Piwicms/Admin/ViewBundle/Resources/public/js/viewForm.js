$(function() {
    $.widget('piwicms.viewForm', {
        // Default options
        options: {
            tinyMceConfig: null
        },

        // Private variables
        _element: null,
        _wysiwygInitialized: false,
        _index: 0,
        _prototype: null,
        _collectionHolder: null,

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
            this._initForm();
            this._initPrototype();

        },

        _initPrototype: function() {
            var $this = this;
            this._collectionHolder = $('.piwicms_view_viewBlock_form_group');
            this._prototype = $(this._collectionHolder).data('prototype');
            this._index = $(this._collectionHolder).find(':input').length;
//            $(this._collectionHolder).children().eq(1).children().each(function() {
//                $this._deleteBlockText(this);
//            });
//
//            this._addBlockText($(this._collectionHolder).children(0).eq(0));
        },

        _deleteBlockText: function(element) {
            $(element).find('label').eq(0).hide();
            $(element).find(':input').parent().append(
                $('<a></a>')
                    .html('Delete')
                    .attr('href', '#')
                    .click(function(e){
                        e.preventDefault();
                        $(element).remove();
                    })
            );
        },

        _addBlockText: function(element) {
            $(element).append(
                $('<p></p>').html(
                    $('<a></a>')
                        .attr('href', '#')
                        .html('Add new block')
                        .click(function(e){
                            e.preventDefault();
                            $this._addBlock();
                        })
                )
            );
        },

        _addBlock: function() {
            var newBlock = $(this._prototype.replace(/__name__/g, this._index).replace(/label__/g, ''));
            $this._deleteBlockText(newBlock);
            $(this._collectionHolder).children(0).eq(1).append(newBlock);
            this._index++;
        },

        _initForm: function() {
            console.log(this.options.tinyMceConfig);
            // Dirty workaround to preserve the widget scope
            $this = this;
            $('#piwicms_view_module').on('change', function(e) {
                if ($(this).val() == 'twig') {
                    if ($this._wysiwygInitialized == true) {
                        alert('remove');
                        $('#piwicms_view_text').tinymce().remove();
                        $this._wysiwygInitialized = false;
                    }
                } else {
                    if ($this._wysiwygInitialized == false) {
                        console.log('init tinymce');
                        $this.initTinyMce('#piwicms_view_text');
                        $this._wysiwygInitialized = true;
                    }
                }
            });
        },

        initTinyMce: function(id) {
            var textarea = $(id);
            if (textarea.is('[required]')) {
                textarea.prop('required', false);
            }
            textarea.tinymce();
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