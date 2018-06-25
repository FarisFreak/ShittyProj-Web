/*!
 * Start Bootstrap - SB Admin 2 v3.3.7+1 (http://startbootstrap.com/template-overviews/sb-admin-2)
 * Copyright 2013-2016 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap/blob/gh-pages/LICENSE)
 */
$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
});

// CUSTOM
function pagenull(){
	$("#body-pnl").html('<div class="col-lg-8"><div class="panel panel-default"><div class="panel-heading"> <i class="fa fa-th-list fa-fw"></i> Main</div><div class="panel-body" id="pnlBody"><div class="progress"> <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:100%"> </div> </div></div></div></div><div class="col-lg-4"><div class="panel panel-default"><div class="panel-heading"> <i class="fa fa-tasks fa-fw"></i> Result<div class="pull-right"><div class="btn-group"> <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> </button><ul class="dropdown-menu pull-right" role="menu"><li><a href="#" id="res-copy" onclick="return false;">Copy</a></li><li><a href="#" id="res-clear" onclick="return false;">Clear</a></li></ul></div></div></div><div class="panel-body"><textarea class="form-control" id="result" readonly="true" rows="10"></textarea></div></div></div>');
}

setInterval(updateInfo, 1000);


function updateInfo() {
	//Balance
	$.ajax({
		url		: './pages/update-balance.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(res){
			$("#balance").html(res);
		},
	});
	//HDSN
	$.ajax({
		url		: './pages/update-hdsn.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(res){
			$("#registeredhdsn").html(res);
		},
	});
	//Point
	$.ajax({
		url		: './pages/update-point.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(res){
			$("#points").html(res);
		},
	});
	//Name
	$.ajax({
		url		: './pages/update-name.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(res){
			$("#usernamex").html(res);
		},
	});
	//News
	$.ajax({
		url		: './pages/update-news.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(res){
			$("#news-body").html(res);
		},
	});
}