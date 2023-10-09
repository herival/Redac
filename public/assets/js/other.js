
let path, url
const domain = window.location.origin;
window.onload = () => {
    path = (document.getElementById('path'));
    url = domain + path.dataset.path + "?";
}
function submitPeriod() {
    let mois = document.getElementById('mois');
    let mois_select = mois.options[mois.selectedIndex].value;

    window.location.href = url + ('&mois=' + mois_select);
}

function onClickBtn(obj) {

    if ((obj.closest('div')).classList.contains("alert-success")) {
        (obj.closest('div')).classList.replace("alert-success", "alert-secondary");
    } else {

        (obj.closest('div')).classList.add("alert-secondary");
        (obj.closest('div')).classList.replace("alert-secondary", "alert-success");

    }
    const url = obj.href;
    fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
        .then(response => response.json())
        .catch(e => {
            console.log(e);
        });

}
