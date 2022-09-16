$(document).ready(() => {
    $('#documentacao').click(function () {
        //$('#pagina').load('documentacao.html')
        /*
        $.get('documentacao.html', data => {
            $('#pagina').html(data)
        })
        */

        $.post('documentacao.html', data => {
            $('#pagina').html(data)
        })
    })

    $('#suporte').click(function () {
        //$('#pagina').load('suporte.html')
        /*
        $.get('suporte.html', data => {
            $('#pagina').html(data)
        })
        */

        $.post('suporte.html', data => {
            $('#pagina').html(data)
        })
    })
})