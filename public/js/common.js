// ajax通信をする
function ajax(type,actionUrl, param, successCallBack, errorCallBack) {

    //* ajax
    $.ajax({
        type: type,
        dataType: 'json',
        url: actionUrl,
        data: param
    }).done(function (data, textStatus, xhr) {
        //* success
        if (data.status == "success") {
            return successCallBack(data);
        } else if (data.status == "failure"){
            return errorCallBack(data);
        } else {
            return errorCallBack(data);
        }

    }).fail(function(xhr, stat, e){
        //*  error 
        var reloadFlag = confirm('通信エラーが発生しました\n画面を再読み込みして下さい。\n再読み込みしますか？');
        if(reloadFlag == true){
            location.reload();
        }
        //console.log(xhr);
        return false;
    }).always(function(data){
        // --
        console.log(data);
    });

}
// alert表示用HTMLを作成する。
function getAlertDom(type,message){
    // メッセージDOM作成
    var alertDom = $('<div></div>',{
        class : 'alert alert-'+ type +' mt-2',
        role: 'alert'
    }).text(message).append(
        $('<button></button>',{
            type:'button',
            class:'close',
            'data-dismiss':'alert',
            'alert-label':'close'
        }
    ).append(
        $('<span></span>',{
            'aria-hidden':'true'
        }
    ).text('×')));
    return alertDom;
}