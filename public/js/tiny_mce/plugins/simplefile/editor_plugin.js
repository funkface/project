(function() {
    
    tinymce.PluginManager.requireLangPack('simplefile');

	tinymce.create('tinymce.plugins.SimpleFile', {
	    
		init : function(ed, url) {
	    
			this.editor = ed;

			ed.addCommand('mceSimpleFile', function() {
			    
			    var content, p = ed.dom.getParent(ed.selection.getNode(), "A");
			    
			    if(p != null){
			        ed.selection.select(p);
			        content = p.textContent;
			    }else{
			        content = ed.selection.getContent();
			    }

			    ed.windowManager.open({
                    url : ed.getParam("plugin_simplefile_pageurl", "/"),
                    width : parseInt(ed.getParam("plugin_simplefile_width", "550")),
                    height : parseInt(ed.getParam("plugin_simplefile_height", "600")),
                    resizable : true,
                    scrollbars : true,
                    popup_css : false
                }, {
                    linkTitle : content
                });
			    
			});

			ed.addButton('simplefile', {
			    title : 'Insert File',
			    cmd : 'mceSimpleFile',
			    image : url + '/images/insertfile.gif'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('simplefile', n.nodeName == 'A' && n.className.indexOf('simplefile') >= 0);
            });
			
		},

		getInfo : function() {
			return {
				longname : 'Simple File Upload',
				author : 'Martin Shopland Front of Mind Creative Communications Digital A&N Media Marketing Services DMGT',
				authorurl : 'http://anmedia.co.uk/',
				infourl : 'http://anmedia.co.uk/',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('simplefile', tinymce.plugins.SimpleFile);
})();