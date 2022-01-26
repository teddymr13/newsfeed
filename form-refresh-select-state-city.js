function refreshSelectProvince(){
    const iso_country = document.getElementById('country').value;
    const select_province = document.getElementById('province');
    const select_city = document.getElementById('city');
    while(select_province.firstChild) select_province.removeChild(select_province.firstChild);
    while(select_city.firstChild) select_city.removeChild(select_city.firstChild);
    select_province.removeEventListener('change', refreshSelectCity);
    select_province.disabled = true;
    select_city.disabled = true;
    if(iso_country!='') {
        $.post('ajax/newsfeed/ajax_refresh_select_province.php', {iso_country: iso_country}, function (data) {
            const obj = JSON.parse(data);
            let option;

            if(obj.length > 0){
                option = document.createElement('option');
                option.value = "";
                select_province.appendChild(option);
                select_province.disabled = false;
                select_province.addEventListener('change', refreshSelectCity);
            }

            for (let i = 0; i < obj.length; i++) {
                option = document.createElement('option');
                option.value = obj[i].state_code;
                option.innerText = obj[i].state;
                select_province.appendChild(option);
            }
        }, 'text');
    }
}
function refreshSelectCity(){
    const iso_country = document.getElementById('country').value;
    const state_code = document.getElementById('province').value;
    const select_city = document.getElementById('city');
    while(select_city.firstChild) select_city.removeChild(select_city.firstChild);
    select_city.disabled = true;
    if(iso_country!='' && state_code!='') {
        $.post('ajax/newsfeed/ajax_refresh_select_city.php', {iso_country: iso_country, state_code: state_code}, function (data) {
            const obj = JSON.parse(data);
            let option;

            if(obj.length > 0){
                option = document.createElement('option');
                option.value = "";
                select_city.appendChild(option);
                select_city.disabled = false;
            }

            for (let i = 0; i < obj.length; i++) {
                option = document.createElement('option');
                option.value = obj[i].id;
                option.innerText = obj[i].city;
                select_city.appendChild(option);
            }
        }, 'text');
    }
}
(function() {
    const select_country = document.getElementById('country');
    const select_province = document.getElementById('province');
    select_country.addEventListener('change', refreshSelectProvince);
    if(select_country.value != '' && select_province.disabled == false) select_province.addEventListener('change', refreshSelectCity);
})();