;(function($) {

    /** Customizations Model */
    window.Customizations = Backbone.Model.extend({

        initialize: function() {
            this.on('change', function() {
                $('#customizations').val(JSON.stringify(this.attributes));
            }, this);
        }

    });

    /** Customize View */
    window.CustomizeView = Backbone.View.extend({

        events: {
            'change': 'change'
        },

        initialize: function() {

            var self = this,
                $items = {};

            /** Set the element */
            this.$el = this.options.$el;

            /** Deep copy of our attributes */
            this.origAttributes = $.extend(true, {}, this.model.attributes);

            /** Change slideshow functionality */
            $('#change-slideshow').bind('change', function() {

                /** Redirect the user */
                if ( self.confirm() )
                    window.location.href = 'http://'+ window.location.hostname + window.location.pathname + '?page=easingsliderpro_customizer&id='+ $(this).val();
                return false;

            });

            /** Warn the user about losing their changes */
            $('.back.button').bind('click', function() {
                if ( !self.confirm() )
                    return false;
            });

            /** Title click functionality */
            $('.customize-section-title').bind('click', function() {

                var $parent = $(this).parent();
                if ( !$parent.hasClass('open') ) {
                    $('.customize-section').removeClass('open');
                    $parent.addClass('open');
                }
                else
                    $('.customize-section').removeClass('open');

            });

            /** Collapse function */
            $('.collapse-sidebar').bind('click', function() {

                /** Collapse/expand overlay */
                var $overlay = $('.wp-full-overlay');
                if ( $overlay.hasClass('expanded') )
                    $overlay.removeClass('expanded').addClass('collapsed');
                else
                    $overlay.removeClass('collapsed').addClass('expanded');
                
            });

            /** Inititiate color pickers */
            $('.color-picker-hex').each(function() {
                $(this).wpColorPicker({
                    change: function(e) {
                        self.change(e);
                    },
                    defaultColor: $(this).attr('data-default')
                });
            });

        },

        confirm: function() {
            if ( JSON.stringify( this.origAttributes ) === JSON.stringify( this.model.attributes ) )
                return true;
            if ( confirm( easingsliderpro.media_upload.discard_changes ) )
                return true;
            return false;
        },

        validate: function(changes, selector) {

            /** Loops through each change and validates */
            for ( var prop in changes ) {

                /** Background images */
                if ( prop == 'background-image' )
                    changes[prop] = 'url('+ changes[prop] +')';

                /** Slideshow border width */
                if ( prop == 'border-width' ) {
                    changes['border-style'] = 'solid';
                    $('.easingsliderpro-shadow').css({ 'margin-left': changes[prop] +'px' });
                }

                /** Arrows height */
                if ( prop == 'height' && selector == '.easingsliderpro-arrows' )
                    changes['margin-top'] = '-'+ Math.floor( changes[prop] / 2 ) +'px';

                /** Enables/Disables the shadow */
                if ( prop == 'shadow-enable' ) {

                    /** Display the shadow container */
                    changes['display'] = ( changes[prop] == 'true' ) ? 'block' : 'none';

                    /** Append the image element if needed */
                    if ( $('img', selector).length == 0 )
                        $(selector).append('<img src="'+ $('input[data-property="shadow-image"]').val() +'" alt="" />');

                    /** Remove false CSS property */
                    delete changes[prop];

                }

                /** Changes the shadow image */
                if ( prop == 'shadow-image' ) {

                    /** Change shadow image */
                    $(selector).html('<img src="'+ changes[prop] +'" alt="" />');

                    /** Remove false CSS property */
                    delete changes[prop];

                }

                /** Apply "px" to end of pixel-based values */
                if ( prop == 'width' || prop == 'height' || prop == 'border-width' || prop == 'border-radius' )
                    changes[prop] = changes[prop] +'px';

            }

            return changes;

        },

        change: function(e) {

            /** Hack, but it works */
            var split = e.target.name.split('['),
                parent = split[0],
                child = split[1].replace(']', ''),
                values = this.model.get(parent),
                attributes = {},
                changes = {};

            /** Set model attributes (had to manually trigger "change" event as it wasn't firing, unsure why) */
            values[child] = e.target.value;
            attributes[parent] = values;
            this.model.set(attributes);

            /** Set our CSS changes */
            changes[e.target.dataset.property] = e.target.value;

            /** Reflect changes on slideshow */
            $(e.target.dataset.selector).css(this.validate(changes, e.target.dataset.selector));

        },

        render: function() {

            /** Show the view */
            this.$el.find('.wp-full-overlay').animate({ 'opacity': 1 }, { duration: 200 });

        }

    });

    /** Let's go! */
    window.customizeView = new CustomizeView({
        $el: $('#customize-container'),
        model: new Customizations(JSON.parse($('#customizations').val()))
    }).render();

})(jQuery);