tinyMCEPopup.onInit.add(function() {
    
    try{
        tinyMCEPopup.editor.execCommand('mceInsertContent', false, document.getElementsByTagName('body')[0].innerHTML);
    }catch(e){
        // adding links inside headings seems to cause an error in IE when trying to get bookmark for adding undo state, why?
        window.close();
    }
    
    if (tinymce.isWebKit){
        // do something here to make safari show the image after insert
    }
    
    tinyMCEPopup.close();
    
});