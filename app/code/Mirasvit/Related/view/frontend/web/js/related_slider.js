define([
    "jquery",
    "underscore"
], function ($, _) {
    $.widget('mirasvit.relatedSlider', {
        $el:   null,
        page:  0,
        pages: 0,
        
        options: {},
        
        itemSelector: '[data-element=item]',
        pageSelector: '[data-element=page]',
        prevSelector: '[data-element=prev]',
        nextSelector: '[data-element=next]',
        
        _create: function () {
            this.$el = $(this.element);
            
            this.pages = $(this.pageSelector, this.$el).length;
            
            $(this.pageSelector, this.$el).on('click', this.onPageClick.bind(this));
            
            $(this.prevSelector, this.$el).on('click', this.onPrev.bind(this));
            $(this.nextSelector, this.$el).on('click', this.onNext.bind(this));
            
        },
        
        onPageClick: function (e) {
            const page = $(e.currentTarget).data('index');
            this.goTo(page);
        },
        
        onPrev: function () {
            this.page--;
            if (this.page < 0) {
                this.page = this.pages - 1;
            }
            
            this.goTo(this.page);
        },
        
        onNext: function () {
            this.page++;
            if (this.page >= this.pages) {
                this.page = 0;
            }
            
            this.goTo(this.page);
        },
        
        goTo: function (page) {
            let movePercent = page * $(this.itemSelector + this.indexSelector(0), this.$el).parent().data('width');
            
            $(this.pageSelector, this.$el).removeClass('_active');
            $(this.pageSelector + this.indexSelector(page), this.$el).addClass('_active');
            
            $(this.itemSelector + this.indexSelector(0), this.$el).parent().
                css('transform', 'translate3d(-' + movePercent + '%, 0, 0)');
        },
        
        indexSelector: function (index) {
            return '[data-index=' + index + ']';
        }
        
    });
    
    return $.mirasvit.relatedSlider;
});