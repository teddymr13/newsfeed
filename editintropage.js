function checkSubmit(){
    const hid_id = document.getElementById('hid_id').value;
    if(hid_id == '' || !validateNumber(hid_id)) location.reload();

    document.getElementById('form_edit_intro_page').action = '';
    return true;
}

(function() {
    watchFormChange('form_edit_intro_page');
    addFormSubmitListener('form_edit_intro_page');
})();