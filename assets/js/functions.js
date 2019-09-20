// search form
$("#hn_nav_search_input").focusin(function() {
	$("#hn_nav_search_results").css("display", "block");
	$("#hn_nav_search_submit").addClass("opened");

	$("#hn_nav_notifications_dd").addClass("hide");
	$("#hn_nav_user_dd").addClass("hide");
});

$("#hn_nav_search_input").focusout(function() {
	if ($("#hn_nav_search_input").val() < 1) {
		$("#hn_nav_search_results").css("display", "none");
		$("#hn_nav_search_submit").removeClass("opened");

		$("#hn_nav_notifications_dd").addClass("hide");
		$("#hn_nav_user_dd").addClass("hide");
	}
});

// nav dropdowns
let hnnndt = false;	let hnudt = false;
let hnndo = false; 	let hnddo = false;

$("#hn_nav_notification-d-t").click(function() {
	if (hnnndt == false) {
		$("#hn_nav_notifications_dd").removeClass("hide");
		$("#hn_nav_notification-d-ol").removeClass("hide");

		$("#hn_nav_user_dd").addClass("hide");
		hnnndt = true;
	}else {
		$("#hn_nav_notifications_dd").addClass("hide");
		$("#hn_nav_notification-d-ol").addClass("hide");

		$("#hn_nav_user_dd").addClass("hide");
		hnnndt = false;
	}
});

$("#hn_nav_notification-d-ol").click(function() {
	if (hnndo = false) {
		$("#hn_nav_notifications_dd").removeClass("hide");
		$("#hn_nav_notification-d-ol").addClass("hide");

		$("#hn_nav_user_dd").addClass("hide");
		hnndo = true;
	}else {
		$("#hn_nav_notifications_dd").addClass("hide");
		$("#hn_nav_notification-d-ol").addClass("hide");

		$("#hn_nav_user_dd").addClass("hide");
		hnndo = false;
	}
});

$("#hn_nav_user-d-t").click(function() {
	if (hnudt == false) {
		$("#hn_nav_user_dd").removeClass("hide");
		$("#hn_nav_user-d-ol").removeClass("hide");

		$("#hn_nav_notifications_dd").addClass("hide");
		hnudt = true;
	}else {
		$("#hn_nav_user_dd").addClass("hide");
		$("#hn_nav_user-d-ol").addClass("hide");

		$("#hn_nav_notifications_dd").addClass("hide");
		hnudt = false;
	}
});

$("#hn_nav_user-d-ol").click(function() {
	if (hnddo = false) {
		$("#hn_nav_user_dd").removeClass("hide");
		$("#hn_nav_user-d-ol").addClass("hide");

		$("#hn_nav_user_dd").addClass("hide");
		hnddo = true;
	}else {
		$("#hn_nav_user_dd").addClass("hide");
		$("#hn_nav_user-d-ol").addClass("hide");

		$("#hn_nav_user_dd").addClass("hide");
		hnndo = false;
	}
});

// chat
var hn_chatter_users = document.getElementsByClassName("hn_chatter_user");
for (var i = 0; i < hn_chatter_users.length; i++) {
	hn_chatter_users[i].onclick = function() {
		this.classList.toggle('is-open');
		this.classList.toggle('active');
		var content = this.nextElementSibling;
		content.classList.toggle('is-open');
	}
}

// posts
var hn_post_d_t = document.getElementsByClassName("hd_post_hdrs-d-t");
for (var i = 0; i < hn_post_d_t.length; i++) {
	hn_post_d_t[i].onclick = function() {
		this.classList.toggle('opened');
		var content = this.nextElementSibling;
		content.classList.toggle('hide');
	}
}


// Autocomplete Search (added: 14 june 2019 9:40 am)
$(function() {
$('#hn_nav_search_form').submit(function(e) {
	e.preventDefault();
});

$('#hn_nav_search_input').keypress(function(e) {
	// let searchphrase = $("#hn_nav_search_input").val();
	$.ajax({
		url: "http://localhost/sfstrue/app/api/search.php?query="+$("#hn_nav_search_input").val(),
		type: 'POST',
		async: true,
		cache: false,
		error: function() {
			$('#hn_nsearch_result_list').html("Error");
		},
		success: function(data) {
			$('#hn_nsearch_result_list').html(' ');
			let rows = JSON.parse(data)
			$.each(rows, function(index) {
				$('#hn_nsearch_result_list').append('<a href="'+rows[index].UserPath+'" style="display: flex; align-items: center; border-bottom:1px solid #000; padding: 5px 10px; box-sizing: border-box;"><img src="'+rows[index].UserImg+'" alt="'+rows[index].UserName+'\'s profile picture" style="height: 35px; width: 35px; border-radius: 50%;" /> '+rows[index].UserName+'</a>')
			})
		}
	});
});
});

// left sidebar hashtags
$(function() {
	$.ajax({
		type: "GET",
		url: "http://localhost/sfstrue/app/api/tags.php?type=sidebar",
		processData: false,
		contentType: "application/json",
		data: '',
		success: function(data) {
			let tags = JSON.parse(data)
			$.each(tags, function(index) {
				$('.hn_plct_list').append('<li><a href="'+tags[index].TagPath+'">#'+tags[index].TagName+'</a></li>')
			})
		},
		error: function(r) {
			console.log("Something went wrong!");
		}
	})
})

// feed autoloading function
function scrollToAnchor(aid){
	try {
		var aTag = $(aid);
		$('html,body').animate({scrollTop: aTag.offset().top},'slow');
	} catch (error) {
		console.log(error)
	}
}

$(".hn_post_i_btn").click(function() {
	console.log('Liked a post with id = ')
})

// no refreshing
var popped = ('state' in window.history && window.history.state !== null), initialURL = location.href;

$(window).bind('popstate', function (event) {
	// Ignore inital popstate that some browsers fire on page load
	var initialPop = !popped && location.href == initialURL
	popped = true
	if (initialPop) return;
});

function isEmpty( el ){
   return !$.trim(el.html())
}

function changePageTitle(title) {
	$("#PageTitle").html(title);
}
