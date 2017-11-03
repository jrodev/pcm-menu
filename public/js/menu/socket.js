var socket = io.connect(SOCKET_URL, {
        'forceNew': true
    })
    , $selNroMesa = $('select.sel-nro-mesa')
    , $btnPedirPlato = $('a.btn-pedir-plato')
;

// Elegir mesa funcionalidad
$selNroMesa.on('change', function () {

    var $panelPlato = $(this).parents('.panel-plato:eq(0)')
        , $btnPedir = $panelPlato.find('.btn-pedir-plato')
        , bNoMesa = ($.trim($(this).val())=='0')
    ;
    console.log(bNoMesa);
    $btnPedir[bNoMesa ? 'addClass' : 'removeClass']('disabled');
});

// Pedir Plato funcionalidad
$btnPedirPlato.on('click', function () {
    // Bolqueado para disabled
    if($(this).hasClass('disabled')){ return false; }
    // Else
    var $this = $(this)
        , $panelPlato = $this.parents('.panel-plato:eq(0)')
        , namePlato = $panelPlato.find('span.plato-name').text()
        , srcPlato = $panelPlato.find('img.plato-img').attr('src')
        , $selNroMesa = $panelPlato.find('select.sel-nro-mesa')
        , oMsgPlato = {
            cod: new Date().valueOf(),
            id: $this.data('platoid'),
            nombre: namePlato,
            src: srcPlato,
            mesa: $selNroMesa.val(),
            estado: 1 // 0:cancelado 1:pendiente, 2:rechazado
        }
    ;
    console.log(oMsgPlato);
    if(confirm('Desea pedir a la cocina el plato: ' + namePlato + '?')){
        socket.emit('nuevoplato', oMsgPlato);
    }
    $selNroMesa.val('0').trigger('change');
});

// PARA EL COCINERO
if ($('#cocinaListaPlatos').length) {

    var $cocinaListaPlatos = $('#cocinaListaPlatos')
        , getHtmlPlato = function (oPlato, bHide) {
            var sHide = (bHide?`hide`:``)
                , sDisabled = (oPlato.estado==2) ? 'disabled' : '';
            ;
            return `
                <div class="panel-plato columns state${oPlato.estado} ${sHide}">
                    <ul class="price">
                        <li class="header"><img class="plato-img" src="${oPlato.src}" width="100%" height="150px" /></li>
                        <li><span class='plato-name'>${oPlato.nombre}</span> - <b>Mesa #${oPlato.mesa}</b></li>
                        <li class="grey">
                            <a href="#"
                                data-platocod="${oPlato.cod}"
                                data-platoid="${oPlato.id}"
                                class="btn-servir-plato button ${sDisabled}">
                            servido!</a>
                        </li>
                        <li class="grey">
                            <a href="#"
                                data-platocod="${oPlato.cod}"
                                data-platoid="${oPlato.id}"
                                class="btn-cancelar-plato button ${sDisabled}">
                            cancelar!</a>
                        </li>
                    </ul>
                </div>
            `;
        }
        , setEventPlato = function ($panelPlato) {

            var $btnServido = $panelPlato.find('a.btn-servir-plato');

            // evento
            $btnServido.on('click', function () {
                var codPlato = parseInt($(this).data('platocod'))
                    , namePlato = $panelPlato.find('span.plato-name').text()
                ;
                if(confirm('Plato: ' + namePlato + ', esta listo ?')){
                    socket.emit('servirplato', {cod:codPlato, estado:2});
                    $btnServido.addClass('disabled');
                    $panelPlato.removeClass('state0').removeClass('state1').addClass('state2');
                }
            });
        }
    ;

    // Cargando Platos al Refresh de la pagina
    socket.on('listarplatos', function(aMsgPlatos) {
        console.log("listarplatos->aMsgPlatos:",aMsgPlatos);
        var sHtmlAllPlatos = aMsgPlatos.map(function(oPlato, index) {
            return getHtmlPlato(oPlato, false);
        }).join(" ");
        console.log("listarplatos->sHtmlAllPlatos:",sHtmlAllPlatos);
        $cocinaListaPlatos.html(sHtmlAllPlatos);
        $cocinaListaPlatos.find('.panel-plato').each(function(i, ele){
            $panelPlato = $(ele);
            setEventPlato($panelPlato);
        });
    });

    // llenando de cola de platos
    socket.on('colaplatos', function(oData) {
        console.log(oData);
        render(oData.platos, oData.lastPlato);
    });

    function render(aPlatos, oPlato) {
        console.log(aPlatos, oPlato);
        var sHtmlPlato = getHtmlPlato(oPlato, true)
            , $panelPlato = $(sHtmlPlato)
        ;

        setEventPlato($panelPlato);

        $cocinaListaPlatos.append($panelPlato);
        $panelPlato.show('slow');
    }

}
