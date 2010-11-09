$(function(){
    
    var lineAdditional = (document.selection) ? 2 : 1;
    
    function getCaretPos(element){

        if(element.selectionStart){
            
            return element.selectionStart;
            
        }else if(document.selection){
            
            var range = document.selection.createRange();
            var dRange = range.duplicate();
            dRange.moveToElementText(element);
            dRange.setEndPoint('EndToEnd', range);
            return dRange.text.length - range.text.length;
        }
        
        return 0;
    }

    $('textarea.multi_autocomplete').each(function(){
       
        var element = $(this);
        var src = element.attr('src') + '/';
        var lineHeight = parseInt(element.css('lineHeight'));
        var elementTop = element.offset().top;
        
        var terms = [];
        var currentLine = 0;
        var currentTerm = '';
        
        var menu;
        
        function extractTerms(){    
            terms = element.val().split('\n');
            updateElement();
        }
        
        function updateElement(){
            if(terms[terms.length - 1].replace(' ', '') != ''){
                terms.push('');
            }
            element.attr('rows', terms.length);
        }
        
        extractTerms();
        element.val(terms.join('\n'));
        
        element.keypress(function(){
            setTimeout(extractTerms, 0);
        });
        
        element.css('overflow', 'hidden');
        element.css('resize', 'none');
        
        $('#to-element p.description').append(
            ' <strong>Autocomplete:</strong> Begin typing a name to see a list of matching names.'
        );
        
        //*
        element.autocomplete({
            
            minLength: 2,
            
            source: function(request, callback){
                $.ajax({url: src + currentTerm, dataType: 'json', success: callback});
            },
            
            // Fires first
            search: function() {
                
                extractTerms();
                
                var curPos = getCaretPos(element.context);
                var lineEnd = currentLine = 0;
                
                for(var i = 0; i < terms.length; i++){
                    
                    lineEnd += terms[i].length + lineAdditional;
                    if(curPos < lineEnd){
                        currentLine = i;
                        break;
                    }
                }
                
                currentTerm = terms[currentLine];

                // custom minLength
                if (currentTerm.length < 2) {
                    return false;
                }
            },
            
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            
            select: function(event, ui) {

                terms.splice(currentLine, 1, ui.item.value);
                element.val(terms.join('\n'));
                
                //*
                if(document.selection){
                    updateElement();
                    var range = element.context.createTextRange();
                    range.move('character', element.val().length + terms.length);
                    range.select();
                }
                //*/
                
                return false;
            },
            
            open: function(event, ui){
                var left = menu.offset().left;
                menu.offset({top: elementTop + (currentLine + 1) * lineHeight, left: left});
            }

       });
       //*/
       
       menu = $('.ui-autocomplete');
       $('#wedge').append(menu);
        
    });

});