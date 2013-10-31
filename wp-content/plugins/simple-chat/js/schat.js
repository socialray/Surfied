/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
chat_channel_status=new Array();//global object used for channel status on current page
/**
 * ChatSettings Object helps in mnipulating friend list and settings window/action
 */


/**
 * This object handles following
 * -Updates the online users count
 * -Updates Online Users List
 * -Initializes timers for polling
 *  -
 *  */
var ChatSettings = {
	
	first_time_check: false,
	update_online_count: function(){
		jQuery.post(ajaxurl, {
				action: 'get_online_users_count',
				'cookie': encodeURIComponent(document.cookie)
			},
			function(ret){
				var oc=jQuery("div#chat_buddylist").find("span.online_count").get(0);
				jQuery(oc).html(ret);
				
				ChatSettings.update_all_windows_status();
				
			})//end of post
    },
    
update_all_windows_status: function( ret ){
	
	// update chat status
	// get all chats
	var chats = jQuery('#schatbar .chat_tab');

	for(i=0; i<chats.length; i++) {
		// if it is a chat window:
		if( chat_id = ChatSettings.get_id(chats[i]) ) {
			// get user id
			chat_with_id = jQuery(chats[i]).find("input.chatting_with_user").attr('value');
			
			// get user status
			status = ChatSettings.get_user_status( chat_with_id );
			
			jQuery(chats[i]).removeClass('on off afk busy').addClass(status);
			
			//alert(status);
		}
			//alert(chat_id)
	}
				
},
get_user_status: function( user_id ){
	var user = jQuery('#chat_with_'+user_id);
	if( user.hasClass('off') )
		return 'off';
	else if( user.hasClass('on') )
		return 'on';
	else if( user.hasClass('afk') )
		return 'afk';
	else if( user.hasClass('busy') )
		return 'busy';
},
update_online_list: function(){
	ChatHelper.reset_intervals();
	ChatSettings.update_online_users_list({action: 'update_online_users_list', 'cookie': encodeURIComponent(document.cookie),output:'html',value: 0, fetch: 1});
	//ChatSettings.update_online_count();
	ChatSettings.update_all_windows_status();
},

update_online_users_list: function(obj){
	jQuery.post(ajaxurl, obj, function(ret){
		jQuery("div#chat_buddylist .win_content").html(ret);
		//alert(1);
	});//end of post
},

update_messages: function(ret, at) {
	
	var channels = JSON.parse(ret);

	// update users online counter
	jQuery("div#chat_buddylist span.online_count").html(channels.users_online);
	
	// return if theres no chat windows opened
	if( !channels.length )
		return;
	
	var last_fetch_time=channels[0].fetch_time;
	
	//var messages=channels.messages;
	//var channel_user_status=data.status;

	// alert(channel_user_status.toString());

	ChatSettings.update_fetch_time(last_fetch_time);//update last fetch time
	
	//alert(channels[0].userdata)
	
	// no new messages
	for( var i=0; i< channels.length; i++ ) {
		var channel  = channels[i];
		var messages = channel.messages;
		var chat_win = jQuery("#chat_channel_"+channel.channel_id);//refrence to chat win object
		
		if ( !ChatWindow.exists(chat_win)) {
			 chat_win=ChatWindow.create( 
				channel.userdata.name,
				channel.userdata.thumbnail, // 'thumbnail'
				channel.userdata.id,
				channel.channel_id,
				channel.userdata.status
			);
			
			// if "schat_notify_file" is defined
			if( schat_notify_file )
				chat_play_notification();
		} 
		else // update chat status
			jQuery(chat_win).removeClass('on off afk busy').addClass(channel.userdata.status);

		//ChatWindow.maximize(chat_win);	//may be the window may be hidden/closed/minimized
		//ChatWindow.update_message( chat_win, channel.userdata.id, channel.userdata.name, 'teste!' );
		//ChatWindow.update_new_message_count( chat_win );
		
		// get the scroll height before
		var height = jQuery(".win_body", chat_win).prop('scrollHeight');
		
		//update channel messagesuser status
		for( var k=0; k<messages.length; k++ ) {
			ChatWindow.update_message( chat_win, messages[k].id, messages[k].name, messages[k].message, at );
			//if(channel_user_status[k].status!='open'||channel_user_status[k].is_online!=1)
			//ChatWindow.update_other_user_status(channel_user_status[k].channel_id,channel_user_status[k].status,channel_user_status[k].is_online,channel_user_status[k].user_status);
		}
		
		// if infinty scroll 
		if( at=='top' && messages.length>0 ) {
			// get the scroll height after
			var height2 = jQuery(".win_body", chat_win).prop('scrollHeight');
			
			// scroll to correctly point
			jQuery(".win_body", chat_win).scrollTo( height2-height, {offset:-125, easing:'easeout'} );
		}
	}
},

infine_scroll: function( channel_id, last_message_id ){
	jQuery.post(ajaxurl, {
			'action': "chat_check_updates",
			'channel_id': channel_id,
			'last_message_id': last_message_id,
			'cookie': encodeURIComponent(document.cookie)
		},

		function(ret){
			ChatSettings.update_messages(ret, 'top');
		});//end of post

},//end check_chat_init

check_chat_init: function(){

	jQuery.post(ajaxurl, {
			'action': "chat_check_updates",
			'fetch_time':ChatSettings.get_fetch_time(),
			'cookie': encodeURIComponent(document.cookie)
		},

		function(ret){
			ChatSettings.update_messages(ret);
		});//end of post

},//end check_chat_init

// get user id
get_id: function(elem){
    if(jQuery(elem).attr("id"))
		return parseInt( jQuery(elem).attr("id").split("_").pop() );//return the id as the last thing after _
},

get_name:function(friend){
     var f=jQuery(friend);
     var temp=f.find(".friend_list_item_name").get(0);

     return jQuery(temp).text();//return the name
},

get_avatar_src:function(friend){
    var avatar=jQuery(friend).find("img");
    
    if(avatar)
		return avatar.attr('src');//return the name
},

get_friend_id:function(elem){
    var friend=jQuery(elem);

    return this.get_id(friend);
},

update_fetch_time:function(time){
    //store time
   jQuery("#chat_buddylist #fetch_time").val(time);
},

get_fetch_time:function (){
	if( !ChatSettings.first_time_check ) {
		ChatSettings.first_time_check = true;
		return;
	}
		
    return jQuery("#chat_buddylist #fetch_time").val();
}

}

//for polling
var ChatHelper={
    chat_count_interval_id:0,
    chat_interval_id:0,
    chat_offline :0,
    
    clear_intervals:function(){
            clearInterval(this.chat_count_interval_id);
            clearInterval(this.chat_interval_id);

            },

    set_intervals:function(){
            //this.chat_count_interval_id = setInterval("ChatSettings.update_online_count()", 10000);//10 sec
            this.chat_count_interval_id = setInterval(
				"ChatSettings.update_online_users_list({action: 'update_online_users_list', 'cookie': encodeURIComponent(document.cookie),output:'html',value: 0, fetch: 1});"
				, 10000);//10 sec
            this.chat_interval_id = setInterval("ChatSettings.check_chat_init()", 3000);//3 sec
            ChatSettings.check_chat_init();
            //intvalNewMsgs = setInterval("fetchNewMsgs()", 5000);
            },

    reset_intervals:function(){
            this.clear_intervals();
            this.set_intervals();
            },

    store_message:function(msg_id){
            //store the currently read message id
            var message_ids=jQuery("#mesage_store").val();//existing values
            var msgs="";
            if(message_ids.length>0)
                 msgs=message_ids+","+msg_id;
            else
                msgs=msg_id;
            jQuery("#mesage_store").val(msgs);
         },

    is_message_shown:function(message_id){
            //check if the message is is shown.
            var message_ids=jQuery("#mesage_store").val();
          //  alert(message_ids);
            var msgs=message_ids.split(",");
            if(jQuery.inArray(message_id, msgs)!=-1)
                return true;
            return false;
        },
    restore_chat_window:function(){
        
        var win_id=jQuery.cookie("maximized_chat_tab_id");
        if(win_id){
        var chat_win=jQuery("#chat_channel_"+win_id);
        ChatWindow.maximize(chat_win);

        //scroll the message
         jQuery(".win_body",chat_win).scrollTo( 500, {offset:-125, easing:'easeout'} );
        }
    }

}//end of helper

//window management or chat box management
var ChatWindow={
    is_open:function(win){
            if(jQuery(win).hasClass("open_toggler"))
                return true;
             return false;
            },

    is_closed:function(win){
            if(jQuery(win).hasClass("disabled"))
                    return true;
            return false;
            },

    is_minimized:function(win){
            if(jQuery(win).hasClass("open_toggler"))
                return false;

            return true;
        },

    is_maximized:function(win){
        if(jQuery(win).hasClass("open_toggler"))
            return true;
        return false;
        },

    is_disabled:function(){},

    exists:function(win){
        if(jQuery(win).get(0))
            return true;
	else return false;
    },

    exists_chat_box_for:function(friend_id){

        //check if a chat box exists for the friend
        if(jQuery("#chatting_with_user_"+friend_id).get(0))
            return true;//for reference
        return false;
    },
    
    close:function(win){
            jQuery(win).addClass("disabled");
            },

    maximize:function(win){
            //minimize all other windows except this one
            jQuery(".active_chat_tabs .chat_tab").each(function(){
             if(jQuery(this).hasClass("open_toggler")&&this!=win)
                 ChatWindow.minimize(this);

            });
            jQuery.cookie( 'maximized_chat_tab_id', ChatSettings.get_id(win), {path: '/'} );
            jQuery(win).addClass("open_toggler");
            ChatWindow.reset_new_message_count(win);
        },

    minimize:function(win){
            jQuery(win).removeClass("open_toggler");
        },

   toggle_window:function(win){
		if(jQuery(win).hasClass("open_toggler")) { //this window was open
			ChatWindow.minimize(win);
			return;
		}

		ChatWindow.maximize(win);
		jQuery(".win_body",win).scrollTo( 500, {offset:-125, easing:'easeout'} );
      },

    open:function(){},

   create:function( name, avatar_src, user_id, channel_id, status ) {//creating window
		
        //clone the template
       
       var win = jQuery("#chat_template").clone();//clone the chat window
		win.prependTo("div.active_chat_tabs")
			.removeClass('on off afk busy').addClass(status);//.css("margin-top", "-275px");
		win.attr("id", "chat_channel_" + channel_id);
                //alert("creating window 1");
               // jQuery("input.chat_with_user",win).val(user_id);//.html(name);
		win.find(".win_header_image_link img").attr("src", avatar_src);
		//alert(win.find(".win_header_image_link img").attr('style', 'border:2px solid blue'))
		
		// infinity scroll
		win.find(".win_body").scroll( function(){
			// if scroll is at the top
			if  (jQuery(this).scrollTop() == 0){
				
				var cannel_id = ChatSettings.get_id(jQuery(this).parent().parent());
				var last_massage_id = jQuery(this).attr('class').replace(/[^0-9]/g, '');
				
				// get old messages
				ChatSettings.infine_scroll( cannel_id, last_massage_id );
			}
		});
		
		win.find(".tab_name").html(name);
		win.find(".win_title_text a").html(name) ;
		win.find("textarea").attr("id", "chat_input_" + channel_id);
		jQuery("input.chatting_with_user",win).val(user_id);//for reference
		win.find("input.chatting_with_user").attr("id", "chatting_with_user_" +user_id);
		
		win.removeClass('disabled');
		win.show('slow');
       
		 // focus on textarea
		 setTimeout('jQuery("#chat_channel_'+channel_id+' textarea").focus();', 100);
		 
		 //ChatSettings.infine_scroll( cannel_id, 0 );
		
		jQuery.post(ajaxurl, {
			'action': "chat_check_updates",
			'channel_id':channel_id,
			'cookie': encodeURIComponent(document.cookie)
		},

		function(ret){
			ChatSettings.update_messages(ret);
		});//end of post

		return jQuery(win);
	

    },
    
    reopen:function(win){
            jQuery(win).removeClass("disabled");
            this.maximize(win);
        },

     hide_all:function(){
         jQuery(".active_chat_tabs_wrapper .chat_tab").removeClass("open_toggler");
        },

    update_message:function( win, id, name, message, at ){
		//check if shown or not
		if(ChatHelper.is_message_shown(id))
			return;
		
		message = message.replace(/&/gi,"&amp;");
		message = message.replace(/\</gi,"&lt;");
		
		// infinty scroll
		if( at=='top' )
			jQuery(".win_content",win).prepend('<div id="message_'+id+'" class="message"><span class="user_name">'+name+'</span> <br /> <span class="msg">'+message+'</span></div>');
		else {
			jQuery(".win_content",win).append('<div id="message_'+id+'" class="message"><span class="user_name">'+name+'</span> <br /> <span class="msg">'+message+'</span></div>');
			jQuery(".win_body", win).scrollTo( 500, {offset:-125, easing:'easeout'} );
		}
		
		// update last_message_id
		var last_message_id = jQuery(".win_body", win).attr('class').replace(/[^0-9]/g, '');
		if( last_message_id > id || last_message_id=='' )
			jQuery(".win_body", win).removeClass().addClass('win_body').addClass('lastid_'+id);
		
		//chat_play_notification();
	
		ChatHelper.store_message(id);
 },

 get_chat_box_for:function(friend_id){
   //get the chat box for friend id if one exists
  
 var friend_acc=jQuery("#chatting_with_user_"+friend_id);//.get(0);
 return find_parent_window(friend_acc);
 },
 
update_new_message_count:function(win){
  if(ChatWindow.is_maximized(win))
      return;
 //update the new message count
    var count=jQuery(".chat_button span.tab_count",win).text()-0;//just to make it integer
    jQuery(".chat_button span.tab_count").html(count+1);//just to make it integer
 //notify
},
reset_new_message_count:function(win){
    jQuery(".chat_button span.tab_count").html("0");//just to make it integer
},
get_new_message_count:function(win){
    return jQuery(".chat_button span.tab_count",win).text()-0;
},
create_chat_box:function(for_user){
    var friend=jQuery(for_user);
    var friend_id=ChatSettings.get_id(friend);
    var friend_name=ChatSettings.get_name(friend);
    var friend_avatar_src=ChatSettings.get_avatar_src(friend);
    var chat_status = ChatSettings.get_user_status( friend_id );
    
    jQuery.post(ajaxurl, {
                action: 'request_channel',
                'user_id': friend_id,
                'cookie': encodeURIComponent(document.cookie)
                },
                function(res){
					var channel_id=res;
					var chat_win=jQuery("#chat_channel_"+channel_id);//refrence to chat win object
					
					if ( !ChatWindow.exists(chat_win)) //{
						var chat_win = ChatWindow.create( friend_name, friend_avatar_src, friend_id, res, chat_status );
						//ChatWindow.maximize(chat_win);
					//}
					//else
						//ChatWindow.maximize(chat_win);
					//just maximize
					ChatWindow.maximize(chat_win);
				}
            );

    
},/*
update_other_user_status:function(channel_id,channel_status,is_online,user_status){
	
	var channel=jQuery("#chat_channel_"+channel_id);

	if(!ChatWindow.exists(channel))
		return;//if the chatbox is not open, do not do anything
	//if the chat tab exists

	var css_class="";
	//set flag to offline, may be we can update a status image
	if(is_online==0)//check for offline
		css_class="user_offline";
	else if(user_status=="idle")
		css_class="user_idle";
	else if(user_status=="active")
		css_class="user_online";
	var tab=channel.find(".tab_name");
	if(!tab.hasClass(css_class))
		tab.removeClass("user_offline user_online user_idle").addClass(css_class);
		
	//find the tab and set status

	/*else if(channel_status=="closed"){

	var shown=chat_channel_status["channel_"+channel_id];
	if(shown=='done')
		return;
	chat_channel_status["channel_"+channel_id]="done";
	if(status=='closed'){//if the status is not open/requested
		//tell the user that the other user has left
	var other_user_name=jQuery(".chat_window .win_title_text a",channel).text();
	//add to the chat window
	var message=" has left the chat.";
	jQuery(".win_content",channel).append('<div class="notice"><span class="user_name">'+other_user_name+'</span> <span class="notice_message">'+message+'</span></div>');
					jQuery(".win_body",channel).scrollTo( 500, {offset:-125, easing:'easeout'} );
	}
}*/
}



//actual binding to dom with events
jQuery(function(){
 
 var j=jQuery;

var cb=ChatWindow;//chat Box object
 
 j(document).ready(function(){
     //bind close button of chat window
   j(".active_chat_tabs .close_button span").live("click",function(evt){
       evt.stopPropagation();
       var window=find_parent_window(this);
      
       var channel_id = ChatSettings.get_id(window);//get the chat channel id
       cb.close(window);//close window

       j.post(ajaxurl,{action:"close_channel",channel_id:channel_id},function(){
             //do nothing here
         });
      //do all the cleanup/server processing here for closing a channel
   });


 // toggle tab/titlebar
 j("#chat_tabs_slider .chat_button, #chat_tabs_slider .win_titlebar").live("click",function(evt){
     //toggle window

     var win=find_parent_window(this);//find the current window
     evt.stopPropagation();
     evt.preventDefault();
	
	 // focus on textarea
	 setTimeout('jQuery("#'+jQuery(win).attr('id')+' textarea").focus();', 100);
	
     cb.toggle_window(win);//toggle this chat box
        
 });
//show new messages popup
 j("#chat_tabs_slider .chat_button").live("mouseover",function(evt){
     //toggle window

     var win=find_parent_window(this);//find the current window
     evt.stopPropagation();
     evt.preventDefault();
     if(!ChatWindow.is_maximized(win)){

    /*
     var count=ChatWindow.get_new_message_count(win);
     //jQuery("#chat_tabs_slider .chat_button").each(function(){
         jQuery(this).poshytip({content:count,
         className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'inner-left',
	offsetY: 5,
	offsetX: 10,
	allowTipHover: false,
	fade: false,
	slide: false
});*/
     }
 
 

  //   });
     
     //cb.toggle_window(win);//toggle this chat box

 });

//for Chat Options, open buddy list
j("#chat_buddylist .chat_button,#chat_buddylist .win_titlebar").live("click",function(evt){
     //toggle window
    // if(this==j("#chat_buddylist a.win_title_text_link").get(0))
         if(j(evt.target).is("#win_title_text_link_settings"))
            return;
     var win=find_parent_window(this);
     evt.stopPropagation();
     evt.preventDefault();
    if(!cb.is_maximized(win)){
        //hide if the settings panel is oopen
        j("#chat_buddylist_settings").addClass("disabled");
    }
        j(win).toggleClass("open_toggler");
        
    // if(cb.is_maximized(win)) //if the list is maximized
         //ChatSettings.update_online_list();//update user list


    });
    
 //maximize
j("#chat_buddylist .win_titlebar a.win_title_text_link").live("click",function(evt){
    evt.preventDefault();
    evt.stopPropagation();
    
    j(this).next().removeClass("disabled");

});

/*/for chat options settings

j("#chat_buddylist_settings ul li a").live("click",function(evt){
    evt.preventDefault();
    evt.stopPropagation();
    var option_selected=j(this).attr("id");
    //if the selected option is not already seletcted earlier, let us select it and highlight the link
    if(!j(this).hasClass("chat_option_active")){
        //if this is not the chat option active, make it active chat option
       //post to server
       
      j("#chat_buddylist_settings li a").removeClass("chat_option_active");
      j(this).addClass("chat_option_active");
        j.post(ajaxurl,{action:"chat_change_preference",prefrence:option_selected},function(){
             //do nothing here

         });
    }
   // j(this).next().removeClass("disabled");

});*/

//open a new chat box when clicking on the friend list item
j("a.online_friend").live("click",function(evt){

    evt.stopPropagation();
    evt.preventDefault();

    var friend_id=ChatSettings.get_friend_id(this);
    
    if(cb.exists_chat_box_for(friend_id)){
          var win=cb.get_chat_box_for(friend_id);//get the chat box for this friend if it exists
          jQuery(win).removeClass("disabled");
          cb.maximize(win);//maximize
          
			// focus on textarea
			setTimeout('jQuery("#'+win.id+' textarea").focus();', 100);
        }
   else
		cb.create_chat_box( this ); //create new chat box
});

//send message
//what happens when presses enter with some text .. should we send empty lines to?
j(".chat_input").live('keydown',
		
	function(event){

		if(event.keyCode == 13){
			var chat = find_parent_window(this);//get reference to current chat window
			var channel_id = ChatSettings.get_id(chat);

			var msg = jQuery(this).val().replace(/^\s+|\s+$/g, '');
			
			if( msg=='' )
				return false;
			
			j(this).val('');//empty current va;lue

			j.post(ajaxurl,{
					"action": 'save_chat_msg',
					"channel_id": channel_id,
					"message": msg,
					'cookie': encodeURIComponent(document.cookie)
				 },
				function(res){

						 var res = eval('('+res+')');
						 cb.update_message(chat,res.id, res.name, msg);
				
				});//end of post
				
			return false;
		}
	});

///setup polling
if(ChatHelper.chat_offline == 0)
		ChatHelper.set_intervals();
 });//end of document.ready


 //maximize any chat box wchich has a open status on the page
 ChatHelper.restore_chat_window();

});//end of jquery block


/*find the parent window*/
function find_parent_window(elem){
    var e=jQuery(elem);// turn to jquery object if not one
    var parent=e.parents(".chat_tab").get(0);
    return parent;
    
}

function chat_play_notification() {
	//create global object
	// soundManager.play('mySound','/path/to/an.mp3');
	//soundManager.createSound('chat_sound', bpchat.plugin_url+"assets/notification.wav");
	//volume: 50
	//});
	//soundManager.play('chat_sound');

	//chat_notification_sound.play();
	
	// destroy the last iframe
	jQuery('#schat_notification').remove();
	
	// create a iframe and play sound
	jQuery('body').append('<iframe id="schat_notification" src="'+schat_notify_file+'" style="display:none;"></iframe>');
}
