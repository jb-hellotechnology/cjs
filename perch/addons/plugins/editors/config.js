Perch.UserConfig.redactor = function(){
   var get = function (profile, config, field) {
    config.imageFigure = true;
    config.imagePosition = {
        "left": "image-left",
        "right": "image-right",
        "center": "image-center"
    };
    config.imageFloatMargin = '1rem';
    config.linkValidation = false;
    config.linkNewTab = true;
    
    config.formatting = ['p', 'blockquote', 'h1', 'h2', 'h3', 'h4'];
    
    config.formattingAdd = {
        "blockquote inline": {
            title: 'Blockquote Inline',
            api: 'module.block.format',
            args: {
                'tag': 'blockquote',
                'class': 'inline'
            }
        },
        "blockquote pullout": {
            title: 'Blockquote Pullout',
            api: 'module.block.format',
            args: {
                'tag': 'blockquote',
                'class': 'pullout'
            }
        },
        "p-alt": {
            title: 'Normal text alternate',
            api: 'module.block.format',
            args: {
                'tag': 'p',
                'class': 'alt'
            }
        },
        "Training Heading": {
            title: 'Heading for TD',
            api: 'module.block.format',
            args: {
                'tag': 'h2',
                'class': 'Train_heading'
            }
        },
        "News Head": {
            title: 'Heading for News',
            api: 'module.block.format',
            args: {
                'tag': 'h2',
                'class': 'News_head'
            }
        },
        "News Head #2": {
            title: 'Heading for News #2',
            api: 'module.block.format',
            args: {
                'tag': 'h2',
                'class': 'NewsHead'
            }
        }
    };
    
    if (config.plugins.indexOf('properties') === -1) {
      config.plugins.push('properties');
    }
    if (config.plugins.indexOf('alignment') === -1) {
      config.plugins.push('alignment');
    }
    if (config.plugins.indexOf('email') === -1) {
      config.plugins.push('email');
    }
    if (config.plugins.indexOf('fontcolor') === -1) {
      config.plugins.push('fontcolor');
    }
    if (config.plugins.indexOf('hr') === -1) {
      config.plugins.push('hr');
    }
    if (config.plugins.indexOf('table') === -1) {
      config.plugins.push('table');
    }
    
    config.linkSize = 1000;
    
    config.buttons = ['html', 'format', 'bold', 'italic', 'underline', 'lists', 'link', 'file', 'image', 'align'];
     
    config.minHeight = "300px";

    return config;
  };

  var load = function (cb) {
     jQuery.getScript(Perch.path+'/addons/plugins/editors/redactor-plugins/alignment.js', function(){
      jQuery.getScript(Perch.path+'/addons/plugins/editors/redactor-plugins/properties.js?v=0.0.1', function(){
        jQuery.getScript(Perch.path+'/addons/plugins/editors/redactor-plugins/email.js', function(){
          jQuery.getScript(Perch.path+'/addons/plugins/editors/redactor-plugins/fontcolor.js', function(){
            jQuery.getScript(Perch.path+'/addons/plugins/editors/redactor-plugins/hr.js', function(){
              jQuery.getScript(Perch.path+'/addons/plugins/editors/redactor-plugins/table.js', cb);
            });
          });
        });
      });
     });

  };

  return {
    'get': get,
    'load': load
  }
}();