/**
 * Created by Mustafa on 10/30/2014.
 */
var $j = jQuery.noConflict();
function initInfo(data)
{
    $j('#modal-title').html($j(data).data('title'));
    $j('.sc-main').attr('src',$j(data).next().find('img').attr('src'));
    $j('#modal-href').attr('href',$j(data).data('href'));
    $j('#modal-desc').html($j(data).data('desc'));
    $j('#modal-sub-cat').html($j(data).data('subcat'));
}
