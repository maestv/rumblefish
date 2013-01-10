var Backstage = {}
var debug = window.debug || false
var path = window.path || ''

/*
 * Utility functions
 */
Backstage.utils = {
    getRequest: function(url, callback, context) {
        this.ajaxRequest({
            type: 'GET',
            url: url,
            callback: callback,
            context: context
        })
    },

    postRequest: function(url, data, callback, context) {
        if (debug){
            var str = '' + callback;
            console.log("postRequest: (\n\t" +
                "type: " + 'POST,\n\t' +
                "url: " + url + ",\n\t" +
                "data: " + data + ",\n\t" +
                "callback: " + str + "..." + ",\n\t" +
                "context: " + context + "\n)");
                
        }
        this.ajaxRequest({
            type: 'POST',
            url: url,
            data: data,
            callback: callback,
            context: context
        })
    },

    ajaxRequest: function(options) {
        Backstage.utils.loading(true)
        $.ajax({
            type: options.type,
            dataType: 'json',
            url: options.url,
            context: options.context,
            data: options.data,
            success: function(response) {
                if (options.callback) {
                    if (debug) console.log("Callback: " + options.callback);
                    options.callback.call(this, response)
                }
            }
        })
    },

    /*
     * Intelligently figures out the target of an Ajax request based on `obj`.
     * Can take one of the following:
     *   - A string, which is simply returned
     *   - A jQuery event object whose target is either an <a> element with an
     *     `href` attribute, or a <form> with an `action`
     *   - One of the above types of elements (<a> or <form>)
     */
    getTargetUrl: function(obj) {
        if (obj.href) {
            return obj.href
        }
        if (obj.target) {
            return obj.target.href || obj.target.action
        }
        return obj
    },

    renderTemplate: function(id, data) {
        if(debug) console.log("Rendering template \"#" + id + "\"")

        var template
        if ( data.template !== undefined ) { // Allow for PHP response template override.
            template = Handlebars.compile($('#template-' + data.template).text())
        } else {
            template = Handlebars.compile($('#template-' + id).text())
        }
        
        return template(data)
    },

    getCookie: function getCookie(name) {
        // Stolen from http://www.quirksmode.org/js/cookies.html
        var nameEQ = name + "="
        var ca = document.cookie.split(';')
        for (var i=0; i < ca.length; i++) {
            var c = ca[i]
            while (c.charAt(0) == ' ') {
                c = c.substring(1, c.length)
            }
            if (c.indexOf(nameEQ) == 0) {
                return c.substring(nameEQ.length, c.length)
            }
        }
        return null
    },

    /*
     * Shows/hides the 'loading' indicator
     */
    loading: function loading(show) {
        if (show) {
            $('#loader').fadeIn()
        } else {
            $('#loader').fadeOut()
        }
    },

    /*
     * An empty function
     */
    empty: function empty() {}
}

/*
 * Contains the Backbone.js models
 */
Backstage.models = {}
Backstage.collections = {}
Backstage.views = {}

Backstage.views.Application = Backbone.View.extend({
    el: 'html',
    events: {
        'click a.modal': 'modalWindow',
        'click a:not(.modal)': 'linkClick',
        'submit form': 'submitForm',
        'change input.fileInput':'uploadfile'
    },
    modalWindow: function(e) {
        var width = 700
        var height = 600

        if ( $(e.target).data("height") != "" ) { var height = parseInt($(e.target).data("height")) }
        if ( $(e.target).data("width") != "" ) { var width = parseInt($(e.target).data("width")) }
        
        $.fancybox({
            "width"         : width,
            "height"        : height,
            "overlayShow"   : true,
            "type"          : "iframe",
            "href"          : $(e.target).attr("href"),
            onStart: function() {
                $.fancybox.showActivity
            },
            onComplete: function() {
                $.fancybox.hideActivity
            }
        })
        
        e.preventDefault()
        e.stopPropagation()
    },
    linkClick: function(e) {
        var href = $(e.target).attr('href')
        // Only handle the click if it's a proper link
        if (href && href !== '#' && $(e.target).attr('target') != "_blank") {
            e.preventDefault()
            Backstage.router.navigate(href, {trigger: true, replace: true})
        }
    },
    submitForm: function(e) {
        e.preventDefault()
        e.stopPropagation()
        
        var url = $(e.target).attr('action')
        Backstage.utils.loading(true)
        
        // var data = $(e.target).find('input, textarea, select, input[type="submit"]').serialize()
        var data = $(e.target).serialize()
        
        console.log(data);
        
        Backstage.utils.postRequest(url, data, function(response) {
            if (response.redirect !== undefined) {
                Backstage.router.navigate(response.redirect, {trigger: true})
            } else if (response.reload) {
                // For log in an log out we reload the page.May be used in other fringe cases
                window.location.href = response.reload
            } else if (response.message) {
                alert(response.message);
                if (debug) console.log("AJAX responded: " + response.message)
            } else if ( response.close_modal == true ) {
                parent.jQuery.fancybox.close()
            } else {
                Backstage.currentPage.render(response)
                this.setTitle(response.title)
            }
        }, this)
    },
    createForm : function(row, action){
        var elements = row.find("input");
        var form = this.addInputsToForm(action, elements);
        return form;
    },
    
    /**
     * name: addInputsToForm
     * Add a list of input elements into a form and return the form element.
     * @param action string The name of the action given in the "form" tag.
     *    e.g. "/user/account/add"
     * @param inputs array The list of jquery objects: input elements that
     *    have a "name" element.
     * @return The form element.
     **/
    addInputsToForm : function(action, inputs){
        if (debug) console.log("action = \"" + action + "\"");
        if (debug) console.log("inputs = \"" + $(inputs) + "\"");
        var root = $("<form></form>");
        root.attr("action", action);
        var sz = inputs.length;
        for (var i = 0; i < sz; ++i)
            root.add(inputs[i]);
        return root;
    },
    
    uploadfile: function(e) {
        var fileInput = $(e.target),
            container = $(fileInput).parent();
        
        // Create a form, spawn an Iframe and submit it.
        var f = document.createElement('form')
            f.setAttribute('action', path + "assets/upload_file")
            f.setAttribute('enctype', "multipart/form-data")
            f.setAttribute('target', 'file_upload_iframe')
            f.setAttribute('method', 'post')
            
        var i = document.createElement('iframe')
            i.setAttribute('class', 'imgUploadIframe')
            i.setAttribute('name', 'file_upload_iframe')
            i.setAttribute("width", "0")
            i.setAttribute("height", "0")
            i.setAttribute("border", "0")
            i.setAttribute("style", "width: 0; height: 0; border: none;")
  
        $(f).append( $(fileInput) )
        $('body').append( $(i) )
        
        $(f).submit();
        $(i).load(function() {
            var response = $.parseJSON( $(i).contents().find('body').html() )
            // Handel this error betta
            if ( response.error !== undefined ) {
                alert(response.error)
                $(container).append($(fileInput))
                return false
            }
            
            // Hand off to local view
            Backstage.currentPage.uploadfile(response, container);

            // Detach extra Craps
            $(i).remove()
            $(f).remove()
        })
    },
    setTitle: function(title) {
        var titleText = Backstage.utils.renderTemplate('title', {title: title})
        document.title = titleText
    }
})

Backstage.views.BaseView = Backbone.View.extend({
    events: {
        "click #theAlphabet a" : "searchLetter"
    },
    render: function(data) {
        this.data = data
        this.$el.html(Backstage.utils.renderTemplate(this.template, data || {}))

        
        this.$('.datePicker').datepicker({dateFormat:"yy/mm/dd"})

        // Check for page Tab Blocks
        if ( this.$('.tabs').length ) {
            var tabs = []
            this.$('.tabs > ul:first span.anchor').each(function() {
                var tab = new Backstage.views.Tab({el: this})
                tabs.push(tab)
            })
            
            if ( window.location.hash != "" ) { // Click the specified tab on load 
                _.each(tabs, function(tab) {
                    if ( tab.$el.data('href') == window.location.hash ) {
                        tab.activate()
                    }
                })
            } else {
                tabs[0].activate()
            }
        }

        // Deal with Rich Text Editors
        if ( this.$el.find('.rte').length ) {
            this.$el.find('.rte').each(function() {
                new wysihtml5.Editor($(this).attr('id'), {
                    toolbar:      $(this).attr('name') + "_toolbar",
                    stylesheets:  path + "static/css/vendors/editor.css",
                    parserRules:  wysihtml5ParserRules
                })
            })
        }
        
        //$(".hideable-pages").css("visibility", "collapse");
    },

    getRequest: function BaseView__getRequest(e, callback) {
        var url = Backstage.utils.getTargetUrl(e)
        Backstage.utils.getRequest(url, callback, this)
    },

    postRequest: function BaseView__postRequest(e, data, callback) {
        var url = Backstage.utils.getTargetUrl(e)
        Backstage.utils.postRequest(url, data, callback, this)
    },
    searchLetter: function(e) {
         this.postRequest(Backstage.currentPage.$el.context.baseURI, { name:$(e.target).attr("href") }, function(response) {
             if ( response.redirect ) {}
             else if ( response.message ) {} 
             else {
                 this.render(response)
             }
         })

        e.preventDefault()
        e.stopPropagation()
    },
    
    /**
     * Validate a form element given rules.
     * @param {Object} parent The parent element (e.g. form.)
     * @param {Array} validationArray
     * Each element has the following properties (in any order):
     *  - id (REQUIRED) : The id of the form element.
     *  - failFunc : If the validation fails, do this.
     *  - failArgs : arguments to the failFunc
     *  - succFunc : If the validation succeeds, do this.
     *  - succArgs : arguments to the succFunc.
     *  - required : Whether the form is required to have input.
     *  - requiredMessge : Add this message to reasons if the field is required.
     *  - message : The message to set if the field is invalid.
     *  - regex : The regular expression to test.
     *  - testFunc : If the function returns true, it's verified.
     *  - testArgs : argument to the testFunc
     * 
     * @param {String} errorClass If an error occurs in this field, apply
     *      the style of errorClass to the field.
     * @param {Object} errorDump The element used for dumping the list of errors.
     * @return
     * The following structure:
     * Array, where each element has the following properties:
     *  - element : The element that errored.
     *  - message : The message returned (may be null)
     */
    doValidation : function(parent, validationArray, errorClass, errorDump){
        var reasons = new Array()
        var r = new Array()
        var valid = false
        var lEl = 'element'
        var lMe = 'message'
        $($(document).find(errorClass)[0]).removeClass(errorClass)
        //if (debug) console.log("Validation array: ")
        //if (debug) console.log(validationArray)
        validationArray.forEach(function doIt(v){
            if ($(parent).find('#'+v['id']).length == 0){
                if (debug) console.log("Could not find #" + v['id'] + " in parent");
                if (debug) console.log(parent);
                return reasons
            }
            element = $(parent.find('#' + v['id']))
            regex =     (v['regex'] == undefined)       ? false : v['regex']
            required =  (v['required'] == undefined)    ? false : v['required']
            message =   (v['message'] == undefined)     ? false : v['message']
            requiredMessage =   (v['requiredMessage'] == undefined)     ? false : v['requiredMessage']
            succFunc =  (v['succFunc'] == undefined)    ? false : v['succFunc']
            succArgs =  (v['succArgs'] == undefined)    ? null  : v['succArgs']
            failFunc =  (v['failFunc'] == undefined)    ? false : v['failFunc']
            failArgs =  (v['failArgs'] == undefined)    ? null  : v['failArgs']
            testArgs =  (v['testArgs'] == undefined)    ? false : v['testArgs']
            testFunc =  (v['testArgs'] == undefined)    ? false : v['testFunc']
            
            r = new Array();
            r[lEl] = $(element)
            r[lMe] = (message) ? message : null
            var len = element.val().length
            valid = (required && len > 0) || !required
            if (debug) console.log("valid = (required [" + required  + "] && len [" + len + "] > 0) || !required => " + valid);
            
            if (!valid){
                r[lMe] = (requiredMessage) ? requiredMessage : null
                if (debug) console.log("r[" + lMe + "] = \"" + requiredMessage + "\"");
            }else if ((required && len > 0) || (!required && len > 0)){
                if (regex){ // If we've included a regex.
                    valid = (element.val().search(regex) != -1) // Test it with the value.
                    if (debug) console.log("Testing regex (" + regex + ") against \""
                        + element.val() + "\" (len=" + len + ") (#" + element.attr("id") + ") ==> " + valid)
                }else if (testFunc){    // If we've included a testfunc.
                    valid = (testFunc(testArgs)) // run it.
                    if (debug) console.log("Testing function:" + testfunc(testArgs))
                }else if (testFunc && regex){ // If we have both a test func and regex...
                    // Error because have no idea which one to use.
                    if (debug) console.log("ERROR: both testFunc and regex detected.")
                    return [null] // Return null because of an error.
                }else if (!(testFunc || regex)){ // This is just for readability of code.
                    if (debug) console.log('[' + v['id'] + '] Accept Anything!')
                }else{
                    if (debug) console.log('This shouldn\'t happen.\n' +
                        'regex=' + regex + '\n' +
                        'required=' + required + '\n' +
                        'message=' + message + '\n' +
                        'succFunc=' + succFunc.toString() + '\n' +
                        'succArgs=' + succArgs.toString() + '\n' +
                        'failFunc=' + failFunc.toString() + '\n' +
                        'failArgs=' + failArgs.toString() + '\n' +
                        'testArgs=' + failFunc.toString() + '\n' +
                        'testFunc=' + testFunc.toString()
                    )
                }
            }
            // Finally, if it is indeed valid, execute the success function (if it exists).
            if (valid){
                if (succFunc) succFunc(succArgs)
            }else{// Otherwise, execute the fail function (if it exists), and push
                // the given reason to the reasons[] array.
                if (failFunc) failFunc(failArgs)
                reasons.push(r)
            }
        })
        
        // Display the Reasons.
        var ol = $('<ol></ol>')
        var li = null
        
        errorDump.html("")
        errorDump.empty()
        if (reasons.length > 0 && errorDump != null){
            errorDump.append("<p>Could not submit form because:</p>")
            reasons.forEach(function showErrors(reason){
                    li = $('<li></li>')
                    li.append(reason[lMe] + " ")
                    a = $('<a></a>')
                    a.attr('href', '#')
                    a.attr('id', 'a-' + $(reason[lEl]).attr('id'))
                    a.html('Show me.')
                    a.click(function autoScroll(){
                        if (debug) console.log(this)
                        if (debug) console.log('autoscrolling')
                        var scrollID = '#' + $(this).attr('id').substring(2)
                        if (debug) console.log('autoscrolling to ' + scrollID)
                        $(document).scrollTop($($(document).find(scrollID)[0]).offset().top-30)
                    })
                    li.append(a)
                    ol.append(li)
                    if (debug) console.log(reason[lEl].attr('id') + " -- " + reason[lMe])
                    if (errorClass != null){
                        reason[lEl].addClass(errorClass)
                        reason[lEl].click(function removeRed(){
                            $(this).removeClass(errorClass)
                        })
                    }
                })
                errorDump.append(ol)
        }
        return reasons
    }
})

Backstage.views.BaseView.extend = function(child) {
    var view = Backbone.View.extend.apply(this, arguments)

    // Don't overwrite `render`
    if (child.render) {
        view.prototype.render = function() {
            Backstage.views.BaseView.prototype.render.apply(this, arguments)
            child.render.apply(this, arguments)
        }
    }
    
    return view
}


Backstage.views.HomePage = Backstage.views.BaseView.extend({
    template: 'user-dashboard',
    render: function(e) {
        if ( e.template != undefined ) {
            this.template = e.template
        }
    }
})

/**
 * This will execute at the same time as the normal form 
 * submission.
 */
Backstage.views.LoginPage = Backstage.views.BaseView.extend({
    template: 'login-page',
    
    events : {
        "click #reset-password-button" : "resetPassword"
    },
    
    resetPassword : function(e){
        this.template = "user-email-password"
        this.render()
        
        e.preventDefault()
        e.stopPropagation()
    },
    sendLink : function(e){
        
    },
    render: function() {
        console.log('herro?')
    }
})

Backstage.views.LogoutPage = Backstage.views.BaseView.extend({
    render: function(e) {
        if ( e.reload ) { window.location = e.reload; } // Extended reload for logout (link click)
    }
})

Backstage.views.passwordReset = Backstage.views.BaseView.extend({
    template: 'user-reset-password'
})

//app = new Backstage.views.Application()

Backstage.views.AccountPage = Backstage.views.BaseView.extend({
    template: 'account-page',
    events: {
    	//"click .tab"                : "toggleTabs",
    	"submit #newSongwriter"     : "submitNewSongwriter",
    	"submit .newPROForm"        : "submitNewPRO",
    	"click #documents .delete"  : "removeDocument",
    	"click .resetEmail"         : "resetEmail"
    },
    render: function(data) {
        if (debug) console.log("The parent is rendering...")
        _.each(data.all_publishers, function(value) {
            var view = new Backstage.views.AccountPage.PublisherRow({parent : this})
            value.all_pros = data.all_pros
            view.render(value)
            view.$el.appendTo('#accountPublishersPage .content')
        }, this);
        _.each(data.songwriters, function(value) {
            var view = new Backstage.views.AccountPage.SongwriterRow({parent : this})
            value.all_publishers = data.all_publishers;
            value.all_pros = data.all_pros;
            view.render(value)
            view.$el.appendTo('#accountSongwritersPage .content')
        }, this);
        _.each(data.all_pros, function(value) {
            var view = new Backstage.views.AccountPage.PRORow({parent : this})
            value.all_pros = data.all_pros;
            view.render(value)
            view.$el.appendTo('#accountProsPage .content')
        }, this);
        
        // Defaults for Selects
        this.$('select').each(function() {
            if ( $(this).data('default') != "" ) {
                $(this).find("option[value='"+ $(this).data('default') +"']").prop('selected', "select");
            }            
        });
    },
    submitNewSongwriter : function(e) {
        var data = $(e.target).serialize()
        this.postRequest(e, data, function songwriterRedirect(response){
            this.data.songwriters.push(response.newsongwriter)
            this.render(this.data)
        })
        e.preventDefault()
        e.stopPropagation()
    },
    submitNewPRO : function(e) {
        var data = $(e.target).serialize()
        var newName = $(e.target).find('.field-pro-name').val()
        this.postRequest(e, data, function proRedirect(response){
            this.render(this.data)
            this.toggleTabById("pros-tab");
        })
        e.preventDefault()
        e.stopPropagation()
    },
    removeDocument: function(e) {
        this.getRequest(e, function(response) {
            if ( response.success == true ) {
                $(e.target).closest('tr').fadeOut(200, function() { $(this).remove() })
            } else {
                alert(response.message)
            }
        }, this);
        
        e.preventDefault()
        e.stopPropagation()
    },
    resetEmail: function(e) {
        this.postRequest(e, { "reset-email":$(e.target).data("email") }, function(response) {
            alert(response.message)
        });
        
        e.preventDefault()
        e.stopPropagation()
    },
    id2name : function(id){ // There is an error in this function when I click on not an input.
        
        
        
        
    	// Check if we have the right format.  If not, then
    	// warn the user (if they're using a debugger.)
    	var testStr = id.substr(id.indexOf("-")+1, id.length-1); 
    	if (id.indexOf("-") == -1 ||
    		 testStr != "tab"){
    			if (debug) console.log("Error! Expected a string in format" +
    				"\n \"<name>-tab\" \n but recieved\n" +
    				"\"" + id + "\" => \"<name>-" + testStr + "\"");
				return; // Most comlicated console log ever.
    	}
    	//Construct it into "page" format: account<name>Page
    	var shownID = id.substr(0, id.indexOf('-'));
    	shownID = shownID.charAt(0).toUpperCase() + shownID.substr(1, shownID.length-1);
    	shownID = "#account" + shownID + "Page";
    	if (debug) console.log("\"" + id + "\" => \"" + shownID + "\"");
    	return shownID;
	},

    /**
     * Show a pop-up containing message.
     **/
    displayWarning : function(message, e){
        $(".lightbox-popup").trigger("close");
        if (debug) console.log("WARNING: " + message);
        var alertBox = $("<div></div>");
        alertBox.addClass("lightbox-popup");
        alertBox.html(message);
        alertBox.lightbox_me({
            centered: true,
            showOverlay: false,
            modalCSS: {bottom: "30px"}
        })
        setTimeout(function(){
          alertBox.trigger("close");
        }, 3000);
        e.preventDefault();
        // alertBox.delay(1000).trigger('close');
    },
    
    /**
     * Given a text input e, replace the text when
     * clicked.  NOTE that the value must also be
     * stored in the "alt" tag.
     **/
    replaceHint : function(e){
        var alt = $(e.target).attr("alt");
        var val = $(e.target).val();
        if (val != "" && val != alt)
            return;
        if (val == ""){
            $(e.target).val($(e.target).attr("alt"));
            $(e.target).css("color", "gray");
            $(e.target).css("font-style", "italic");
        }else{
            $(e.target).val("");
            $(e.target).css("color", "black");
            $(e.target).css("font-style", "normal");
        }
    }
})

/* Songwriters moved out of accounts */
Backstage.views.songwritersPage = Backstage.views.BaseView.extend({
    template: 'songwriters',
    render: function(data) {
        _.each(data.songwriters, function(value) {
            
            var view = new Backstage.views.AccountPage.SongwriterRow()
                value.all_publishers = data.all_publishers;
                value.all_pros = data.all_pros;
            
            view.render(value)
            view.$el.appendTo('.songwritersList')
            
        }, this);
    }
});

Backstage.views.AccountPage.SongwriterRow = Backstage.views.BaseView.extend({
    template: 'songwriters-row-display',
    events: {
      'click .edit': 'edit',
      'click .delete' : 'remove',
      'click .cancel' : "revert",
      'submit form': 'submit'
    },
    edit: function(e) {
        this.template = 'songwriters-row-edit'
        this.render(this.data)
        
        var songwriter_pro_id = this.data.pro_id
        var publisher_id = this.data.publisher_id
        if ( this.data.publisher ) { var publisher_pro_id = this.data.publisher.pro.id }
        
        this.$el.find('.field-songwriter-pro option').each(function() {
            if ( parseInt($(this).attr('value')) == songwriter_pro_id ) { $(this).prop('selected', true) }
        })
        
        this.$el.find('.field-songwriter-publisher option').each(function() {
            if ( parseInt($(this).attr('value')) == publisher_id ) { $(this).prop('selected', true) }
        })
        
        this.$el.find('.field-songwriter-publisher-pro option').each(function() {
            if ( parseInt($(this).attr('value')) == publisher_pro_id ) { $(this).prop('selected', true) }
        })
        
        e.preventDefault()
        e.stopPropagation()
    },
    revert: function(e) {
        this.template = "songwriters-row-display"
        this.render(this.data)
        
        e.preventDefault()
        e.stopPropagation()
    },
    submit: function(e) {
        this.postRequest(e, $(e.target).serialize(), function(response) {
            this.template = 'songwriters-row-display'
            this.render(response.row);
        })

        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.AccountPage.PublisherRow = Backstage.views.BaseView.extend({
    template: "publishers-row-display",
    
    events : {
      'click .create' : 'create',
      'click .edit': 'edit',
      'click .delete' : 'remove',
      'submit form': 'submit'
    },
    
    initialize : function(options){
      this.parent = options.parent;
    },
        
    create : function(e){
        var name = $(e.target).find(".publisher-new-name");
        var pro = $(e.target).find(".publisher-new-pro");
        var data = null;

        this.postRequest(e, data, function(response) {
            this.template = 'publishers-row-display'
            this.render(response)
            this.parent.data.all_publishers.push(response.new_pro);
            this.parent.render(this.parent.data);
        })
    },

    edit: function(e) {
        if (debug) console.log ("Editing PROs...");
        this.template = 'publishers-row-edit'
        this.render(this.data)
        var form = $(".form-edit");
        this.setFieldDefaults(form);
        e.preventDefault()
        e.stopPropagation()
    },
    
    setFieldDefaults : function(frm){
        var pro_id = $(".field-publisher-pro-id").val()
        var pro_select = $("select.field-publisher-pro")
        this.setOptionSelected(pro_select, pro_id)
    },
    
    setOptionSelected : function(theSelect, targetVal){
        theSelect.find("option").each(function(){
            if ($(this).val() == targetVal)
                $(this).attr("selected", "selected")
            else
                $(this).removeAttr("selected")
        })
    },
    
    submit: function(e) {
        this.template = 'publishers-row-edit'
        var data = $(e.target).serialize();
        if (debug) console.log("Data >>> " + data);
        this.postRequest(e, data, function(response) {
            this.template = 'publishers-row-display'
            this.render(response)
            var fake = $("<li></li>")
            fake.addClass("tab like-link")
            fake.attr("id", "publishers-tab")
            Backstage.views.AccountPage.toggleFakeTab(fake)
        })

        e.preventDefault()
        e.stopPropagation()
    },
    
    confirmDialog : function(id, callbackYes, callbackNo){
        $(".lightbox-confirmdelete").trigger("close");
        var alertBox = $("<div></div>");
        alertBox.addClass("lightbox-confirmdelete");
        alertBox.html("Would you like to <b>delete</b> publisher <b>&quot;" + id + "?&quot;</b>");
        var confirmYes = $("<button>Yes</button>");
        var confirmNo = $("<button>No</button>");
        confirmYes.click(function(){
            $(".lightbox-confirmdelete").trigger("close");
            callbackYes();
            return true;
        });
        confirmNo.click(function(){
            $(".lightbox-confirmdelete").trigger("close");
            callbackNo();
            return false;
        });
        alertBox.append(confirmYes);
        alertBox.append(confirmNo);
        alertBox.lightbox_me({
            centered: true,
            showOverlay: false,
            modalCSS: {bottom: "30px"}
        });
    },
    
    remove : function(e){
        var url = $(e.target).parent().attr("action");
        var form = $(e.target).parent().parent().parent();
        var name = form.find("input:text.field-publisher-name").val();
        this.confirmDialog(name,
            // If we've clicked yes, execute the following...
            function callbackYes(){
                if (debug) console.log("You said \"yes\"");
                form.css("visibility", "collapse");
                Backstage.utils.postRequest(url, form, function callB(response){
                    this.render(response);
                }, this);
            },
            // if we've clicked no, just ignore the response.
            function callbackNo(){
                if (debug) console.log("You said \"no\"");
            }
        );
    },
    
    getID : function(action){
        var i = action.lastIndexOf("/")+1;
        var j = action.length;
        var id = action.substring(i, j);
        return id;
    }
})

Backstage.views.AccountPage.PRORow = Backstage.views.BaseView.extend({
    template: "pro-row-display",
    
    events : {
      'click .create' : 'create',
      'click .edit': 'edit',
      'click .delete' : 'remove',
      'submit form': 'submit'
    },
    
    
    initialize : function(options){
      this.parent = options.parent;
    },
    
    create : function(e){
        var name = $(e.target).find(".pro-new-name")
        var pro = $(e.target).find(".pro-new-pro")
        var data = $(e.target).serialize();

        this.postRequest(e, data, function(response) {
            if (debug) console.log("CREATING a new pro")
            this.template = 'pro-row-display'
            this.parent.data.all_pros.push(response.new_pro)
            this.parent.render(this.parent.data)
            this.parent.toggleTabById("pro-tab")
            this.render(response)
        })
        e.preventDefault()
        e.stopPropagation()
    },

    edit: function(e) {
        this.template = 'pro-row-edit'
        this.render(this.data)
        //this.parent.render(this.parent.data)
        var form = $(".form-edit");
        e.preventDefault()
        e.stopPropagation()
    },
    
    submit: function(e) {
        this.template = 'pro-row-edit'
        var data = $(e.target).serialize()
        if (debug) console.log("Data >>> " + data)
        this.postRequest(e, data, function(response) {
            this.template = 'pro-row-display'
            
            this.parent.data.all_pros.push(response.new_pro)
            this.parent.render(response)
            this.parent.toggleTabById("pros-tab")
        })
        
        e.preventDefault()
        e.stopPropagation()
    },
    
    confirmDialog : function(id, callbackYes, callbackNo){
        $(".lightbox-confirmdelete").trigger("close");
        var alertBox = $("<div></div>");
        alertBox.addClass("lightbox-confirmdelete");
        alertBox.html("Would you like to <b>delete</b> PRO <b>&quot;" + id + "?&quot;</b>");
        var confirmYes = $("<button>Yes</button>");
        var confirmNo = $("<button>No</button>");
        confirmYes.click(function(){
            $(".lightbox-confirmdelete").trigger("close");
            callbackYes();
            return true;
        });
        confirmNo.click(function(){
            $(".lightbox-confirmdelete").trigger("close");
            callbackNo();
            return false;
        });
        alertBox.append(confirmYes);
        alertBox.append(confirmNo);
        alertBox.lightbox_me({
            centered: true,
            showOverlay: false,
            modalCSS: {bottom: "30px"}
        });
    },
    
    remove : function(e){
        var url = $(e.target).parent().attr("action");
        var form = $(e.target).parent().parent().parent();
        var name = form.find("input:text.field-pro-name").val();
        this.confirmDialog(name,
            // If we've clicked yes, execute the following...
            function callbackYes(){
                if (debug) console.log("You said \"yes\"");
                form.css("visibility", "collapse");
                Backstage.utils.postRequest(url, form, function callB(response){
                    this.render(response);
                }, this);
            },
            // if we've clicked no, just ignore the response.
            function callbackNo(){
                if (debug) console.log("You said \"no\"");
            }
        );
    },
    
    getID : function(action){
        var i = action.lastIndexOf("/")+1;
        var j = action.length;
        var id = action.substring(i, j);
        return id;
    }
})

Backstage.views.registerPage = Backstage.views.BaseView.extend({
    template: 'register-page',
    events : {
        'click .next-button' : 'loadNextForm',
        'click input[name=reg-type]:radio' : 'setType',
        'click .add-additional' : 'addPlacement',
        "click #addArtists" : "duplicateArtist"
    },
    
    render : function(){
        var i = "-0"
        var j = ""
        showID = "#reg-form" + i + j;
        if (debug) console.log(showID)
        $(showID).css("display", "block")
        $(showID).css("visibility", "visible")
    },
    
    /**
     * Automatically get the next form ID based on the
     * ".next-button" ID, which will be in the form:
     *      next-reg-form-#-_%_
     * where # is a number and % is a special value (e.g. artists., if
     * it exists.). We want to convert it to:
     *      reg-form-#-_%_
     * @param Obj e The object that was clicked (a link).
     * @return none.
     **/
    loadNextForm : function(e) {
        
        var req = " is required."
        var passwordsMatch = function(){
            return($("#password").val() == $("#confirm-password").val())
        }
        var emailsMatch = function(){
            return ($("#email").val() == $("#confirm-email").val());
        }
        var nameRegex = /^[\w\-\.]+\s[\w\-\.]+$/gi   // allow for hyphened names and Jr. and Sr.
        var fullNameRegex = /^[\w\-\.]+\s[\w\-\.]+$/gi   // allow for hyphened names and Jr. and Sr.
        var emailRegex = /^[\w_\.]{1,40}@[a-zA-Z0-9]{1,20}\.[a-zA-Z]{1,6}$/gi
        var forms = [
            [
                //{"id" : "username", "regex" : /^[\w\d\_\-\.]+$/gi, "required" : true, "requiredMessage" : "Username" + req, "message" : "Username can contain letters, numbers, and the following symbols: \"_\", \"-\", and \".\""},
                {"id" : "password", "regex" : /^.{6,15}$/gi, "message" : "Password must be between 6 and 15 characters.", "required" : true, "requiredMessage" : "Password" + req},
                {"id" : "confirm-password", "testFunc" : passwordsMatch, "message" : "Passwords must match", "required": true, "requiredMessage" : "Password confirmation" + req},
                {"id" : "full_name", "regex" : fullNameRegex, "message" : "Full can only include letters and spaces", "required" : true, "requiredMessage" : "Full name" + req},
                {"id" : "email", "regex" : emailRegex, "required" : true, "requiredMessage" : "Email" + req, "message" : "Email must be in the form &lt;name&gt;@&lt;domain&gt;.&lt;com/org/etc...&gt;"},
                {"id" : "confirm-email", testFunc : emailsMatch, "required" : true, "requiredMessage" : "Email" + req, "message" : "Emails don't match.'"},
                {"id" : "street1", "regex" : /^\d{2,10}\w[\w\d\s\.\']+$/gi, "required" : true, "requiredMessage" : "Address" + req, "message" : "Address is invalid"},
                {"id" : "street2", "regex" : /^[\w\d\s\.\']+$/gi, "required" : false, "message" : "Address (#2) is invalid"},
                {"id" : "city", "regex" : /^[\d\w\s\.\-\']+$/gi, "required" : true, "requiredMessage" : "City" + req, "message" : "City is invalid"},
                {"id" : "country", "regex" : /^[\d\w\s\.\-\']+$/gi, "required" : true, "requiredMessage" : "Country" + req, "message" : "Country is invalid"},
                {"id" : "postal", "regex" : /^\d{5}(?:[-\s]\d{4})?$/gi, "required" : true, "requiredMessage" : "Zip" + req, "message" : "Zip can only contain numbers and dashes."},
            ],
        ]
        
        var cid = null
        var isLabel = false;
        var nextID = $(e.target).attr("id").match(/\d+/)[0]
        
        var isFinal = false;
               if ($(e.target).attr("id").search(/_type_/) >= 0){
                   cid = $(":checked").val();
                   isLabel = (cid == "label")
                   if (isLabel && debug) console.log("This is a label.")
                   if (!isLabel && debug) console.log("This is an artist.")
               }
               else if ($(e.target).attr("id").search(/final/) >= 0) {
                   $(".collapsable-form").css("visibility", "collapse")
                   $(".collapsable-form").css("display", "none")
                   $("#reg-form-final").css("visibility", "visible")
                   $("#reg-form-final").css("display", "block")
                   isFinal = true;
               }
               
        if (cid != null)
            cid = "-" + cid
        
        // var reasons = this.doValidation($(e.target).parent().parent().parent(),
        //    forms[0], "field-error", $(".errorDisp"))
            
        var nextFullID = "#reg-form-" + nextID + cid
        
        reasons = {}
        if (reasons.length == 0){
            $(".collapsable-form").css("visibility", "collapse")
            $(".collapsable-form").css("display", "none")
            $(nextFullID).css("visibility", "visible")
            $(nextFullID).css("display", "block")
        }

        var hasSpecial = ($(e.target).attr("id").search(/_\w+_/gi) != -1)
        var special = ""
        if (hasSpecial)
            special = "-" + $("#selected-reg-type").val()

        var id = "#reg-form-" + nextID + special
        

        $(".collapsable-form").css("display", "none")
        $(".collapsable-form").css("visibility", "collapse")
        $(id).css("display", "block")
        $(id).css("visibility", "visible")

        e.preventDefault()
        e.stopPropagation()
    },
    duplicateArtist: function(e) {
        $('.artist-form').hide()
        var target = $('#reg-form-1-artist').clone()
        
            // Clean Target up
            target.attr("id", "")
            target.show()
        
            target.find('input, select, textarea').each(function() {
                if ( !$(this).hasClass('button') ) {
                    $(this).attr("value", "")
                    $(this).val("")
                    
                    // Interate the name
                    $(this).attr('name', $(this).attr('name').replace("[0]", "["+ $('.artist-form').length +"]"))  
                }
            })
            
            target.find(".placement-duplicate-label:not(.placement-original)").remove()

        // Put in the dom
        $('#reg-form-1-artist').after(target)

        e.preventDefault()
        e.stopPropagation()
    },
    addPlacement : function(e){
        var placement = $(e.target).closest('.collapsable-form').find('.placement-original').clone()
            placement.attr("id", "")
            placement.attr("value", "")
            placement.removeClass('placement-original')
        
        var appendTarget = $(e.target).closest('.collapsable-form').find('.placement-original').parent();
        appendTarget.append(placement)
        
        // If we have 3 disable the button
        if ( appendTarget.find('input').length == 3 ) {
            $(e.target).prop('disabled', 'true');
        }
        
    },
    setType : function(e){
      $("#selected-reg-type").val($(e.target).val());
      if (debug) console.log("Setting #selected-reg-type[val] to " + $("#selected-reg-type").val())
    }
})

Backstage.views.RegisterSuccessPage = Backstage.views.BaseView.extend({
    template: 'register-success-page'
})





Backstage.views.addDocuments = Backstage.views.BaseView.extend({
    template: "admin-documents-add",
    uploadfile: function(response, container) {
        
        var fn = document.createElement('input')
            fn.setAttribute('type', 'hidden')
            fn.setAttribute('name', "document_name")
            fn.setAttribute('value', response.file_name)
            
        var fp = document.createElement('input')
            fp.setAttribute('type', 'hidden')
            fp.setAttribute('name', "document_path")
            fp.setAttribute('value', response.file_path + response.file_name)
            
        var fu = document.createElement('input')
            fu.setAttribute('type', 'hidden')
            fu.setAttribute('name', "document_url")
            fu.setAttribute('value', response.url)
            
        var visual = document.createElement('input')
            visual.setAttribute('type', 'text')
            visual.setAttribute('readonly', 'readonly')
            visual.setAttribute('name', 'visual')
            visual.setAttribute('value', response.file_name)
            visual.setAttribute('style', "display: none;")
            
        // Remove On Edit
        if ( $(container).find('input[name="document_name"]').length > 0 ) {
            $(container).find('input[name="document_name"]').remove()
            $(container).find('input[name="document_path"]').remove()
            $(container).find('input[name="document_url"]').remove()
            $(container).find('input[name="visual"]').remove()
        }
        
        $(container).append( $(fn), $(fp), $(fu), $(visual) )
        $(visual).fadeIn(350)  
    }
})




Backstage.views.userSearch = Backstage.views.BaseView.extend({
    template: 'user-search',
    render: function() {
        this.$('.datePicker').datepicker({dateFormat:"yy/mm/dd"})
    }
})

Backstage.views.userDashboard = Backstage.views.BaseView.extend({
    template: 'user-dashboard',
    render: function(e) {
        if ( e.template != undefined ) {
            this.template = e.template
        }
    }
})

Backstage.views.adminPageView = Backstage.views.BaseView.extend({
    template: 'admin-page-view'
})

Backstage.views.adminPageEdit = Backstage.views.BaseView.extend({
    template: 'admin-page-edit',

    render: function() {
        //Deal with page status
        var statuses = ['live', "draft", "archived"];
        var selector = this.$('#pageStatus');

        _.each(statuses, function(status) {
            if ( $(selector).find('option[value="'+status+'"]').length == 0 ) {
                var option = document.createElement('option')
                option.setAttribute("value", status)
                if ( status == $(selector).data('set') ) {
                    option.setAttribute("selected", "selected")
                }
                option.innerHTML = status
                $(selector).append(option)
            }
        })
    }
})

Backstage.views.permissionsView = Backstage.views.BaseView.extend({
    template: 'admin-role-view'
})
Backstage.views.permissionsEdit = Backstage.views.BaseView.extend({
    template: 'admin-role-edit'
})
Backstage.views.userChangePassword = Backstage.views.BaseView.extend({
    template: 'user-email-password'
})

Backstage.views.Tab = Backbone.View.extend({
    events: {
        'click': 'activate'
    },
    activate: function() {
        var parent = this.$el.parents('.tabs'),
            target = parent.find(this.$el.data('href'))

        // Hide children
        parent.find('.tab').each(function() {
            $(this).hide().removeClass('active')
        })

        // Remove active class from nav
        parent.find('.nav .anchor').each(function() {
            $(this).removeClass('active')
        })

        // Show child
        target.show().addClass('active')

        // Add active to nav
        this.$el.addClass('active')
        
        // Add Hash to window.
        window.location.hash = this.$el.data('href');
    }
})
Backstage.views.assetsCreateTrack = Backstage.views.BaseView.extend({
    template: 'asset-form',
    render: function() {
        if ( $('.inlineSearch').length > 0 ) {
            $('.inlineSearch').each(function() {
                $(this).autoSearch();
            });
        }
    },
    uploadfile: function(response, container) {
        
        var fn = document.createElement('input')
            fn.setAttribute('type', 'hidden')
            fn.setAttribute('name', "filename")
            fn.setAttribute('value', response.file_name)
            
        var fp = document.createElement('input')
            fp.setAttribute('type', 'hidden')
            fp.setAttribute('name', "file_path")
            fp.setAttribute('value', response.file_path + response.file_name)
            
        var visual = document.createElement('input')
            visual.setAttribute('type', 'text')
            visual.setAttribute('readonly', 'readonly')
            visual.setAttribute('name', 'visual')
            visual.setAttribute('value', response.file_name)
            visual.setAttribute('style', "display: none;")
            
        // Remove On Edit
        if ( $(container).find('input[name="filename"]').length > 0 ) {
            $(container).find('input[name="filename"]').remove()
            $(container).find('input[name="file_path"]').remove()
            $(container).find('input[name="visual"]').remove()
        }
            
        
        $(container).append( $(fn), $(fp), $(visual) )
        $(visual).fadeIn(350)  
    }
})

Backstage.views.assetsCreateAlbum = Backstage.views.BaseView.extend({
    template: 'album-form',
    render: function() {
        this.$('.datePicker').datepicker({dateFormat:"yy/mm/dd"})
    },
    uploadfile: function(response, container) {
        
        // Attach hidden elements
        var cu = document.createElement('input')
            cu.setAttribute('type', 'hidden')
            cu.setAttribute('name', "cover_url")
            cu.setAttribute('value', response.url)
            
        var cf = document.createElement('input')
            cf.setAttribute('type', 'hidden')
            cf.setAttribute('name', 'cover_filename')
            cf.setAttribute('value', response.file_name)

        var ce = document.createElement('input')
            ce.setAttribute('type', 'hidden')
            ce.setAttribute('name', 'cover_extension')
            ce.setAttribute('value', response.file_ext)

        var cp = document.createElement('input')
            cp.setAttribute('type', 'hidden')
            cp.setAttribute('name', 'cover_path')
            cp.setAttribute('value', response.file_path)

        var cw = document.createElement('input')
            cw.setAttribute('type', 'hidden')
            cw.setAttribute('name', 'cover_width')
            cw.setAttribute('value', response.image_width)

        var ch = document.createElement('input')
            ch.setAttribute('type', 'hidden')
            ch.setAttribute('name', 'cover_height')
            ch.setAttribute('value', response.image_height)

        var preview = document.createElement('img')
            preview.setAttribute('src', response.url)
            preview.setAttribute('width', response.image_width)
            preview.setAttribute('height', response.image_height)
            preview.setAttribute('style', "display: none;")
            
        // Remove On Edit
        container = $(container)
        if ( container.find('input[name="cover_url"]').length > 0 ) {
            container.find('input[name="cover_url"]').remove()
            container.find('input[name="cover_filename"]').remove()
            container.find('input[name="cover_extension"]').remove()
            container.find('input[name="cover_path"]').remove()
            container.find('input[name="cover_width"]').remove()
            container.find('input[name="cover_height"]').remove()
            container.find('img').remove()
        }
        
        container.append($(preview), $(cu), $(cf), $(ce), $(cw), $(ch))
        $(preview).fadeIn(350)
    }
})

Backstage.views.assetsView = Backstage.views.BaseView.extend({
    template: 'asset-view-tracks'
})

Backstage.views.assetsSearch = Backstage.views.BaseView.extend({
    template: 'assets-user-search',

    events: {
        'click .add-to-playlist': 'addToPlaylist',
        'click .pages a'        : "changePage",
        'click a.details'       : "showRowDetails"
    },
    changePage: function(e) {
        var form = $(e.target).closest('form')
            form.find("input[name=page]").val($(e.target).data('page'));
            form.submit();
        
        e.preventDefault()
        e.stopPropagation()
    },
    showRowDetails: function(e) {
    
        var target = $(e.target).closest('tr').attr('id');
        
        $('#'+target+'-row').toggle();
        e.preventDefault()
        e.stopPropagation()
    
    },
    render: function(response) {
        // Deals with search arrays
        // $('.addToSearch').on('click', function(e) {
        //     e.preventDefault()
        // 
        //     var parent = $(this).parent(),
        //         target = $(parent).find('input:first'),
        //         i = $(parent).find('input').length
        // 
        //     var input = $(target).clone()
        //     input.attr('name', $(target).attr('name').replace("[0]", "["+i+"]"))
        // 
        //     $(target).after(input)
        // })

        // Deal with pages
        // $('.pages').find('a').on('click', function() {
        //           var form = $(this).closest('form')
        //           
        //           form.attr('action', $(this).attr('href'))
        //           //form.submit()
        //           
        //           // e.preventDefault()
        //           // e.stopPropagation()
        //           
        //           return false
        //       })
    },

    addToPlaylist: function(e) {
        var id = $(e.target).parents('tr').attr('id').split('-')[1]
        var currentPlaylist = Backstage.utils.getCookie('currentPlaylist')
        var data = {
            playlist: currentPlaylist,
            song: id
        }

        this.postRequest(e, data, Backstage.utils.empty)

        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.assetsManage = Backstage.views.BaseView.extend({
    template: 'assets-manage'
})

Backstage.views.artistView = Backstage.views.BaseView.extend({
    template: 'artist-view'
})

Backstage.views.artistEdit = Backstage.views.BaseView.extend({
    template: 'artist-form',
    uploadfile: function(response, container) {
        var fn = document.createElement('input')
            fn.setAttribute('type', 'hidden')
            fn.setAttribute('name', "photo_filename")
            fn.setAttribute('value', response.file_name)
            
        var fp = document.createElement('input')
            fp.setAttribute('type', 'hidden')
            fp.setAttribute('name', "photo_url")
            fp.setAttribute('value', response.url)
        
        var ce = document.createElement('input')
            ce.setAttribute('type', 'hidden')
            ce.setAttribute('name', 'photo_extension')
            ce.setAttribute('value', response.file_ext)
        
        var visual = document.createElement('input')
            visual.setAttribute('type', 'text')
            visual.setAttribute('readonly', 'readonly')
            visual.setAttribute('value', response.file_name)
            visual.setAttribute('style', "display: none;")
        
        // Remove On Edit
        if ( $(container).find('input[name="photo_filename"]').length > 0 ) {
            $(container).find('input[name="photo_filename"]').remove()
            $(container).find('input[name="photo_url"]').remove()
            $(container).find('input[name="photo_extension"]').remove()
            $(container).find('img').attr('src', response.url)
        }
        
        // Attach New stuffs
        $(container).append( $(fn), $(fp), $(visual) )
        $(visual).fadeIn(350)
    }
})


Backstage.views.artistDetails = Backstage.views.BaseView.extend({
    template: 'artist-details'
})


Backstage.views.PlaylistsView = Backstage.views.BaseView.extend({
    template: 'playlists-view',

    events: {
        'click .add': 'createPlaylist'
    },

    render: function(response) {
        _.each(response.playlists, function(value) {
            var view = new Backstage.views.PlaylistRow()
            view.render(value)
            view.$el.appendTo(this.$('.content'))
        })
    },

    createPlaylist: function(e) {
        var view = new Backstage.views.PlaylistRow()
        view.template = 'playlist-row-edit'
        view.render()
        view.$el.appendTo('#playlists .content')

        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.PlaylistRow = Backstage.views.BaseView.extend({
    template: 'playlist-row-view',

    events: {
        'click .edit': 'edit',
        'click .set-active': 'setActive',
        'submit form': 'submit'
    },

    edit: function(e) {
        this.template = 'playlist-row-edit'
        this.render(this.data)

        e.preventDefault()
        e.stopPropagation()
    },

    setActive: function(e) {
        document.cookie = 'currentPlaylist=' + this.$('input[name="id"]').val()
        e.preventDefault()
        e.stopPropagation()
    },

    submit: function(e) {
        var data = $(e.target).serialize()

        this.postRequest(e, data, function(response) {
            this.template = 'playlist-row-view'
            this.render(response.playlist)
        })

        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.adminCatalogView = Backstage.views.BaseView.extend({
    template: 'catalog-view'
})


Backstage.views.adminPortalView = Backstage.views.BaseView.extend({
    template: 'portals-view'
})

Backstage.views.adminPortalCreate = Backstage.views.BaseView.extend({
    template: 'portal-form',

    events: {
        'click input' : 'removeRed',
        'click #sameas' : 'copyBilling',
        'click .autoscroll' : 'autoScroll',
        "submit form.newPortal" : "validate"
    },
    
    /**
     * Remove the red outline if it's errored.
     */
    removeRed : function(e){
        $(e.target).removeClass('field-error')
    },
    
    /**
     * Copy the billing information to the contact
     * information.
     */
    copyBilling : function(e){
        var e2 = $(e.target).parent().parent()
        var name1 = e2.find('#billing_name')
        var phone1 = e2.find('#billing_phone')
        var email1 = e2.find('#billing_email')
        var name2 = e2.find('#contact_name')
        var phone2 = e2.find('#contact_phone')
        var email2 = e2.find('#contact_email')
        email2.val(emal1.val())
        name2.val(name1.val())
        phone2.val(phone1.val())

        e.preventDefault()
        e.stopPropagation()
        return false;
    },
    
    autoScroll : function(e){
        if (debug) console.log('autoscrolling')
        var scrollID = '#' + $(e.target).attr('id').substring(2)
        if (debug) console.log('autoscrolling to ' + scrollID)
        $(document).scrollTop($($(document).find(scrollID)[0]).offset().top-30)

        e.preventDefault()
        e.stopPropagation()
    },
    
    uploadfile: function(response, container) {
        
        var fn = document.createElement('input')
            fn.setAttribute('type', 'hidden')
            fn.setAttribute('name', "logo_filename")
            fn.setAttribute('value', response.file_name)
            
        var fu = document.createElement('input')
            fu.setAttribute('type', 'hidden')
            fu.setAttribute('name', "logo_url")
            fu.setAttribute('value', response.url)
        
        var fe = document.createElement('input')
            fe.setAttribute('type', 'hidden')
            fe.setAttribute('name', 'logo_extension')
            fe.setAttribute('value', response.file_ext)
            
        var fp = document.createElement('input')
            fp.setAttribute('type', 'hidden')
            fp.setAttribute('name', 'logo_path')
            fp.setAttribute('value', response.file_path)
        
        var visual = document.createElement('img')
            visual.setAttribute('src', response.url)
            visual.setAttribute('width', response.image_width)
            visual.setAttribute('height', response.image_height)
            visual.setAttribute('style', "display: none;")
        
        // Remove On Edit
        if ( $(container).find('input[name="logo_filename"]').length > 0 ) {
            $(container).find('input[name="logo_filename"]').remove()
            $(container).find('input[name="logo_url"]').remove()
            $(container).find('input[name="logo_extension"]').remove()
            $(container).find('input[name="logo_path"]').remove()
            $(container).find('img').attr('src', response.url)
        }
        
        // Attach New stuffs
        $(container).append( $(fn), $(fu), $(fe), $(fp), $(visual) )
        $(visual).fadeIn(350)
    },
    
    validatePublicKey : function(publicKey){
        var hasSpace = /((\w+)(\s+)(\w+)).|((\w+)(\s+))|((\s+)(\w+))/gi
        //if (debug) console.log("PUBLIC KEY:")
        //if (debug) console.log(publicKey)
        var reasons = [null]
//        if (debug) console.log("value is " + $(publicKey).val());
        if ($(publicKey).val() == null){
            if (debug) console.log("Reasons is null")
            return reasons
        }
        if ($(publicKey).val().toString().search(hasSpace) != -1){
           // if (debug) console.log("This has a space")
            reasons.push(this.reasonate(publicKey,
                'Public key should not contain spaces.'))
        }//else{ if (debug) console.log("This does not have a space")}
        
        return reasons
    },
    validate: function(form) {
        // Collapse the warning div adn erase the contents.
        if (debug) console.log("validating...")
        var states = [
            { 'abbrev' : 'AL', 'name' : 'Alabama' },
            { 'abbrev' : 'AK', 'name' : 'Alaska' },
            { 'abbrev' : 'AZ', 'name' : 'Arizona' },
            { 'abbrev' : 'AR', 'name' : 'Arkansas' },
            { 'abbrev' : 'CA', 'name' : 'California' },
            { 'abbrev' : 'CO', 'name' : 'Colorado' },
            { 'abbrev' : 'CT', 'name' : 'Connecticut' },
            { 'abbrev' : 'DE', 'name' : 'Delaware' },
            { 'abbrev' : 'FL', 'name' : 'Florida' },
            { 'abbrev' : 'GA', 'name' : 'Georgia' },
            { 'abbrev' : 'HI', 'name' : 'Hawaii' },
            { 'abbrev' : 'ID', 'name' : 'Idaho' },
            { 'abbrev' : 'IL', 'name' : 'Illinois' },
            { 'abbrev' : 'IN', 'name' : 'Indiana' },
            { 'abbrev' : 'IA', 'name' : 'Iowa' },
            { 'abbrev' : 'KS', 'name' : 'Kansas' },
            { 'abbrev' : 'KY', 'name' : 'Kentucky' },
            { 'abbrev' : 'LA', 'name' : 'Louisiana' },
            { 'abbrev' : 'ME', 'name' : 'Maine' },
            { 'abbrev' : 'MD', 'name' : 'Maryland' },
            { 'abbrev' : 'MA', 'name' : 'Massachusetts' },
            { 'abbrev' : 'MI', 'name' : 'Michigan' },
            { 'abbrev' : 'MN', 'name' : 'Minnesota' },
            { 'abbrev' : 'MS', 'name' : 'Mississippi' },
            { 'abbrev' : 'MO', 'name' : 'Missouri' },
            { 'abbrev' : 'MT', 'name' : 'Montana' },
            { 'abbrev' : 'NE', 'name' : 'Nebraska' },
            { 'abbrev' : 'NV', 'name' : 'Nevada' },
            { 'abbrev' : 'NH', 'name' : 'New Hampshire' },
            { 'abbrev' : 'NJ', 'name' : 'New Jersey' },
            { 'abbrev' : 'NM', 'name' : 'New Mexico' },
            { 'abbrev' : 'NY', 'name' : 'New York' },
            { 'abbrev' : 'NC', 'name' : 'North Carolina' },
            { 'abbrev' : 'ND', 'name' : 'North Dakota' },
            { 'abbrev' : 'OH', 'name' : 'Ohio' },
            { 'abbrev' : 'OK', 'name' : 'Oklahoma' },
            { 'abbrev' : 'OR', 'name' : 'Oregon' },
            { 'abbrev' : 'PA', 'name' : 'Pennsylvania' },
            { 'abbrev' : 'RI', 'name' : 'Rhode Island' },
            { 'abbrev' : 'SC', 'name' : 'South Carolina' },
            { 'abbrev' : 'SD', 'name' : 'South Dakota' },
            { 'abbrev' : 'TN', 'name' : 'Tennessee' },
            { 'abbrev' : 'TX', 'name' : 'Texas' },
            { 'abbrev' : 'UT', 'name' : 'Utah' },
            { 'abbrev' : 'VT', 'name' : 'Vermont' },
            { 'abbrev' : 'VA', 'name' : 'Virginia' },
            { 'abbrev' : 'WA', 'name' : 'Washington' },
            { 'abbrev' : 'WV', 'name' : 'West Virginia' },
            { 'abbrev' : 'WI', 'name' : 'Wisconsin' },
            { 'abbrev' : 'WY', 'name' : 'Wyoming' }
        ]
        //if (debug) console.log("====== FORM ====")
        //if (debug) console.log(form)
        //if (debug) console.log("================")
        
        // Gather all the information from $(form.target) into
        // friendly variables.
        var t = $(form.target);
        
        $($(document).find('div#reasons-warning')).css('display', 'none')
        $(t.find('.field-error')).removeClass('field-error')
        
        var receipt = new Array()
        receipt['text'] = new Array()
        receipt['text']['header'] = $(t.find("#portalName")[0])
        receipt['text']['footer'] = $(t.find('#receipt_footer')[0])
        receipt['value'] = $(t.find('#receipt_value')[0])
        receipt['subject'] = $(t.find('#receipt_subject')[0])
        var billing = new Array()
        billing['name'] = $(t.find('#billing_name')[0])
        billing['email'] = $(t.find('#billing_email')[0])
        billing['phone'] = $(t.find('#billing_phone')[0])
        billing['address'] = [ $(t.find('#billing_address1')[0]), $(t.find('#billing_address2')[0]) ]
        billing['city'] = $(t.find('#billing_city')[0])
        billing['state'] = $(t.find('#billing_state')[0])
        billing['country'] = $(t.find('#billing_country')[0])
        billing['postal'] = $(t.find('#billing_postal')[0])
        var contact = new Array()
        contact['name'] = $(t.find('#contact_name')[0])
        contact['email'] = $(t.find('#contact_email')[0])
        contact['phone'] = $(t.find('#contact_phone')[0])
        var rate_card = new Array()
        rate_card['card'] = $(t.find('#rate_card')[0])
        rate_card['description'] = $(t.find('#rate_card_description')[0])
        var reasons = new Array()
        var isOkay = true;
        //if (debug) console.log("t=")
        //if (debug) console.log(t)

        // Given all the variables above, validate all the values.  Basically,
        // the validate* functions produce a reasons[] array.  Each element of
        // reasons[] must be appended to the reasons[] array in this function.
        // At the end, we'll check the array (see below)
        this.validatePublicKey(t.find("#portalName")[0]).forEach(function addReasons(reason){
            reasons.push(reason)
        })

        this.validatePassword(t.find("#portalPassword")[0]).forEach(function addReasons(reason){
            reasons.push(reason)
        })
                  
        this.validateLogo(t.find("#portalLogo")[0]).forEach(function addReasons(reason){
            reasons.push(reason)
        })
                
        this.validateInvoicing(t.find('#invoicing')[0]).forEach(function addReasons(reason){
            reasons.push(reason)
        })
        this.validateAllBilling(billing, states).forEach(function addReasons(reason){
            reasons.push(reason)
        })

        this.validateAllContact(contact).forEach(function addReasons(reason){
            reasons.push(reason)
        })

        this.validateRateCard(rate_card).forEach(function addReasons(reason){
            reasons.push(reason)
        })
                
        this.validateAdmin(t.find('#admin')).forEach(function addReasons(reason){
            reasons.push(reason)
        })

        // Remove all the null values.
        var tmpArray = new Array()
        reasons.forEach(function addNotNull(i){
            if (i != null)
                tmpArray.push(i)
        })
        reasons = tmpArray
        
        // if reasons is all null then there's no
        // problem.
        isOkay = tmpArray.length == 0
        if (!isOkay && debug){
            reasons.forEach(function explain(reason){
                if (reason != null)
                    if (debug) console.log('[' + reason['field'] + '] ' + reason['reason'])
            })
        }else if (isOkay && debug){
            if (debug) console.log("Everything checks out.")
        }
        
       if (!isOkay){
           this.fillWarnings(reasons, t.parent())
           $(document).scrollTop($($(document).find('#reasons-warning')[0]).offset().top-30)
       }
 
       //if (!isOkay) explain(reasons)
       return isOkay;
    },
    
    /**
     * Fill up the warnings div with the reasons.
     */
    fillWarnings : function(reasons, context){
        reasons.forEach(function dispReasons(reason){
            var li = $('<li></li>')
            var a = $('<a></a>')
            $(context.find('#' + reason['field'])[0]).addClass('field-error')
            if (debug) console.log('Finding context #' + reason['field'])
            a.attr('href', '#')
            a.attr('id', 'a-' + reason['field'])
            a.html('Show me.')
            a.addClass('autoscroll')
            li.html(reason['reason'] + " ")
            li.append(a)
            // A little help from http://stackoverflow.com/questions/
            // 12329008/scrolling-to-the-next-element
            /**a.click(function scrollTo2(){
                var scrollID = '#' + $(this).attr('id').substring(2)
                if (debug) console.log("Scrolling to " + scrollID)
                $(document).scrollTop($(this).find(scrollID).offset().top)
            })**/
            $(context.find('ol#reasons-warning-ol')[0]).append(li)
            $(context.find('div#reasons-warning')[0]).css('display', 'block')
        })
    },
    
    /**
     * Validate the password field.
     * Just make sure it's between 6 and 14 characters.
     */
    
    validatePassword : function(pw){
        var isPassword = /.{6,14}/
        var reasons = [null]
        if ($(pw).val().search(isPassword) == -1){
            reasons.push(this.reasonate(pw, 'Password must be between 6 and 14 characters'))
        }
        return reasons
    },
    
    /**
     * Validate the logo field.  The logo must have the format:
     * (1) Start with either http://, https://, ftp://, ftps://, or file://
     * (2) May contain slashes, numbers, letters, underscores, dashes, dollar
     *      signs, periods, or question marks.
     */
    validateLogo : function(logo){
        var isURL  = /(http(s)?|ftp|file)\:\/\/[A-Z0-9_\/\-\?\.\$]+/gi
        var reasons = [null]
        if ($(logo).val().search(isURL) == -1 && $(logo).val().length > 0){
            reasons.push(this.reasonate(logo,
                'URL was not a valid URL'))
            return reasons
        }
        return reasons
    },
    
    /**
     * Validate receipt
     */
    validateReceipt : function(v){
        var reasons = []
        return reasons
    },
    
    /**
     * Validate all the billing information in the billing_*
     * fields.  Here is the requirements for billing:
     * City:
     *      - Contains only letters, periods, commas, and spaces.
     * State:
     *      - Contains only letters, periods, commas, and spaces.
     *      - Must be found in the "states" array.
     * Country:
     *      - Contains only letters, periods, commas, and spaces.
     * Postal (zip):
     *      - Contains only 7 digits with dashes grouping.
     * 
     * Anything else will be handled by validateAllContact()
     */
    validateAllBilling : function(billing, states){
        var hasAlphaNumerics = /^[A-Za-z0-9]+$/gi
        var isAlphaWSpace = /^[A-Za-z0-9\s\.,$]+/gi  /** We'll allow periods for abbreviations **/
        var isAlphaOnly = /^[a-z\s]+$/gi
        var isPhoneNumber = /^\d{7,10}(\+\d{0,10})?$/gi
        var isEmail = /^[\w_\.]{1,40}@[a-zA-Z0-9]{1,20}\.[a-zA-Z]{1,6}$/gi
        var isZip = /^\d{5}(?:[-\s]\d{4})?$/gi
        
        var reasons = [null]
        
        var r  = new Array()
        
        if (billing['city'].val().search(isAlphaWSpace) == -1 &&
        billing['city'].val().length > 0){
            reasons.push(this.reasonate(billing['city'],
                'City cannot contian alphanumerics'))
        }
        user_state = billing['state'].val()
        isState = states.some(function testState(st){
            if (user_state.toUpperCase() == st.abbrev ||
                user_state.toLowerCase() == st.name.toLowerCase())
                    return true
            return false
        })
        
       if (billing['state'].val().search(isAlphaWSpace) == -1 &&
        billing['state'].val().length > 0){
            reasons.push(this.reasonate(billing['state'],
                'State cannot contain numbers'))
        } else if (! isState && billing['state'].val() > 0) {
            reasons.push(this.reasonate(billing['state'],
                'State is invalid.'))
        } if (billing['country'].val().search(isAlphaWSpace) == -1 &&
            billing['country'].val().length > 0) {
            reasons.push(this.reasonate(billing['country'],
                'Country is not valid.'))
        } if (billing['postal'].val().search(isZip) == -1 &&
            billing['postal'].val().length > 0) {
            reasons.push(this.reasonate(billing['postal'],
                'Zip code is not valid.'))
        }
        
        // Treat billing like contacts.
        r = this.validateAllContact(billing)
        r.forEach(function getReasons(r2){
            reasons.push(r2)
        })
        
        return reasons
        
    },
    
    /**
     * Validate all contact information
     * 
     * Here are the requirements:
     * 
     * Phone:
     *      - Contains 9 digits with the option of a plus followed by
     *          numbers (if they have an extension)
     *      NOTE: ensure the phone is stripped of all symbols.
     * 
     * Name:
     *      - Contains only numbers, spaces, and periods.
     * Email:
     *      - Starts with a word of length between 1 and 40.
     *      - The word is followed by '@'
     *      - The '@' is followed by a word of length between 1 and 20,
     *          a period, and a word of length between 1 and 6.
     */
    validateAllContact : function(contact){
        var hasAlphaNumerics = /^[A-Za-z0-9]+$/gi
        var isAlphaWSpace = /^[A-Za-z0-9\s\.,$]+/gi  /** We'll allow periods for abbreviations **/
        var isAlphaOnly = /^[a-z\s]+$/gi
        var isPhoneNumber = /^\d{7,10}(\+\d{0,10})?$/gi
        var isEmail = /^[\w_\.]{1,40}@[a-zA-Z0-9]{1,20}\.[a-zA-Z]{1,6}$/gi
        var isZip = /^\d{5}(?:[-\s]\d{4})?$/gi
        var reasons = [null]
        if (contact['phone'].val().search(isPhoneNumber) == -1 &&
            contact['phone'].val().length > 0) {
            reasons.push(this.reasonate(contact['phone'],
                'Phone number must be 7 digits'))
        } if (contact['name'].val().search(isAlphaWSpace) == -1 &&
            contact['name'].val().length > 0){
            reasons.push(this.reasonate(contact['name'],
                'You cannot have a number or symbol in a name.'))
        }if (contact['email'].val().search(isEmail) == -1 &&
            contact['email'].val().length > 0){
            reasons.push(this.reasonate(contact['email'],
                'Invlid email address.'))
        }
        return reasons
    },
    validateInvoicing : function(invoicing){
        var reasons = []
        return reasons
    },
    validateRateCard : function(rateCard){
        var reasons = []
        return reasons
    },
    validateAdmin : function(admin){
        var reasons = []
        return reasons
    },
    
    /**
     * Generate a reason.
     * @param {Object} field The JQuery object (or DOM object)
     *  that contains the value.
     * @param {Object} reason What happens if validation fails?
     */
    reasonate : function(field, reason){
        var o = field
        if (!(field instanceof jQuery))
            o = $(field)
        var r = new Array()
        r['field'] = o.attr('id')
        r['reason'] = reason
        return r
    }
})

Backstage.views.adminPortalLicenses = Backstage.views.BaseView.extend({
    template: 'portal-licenses',
    events: {
        "submit form":"addLicense"
    },
    addLicense: function(e) {  
        this.postRequest(e, $(e.target).serialize(), function(response) {              
            var view = new Backstage.views.adminPortalLicenses.LicenseRow({parent : this})
            response.license.portal_id = response.portal_id
       
            view.render(response.license)
            view.$el.appendTo('.current-licenses')
        })

        e.preventDefault()
        e.stopPropagation()
    },
    render: function(data) {
           _.each(data.current_licenses, function(value) {
               var view = new Backstage.views.adminPortalLicenses.LicenseRow({parent : this})
               value.portal_id = data.portal_id
               
               view.render(value)
               view.$el.appendTo('.current-licenses')
           }, this);
      }
})

Backstage.views.adminPortalLicenses.LicenseRow = Backstage.views.BaseView.extend({
    template: 'portal-license-row-view',
    events: {
        'click .edit': 'edit',
        'click a.delete':'delete',
        'submit form': 'submit',
    },
    tagName: "ul",
    el: $('#template'+this.template).html(),
    initialize : function(options){
        this.parent = options.parent;
    },
    
    edit: function(e) {
        this.template = 'portal-license-row-edit'
        this.render(this.data)
    
        e.preventDefault()
        e.stopPropagation()
    },
    submit: function(e) {
        
        // Otherwise, post the request.
        this.postRequest(e, $(e.target).serialize(), function(response) {
            if ( response.message ) {
                alert(response.message) // something better
            }
            else if ( response.redirect ) {
                 Backstage.router.navigate(response.redirect, {trigger: true})
            } else {
                this.template = 'portal-license-row-view'
                this.render(response.licence);
            }   
        });
        
        e.preventDefault()
        e.stopPropagation()
    },
    delete: function(e) {
        if ( confirm("Are you sure you want to remove this license?") ) {
            this.getRequest(e, function(response) {
                if ( response.message ) {
                    alert(response.message) // something better
                }
                else if ( response.redirect ) {
                     Backstage.router.navigate(response.redirect, {trigger: true})
                } 
                else if ( response.success = 'true' ) {
                    $(e.target).closest('ul').fadeOut(100, function() { $(this).remove() })
                }
            })
        }
        
        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.adminPortalCatalogs = Backstage.views.BaseView.extend({
    template: 'portal-catalogs',
    events: {
        'submit form':'addCatalog'
    },
    render: function(data) {
        _.each(data.current_catalogs, function(value) {
           var view = new Backstage.views.adminPortalCatalogs.catalogRow({parent : this})
           view.render(value)
           view.$el.appendTo('.current-catalogs')
       }, this);
    },
    addCatalog: function(e) {
        this.postRequest(e, $(e.target).serialize(), function(response) {              
            var view = new Backstage.views.adminPortalCatalogs.catalogRow({parent : this})
       
            view.render(response.catalog)
            view.$el.appendTo('.current-catalogs')
        })
        
        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.adminPortalCatalogs.catalogRow = Backstage.views.BaseView.extend({
    template: 'portal-catalog-row',
    events: {
        "click a.remove":"confirmRemoval"
    },
    tagName: "ul",
    el: $('#template'+this.template).html(),
    initialize : function(options){
        this.parent = options.parent;
    },
    confirmRemoval: function(e) {
        if ( confirm("Are you sure you want to remove this catalog?") ) {
           this.getRequest(e, function(response) {
               if ( response.error ) {
                   alert(response.error) // something better
               }
               else if ( response.success ) {
                    $(e.target).closest('ul').fadeOut(100, function() { $(this).remove() })  
               }
           })
        }

        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.adminCatalogCreate = Backstage.views.BaseView.extend({
    template: 'catalog-form',
    events: {
       "submit form" : "validate"
    },
    validate: function() {
        
    }
})

Backstage.views.adminCatalogLicenses = Backstage.views.BaseView.extend({
    template: 'catalog-licenses',
    events: {
        'submit form':'addLicense'
    },
    render: function(data) {
         _.each(data.catalog.catalog_licenses, function(value) {
               var view = new Backstage.views.adminCatalogLicenses.catalogLicenseRow({parent : this})
               
               view.render(value)
               view.$el.appendTo('.catalog-licenses')
           }, this);
    },
    addLicense: function(e) {
        this.postRequest(e, $(e.target).serialize(), function(response) {              
            var view = new Backstage.views.adminCatalogLicenses.catalogLicenseRow({parent : this})
            
            view.render(response.license)
            view.$el.appendTo('.catalog-licenses')
        })

        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.adminCatalogLicenses.catalogLicenseRow = Backstage.views.BaseView.extend({
    template: 'catalog-licenses-row',
    events: {
        "click a.edit":"edit",
        "click a.remove":"confirmRemoval",
        "submit form":"validate"
    },
    tagName: "ul",
    el: $('#template'+this.template).html(),
    initialize : function(options){
        this.parent = options.parent;
    },
    edit: function(e) {
        this.template = 'catalog-licenses-edit'
        this.render(this.data)
    
        e.preventDefault()
        e.stopPropagation()
    },
    confirmRemoval: function(e) {
        if ( confirm("Are you sure you want to remove this license?") ) {
            this.getRequest(e, function(response) {
                if ( response.error ) {
                    alert(response.error) // something better
                }
                else if ( response.success ) {
                    $(e.target).closest('ul').fadeOut(100, function() { $(this).remove() })  
                }
            })
        }
        
        e.preventDefault()
        e.stopPropagation()
    }
})

Backstage.views.adminLicensesView = Backstage.views.BaseView.extend({
    template: 'licenses-view'
})

Backstage.views.adminLicensesCreate = Backstage.views.BaseView.extend({
    template: 'licenses-form',
    events: {
       "submit form" : "validate"
    },
    validate: function(e) {
        var req = ' is required.'
        var id = 'id'
        var regex = 'regex'
        var required = 'required'
        var message = 'message'
        var requiredMessage = 'requiredMessge' 
        var validation = [
            {id : 'purpose', regex : /^\w+\s?\w+$/gi, required : true,
                message : 'Message must be one or two words.', 
                requiredMessage : 'Purpose' + req},
            {id : 'type', required : true, regex : /^(\d(\.\d)?)+$/gi,
                message : 'Type must be a number (e.g. 1.3.3).',
                requiredMessage : 'Type' + req},
            {id : 'version', required : true, regex : /^(\d(\.\d)?)+$/gi,
                message : 'Version must be a number (e.g. 1.3.3).',
                requiredMessage : 'Version' + req},
            {id : 'rights', required: true, requiredMessage : 'Rights' + req},
            {id : 'region', required : true, requiredMessage : 'Region' + req},
            {id : 'term',
                regex : /^(\d+\s+(month(s)?|day(s)?|week(s)?|year(s)?)([\,\s(and)])*)+$/gi,
                required : true,
                message : 'Please use correct term format, e.g.: 3 years, 5 months and 4 days',
                requiredMessage : 'Term' + req
            },
            {id : 'short_description', regex : /^(.{0,255})?$/,
                message: 'Short Description must be less than 255 characters.'},
            // long_description not required.
            // text not required
        ]
        
        var test = [ validation[2] ]
        form = $(e.target)
        warn = $($(document).find('#warning-message')[0])
        reasons = Backstage.currentPage.doValidation(form, validation, '.field-error', warn)

        return reasons == 0
    }
})

Backstage.views.adminLicensesDetails = Backstage.views.BaseView.extend({
    template: 'license-details',
    render: function() {
        if (debug) console.log('adminLicensesDetails')
    }
})

Backstage.views.confirmEmail = Backstage.views.BaseView.extend({
    template: 'confirm-success'
})

Backstage.views.pagesDefault = Backstage.views.BaseView.extend({
    template: 'pages-default',
    render: function() {
        if ( debug ) console.log('hit default');
    }
})

Backstage.views.userFirstLogin1 = Backstage.views.BaseView.extend({
    template: 'first-login-1',
})
Backstage.views.userFirstLogin2 = Backstage.views.BaseView.extend({
    template: 'first-login-2',
})


Backstage.appView = new Backstage.views.Application()

Backstage.AccountRouter = Backbone.Router.extend({
 route : function(page){
     $(".hideable-pages").fadeOut("slow");
     $("#" + page).delay(100).fadeIn("slow");
     $(".form-row").hide();
     $(".add").show();
 }
})

//var accountRouter = new Backstage.AccountRouter();

Backstage.Router = Backbone.Router.extend({
    pages: {
        '*whatever'                     : 'pagesDefault',
        ''                              : 'HomePage',
        'user/login'                    : 'LoginPage',
        'user/logout'					: 'LogoutPage',
        'user/register'                 : 'registerPage',
        'user/passwordreset/:any'       : 'passwordReset',
        'user/changepassword'           : 'userChangePassword',
        
        'users'                         : 'userDashboard',
        'users/account'                 : 'AccountPage',
        'user/confirmemail/:any'        : 'confirmEmail',
        
        'users/artists'                 : 'artistView',
        'users/artists/view/:number'    : 'artistDetails',
        'users/artists/add'             : 'artistEdit',
        'users/artists/edit/:number'    : 'artistEdit',
        
        'users/assets/search'           : 'assetsSearch',
        'users/assets/search/:number'   : 'assetsSearch',
        'users/assets/create-album'     : 'assetsCreateAlbum',
        'users/assets/edit-album/:number': 'assetsCreateAlbum',
        'users/assets/create-track'     : 'assetsCreateTrack',
        'users/assets/create-track/:number': 'assetsCreateTrack',
        'users/assets/edit-track/:number': 'assetsCreateTrack',
        
        'account/contacts' 		    	: 'accountContactsPage',
        'account/documents' 		    : 'accountDocumentsPage',
        'users/songwriters' 		    : 'songwritersPage',
        
        'songwriters/add'               : 'accountSongwritersPageNew',
        
        'account/songwriters/addnew'    : 'accountSongwritersPage',
        'account/songwriters/update'    : 'accountSongwritersPage',
        'account/songwriters/delete'    : 'accountSongwritersPage',
        'account/songwriters/new'       : 'accountSongwritersPageNew',
        'account/publishers' 		    : 'accountPublishersPage',
        'account/publishers/delete'     : 'accountPublishersPage',
        'permissions/roles'             : 'permissionsView',
        'permissions/edit_role/:number' : 'permissionsEdit',
        
        'assets/view/:any'              : 'assetsView',
        'assets/search'                 : 'assetsSearch',
        'admin/users'                   : 'userSearch',
        'admin/users/edit/:number'      : 'AccountPage',
        'admin/users/adddocuments/:number' : 'addDocuments',
        
        'assets'                        : 'assetsManage',
        'assets/search/:number'         : 'assetsSearch',
        'playlists'                     : 'PlaylistsView',
        
        'admin/page/view'               : 'adminPageView',
        'admin/page/view/:number'       : 'adminPageView',
        'admin/page/edit/:number'       : 'adminPageEdit',
        'admin/page/new/:number'        : 'adminPageEdit',
        'admin/catalog/view'            : 'adminCatalogView',
        'admin/catalog/create'          : 'adminCatalogCreate',
        'admin/catalog/edit/:number'    : 'adminCatalogCreate',
        'admin/catalog/licenses/:number': 'adminCatalogLicenses',
        'admin/portals/view'            : "adminPortalView", 
        'admin/portals/create'          : "adminPortalCreate",
        'admin/portals/details/:number' : "adminPortalCreate",
        'admin/portals/licenses/:number': "adminPortalLicenses",
        'admin/portals/catalogs/:number': "adminPortalCatalogs",
        'admin/licenses/view'           : "adminLicensesView", 
        'admin/licenses/create'         : "adminLicensesCreate",
        'admin/licenses/details/:number': "adminLicensesDetails",
        'admin/licenses/edit/:number'   : "adminLicensesCreate",
        'users/welcome/1'               : "userFirstLogin1",
        'users/welcome/2'               : "userFirstLogin2"
    },

    initialize: function() {        
        _.each(this.pages, function(value, key) {
            this.route(key, value, function() {
                var url = path + Backbone.history.fragment
                Backstage.utils.getRequest(url, function(response) {
                    if (response.redirect !== undefined) {
                        Backstage.router.navigate(response.redirect, {trigger: true})
                    } else if (response.reload) {
                        // For log in an log out we reload the page.May be used in other fringe cases
                        window.location.href = response.reload
                    } else {
                        //Backstage.appView.setTitle(response.title)
                        var view = new Backstage.views[value]()
                        view.setElement(document.getElementById('body'))
                        view.data = response
                        view.render(response)
                        Backstage.currentPage = view
                    }
                    Backstage.utils.loading(false)
                }, this)
            })
        }, this)
    }
})

Backstage.router = new Backstage.Router
Backbone.history.start({pushState: true})
