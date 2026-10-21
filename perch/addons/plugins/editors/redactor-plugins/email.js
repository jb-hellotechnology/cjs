(function($R)
{
   $R.add('plugin', 'email', {
        modals: {
            // this is variable with modal HTML body
            'emailmodal': '<form action=""><div class="form-item"><label>Email Address</label><input type="text" name="emailaddress"><label>Email Subject</label><input type="text" name="emailsubject"></div></form>'
        },
        // set translations
        translations: {
            en: {
                "email": "Email"
            },
        },
        init: function(app)
        {
            this.app = app;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;

            // define lang service
            this.lang = app.lang;
        },
        // messages
        onmodal: {
            emailmodal: {
               insert: function($modal, $form)
               {
                  var data = $form.getData();
                  this._insert(data);
               }
            }
        },
        start: function()
        {
            // set up the button with lang variable
            var buttonData = {
                title: this.lang.get('email'),
                api: 'plugin.email.open'
            };

            // add the button to the toolbar
            var $button = this.toolbar.addButton('email', buttonData);
        },
        open: function()
        {
            var options = {
                title: 'Insert Email Address', // the modal title
                name: 'emailmodal', // the modal variable in modals object
                commands: {
                    cancel: { title: 'Cancel' }, // the cancel button in the modal
                    insert: { title: 'Insert' }, // the insert button in the modal
                }
            };

            // open the modal with API
            this.app.api('module.modal.build', options);
        },
        // private
        _insert: function(data)
        {
            this.app.api('module.modal.close');

            if (data.emailaddress.trim() === '') return;
            var emaillink = '<a href="mailto:'+data.emailaddress+'?subject='+data.emailsubject+'">'+data.emailaddress+'</a>';
            this.insertion.insertHtml(emaillink);
        }
    });
})(Redactor);