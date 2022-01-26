function checkSubmit(){
    let err = '';
    let flagerr = 0;

    const title = document.getElementById('title').value;
    if(title == ''){
        err = err + '- Please Enter Title!<br/>';
        flagerr = 1;
    }
    const short_description = document.getElementById('short_description').value;
    if(short_description == ''){
        err = err + '- Please Enter Meta Tag Description / Short Description!<br/>';
        flagerr = 1;
    }
    const keywords = document.getElementById('keywords').value;
    if(keywords == ''){
        err = err + '- Please Enter Meta Tag Keywords!<br/>';
        flagerr = 1;
    }
    const status = document.getElementsByName('status');
    let isChecked = false;
    for(let i=0; i<status.length; i++){
        if(status[i].checked){
            isChecked = true;
            break;
        }
    }
    if(!isChecked){
        err = err + '- Please Select Status!<br/>';
        flagerr = 1;
    }
    const country = document.getElementById('country').value;
    if(country == ''){
        err = err + '- Please Choose Country!<br/>';
        if(flagerr===0) flagerr = 2;
    }
    const province = document.getElementById('province').value;
    if(province == ''){
        err = err + '- Please Choose Province!<br/>';
        if(flagerr===0) flagerr = 2;
    }
    const city = document.getElementById('city').value;
    if(city == ''){
        err = err + '- Please Choose City!<br/>';
        if(flagerr===0) flagerr = 2;
    }
    const type = document.getElementById('type').value;
    if(type == ''){
        err = err + '- Please Choose Type!<br/>';
        if(flagerr===0) flagerr = 3;
    }
    const category = document.getElementById('category').value;
    if(category == ''){
        err = err + '- Please Choose Category!<br/>';
        if(flagerr===0) flagerr = 3;
    }
    const thumbnail = document.getElementById('thumbnail').value;
    if(thumbnail == ''){
        err = err + '- Please Enter Picture Thumbnail!<br/>';
        if(flagerr===0) flagerr = 4;
    }
    else{
        if(thumbnail!==document.getElementById('preview_thumbnail').src){
            err = err + '- Invalid URL for Picture Thumbnail!<br/>';
            if(flagerr===0) flagerr = 4;
        }
    }

    if(flagerr > 0){
        focusHasErrorFormTab(flagerr);
        showAlert(err, 'danger', 'Incomplete!');
        return false;
    }
    else if(flagerr === 0){
        document.getElementById('form_article').action = '';
        return true;
    }
}
(function() {
    watchFormChange('form_article', function () { refreshPreviewPicture("preview_thumbnail", "thumbnail"); });
    addFormSubmitListener('form_article');
    document.getElementById('short_description').addEventListener('keypress', function () { keyLimitTextareaNoNL('short_description', 160); });
    document.getElementById('thumbnail').addEventListener('keyup', function(){
        refreshPreviewPicture("preview_thumbnail", "thumbnail");
    });
})();