(function($R)
{
   $R.add('plugin', 'hr', {
        // set translations
        translations: {
            en: {
                "hr": "hr"
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
        start: function()
        {
            // set up the button with lang variable
            var buttonData = {
                title: this.lang.get('hr'),
                api: 'plugin.hr.insert'
            };

            // add the button to the toolbar
            var $button = this.toolbar.addButton('hr', buttonData);
        },
        insert: function()
        {
            this.insertion.insertHtml('<hr />');
        },
    });
})(Redactor);