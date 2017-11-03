$(function () {
    var $formSendMail = $('#formSendMail')
        , $inputEmail = $('#inputEmail')
        , fncIsValidMail = function (email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
        , fncValidateMail = function (sEmail) {
            var bIsValidMail = fncIsValidMail(sEmail);
            $inputEmail[bIsValidMail?'removeClass':'addClass']('border-red');
            return bIsValidMail;
        }
    ;
    $formSendMail.on('submit', function (e) {
        e.preventDefault();
        var sEmail = $.trim($inputEmail.val());
        if(fncValidateMail(sEmail)){
            //location.href = App.baseUrl+'empleados/listar/email/' + encodeURIComponent(sEmail);
            $formSendMail[0].submit();
        }
    });
});
