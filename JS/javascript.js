function subBTN(button, status) {
    if (status == true) {
        button.style.borderTop = "solid 0";
        button.style.borderBottom = "solid 2px";
        button.style.color = "rgb(220,220,220)";
    }
    else {
        button.style.borderTop = "solid 2px";
        button.style.borderBottom = "solid 0";
        button.style.color = "rgb(225,225,225)";
    }
}

function subMenu() {
    var checkBox = document.getElementById("sub-menu-check");
    var subMenuTop = document.getElementsByTagName("ul");
    for (i=0;i<=subMenuTop.length;i++) {
        if (subMenuTop[i].className == "sub-menu"){
            var subMenuTop = subMenuTop[i];
            break;
        }
    }
    if (checkBox.checked == true) {
        subMenuTop.style.display = "block";
    }
    else {
        subMenuTop.style.display = "none";
    }
}

function subMenuManager(buttonId) {
    var checkBox = document.getElementById("sub-menu-check");
    var button = document.getElementById(buttonId);
    if (checkBox.checked == true){
        checkBox.checked = false;
        subBTN(button, false);
        subMenu();
    }
    else {
        checkBox.checked = true;
        subBTN(button, true);
        subMenu();
    }
}