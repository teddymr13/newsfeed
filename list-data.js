function checkAll(){
    const elem = document.getElementsByName("check_row");
    let i;

    if(document.getElementById("check_all").checked){
        for(i = 0; i < elem.length; i++) elem[i].checked = true;
        toggleActionSelectedButton(1);
    }
    else{
        for(i = 0; i < elem.length; i++) elem[i].checked = false;
        toggleActionSelectedButton(0);
    }
}
function checkRow(){
    const elem = document.getElementsByName("check_row");
    let flag = 1;
    let flg_btn = 0;
    let i;

    for(i = 0; i < elem.length; i++){
        if(!elem[i].checked){
            flag = 0;
            break;
        }
    }

    for(i = 0; i < elem.length; i++){
        if(elem[i].checked){
            flg_btn = 1;
            break;
        }
    }

    if(flag === 1) document.getElementById("check_all").checked = true;
    else if(flag === 0) document.getElementById("check_all").checked = false;

    toggleActionSelectedButton(flg_btn);
}
function toggleActionSelectedButton(param){
    const elem_button_remove = document.getElementById('button_remove_selected');
    const elem_button_hide = document.getElementById('button_hide_selected');
    const elem_button_show = document.getElementById('button_show_selected');
    switch(param){
        case 0 :
            if(elem_button_remove) {
                elem_button_remove.classList.remove("btn-danger");
                elem_button_remove.className += " btn-secondary";
                elem_button_remove.disabled = true;
            }
            if(elem_button_hide) {
                elem_button_hide.classList.remove("btn-warning");
                elem_button_hide.className += " btn-secondary";
                elem_button_hide.disabled = true;
            }
            if(elem_button_show) {
                elem_button_show.classList.remove("btn-success");
                elem_button_show.className += " btn-secondary";
                elem_button_show.disabled = true;
            }
            break;
        case 1 :
            if(elem_button_remove) {
                elem_button_remove.classList.remove("btn-secondary");
                elem_button_remove.className += " btn-danger";
                elem_button_remove.disabled = false;
            }
            if(elem_button_hide) {
                elem_button_hide.classList.remove("btn-secondary");
                elem_button_hide.className += " btn-warning";
                elem_button_hide.disabled = false;
            }
            if(elem_button_show) {
                elem_button_show.classList.remove("btn-secondary");
                elem_button_show.className += " btn-success";
                elem_button_show.disabled = false;
            }
            break;
        default: break;
    }
}
function removeSelectedRowData(url){
    const elem = document.getElementsByName("check_row");
    let flg_btn = 0;
    let arr = [];
    let i;
    let j = 0;
    for(i = 0; i < elem.length; i++){
        if(elem[i].checked){
            arr[j] = elem[i].value;
            flg_btn = 1;
            j++;
        }
    }
    if(flg_btn === 1)
        confirm("Are You sure to remove selected row data?", "Remove Selected", "OK", function () {
            for(i = 0; i < j; i++) doRemoveRowData(url, arr[i]);
            location.reload();
        });
}
function doRemoveRowData(url, dataId) {
    let result = null;
    $.ajaxSetup({async: false});
    $.post(url, { data_id: dataId }, function (data) { result = data; }, 'text');
    return result;
}
function initTableCheckboxes(url){
    if(document.getElementById('button_remove_selected')) document.getElementById('button_remove_selected').addEventListener('click', function () { removeSelectedRowData(url); });
    if(document.getElementById('check_all')) document.getElementById('check_all').addEventListener('click', function () { checkAll(); });
    $('input[name="check_row"]').on('click', function () { checkRow(); });
    $('button[name="button_remove"]').on('click', function () { removeRowData(this, url); });
}