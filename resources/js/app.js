import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

//traitement js
$(document).ready(function(){
    $('#form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('data-action'),
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            processData: false,
            success:function(response){
                addCart(response.country, response.status, response.flag);
                $('#country').val('');
                $('.country').hide();
                $('.country[data-name="'+response.country+'"]').remove();
            },
            error: function(response){
                console.log(response);
            }
        });
    });

    function addCart(country, status, flag){
        var htmlResult = status+' kms';
        if(status == 'win')
            htmlResult = 'Victoire !';
        else if(status == 'border')
            htmlResult = 'Frontalier !'
        var html = '<tr class="line '+status+'"><td>'+flag+'</td><td>'+country+ '</td><td>'+htmlResult+'</td></tr>'
        $('#line-container').prepend(html)
    }

    $('#country').keyup(function(){
        let saisie = $("#country").val().toLowerCase();
        if(saisie.length>0){
            let nbElShow = 0;
            $('.country').each(function(ind, el){
                if($(el).text().toLowerCase().includes(saisie) && nbElShow < 6){
                    $(el).show();
                    nbElShow++;
                }
                else
                    $(el).hide();
            });  
        }
        else
            $('.country').hide();
    });

    $('.country').click(function(el){
        $('#country').val($(el.target).data('name'));
        $('.country').hide();
    })
});
