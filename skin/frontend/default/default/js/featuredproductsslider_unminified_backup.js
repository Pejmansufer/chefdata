document.observe("dom:loaded", function(){

    ProductVerticalSlider = Class.create();
    Object.extend(Object.extend(ProductVerticalSlider.prototype, Abstract.prototype), {
        initialize: function(wraper,elements,buttonNext,buttonPrev, options){
            this.wraper    = wraper;
            this.elements    = elements;
            this.bNext    = buttonNext;
            this.bPrev    = buttonPrev;
            this.index   = parseInt(0);
            this.lastIndex   = this.elements.length-1;
            this.options    = Object.extend({visibleCount:3,totalCount:1,duration:0.5,mode:'vertical'}, options || {});

            this.events = {
                slideNext: this.slideNext.bind(this),
                slidePrev: this.slidePrev.bind(this)
            };
            this.resizeWrapper();
            this.addObservers();
            this.moveTo(this.elements[this.index],this.index);
        },

        resizeWrapper: function(){
            if(this.options.mode=='vertical'){
                this.options.zeroOffset = this.elements[0].cumulativeOffset();
                var minHeight = this.elements[0].getHeight();
                this.elements.each(function(element){if (minHeight<element.getHeight()) minHeight = element.getHeight()});
                var frame_count = this.wraper.getHeight()/(minHeight*this.options.visibleCount).toFixed();
                this.lastIndex = frame_count-1;
                this.options.frameHeight = this.wraper.getHeight()/frame_count;
                this.wraper.setStyle({height:this.options.frameHeight+'px',overflow:'hidden'});
                this.options.visibleCount = 1;
            }else {
                this.options.visibleCount = (this.wraper.getWidth()/this.elements[0].getWidth()).toFixed();
                this.wraper.select('ul.secondary-wrapper')[0].setStyle({width:'3194.29px'});
                this.elements.invoke('setStyle',{width:(this.wraper.getWidth()/this.options.visibleCount)+'px'});
            }
            return false;
        },

        addObservers: function(){
            this.bNext.observe('click', this.events.slideNext);
            this.bPrev.observe('click', this.events.slidePrev);
        },

        controlToggle: function(){
            if (this.index == 0) this.bPrev.hide(); else this.bPrev.show();
            if (this.index == (this.lastIndex+1-this.options.visibleCount)) this.bNext.hide(); else this.bNext.show();
        },


        slideNext: function (event){
            var newIndex = this.index + parseInt(this.options.visibleCount);
            if (newIndex>(this.lastIndex+1-this.options.visibleCount)) newIndex = this.lastIndex+1-this.options.visibleCount;
            this.moveTo(this.elements[newIndex],newIndex);
            Event.stop(event);
        },

        slidePrev: function (event){
            var newIndex = this.index - this.options.visibleCount;
            if (newIndex<0) newIndex = 0;
            this.moveTo(this.elements[newIndex],newIndex);
            Event.stop(event);
        },

        moveTo: function (element,index){
            var scrollerOffset = this.wraper.cumulativeOffset();
            if(this.options.mode=='vertical'){
                var x = this.options.zeroOffset[0]- scrollerOffset[0];
                var y = this.options.zeroOffset[1]+index*this.options.frameHeight -scrollerOffset[1];
            }else {
                var  elementOffset  = element.cumulativeOffset();
                var x = elementOffset[0]- scrollerOffset[0];
                var y = elementOffset[1]- scrollerOffset[1];
            }
            new Effect.SmoothScroll(this.wraper, {
                duration: this.options.duration,
                x: (x),
                y: (y),
                transition: Effect.Transitions.sinoidal,
                afterFinish: (function () {
                    this.index = index;
                    this.controlToggle();

                }).bind(this)});
            return false;
        }

    });


    Effect.SmoothScroll = Class.create();
    Object.extend(Object.extend(Effect.SmoothScroll.prototype, Effect.Base.prototype), {
        initialize: function(element) {
            this.element = $(element);
            var options = Object.extend({
                x:    0,
                y:    0,
                mode: 'absolute'
            } , arguments[1] || {}  );
            this.start(options);
        },
        setup: function() {
            if (this.options.continuous && !this.element._ext ) {
                this.element.cleanWhitespace();
                this.element._ext=true;
                this.element.appendChild(this.element.firstChild);
            }

            this.originalLeft=this.element.scrollLeft;
            this.originalTop=this.element.scrollTop;

            if(this.options.mode == 'absolute') {
                this.options.x -= this.originalLeft;
                this.options.y -= this.originalTop;
            }
        },
        update: function(position) {
            this.element.scrollLeft = this.options.x * position + this.originalLeft;
            this.element.scrollTop  = this.options.y * position + this.originalTop;
        }
    });



    (function(){
        var webTexProSliders = $$(".WebTexSoftFeaturedSlider");
        var wrapper = null;
        var elements = null;
        var buttonSlideNext = null;
        var buttonSlidePrev = null;
        var visibleCount = null;
        var totalCount = null;

        webTexProSliders.each(function(webTexProSlider){
            if((webTexProSlider.select(".hor-wr")).length > 0)
            {
                wrapper = webTexProSlider.select(".hor-wr");
                wrapper.each(function(data){
                    elements = data.firstDescendant().childElements();
                    buttonSlideNext = data.up(0).select("a.btn-next-horizontal")[0];
                    buttonSlidePrev = data.up(0).select("a.btn-prev-horizontal")[0];
                    visibleCount = webTexProSlider.select('.items-count-horizontal')[0].value;

                    totalCount = webTexProSlider.select('.total-count-horizontal')[0].value;

                    new ProductVerticalSlider(data, elements, buttonSlideNext, buttonSlidePrev, {/*visibleCount: visibleCount, */mode: 'horizontal', totalCount: totalCount});
                });
            }
            else if((webTexProSlider.select(".block-related-slider")).length > 0)
            {
                wrapper =  webTexProSlider.select(".block-related-slider");
                wrapper.each(function(data){
                    var localContainer = data.up(1); //class="block-related"
                    elements = data.childElements();
                    buttonSlideNext = localContainer.select(".btn-next-vertical")[0];
                    buttonSlidePrev = localContainer.select(".btn-prev-vertical")[0];
                    visibleCount = localContainer.select(".items-count")[0].value;
                    new ProductVerticalSlider(data,elements,buttonSlideNext,buttonSlidePrev,{visibleCount:visibleCount});
                });
            }
        });
    })();
});