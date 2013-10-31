// JavaScript Document
var FbAll = {
    facebookLogin: function () {
		var height = 300;
	    var width = 550;
	    var left = Number((screen.width/2)-(width/2));
	    var top = Number((screen.height/2)-(height/2));
	    var clientid = document.getElementById("client_id");
        var redirecturi = document.getElementById("redirect_uri");
 
        if (clientid.value == '') {
            alert("You have not configure facebook api settings.")
        } else {
            var openedwin = window.open('https://graph.facebook.com/oauth/authorize?client_id=' + clientid.value + '&redirect_uri=' + redirecturi.value + '&scope=email,user_birthday,user_hometown,user_location,user_work_history,user_website,publish_stream&display=popup', '', 'scrollbars=no, menubar=no, height='+height+', width='+width+', top='+top+', left='+left+', resizable=yes, toolbar=no, status=no');
			if (window.focus) {openedwin.focus()}
        }
    },
 
    parentRedirect: function (config) {
        var redirectto = document.getElementById("fball_login_form_uri");
        var form = document.createElement('form');
        form.id = 'fball-loginform';
        form.method = 'post';
        form.action = redirectto.value;
        form.innerHTML = '<input type="hidden" id="fball_redirect" name="fball_redirect" value="' + redirectto.value + '">';
 
        var key;
        for (key in config) {
			form.innerHTML += '<input type="hidden" id="' + key + '" name="' + key + '" value="' + config[key] + '">';
        }
 
        document.body.appendChild(form);
        form.submit();
    }
 }



