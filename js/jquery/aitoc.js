
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.11
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
/**
 * 
 */

try 
{
    jQuery.noConflict();
}
catch (e) {}

/** Compare objects
 */
/*Object.prototype.equals = function(x2)
{
    for (p in this)
    {
        if(typeof(x[p])=='undefined') {return false;}
    }
    
    for (p in this)
    {
        if (this[p])
        {
            switch (typeof(this[p]))
            {
                case 'object':
                    if (!this[p].equals(x[p])) 
                    { 
                        return false;
                    } 
                    break;

                case 'function':
                    if (typeof(x[p])=='undefined' || (p != 'equals' && this[p].toString() != x[p].toString())) 
                    { 
                        return false; 
                    } 
                    break;

                default:
                    if (this[p] != x[p]) 
                    { 
                        return false; 
                    }
            }
        }
        else if (x[p])
        {
            return false;
        }
    }

    for (p in x)
    {
        if (typeof(this[p])=='undefined') 
        {
            return false;
        }
    }
    
    return true;
}*/