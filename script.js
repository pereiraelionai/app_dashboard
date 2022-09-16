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

    //ajax
    $('#competencia').change(function (e) {
        let competencia = $(e.target).val()
        //console.log(competencia)
        //ajax = metodo, url, dados, sucesso(o que fazer), erro(o que fazer)
        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`, //x-www-form-urlencode
            dataType: 'json',
            success: function (dados) {
                $('#num_vendas').html(dados.numero_vendas)
                $('#total_vendas').html(dados.total_vendas)
                console.log(dados.numero_vendas, dados.total_vendas)
            },
            error: erro => { console.log(erro) }
        })
    })
})