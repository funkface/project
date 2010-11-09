(function() {
    
    tinymce.PluginManager.requireLangPack('simpleimage');

	tinymce.create('tinymce.plugins.SimpleImage', {
	    
		init : function(ed, url) {
	    
			this.editor = ed;

			ed.addCommand('mceSimpleImage', function() {
			    
			    var content, p = ed.selection.getNode();
			    
			    if(p != null){
			        content = p.getAttribute('title');
			    }else{
			        content = ed.selection.getContent();
			    }

			    ed.windowManager.open({
                    file : ed.getParam("plugin_simpleimage_pageurl", "/"),
                    width : parseInt(ed.getParam("plugin_simpleimage_width", "550")),
                    height : parseInt(ed.getParam("plugin_simpleimage_height", "600")),
                    resizable : true,
                    scrollbars : true,
                    popup_css : false
                }, {
                    linkTitle : content
                });
			    
			});

			ed.addButton('simpleimage', {
			    title : 'Insert Image',
			    cmd : 'mceSimpleImage',
			    image : url + '/images/insertimage.gif'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('simpleimage', n.nodeName == 'IMG' && n.className.indexOf('simpleimage') >= 0);
            });
			
		},

		getInfo : function() {
			return {
				longname : 'Simple Image Upload',
				author : 'Martin Shopland Front of Mind Creative Communications Digital A&N Media Marketing Services DMGT',
				authorurl : 'http://anmedia.co.uk/',
				infourl : 'http://anmedia.co.uk/',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('simpleimage', tinymce.plugins.SimpleImage);
})();