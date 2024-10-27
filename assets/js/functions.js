function copyToClipboardajaxtwitters(text1, url, book_name, id) {
	var alink = jQuery('.twitter_content').html();
	var newfb_window = window.open(alink);
	var timer = setInterval(function () {
		if (newfb_window.closed) {
			clearInterval(timer);
			jQuery.ajax({
				type: "POST",				
				url: my_ajax_obj.ajax_url,
				cache: false,
				data: {
					action: "aha_share",
					_ajax_nonce: my_ajax_obj.nonce,
					social: "twitter",
					method: "shocial",
					fb: +text1,
					book_name: book_name,
					id: id
				},
				success: function (data) {
					if (data == 1) {
						window.location = window.location.href;
					}
				}
			});
		}
	}, 1000);
}

function copyToClipboardajaxtwitterss(text1, url, book_name, id) {
	var alink = jQuery('.twitter_contents').text();
	var newfb_window = window.open(alink);
	var timer = setInterval(function () {
		if (newfb_window.closed) {
			clearInterval(timer);
			jQuery.ajax({				
				type: "POST",
				url: my_ajax_obj.ajax_url,
				cache: false,
				data: {
					action: "aha_share",
					_ajax_nonce: my_ajax_obj.nonce,
					social: "linkedin",
					method: "shocial",
					fb: +text1,
					book_name: book_name,
					id: id
				},
				success: function (data) {
					if (data == 1) {
						window.location = window.location.href;
					}
				}
			});
		}
	}, 1000);

}

function copyToClipboardajaxFBs(text1, text, url, book_name, id) {
	var res = text.replace("&quot;", '"');
	res = res.replace("&#64;", "@");
	res = res.replace("&quot;", '"');
	res = res.replace("&acute;", "'");
	var fb_post = window.prompt("Posting on Facebook: In order to post, please copy and paste the text.", res);
	if (fb_post != null) {
		var a = "https://www.facebook.com/sharer/sharer.php?s=100&app_id=261753500669481&sdk=joey&u=http%3A%2F%2Fwww.ahathat.com%2Fexternal%2Ftweetsshare%2F" + text1 + "&display=popup&ref=plugin&src=share_button&scrape=true";
		var newfb_window = window.open(a);
		var timer = setInterval(function () {
			if (newfb_window.closed) {
				clearInterval(timer);
				jQuery.ajax({					
					type: "POST",
					url: my_ajax_obj.ajax_url,
					cache: false,
					data: {
						action: "aha_share",
						_ajax_nonce: my_ajax_obj.nonce,
						social: "fb",
						method: "shocial",
						fb: +text1,
						book_name: book_name,
						id: id
					},
					success: function (data) {
						if (data == 1) {
							window.location = window.location.href;
						}
					}
				});
			}
		}, 1000);
		//end
	}
}


jQuery(".copyToClipboardajaxlinked").click(function () {
});

function random(bookid, url, id, name) {
	jQuery.ajax({		
		type: "POST",
		url: my_ajax_obj.ajax_url,
		data: {
			action: "aha_key",
			_ajax_nonce: my_ajax_obj.nonce,
			id: id,
			session_name: name
		},
		success: function (data) {
			var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
			jQuery(".ahamessagearea").html(result);
			var height = jQuery(".main_head").height();
			var line_height = jQuery(".main_head").css('line-height');
			line_height = parseFloat(line_height)
			var rows = height / line_height;
			Math.round(rows);
			if (Math.round(rows) == 1) {
				jQuery(".main_head").append("<br />");
			}
		}
	});
}

function next(tweet, url, id, name) {
	jQuery.ajax({		
		type: "POST",
		url: my_ajax_obj.ajax_url,
		data: {
			action: "next",
			_ajax_nonce: my_ajax_obj.nonce,
			tweets: tweet,
			id: id,
			session_name: name
		},
		success: function (data) {
			var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
			jQuery(".ahamessagearea").html(result);
			var height = jQuery(".main_head").height();
			var line_height = jQuery(".main_head").css('line-height');
			line_height = parseFloat(line_height)
			var rows = height / line_height;
			Math.round(rows);
			if (Math.round(rows) == 1) {
				jQuery(".main_head").append("<br />");
			}
		}
	});
}

function last(tweet, url, id, name) {
	jQuery.ajax({		
		type: "POST",
		url: my_ajax_obj.ajax_url,
		data: {
			action: "last",
			_ajax_nonce: my_ajax_obj.nonce,
			tweets: tweet,
			id: id,
			session_name: name
		},
		success: function (data) {
			var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
			jQuery(".ahamessagearea").html(result);
			var height = jQuery(".main_head").height();
			var line_height = jQuery(".main_head").css('line-height');
			line_height = parseFloat(line_height)
			var rows = height / line_height;
			Math.round(rows);
			if (Math.round(rows) == 1) {
				jQuery(".main_head").append("<br />");
			}
		}
	});
}

function active_fs() {
	jQuery("#active_f").val('0');
}

function active_ss() {
	jQuery("#active_s").val('0');
}

function login(url) {
	jQuery("#img_loadss").show();
	var email = jQuery('.login #email').val();
	var password = jQuery('.login #password').val();
	var process = jQuery('.login #process').val();
	jQuery.ajax({
		type: "POST",
		url: my_ajax_obj.ajax_url,
		data: {
			action: "aha_login",
			_ajax_nonce: my_ajax_obj.nonce,
			email: email,
			password: password,
			process: process
		},
		success: function (data) {
			jQuery("#img_load").hide();
			var data;
			if (data == 'error') {
				jQuery('#error').html('Invalid Email or password');
			} else {
				jQuery(".modas-box, .modas-overlay").fadeOut(500, function () {
					jQuery(".modas-overlay").remove();
					window.location.reload();
				});
			}
		}
	});
}

function signup(url) {
	jQuery("#img_loads").show();
	var email = jQuery('.signup #email').val();
	var name = jQuery('.signup #name').val();
	var password = jQuery('.signup #password').val();
	var cpassword = jQuery('.signup #cpassword').val();
	var process = jQuery('.signup #process').val();
	jQuery.ajax({
		type: "POST",
		url: my_ajax_obj.ajax_url,
		data: {
			action: "aha_login",
			_ajax_nonce: my_ajax_obj.nonce,
			email: email,
			name: name,
			password: password,
			cpassword: cpassword,
			process: process,
			accept: 'on'
		},
		success: function (data) {

			var data = jQuery.parseJSON(data);
			if (data['error']) {
				jQuery('.signup #error').html(data['error']);
			} else {
				var email = data['data']['email'];
				var password = data['data']['emails'];
				jQuery.ajax({
					type: "POST",
					url: my_ajax_obj.ajax_url,
					data: {
						action: "aha_login",
						email: email,
						password: password,
						process: "login"
					},
					success: function (data) {
						jQuery("#img_loads").hide();
						jQuery(".modas-box, .modas-overlay").fadeOut(500, function () {
							jQuery(".modas-overlay").remove();
						});
						window.location.reload();
					}
				});
				// window.location.reload();
			}
		}
	});
}

function logout(url) {
	jQuery.ajax({		
		type: "POST",
		url:my_ajax_obj.ajax_url,
		data: {
			action: "aha_login",
			_ajax_nonce: my_ajax_obj.nonce,
			process: "logout"
		},
		success: function (data) {
			window.location.reload();
		}
	});
}
jQuery(document).ready(function () {

	if (jQuery('input#AHAbook-0').is(':checked')) {
		jQuery("#img_loads").show();
		jQuery(".book_main_con").removeClass('row').addClass('hidden');
		jQuery(".search_form").css('display','none');
		jQuery(".book_main_con_all").removeClass('hidden').addClass('row');
		jQuery(".show_stop").removeClass('hidden');
		jQuery(".cats_books").removeClass('row').addClass('hidden')
		jQuery("#img_loads").show();
		jQuery.ajax({
			type: "POST",
			url: my_ajax_obj.ajax_url,
			data: {
				action: "aha_single_books",
				_ajax_nonce: my_ajax_obj.nonce,
				type: 'single',
				limit: jQuery('#limit_a').val(),
				id: jQuery('input#AHAbook-0').attr('url_id')
			},
			success: function (result) {
				jQuery("#img_loads").hide();
				//jQuery("#myInputbook").show(); 
				// var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
				jQuery(".book_main_con_all").html(result);
			}

		});
	}

	if (jQuery('input#library').is(':checked')) {
		jQuery("#img_loads").show();
		// jQuery("#myInputbook").hide();
		jQuery(".book_main_con").removeClass('hidden').addClass('row');
		jjQuery(".search_form").css('display','none');
		jQuery(".book_main_con_all").removeClass('row').addClass('hidden');
		jQuery(".show_stop").addClass('hidden');
		jQuery(".cats_books").removeClass('row').addClass('hidden');
		jQuery.ajax({			
			type: "POST",
			url: my_ajax_obj.ajax_url,
			data: {
				action: "aha_single_books",
				_ajax_nonce: my_ajax_obj.nonce,
				type: 'labs',
				id: jQuery('input#AHAbook-0').attr('url_id')
			},
			success: function (result) {
				jQuery("#img_loads").hide();
				jQuery("#myInputLIB").show();
				// var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
				jQuery(".book_main_con").html(result);
			}

		});
	}

	if (jQuery('input#Categories').is(':checked')) {
		jQuery(".search_form").css('display','block');
		jQuery(".book_main_con").removeClass('row').addClass('hidden');
		jQuery(".book_main_con_all").removeClass('row').addClass('hidden');
		jQuery(".show_stop").addClass('hidden');
		//jQuery(".cats_books").css('display','block');
		//jQuery("#myInputbook").hide();
		if (jQuery('input.pcatchecks').is(':checked')) {
			jQuery(".cats_books").removeClass('hidden').addClass('row')
			jQuery('.pcatchecks:checkbox:checked').length;
			var checkedValues = jQuery('.pcatchecks:checkbox:checked').map(function () {
				return this.value;
			}).get();
			var cur_ul_level = jQuery(this).parent('li').attr('id');

			if (checkedValues != '') {
				jQuery(this).parent('li').children('ul.' + cur_ul_level).toggle();
				jQuery.ajax({					
					type: "POST",
					url: my_ajax_obj.ajax_url,
					data: {
						action: "aha_single_books",
						_ajax_nonce: my_ajax_obj.nonce,
						type: 'cats',
						cat_id: checkedValues + ',',
						id: jQuery('input#AHAbook-0').attr('url_id')
					},
					success: function (result) {
						var results = result + "<hr><div class='col-md-12 col-sm-12'><span id='prepage_single_c' class='button button-primary'>PRE</span> &nbsp;&nbsp;&nbsp;<span id='nextpage_single_c' class='button button-primary'>NEXT</span></div>"
						jQuery(".cats_books").html(results);
						jQuery(".cats_books .sbook").hide();
						jQuery(".cats_books .sbook").slice(0, 18).show();
						jQuery("#prepage_single_c").hide();
					}
				});
			} else {
				jQuery(".cats_books").html('');
			}

		}

	}

	jQuery("#Categories").click(function () {
		if (jQuery('input#Categories').is(':checked')) {
			jQuery(".search_form").css('display','block');
			jQuery(".book_main_con").removeClass('row').addClass('hidden');
			jQuery(".book_main_con_all").removeClass('row').addClass('hidden');
			jQuery(".show_stop").addClass('hidden');
			jQuery(".cats_books").removeClass('hidden').addClass('row');
			//jQuery("#myInputbook").hide();			
		}
	});

	jQuery("#library").click(function () {
		jQuery("#img_loads").show();
		//jQuery("#myInputbook").hide();
		if (jQuery('input#library').is(':checked')) {
			jQuery(".book_main_con").removeClass('hidden').addClass('row')
			jQuery(".cats_books").removeClass('row').addClass('hidden');
			jQuery(".search_form").css('display','none');
			jQuery(".book_main_con_all").removeClass('row').addClass('hidden');
			jQuery(".show_stop").addClass('hidden');

			jQuery.ajax({
				type: "POST",
				url: my_ajax_obj.ajax_url,
				data: {
					action: "aha_single_books",
					_ajax_nonce: my_ajax_obj.nonce,
					type: 'labs',
					id: jQuery('input#AHAbook-0').attr('url_id')
				},
				success: function (result) {
					jQuery("#img_loads").hide();
					jQuery("#myInputLIB").show();

					// var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
					jQuery(".book_main_con").html(result);
				}
			});
		}
	});


	jQuery("#AHAbook-0").click(function () {
		if (jQuery('input#AHAbook-0').is(':checked')) {
			jQuery("#img_loads").show();
			jQuery(".search_form").css('display','none');
			jQuery(".cats_books").removeClass('row').addClass('hidden');
			jQuery(".book_main_con").removeClass('row').addClass('hidden');
			jQuery(".book_main_con_all").removeClass('hidden').addClass('row');
			jQuery(".show_stop").removeClass('hidden');
			jQuery.ajax({
				
				type: "POST",
				url: my_ajax_obj.ajax_url,
				data: {
					action: "aha_single_books",
					_ajax_nonce: my_ajax_obj.nonce,
					type: 'single',
					limit: jQuery('#limit_a').val(),
					id: jQuery('input#AHAbook-0').attr('url_id')
				},
				success: function (result) {
					jQuery("#img_loads").hide();
					jQuery(".book_main_con_all").html(result);
				}
			});
		}
	});


	jQuery(".pcatchecks").click(function () {
		var curid = jQuery(this).val();
		var subul = jQuery(this).parent('#' + curid).children('#childul_' + curid);
		var checkallinner = 'remove';
		if (jQuery(this).is(':checked')) {
			checkallinner = 'check';
		}
		if (subul.length > 0) {
			var cur_class = subul.attr('class');
			var cur_class_arr = cur_class.split('_');
			var level = cur_class_arr[1];
			check_alls(level, '', checkallinner, curid);
		}

		jQuery(".cats_books").removeClass('hidden').addClass('row');
		jQuery("#myInputcat").show();
		jQuery('.pcatchecks:checkbox:checked').length;
		var checkedValues = jQuery('.pcatchecks:checkbox:checked').map(function () {
			return this.value;
		}).get();
		var cur_ul_level = jQuery(this).parent('li').attr('id');

		if (checkedValues != '') {
			jQuery(this).parent('li').children('ul.' + cur_ul_level).toggle();
			jQuery.ajax({
				type: "POST",
				url: my_ajax_obj.ajax_url,
				data: {
					action: "aha_single_books",
					_ajax_nonce: my_ajax_obj.nonce,
					type: 'cats',
					cat_id: checkedValues + ',',
					id: jQuery('input#AHAbook-0').attr('url_id')
				},
				success: function (result) {
					// var result = jQuery('<div />').append(data).find('.ahamessagearea').html();
					var results = result + "<hr><div class='col-md-12 col-sm-12'><span id='prepage_single_c' class='button button-primary'>PRE</span> &nbsp;&nbsp;&nbsp;<span id='nextpage_single_c' class='button button-primary'>NEXT</span></div>"
					jQuery(".cats_books").html(results);
					jQuery(".cats_books .sbook").hide();
					jQuery(".cats_books .sbook").slice(0, 18).show();
					jQuery("#prepage_single_c").hide();
				}
			});
		} else {
			jQuery(".cats_books").html('');
		}
	});
	if (jQuery('input.pcatchecks').is(':checked')) {
		jQuery(".cats_books").show();
		jQuery('.pcatchecks:checkbox:checked').length;
		var checkedValues = jQuery('.pcatchecks:checkbox:checked').map(function () {
			return this.value;
		}).get();
		var cur_ul_level = jQuery(this).parent('li').attr('id');

		if (checkedValues != '') {
			jQuery(this).parent('li').children('ul.' + cur_ul_level).toggle();
			jQuery.ajax({
				type: "POST",
				url: my_ajax_obj.ajax_url,
				data: {
					action: "aha_single_books",
					_ajax_nonce: my_ajax_obj.nonce,
					type: 'cats',
					cat_id: checkedValues + ',',
					id: jQuery('input#AHAbook-0').attr('url_id')
				},
				success: function (result) {
					var results = result + "<hr><div class='col-md-12 col-sm-12'><span id='prepage_single_c' class='button button-primary'>PRE</span> &nbsp;&nbsp;&nbsp;<span id='nextpage_single_c' class='button button-primary'>NEXT</span></div>"
					jQuery(".cats_books").html(results);
					jQuery(".cats_books .sbook").hide();
					jQuery(".cats_books .sbook").slice(0, 18).show();
					jQuery("#prepage_single_c").hide();
				}
			});
		} else {
			jQuery(".cats_books").html('');
		}
	}

	jQuery(".catcheck").click(function () {

		var cur_ul_level = jQuery(this).attr('id');
		jQuery(this).closest('li').children('ul.' + cur_ul_level).toggle();
		jQuery('ul.'+cur_ul_level).toggle();
	});

	jQuery(".catcheck").click(function(){
		var cur_ul_level = jQuery(this).attr('id') ; 
		jQuery(this).parent('li').children('ul.'+cur_ul_level).toggle();				
	});

	jQuery(".pcatchecks").click(function(){
		var cur_ul_level = jQuery(this).attr('id') ; 
		jQuery(this).parent('li').children('ul.'+cur_ul_level).toggle();				
	});
	
	function check_alls(level, childul, type, ulid) {
		var cat_str = '';
		var sul = '';

		if (ulid != '') {
			sul = "ul#childul_" + ulid + " li.level_li_" + level;
		} else {
			if (childul == '') {
				sul = "ul.level_" + level + " li.level_li_" + level;
			} else {
				sul = childul + ' li.level_li_' + level;
			}
		}
		jQuery(sul).each(function () {
			var cid = jQuery(this).attr('id');
			if (jQuery(this).children('input.check_' + cid + '_level_' + level).is(':checked')) {
				if (type == 'check') {
					jQuery(this).children('input.check_' + cid + '_level_' + level).prop('checked', true);
				} else {
					jQuery(this).children('input.check_' + cid + '_level_' + level).prop('checked', false);
				}
			} else {
				if (type == 'check') {
					jQuery(this).children('input.check_' + cid + '_level_' + level).prop('checked', true);
				} else {
					jQuery(this).children('input.check_' + cid + '_level_' + level).prop('checked', false);
				}
			}
			var next_level = '';
			next_level = parseInt(level) + 1;
			if (jQuery(this).children('ul#childul_' + cid).hasClass('level_' + next_level)) {
				check_alls(next_level, 'ul#childul_' + cid, type, '');
			}
		});
	}

	jQuery("body").on("keyup", "#myInputLIB", function (e) {
		var str = jQuery("#myInputLIB").val();
		jQuery(".book_main_con .book_con").each(function (index) {
			if (jQuery(this).find("p:first")) {
				if (!jQuery(this).find("p:first").text().match(new RegExp(str, "i"))) {
					jQuery(this).fadeOut("fast");
				} else {
					jQuery(this).fadeIn("slow");
				}
			}
		});
	});

	jQuery("body").on("keyup", "#myInputcat", function (e) {
		var str = jQuery("#myInputcat").val();
		jQuery(".cats_books .sbook").each(function (index) {
			if (jQuery(this).find(".stestarea a")) {
				if (!jQuery(this).find(".stestarea a").text().match(new RegExp(str, "i"))) {
					jQuery(this).fadeOut("fast");
				} else {
					jQuery(this).fadeIn("slow");
				}
			}
		});
	});

	jQuery("body").on("keyup", "#myInputbook", function (e) {
		var str = jQuery("#myInputbook").val();
		jQuery(".book_main_con_all .book_con").each(function (index) {
			if (jQuery(this).find("p:first").text()) {
				if (!jQuery(this).find("p:first").text().match(new RegExp(str, "i"))) {
					jQuery(this).fadeOut("fast");
				} else {
					jQuery(this).fadeIn("slow");
				}
			}
		});
	});

	jQuery("body").on("click", "#nextpage_single", function (e) {
		var limit = jQuery("#limit_a").val();
		jQuery("#limit_a").val(Number(limit) + 24);
		//jQuery("#limit_a").val(1000);
		jQuery("#AHAbook-0").click();
		jQuery("#img_loadss").show();
	});

	jQuery("body").on("click", "#prepage_single", function (e) {
		var limit = jQuery("#limit_a").val();
		if (Number(limit) > 0) {
			jQuery("#limit_a").val(Number(limit) - 24);
			//jQuery("#limit_a").val(27);
			jQuery("#AHAbook-0").click();
			jQuery("#img_loadss").show();
		}
	});

	//////////Category next pre button///////////////////
	jQuery("body").on("click", "#nextpage_single_c", function (e) {

		jQuery(".cats_books .sbook").hide();
		var limit = Number(jQuery("#limit_c").val()) + 18;
		jQuery(".sbook").slice(limit, Number(limit) + 18).show();
		jQuery("#limit_c").val(Number(limit));
		var lim = Number(jQuery("#limit_c").val());
		if (lim == 0 && lim == '') {
			jQuery("#prepage_single_c").hide();
		} else {
			jQuery("#prepage_single_c").show();
		}
		var len = Number(jQuery(".cats_books .sbook").length);
		if ((limit + 18) >= len) {
			jQuery("#nextpage_single_c").hide();
		} else {
			jQuery("#nextpage_single_c").show();
		}
	});

	jQuery("body").on("click", "#prepage_single_c", function (e) {
		jQuery(".cats_books .sbook").hide();
		var limit = Number(jQuery("#limit_c").val()) - 18;
		jQuery(".sbook").slice(limit, Number(limit) + 18).show();
		jQuery("#limit_c").val(Number(limit));
		var lim = Number(jQuery("#limit_c").val());
		if (lim == 0 && lim == '') {
			jQuery("#prepage_single_c").hide();
		} else {
			jQuery("#prepage_single_c").show();
		}
		var len = Number(jQuery(".cats_books .sbook").length);
		var limits = Number(jQuery("#limit_c").val()) + 18;
		if ((limits) >= len) {
			jQuery("#nextpage_single_c").hide();
		} else {
			jQuery("#nextpage_single_c").show();
		}
	});
});