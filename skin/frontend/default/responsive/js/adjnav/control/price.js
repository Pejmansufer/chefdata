
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjnavPrice = Class.create({
    addListeners: function(element) {
        var items = $(element).select('.adj-nav-price-input-id');

        for (var i=0; i<items.length; ++i)
        {
            var btn = $('adj-nav-price-go---' + items[i].value);
            if (Object.isElement(btn)){
                Event.observe(btn, 'click', AdjnavListener.listenPriceInput.bind(AdjnavListener));
                Event.observe($('adj-nav-price-from---' + items[i].value), 'keypress', AdjnavListener.listenPriceInput.bind(AdjnavListener));
                Event.observe($('adj-nav-price-to---' + items[i].value), 'keypress', AdjnavListener.listenPriceInput.bind(AdjnavListener));
            }
        }
    },

    createSlider: function(width, from, to, min_price, max_price, sKey) {
        var price_slider = $('adj-nav-price-slider' + sKey);

        return new Control.Slider(price_slider.select('.handle'), price_slider, {
            range: $R(0, width),
            sliderValue: [from, to],
            restricted: true,
            price: new AdjnavPrice(),

            onChange: function (values) {
                var f = this.price.calculate(width, min_price, max_price, values[0]),
                    t = this.price.calculate(width, min_price, max_price, values[1]);

                adjnavNavigation.addParams(sKey, f + ',' + t, true);

                // we can change values without sliding
                $('adj-nav-range-from' + sKey).update(f);
                $('adj-nav-range-to' + sKey).update(t);

                adjnavNavigation.makeRequest();
            },
            onSlide: function(values) {
                $('adj-nav-range-from' + sKey).update(this.price.calculate(width, min_price, max_price, values[0]));
                $('adj-nav-range-to' + sKey).update(this.price.calculate(width, min_price, max_price, values[1]));
            }
        });
    },

    calculate: function(width, min_price, max_price, value) {
        return this.round(((max_price-min_price)*value/width) + min_price, (max_price-min_price));
    },

    round: function(num, range) {
        num = parseFloat(num);
        if (isNaN(num))
            num = 0;

        //return Math.round(num);
        if(range <= 10)
        {
            return num.toFixed(2);
        }
        else if(range <= 100)
        {
            return num.toFixed(1);
        }
        else
        {
            return num.toFixed(0);
        }
    }
});

isIE = /*@cc_on!@*/false;

Control.Slider.prototype.setDisabled = function()
{
    this.disabled = true;

    if (!(isIE || !!navigator.userAgent.match(/Trident\/7\./)))
    {
        this.track.parentNode.className = this.track.parentNode.className + ' disabled';
    }
};


Control.Slider.prototype._isButtonForDOMEvents = function (event, code) {
    return event.which ? (event.which === code + 1) : (event.button === code);
}

Control.Slider.prototype.startDrag = function(event) {
    if((this._isButtonForDOMEvents(event,0))||(Event.isLeftClick(event)))  {
        if (!this.disabled){
            this.active = true;

            var handle = Event.element(event);
            var pointer  = [Event.pointerX(event), Event.pointerY(event)];
            var track = handle;
            if (track==this.track) {
                var offsets  = this.track.cumulativeOffset();
                this.event = event;
                this.setValue(this.translateToValue(
                    (this.isVertical() ? pointer[1]-offsets[1] : pointer[0]-offsets[0])-(this.handleLength/2)
                ));
                var offsets  = this.activeHandle.cumulativeOffset();
                this.offsetX = (pointer[0] - offsets[0]);
                this.offsetY = (pointer[1] - offsets[1]);
            } else {
                // find the handle (prevents issues with Safari)
                while((this.handles.indexOf(handle) == -1) && handle.parentNode)
                    handle = handle.parentNode;

                if (this.handles.indexOf(handle)!=-1) {
                    this.activeHandle    = handle;
                    this.activeHandleIdx = this.handles.indexOf(this.activeHandle);
                    this.updateStyles();

                    var offsets  = this.activeHandle.cumulativeOffset();
                    this.offsetX = (pointer[0] - offsets[0]);
                    this.offsetY = (pointer[1] - offsets[1]);
                }
            }
        }
        Event.stop(event);
    }
};