var jcm = jQuery.noConflict(true);

(function($){
    $.extend({
        LeCartMigration : function(options){
            var defaults = {
                formKey : '',
                urlRun : '',
                resumeFunction : '',
                menuId : '#sidemenu',
                contentId : '#le-content',
                menuStep1 : '#sidemenu a[href="#step1"]',
                menuStep2 : '#sidemenu a[href="#step2"]',
                menuStep3 : '#sidemenu a[href="#step3"]',
                formIdResume : 'le-form-resume',
                formIdStep1 : 'le-form-step1',
                formIdStep2 : 'le-form-step2',
                formIdStep3 : 'le-form-step3',
                formResume : '#le-form-resume',
                formStep1 : '#le-form-step1',
                formStep2 : '#le-form-step2',
                formStep3 : '#le-form-step3',
                submitFormResume : '#lecm-resume-submit',
                submitFormStep1 : '#lecm-form1-submit',
                submitFormStep2 : '#lecm-form2-submit',
                submitFormStep3 : '#lecm-form3-submit',
                submitFormStep4 : '#lecm-form4-submit',
                backStep1 : '#lecm-back-step1',
                backStep2 : '#lecm-back-step2',
                wrapFormStep2 : '#step2',
                wrapFromStep3 : '#step3 .le-container',
                step3Confirm : '#confirm',
                step3Migration : '#migration',
                consoleCheck : '#lecm-check-log',
                processCategories: '#process-categories',
                processProducts: '#process-products',
                processCustomers: '#process-customers',
                processOrders: '#process-orders',
                processTaxes : '#process-taxes',
                processReviews: '#process-reviews',
                processManufacturers : '#process-manufacturers',
                processClearStore : '#process-clear-data',
                warningClearStore : '#lecm-warning-clear',
                processBar: '.process-bar',
                processBarLoading: '.process-bar-loading-none_show',
                processBarPoint : '.process-bar-point-none_show',
                processBarWidth : '.process-bar-width',
                processBarConsole: '.console-log',
                processBarTryAgain : '.try-import',
                entitySelectId : '#input-fields-select',
                tryImportCategories : '#try-import-categories',
                tryImportProducts : '#try-import-products',
                tryImportCustomers : '#try-import-customers',
                tryImportOrders : '#try-import-orders',
                tryImportTaxes: '#try-import-taxes',
                tryImportReviews: '#try-import-reviews',
                tryImportManufacturers: '#try-import-manufacturers',
                tryImportWithoutClear :'#try-import-not-clear',
                consoleImport : '#lecm-import-log',
                consoleLogContent : '.lecm-console-log',
                selectLangId : '#lecm-lang-dup',
                timeDelay : 2000,
                autoRetry : 30000
            };
            var settings = $.extend(defaults, options);

            function convertFromToData(elm){
                var data = '';
                var element = $(elm);
                if(element.length !== 0){
                    data = element.serialize();
                }
                return data;
            }

            function insertFormKey(data){
                var new_data = data+'&form_key='+settings.formKey;
                return new_data;
            }
            function validateForm(form, form_id){
                var result = true;
                if($(form).length !== 0){
                    var leFormValid = new varienForm(form_id, '');
                    if(leFormValid.validator && leFormValid.validator.validate()){
                        result = true;
                    }else {
                        result = false;
                    }
                }
                return result;
            }

            function resetValidateForm(form, form_id){
                if($(form).length !== 0){
                    var leFormValid = new varienForm(form_id, '');
                    leFormValid.validator.reset();
                }
            }

            function showProcessBarChoose(response){
                showProcessBarCategories(response);
                showProcessBarProducts(response);
                showProcessBarCustomers(response);
                showProcessBarOrders(response);
                showProcessBarTaxes(response);
                showProcessBarReviews(response);
                showProcessBarManufacturers(response);
                showProcessClearStore(response);
            }

            function showProcessBarCategories(response){
                if(response.categories === 'true'){
                    $(settings.processCategories).show();
                }else {
                    $(settings.processCategories).hide();
                }
            }
            function showProcessBarProducts(response){
                if(response.products === 'true'){
                    $(settings.processProducts).show();
                }else {
                    $(settings.processProducts).hide();
                }
            }
            function showProcessBarCustomers(response){
                if(response.customers === 'true'){
                    $(settings.processCustomers).show();
                }else {
                    $(settings.processCustomers).hide();
                }
            }
            function showProcessBarOrders(response){
                if(response.orders === 'true'){
                    $(settings.processOrders).show();
                }else {
                    $(settings.processOrders).hide();
                }
            }

            function showProcessBarTaxes(response){
                if(response.taxes === 'true'){
                    $(settings.processTaxes).show();
                }else {
                    $(settings.processTaxes).hide();
                }
            }

            function showProcessBarReviews(response){
                if(response.reviews === 'true'){
                    $(settings.processReviews).show();
                }else {
                    $(settings.processReviews).hide();
                }
            }

            function showProcessBarManufacturers(response){
                if(response.manufacturers === 'true'){
                    $(settings.processManufacturers).show();
                }else {
                    $(settings.processManufacturers).hide();
                }
            }

            function showProcessClearStore(response){
                if(response.clear_data === 'true'){
                    $(settings.processClearStore).show();
                    showClearStoreWarning();
                }else {
                    $(settings.processClearStore).hide();
                }
            }

            function showClearStoreWarning(){
                $(settings.warningClearStore).show();
            }

            function hideClearStoreWarning(){
                $(settings.warningClearStore).hide();
            }
            function clearStore(){
                $(settings.processClearStore).show();
                $.ajax({
                    url : settings.urlRun,
                    type: 'post',
                    data : insertFormKey('action=clear'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            setTimeout(importCurrencies, settings.timeDelay);
                            $(settings.processClearStore).hide();
                            $(settings.processClearStore).hide();
                        } else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            $(settings.processClearStore).hide();
                            $(settings.tryImportWithoutClear).show();
                        } else if(json.result === 'prepare'){
                            $(settings.processClearStore).hide();
                        } else if(json.result === 'process'){
                            setTimeout(clearStore, settings.timeDelay);
                        } else{
                            setTimeout(importCurrencies, settings.timeDelay);
                            $(settings.processClearStore).hide();
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        $(settings.processClearStore).hide();
                        $(settings.tryImportWithoutClear).show();
                    }
                });
            }

            function importCurrencies(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type: 'post',
                    data : insertFormKey('action=currencies'),
                    dataType : 'json',
                    success: function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'prepare'){
                        } else {
                            showProcessBarLoading(settings.processTaxes);
                            setTimeout(importTaxes, settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showProcessBarLoading(settings.processTaxes);
                        setTimeout(importTaxes, settings.timeDelay);
                    }
                });
            }

            function importTaxes(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=taxes'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            var taxes = json.taxes;
                            hideProcessBarLoading(settings.processTaxes);
                            showProcessBar(settings.processTaxes,taxes.total_count, taxes.imported_count, taxes.error_count, taxes.point);
                            showProcessBarLoading(settings.processManufacturers);
                            setTimeout(importManufacturers, settings.timeDelay);
                        } else if (json.result === 'process'){
                            var taxes = json.taxes;
                            hideProcessBarLoading(settings.processTaxes);
                            showProcessBar(settings.processTaxes,taxes.total_count, taxes.imported_count, taxes.error_count, taxes.point);
                            importTaxes();
                        } else if (json.result === 'error') {
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processTaxes);
                            autoRetry(settings.processTaxes);
                        } else {
                            showProcessBarLoading(settings.processManufacturers);
                            setTimeout(importManufacturers, settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processTaxes);
                        autoRetry(settings.processTaxes);
                    }
                });
            }

            function importManufacturers(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=manufacturers'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success' ){
                            var manufacturers = json.manufacturers;
                            hideProcessBarLoading(settings.processManufacturers);
                            showProcessBar(settings.processManufacturers,manufacturers.total_count, manufacturers.imported_count, manufacturers.error_count, manufacturers.point);
                            showProcessBarLoading(settings.processCategories);
                            setTimeout(importCategories, settings.timeDelay);
                        } else if(json.result === 'process'){
                            var manufacturers = json.manufacturers;
                            hideProcessBarLoading(settings.processManufacturers);
                            showProcessBar(settings.processManufacturers,manufacturers.total_count, manufacturers.imported_count, manufacturers.error_count, manufacturers.point);
                            importManufacturers();
                        } else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processManufacturers);
                            autoRetry(settings.processManufacturers);
                        } else{
                            showProcessBarLoading(settings.processCategories);
                            setTimeout(importCategories, settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processManufacturers);
                        autoRetry(settings.processManufacturers);
                    }
                });
            }

            function importCategories(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=categories'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            var categories = json.categories;
                            hideProcessBarLoading(settings.processCategories);
                            showProcessBar(settings.processCategories, categories.total_count, categories.imported_count, categories.error_count, categories.point);
                            showProcessBarLoading(settings.processProducts);
                            setTimeout(importProducts, settings.timeDelay);
                        } else if(json.result === 'process'){
                            var categories = json.categories;
                            hideProcessBarLoading(settings.processCategories);
                            showProcessBar(settings.processCategories, categories.total_count, categories.imported_count, categories.error_count, categories.point);
                            importCategories();
                        }else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processCategories);
                            autoRetry(settings.processCategories);
                        }else{
                            showProcessBarLoading(settings.processProducts);
                            setTimeout(importProducts, settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processCategories);
                        autoRetry(settings.processCategories);
                    }
                });
            }

            function importProducts(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=products'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            var products = json.products;
                            hideProcessBarLoading(settings.processProducts);
                            showProcessBar(settings.processProducts, products.total_count, products.imported_count, products.error_count, products.point);
                            showProcessBarLoading(settings.processCustomers);
                            setTimeout(importCustomers, settings.timeDelay);
                        } else if(json.result === 'process'){
                            var products = json.products;
                            hideProcessBarLoading(settings.processProducts);
                            showProcessBar(settings.processProducts, products.total_count, products.imported_count, products.error_count, products.point);
                            importProducts();
                        }else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processProducts);
                            autoRetry(settings.processProducts);
                        }else{
                            showProcessBarLoading(settings.processCustomers);
                            setTimeout(importCustomers, settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport,'<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processProducts);
                        autoRetry(settings.processProducts);
                    }
                });
            }

            function importCustomers(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=customers'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            var customers = json.customers;
                            hideProcessBarLoading(settings.processCustomers);
                            showProcessBar(settings.processCustomers, customers.total_count, customers.imported_count, customers.error_count, customers.point);
                            showProcessBarLoading(settings.processOrders);
                            setTimeout(importOrders, settings.timeDelay);
                        } else if(json.result === 'process'){
                            var customers = json.customers;
                            hideProcessBarLoading(settings.processCustomers);
                            showProcessBar(settings.processCustomers, customers.total_count, customers.imported_count, customers.error_count, customers.point);
                            importCustomers();
                        }else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processCustomers);
                            autoRetry(settings.processCustomers);
                        }else{
                            showProcessBarLoading(settings.processOrders);
                            setTimeout(importOrders,settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport,'<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processCustomers);
                        autoRetry(settings.processCustomers);
                    }
                });
            }

            function importOrders(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=orders'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            var orders = json.orders;
                            hideProcessBarLoading(settings.processOrders);
                            showProcessBar(settings.processOrders, orders.total_count, orders.imported_count, orders.error_count, orders.point);
                            showProcessBarLoading(settings.processReviews);
                            setTimeout(importReviews, settings.timeDelay);
                        } else if(json.result === 'process'){
                            var orders = json.orders;
                            hideProcessBarLoading(settings.processOrders);
                            showProcessBar(settings.processOrders, orders.total_count, orders.imported_count, orders.error_count, orders.point);
                            importOrders();
                        }else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processOrders);
                            autoRetry(settings.processOrders);
                        }else{
                            showProcessBarLoading(settings.processReviews);
                            setTimeout(importReviews, settings.timeDelay);
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processOrders);
                        autoRetry(settings.processOrders);
                    }
                });
            }

            function importReviews(){
                createLecmCookie(1);
                $.ajax({
                    url : settings.urlRun,
                    type : 'post',
                    data : insertFormKey('action=reviews'),
                    dataType : 'json',
                    success : function(json){
                        if(json.msg !== ''){
                            showConsoleLog(settings.consoleImport, json.msg);
                        }
                        if(json.result === 'success'){
                            var reviews = json.reviews;
                            hideProcessBarLoading(settings.processReviews);
                            showProcessBar(settings.processReviews,reviews.total_count, reviews.imported_count, reviews.error_count, reviews.point);
                            // finish
                            deleteLecmCookie();
                            $(settings.submitFormStep4).show();
                        } else if(json.result === 'process'){
                            var reviews = json.reviews;
                            hideProcessBarLoading(settings.processReviews);
                            showProcessBar(settings.processReviews,reviews.total_count, reviews.imported_count, reviews.error_count, reviews.point);
                            importReviews();
                        } else if(json.result === 'error'){
                            showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                            showTryAgainImport(settings.processReviews);
                            autoRetry(settings.processReviews);
                        } else {
                            // finish
                            $(settings.submitFormStep4).show();
                            deleteLecmCookie();
                        }
                    },
                    error : function(xhr, ajaxOptions, thrownError){
                        showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                        showTryAgainImport(settings.processReviews);
                        autoRetry(settings.processReviews);
                    }
                });
            }

            function showTryAgainImport(elm){
                var element = $(elm).find(settings.processBarTryAgain);
                if(element.length !== 0){
                    element.show();
                }
            }

            function hideTryAgainImport(elm){
                var element = $(elm).find(settings.processBarTryAgain);
                if(element.length !== 0){
                    showConsoleLog(settings.consoleImport,'<p class="success"> - Resuming import ...</p>');
                    element.hide();
                }
                createLecmCookie(1);
            }

            function showConsoleLog(elm, msg){
                var html = msg;
                var element = $(elm);
                var content = element.children(settings.consoleLogContent);
                if(element.length !== 0){
                    element.css({
                        'display' : 'block'
                    });
                    content.append(html);
                    content.animate({scrollTop: content.prop("scrollHeight")});
                }
            }

            function hideConsoleLog(elm){
                var element = $(elm);
                var content = element.children(settings.consoleLogContent);
                if(element.length !== 0){
                    element.css({
                        'display' : 'none'
                    });
                    content.html("");
                }
            }

            function showProcessBar(elm , total, imported , error, point){
                var element = $(elm);
                if(element.length !== 0){
                    showProcessBarWidth(element, point);
                    showProcessBarPoint(element, point);
                    showProcessBarConsole(element, total, imported, error);
                }else{
                    return false;
                }
            }

            function showProcessBarWidth(element, point){
                var pbw = element.find(settings.processBarWidth);
                if(pbw.length !== 0 && point !== null){
                    var pbw_width = element.find(settings.processBar).width();
                    var width = pbw_width * point;
                    pbw.css({
                        'display' :'block',
                        'width' : width+'px'
                    });
                } else {
                    return false;
                }
            }

            function showProcessBarPoint(element , point){
                var pbp = element.find(settings.processBarPoint);
                if(pbp.length !== 0 && point !== null){
                    pbp.css({'display' : 'block'});
                    var percent = point * 100;
                    var html = percent+'%';
                    pbp.html(html);
                } else {
                    return false;
                }
            }

            function showProcessBarConsole(element, total, imported, error){
                var pbc = element.find(settings.processBarConsole);
                if(pbc.length !== 0){
                    var html = 'Imported: '+imported+'/'+total+', Errors: '+error;
                    pbc.show();
                    pbc.html(html);
                } else {
                    return false;
                }
            }
            function showProcessBarLoading(elm){
                var element = $(elm).find(settings.processBarLoading);
                if(element.length !== 0){
                    element.css({
                        'display': 'block'
                    });
                }
            }

            function hideProcessBarLoading(elm){
                var element = $(elm).find(settings.processBarLoading);
                if(element.length !== 0){
                    element.css({
                        'display': 'none'
                    });
                }
            }

            function nextStepByMenu(elm){
                var element = $(elm);
                if(element.length !== 0){
                    var id_content = element.attr('href');
                    element.removeClass('disabled').addClass('enabled');
                    $(settings.menuId).find('a').removeClass('open');
                    $(settings.contentId).children().hide();
                    element.addClass('open');
                    $(id_content).show();
                }
            }

            function openMenu(element){
                var id_content = element.attr('href');
                $(settings.menuId).find('a').removeClass('open');
                $(settings.contentId).children().hide();
                element.addClass('open');
                $(id_content).show();
            }

            function disabledMenu(elm){
                var element = $(elm);
                element.removeClass('enabled').removeClass('open').addClass('disabled');
            }

            function selectedMenu(elm){
                var element = $(elm);
                element.css('border-bottom-color', '#14c10b');
            }

            function backMenu(elm){
                var element = $(elm);
                element.css('border-bottom-color', '#000');
            }

            function checkSelectEntity(){
                var result = false;
                var element = $(settings.entitySelectId);
                if($('input:checkbox:checked', element).length > 0){
                    $('.le-error').fadeOut();
                    result = true;
                }else {
                    $('.le-error').fadeIn();
                }
                return result;
            }

            function createLecmCookie(value){
                var date = new Date();
                date.setTime(date.getTime()+(24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
                document.cookie = "le_cart_migration_run="+value+expires+"; path=/";
            }

            function getLecmCookie(){
                var nameEQ = "le_cart_migration_run=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)===' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }

            function deleteLecmCookie(){
                var date = new Date();
                date.setTime(date.getTime()+(-1*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
                document.cookie = "le_cart_migration_run="+expires+"; path=/";
            }

            function checkLecmCookie(){
                var check = getLecmCookie();
                var result = false;
                if(check === '1'){
                    result = true;
                }
                return result;
            }

            function checkOptionDuplicate(elm){
                var check = new Array();
                $(elm).each(function(index, value){
                    var element = $(value);
                    check[index] = element.val();
                });
                var result = true;
                check.forEach(function(value, index) {
                    check.forEach(function(value_tmp, index_tmp){
                        if(value_tmp === value && index !== index_tmp){
                            result = false;
                        }
                    });
                });
                return result;
            }

            function checkSelectLangDuplicate(){
                var select = settings.selectLangId+' select';
                var check = checkOptionDuplicate(select);
                if(check === true){
                    $('.lecm-error', settings.selectLangId).hide();
                } else{
                    $('.lecm-error', settings.selectLangId).show();
                }
                return check;
            }

            function showAllIconSuccess(elm){
                var element = $(elm);
                $('.success-icon', element).css({'display': 'inline-block'});
            }

            function hideAllIconSuccess(elm){
                var element = $(elm);
                $('.success-icon', element).css({'display': 'none'});
            }

            function hideIconSuccessByValid(elm){
                var error = $(elm);
                var icon = error.parent().find('.success-icon');
                icon.css({'display':'none'});
            }

            function checkElementShow(elm){
                var check = $(elm).is(':visible');
                return check;
            }

            function autoRetry(elm){
                if(settings.autoRetry > 0){
                    setTimeout(function(){triggerClick(elm)}, settings.autoRetry);
                }
            }

            function triggerClick(elm){
                var par_elm = elm+' .try-import';
                var check_show = checkElementShow(par_elm);
                var button = $(par_elm).children('div');
                if(check_show){
                    button.trigger('click');
                }
            }

            return process();

            function process(){
                deleteLecmCookie();
                $(settings.submitFormStep1).on('click', function(){
                    $(settings.formResume).css({'display': 'none'});
                    $('#error-cart , #error-url , #error-token').hide();
                    if(validateForm(settings.formStep1, settings.formIdStep1)=== true ){
                        $('#le-loading-step').fadeIn();
                        resetValidateForm(settings.formStep1, settings.formIdStep1);
                        var data = convertFromToData(settings.formStep1);
                        data = insertFormKey(data);
                        $.ajax({
                            url : settings.urlRun,
                            type : 'post',
                            data : data,
                            dataType : 'json',
                            success: function(json){
                                if(json.result === 'success'){
                                    showAllIconSuccess('#step1');
                                    showProcessBar(settings.processTaxes, json.taxes.total_count, 0 , 0 , 0);
                                    showProcessBar(settings.processManufacturers, json.manufacturers.total_count, 0 , 0 , 0);
                                    showProcessBar(settings.processCategories, json.categories.total_count, 0 , 0 , 0);
                                    showProcessBar(settings.processProducts, json.products.total_count, 0 , 0 , 0);
                                    showProcessBar(settings.processCustomers, json.customers.total_count, 0 , 0 , 0);
                                    showProcessBar(settings.processOrders, json.orders.total_count, 0 , 0 , 0);
                                    showProcessBar(settings.processReviews, json.reviews.total_count, 0 , 0 , 0);
                                    $.ajax({
                                        url : settings.urlRun,
                                        type : 'post',
                                        data : insertFormKey('action=step2'),
                                        dataType : 'json',
                                        success: function(json_2){
                                            if(json_2.result === 'success'){
                                                $(settings.wrapFormStep2).html(json_2.msg);
                                                hideConsoleLog(settings.consoleCheck);
                                                nextStepByMenu(settings.menuStep2);
                                                disabledMenu(settings.menuStep1);
                                                selectedMenu('#stepmenu2');
                                                $('#le-loading-step').fadeOut();
                                            } else{
                                                showConsoleLog(settings.consoleCheck, json_2.msg);
                                                $('#le-loading-step').fadeOut();
                                            }
                                        },
                                        error : function(xhr, ajaxOptions, thrownError){
                                            alert('Request timeout or server isn\'t responding, please reload the page.');
                                            $('#le-loading-step').fadeOut();
                                        }
                                    });
                                } else {
                                    $(json.msg).show();
                                    showAllIconSuccess('#step1');
                                    hideIconSuccessByValid(json.msg);
                                    $('#le-loading-step').fadeOut();
                                }
                            },
                            error : function(xhr, ajaxOptions, thrownError){
                                alert('Request timeout or server isn\'t responding, please reload the page.');
                                $('#le-loading-step').fadeOut();
                            }
                        });
                    }
                    return false;
                });

                $(document).on('click',settings.submitFormStep2, function(){
                    if(validateForm(settings.formStep2, settings.formIdStep2) === true
                        && checkSelectEntity() === true
                        && checkSelectLangDuplicate() === true )
                    {
                        $('#le-loading-step2').fadeIn();
                        resetValidateForm(settings.formStep2, settings.formIdStep2)
                        data = convertFromToData(settings.formStep2);
                        data = insertFormKey(data);
                        $.ajax({
                            url : settings.urlRun,
                            type : 'post',
                            data : data,
                            dataType: 'json',
                            success: function(json){
                                if(json.result === 'success'){
                                    showProcessBarChoose(json.showprocess);
                                    $.ajax({
                                        url : settings.urlRun,
                                        type : 'post',
                                        data : insertFormKey('action=confirm'),
                                        dataType : 'json',
                                        success: function(json){
                                            $(settings.wrapFromStep3).html(json.msg);
                                            nextStepByMenu(settings.menuStep3);
                                            disabledMenu(settings.menuStep2);
                                        },
                                        error : function(xhr, ajaxOptions, thrownError){
                                            alert('Request timeout or server isn\'t responding, please reload the page.');
                                        }
                                    });
                                    $('#le-loading-step2').fadeOut();
                                }
                            },
                            error : function(xhr, ajaxOptions, thrownError){
                                // log error
                                alert('Request timeout or server isn\'t responding, please reload the page.');
                                $('#le-loading-step2').fadeOut();
                            }
                        });
                    }
                    return false;
                });

                $(settings.submitFormStep3).on('click', function(){
                    disabledMenu(settings.menuStep1);
                    disabledMenu(settings.menuStep2);
                    selectedMenu('#stepmenu3');
                    $(settings.step3Confirm).css({'display':'none'});
                    $(settings.step3Migration).css({'display':'block'});
                    hideClearStoreWarning();
                    var select_clear = checkElementShow(settings.processClearStore);
                    if(select_clear === true){
                        showConsoleLog(settings.consoleImport, '<p class="success"> - Clearing store ...</p>');
                    }
                    createLecmCookie(1);
                    var data = convertFromToData(settings.formStep3);
                    $.ajax({
                        url: settings.urlRun,
                        type : 'post',
                        data : insertFormKey(data),
                        dataType : 'json',
                        success: function(json){
                            if(json.msg !== ''){
                                showConsoleLog(settings.consoleImport, json.msg);
                            }
                            if(json.result === 'success'){
                                setTimeout(importCurrencies, settings.timeDelay);
                                $(settings.processClearStore).hide();
                            } else if(json.result === 'prepare'){
                                $(settings.processClearStore).hide();
                            } else if(json.result === 'process'){
                                setTimeout(clearStore, settings.timeDelay);
                            } else if (json.result === 'error'){
                                showConsoleLog(settings.consoleImport, '<p class="warning">Please try again.</p>');
                                $(settings.tryImportWithoutClear).show();
                                $(settings.processClearStore).hide();
                            } else {
                                setTimeout(importCurrencies, settings.timeDelay);
                                $(settings.processClearStore).hide();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError){
                            showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding, please try again.</p>');
                            $(settings.processClearStore).hide();
                            $(settings.tryImportWithoutClear).show();
                        }
                    });
                });

                $(settings.submitFormStep4).on('click', function(){
                    $('#le-loading-step4').fadeIn();
                    $.ajax({
                        url : settings.urlRun,
                        type : "post",
                        data : insertFormKey('action=finish'),
                        dataType : 'json',
                        success: function(json){
                            $('#le-loading-step4').fadeOut();
                            showConsoleLog(settings.consoleImport, json.msg);
                            $(settings.submitFormStep4).hide();
                        },
                        error: function(xhr, ajaxOptions, thrownError){
                            $('#le-loading-step4').fadeOut();
                            showConsoleLog(settings.consoleImport, '<p class="error">Request timeout or server isn\'t responding. Please reindex and clear cache .</p>');
                        }
                    });
                });

                $('a', settings.menuId).on('click', function(e){
                    var _this = $(this);
                    if( _this.hasClass('disabled') || _this.hasClass('open')){
                        // do nothing
                    } else {
                        openMenu(_this);
                    }
                    return false;
                });
                $(settings.tryImportCategories).on('click', function(){
                    importCategories();
                    hideTryAgainImport(settings.processCategories);
                });
                $(settings.tryImportProducts).on('click', function(){
                    importProducts();
                    hideTryAgainImport(settings.processProducts);
                });
                $(settings.tryImportCustomers).on('click', function(){
                    importCustomers();
                    hideTryAgainImport(settings.processCustomers);
                });
                $(settings.tryImportOrders).on('click', function(){
                    importOrders();
                    hideTryAgainImport(settings.processOrders);
                });
                $(settings.tryImportTaxes).on('click', function(){
                    importTaxes();
                    hideTryAgainImport(settings.processTaxes);
                });
                $(settings.tryImportManufacturers).on('click', function(){
                    importManufacturers();
                    hideTryAgainImport(settings.processManufacturers);
                });
                $(settings.tryImportReviews).on('click', function(){
                    importReviews();
                    hideTryAgainImport(settings.processReviews);
                });
                $(settings.tryImportWithoutClear).on('click', function(){
                    createLecmCookie(1);
                    $(this).hide();
                    setTimeout(clearStore, settings.timeDelay);
                });
                $(document).on('click','#select-all',function(){
                    $('#input-fields-select input:checkbox').prop('checked',this.checked);
                });
                $(document).on('click','#select-products',function(){
                    $('#le-list-product input:checkbox').prop('checked',this.checked);
                });
                $(document).on('click','#customers',function(){
                    $('#le-list-customers input:checkbox').prop('checked',this.checked);
                });
                $(document).on('click','.lv2', function(){
                    var lv2 = $(this);
                    var lv0 = lv2.parents('.lv0');
                    var lv1 = lv0.find('.lv1');
                    if(lv2.prop('checked') === true){
                        lv1.prop('checked', true);
                    }
                });

                $(document).on('click', '.le-select-checkbox', function(){
                    var label = $(this);
                    var input = label.parent().children('input');
                    input.trigger('click');
                });

                $(window).on('beforeunload', function(){
                    var check = checkLecmCookie();
                    if(check === true){
                        return "Migration is in progress, leaving current page will stop it! Are you sure want to stop?";
                    }
                });

                $(document).on('click', settings.backStep1, function(){
                    nextStepByMenu(settings.menuStep1);
                    disabledMenu(settings.menuStep2);
                    backMenu('#stepmenu2');
                });

                $(document).on('click', settings.backStep2, function(){
                    nextStepByMenu(settings.menuStep2);
                    disabledMenu(settings.menuStep3);
                    backMenu('#stepmenu3');
                });

                $(document).on('click', settings.submitFormResume, function(){
                    $(settings.formResume).css({'display': 'none'});
                    nextStepByMenu(settings.menuStep3);
                    disabledMenu(settings.menuStep1);
                    selectedMenu('#stepmenu2');
                    selectedMenu('#stepmenu3');
                    $(settings.processClearStore).hide();
                    $(settings.step3Confirm).css({'display':'none'});
                    $(settings.step3Migration).css({'display':'block'});
                    createLecmCookie(1);
                    setTimeout(eval(settings.resumeFunction), settings.timeDelay);
                    showConsoleLog(settings.consoleImport, '<p class="success">Resuming ... <p>');
                });

                $(document).on('click', '#choose-seo', function(){
                    $('#seo_plugin').slideToggle();
                });
            }
        }
    });
})(jcm)
