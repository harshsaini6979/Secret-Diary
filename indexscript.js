$('[data-toggle="tooltip"]').tooltip();
            
            $('#login').click(function(){
                $('#signuptab').toggle();
                $('#logintab').toggle();
            })
            $('#signup').click(function(){                
                $('#logintab').toggle();
                $('#signuptab').toggle();
            });
$('body').css('height',$(window).height() - 70);