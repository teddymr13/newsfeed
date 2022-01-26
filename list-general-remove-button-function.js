function removeRowData(elem, url){
    let dataId = parseInt(elem.getAttribute('data-id-row'));
    if(dataId > 0){
        confirm("Are You sure You want to remove?", "Remove", "OK", function () {
            let err = '';

            const result = doRemoveRowData(url, dataId);
            if(result!==null) {
                switch (result) {
                    case '1' :
                        location.reload();
                        break;
                    case '2' :
                        err = "Fail to remove. Query Fail.";
                        break;
                    case '3' :
                        err = "Fail to remove. Invalid Id.";
                        break;
                    case '4' :
                        window.location = '';
                        break;
                    case '5' :
                        window.location = 'user/sign-out/';
                        break;
                    default:
                        err = "Unknown error.";
                        break;
                }
            }
            else err = "Ajax connection failed.";

            showAlert(err, 'danger', 'Error!');
        });
    }
}