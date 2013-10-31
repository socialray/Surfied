<?php

/* PLUGIN SETTINGS */
/* handling the admin option page for ajaxize */

// add the admin options page
add_action('admin_menu', 'ajaxize_admin_add_page');
// add the admin settings and such
add_action('admin_init', 'ajaxize_admin_init');

$div_content = '';

function ajaxize_this_generate_div_header() {
?>
    <div class="wrap">
    <h2><?php _e('Generate Ajaxized DIV for your function'); ?></h2>

    <p> Ajaxize will allow you to ajaxize almost any php function on your site. </p>
    <p> It can be a plugin, a function you wrote, or even a core wordpress function. </p>
    <br/>
    <p> There are some (obvious or less obvious) limitations currently: <p>
    <p> 
    <ul style="list-style-type: disc; margin-left: 20px;">
    <li>Functions must return valid HTML - this will be called in php and returned via the Ajax call</li>
    <li>Functions cannot accept any parameters (at least at the moment)</li>
    </ul>
    </p>
    <br />
    <form action="" method="post" id="ajaxize-this-function" style="">
    <?php if ( function_exists('wp_nonce_field') )
        wp_nonce_field('ajaxize_this_generate_div');
    ?>
    <p> Enter a function name below.</p>
    <p>Function Name: <input type="text" id="function_name" name="function_name"></p>
    <p class="submit"><input type="submit" name="submit" value="<?php _e('Generate DIV &raquo;'); ?>" /></p>
    <p>The generated div can be inserted to any page/post on the site and will ajaxize the call to the function automatically.</p>
    <p>Please make sure you enter a valid function name, that the function does not require any parameters, and that it returns valid HTML.</p>
    </form>
    </div>
<?php 
    global $div_content;
    echo $div_content;
}

function ajaxize_this_generate_div_content($fn_name) {
    $content = '';
    $content .= "<p> Copy & paste this div below </p>";
    $output_div = "<div id=\"ajaxize_this:" . $fn_name . ":" . ajaxize_this_hmac($fn_name) . "\"></div>";
    $content .= "<pre><strong>" . htmlentities($output_div) . "</strong></pre>";
    $content .= "<p> Function output (via ajaxize): </p>";
    $content .= '<div style="border: #d4d4d4 1px solid; border-radius: 8px; webkit-border-radius: 8px; moz-border-radius: 8px;">';
    $content .= str_replace('><', '> If you see this message. Something is not working <', $output_div);
    $content .= '</div>';
    return $content;
}


function ajaxize_admin_add_page() {
    global $ajaxize_this_hook;
    $ajaxize_this_hook = add_options_page('Ajaxize settings', 'Ajaxize', 'manage_options', 'ajaxize_this', 'ajaxize_options_page');
}

// display the admin options page
function ajaxize_options_page() { ?>
    <div class="wrap">
    <h2>Ajaxize</h2>

    <form action="options.php" method="post">
    <?php settings_fields('ajaxize_this_options'); ?>
    <?php do_settings_sections('ajaxize_this'); ?>
<p>A random Secret Key was automatically generated when the plugin was first installed.<br />
You can change your secret key, but please be aware that any previously-generated divs will stop working.<br />
Do not post or share this key.</p>
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form></div>

<?php ajaxize_this_generate_div_header();
}


function ajaxize_admin_init(){
    register_setting( 'ajaxize_this_options', 'ajaxize_this_options', 'ajaxize_options_validate_secret_key' );
    add_settings_section('plugin_main', 'Security Settings', create_function('',''), 'ajaxize_this');
    add_settings_field('secret_key', 'Secret Key', 'plugin_setting_secret_key', 'ajaxize_this', 'plugin_main');
    init_ajaxize_options('ajaxize_this_options');

    // handle POST of function name (placed here to allow displaying errors)
    if ( isset($_POST['submit']) && isset($_POST['function_name'])) {
        check_admin_referer('ajaxize_this_generate_div');
        $fn_name = ajaxize_this_validate_function($_POST['function_name']);
        if (! empty($fn_name) ) {
            global $div_content;
            $div_content = ajaxize_this_generate_div_content($fn_name);
        }
    }
}

function init_ajaxize_options($opt_name) {
    $options = get_option($opt_name);
    if (empty($options)) {
        $options['secret_key'] = sha1(session_id());
        update_option($opt_name, $options);
    }

}

function plugin_setting_secret_key () {
    $options = get_option('ajaxize_this_options');
    echo "<input id='secret_key' name='ajaxize_this_options[secret_key]' size='40' type='text' value='{$options['secret_key']}' />";
}

// validate our options
function ajaxize_options_validate_secret_key($input) {
    $options = get_option('ajaxize_this_options');
    // doing per-field regex validation. 
    // You can add / change validation rules here
    foreach ($input as $k => $v) {
        if (preg_match('/^[a-z0-9]{12,}$/i', $v)) {
            $options[$k] = $v;
        }
        else {
            add_settings_error('secret_key','ajaxize_this_err','Secret Key must be alphanumeric and at least 12 characters long','error');
        }
    }
    return $options;
}


function ajaxize_this_validate_function($fn_name) {
    if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $fn_name)) {
        if (! function_exists($fn_name)) {
            add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p>Fuction not found.</p></div>\';'));
        }
        else return $fn_name;
    }
    else {
        add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p>Invalid function name. Make sure to remove () and any extra spaces.</p></div>\';'));
    }

}

add_filter('contextual_help', 'ajaxize_this_help', 10, 3);
// help page for ajaxize plugin (taken from FAQ)
function ajaxize_this_help($contextual_help, $screen_id, $screen) {
    global $ajaxize_this_hook;
    if ($screen_id == $ajaxize_this_hook) {
        $contextual_help = <<<EOD
    <h1>Ajaxize</h1> 
 
    <p>Ajaxize will allow you to ajaxize almost any php function on your site.
It can be a plugin, a function you wrote, or even a core wordpress function.</p>    <hr /> 
        <h3>Frequently Asked Questions</h3> 
    <h4>How does ajaxize work?</h4> 
 
<p>To ajaxize your plugin or function, the only thing you need is the function name.
Go to Settings-&gt;ajaxize and enter your function name. Then click 'Generate DIV'.</p> 
 
<p>If all is working fine you should get a div that you can add to any page, post or template. 
You should also see the output of the generated div below.</p> 
 
<h4>How do I find the correct function name?</h4> 
 
<p>Many plugins come with shortcodes or function names that can be entered directly into the template. 
It is usually within the plugin documentation. Search the documentation for information how to use the plugin in your templates. Otherwise, you can try the plugin editor (Plugins-&gt;Editor), select your plugin and then search for the function name inside the php code.</p> 
 
<h4>Are there any limitations to which functions I can use?</h4> 
 
<p>Yes.
<ul>
<li>Functions must return valid HTML - this will be called in php and returned via the Ajax call</li>
<li>Functions cannot accept any parameters (at least at the moment)</li></ul>
</p>
 
<h4>How can I test if ajaxize is working?</h4> 
 
<p>Try 'ajaxize_this_test' (without the quotes) and click Generate DIV to test it.</p> 
 
<h4>What is the Secret Key? Do I need to change it?</h4> 
 
<p>The secret key is there to allow you to ajaxize any function you want, but only the functions you want and not others. The secret key is used to create a 'signature' on the div you generate. This signature is generated using HMAC, and the Secret Key is the key used by HMAC.</p> 
 
<p>A few notes about the secret key:
<ul>
<li>A random Secret Key is automatically generated when the plugin is first installed.</li>
<li>You can change your secret key, but please be aware that any previously-generated divs will stop working.</li>
<li>Do not post or share this key! Please only change it if you know what you're doing.</li></ul>
</p> 
 
<h4>Can I add stuff or modify the div?</h4> 
 
<p>This is a normal div. The only part that you cannot change is the id section. Even the smallest change to the id will invalidate the signature, and it will stop working.
Therefore, if you have a new function you also want to ajaxize - you'd have to use the ajaxize generator. Don't just replace the function name, because the signature will not be valid for any other function.</p> 
 
<h4>What is this good for?</h4> 
 
<p>ajaxize is most suitable when you are using a caching solution (W3 Total Cache, WP Super Cache etc). With ajaxize you can keep the page cached, but still pull content dynamically. Best used for quotes, feedbacks, statistics etc, but can work on almost any type of output.</p> 
 
<p>It might also be useful to speed up page loads with plugins like Facebook and Twitter buttons (which often take some time to load if embedded directly).</p> 
 
<p>It won't make you rich in 21 days nor will it make your pencil longer.</p> 
 
<h4>Can I automatically refresh the div every X seconds?</h4> 
 
<p>Yes, but currently you'd have to write your own javascript for it. 
Here is a small example using jQuery (replace with your own div id):</p> 
 
<pre><code>&lt;script&gt;
var refreshId = setInterval(function() {
 
    var $data = $('div&#x5b;id*="ajaxize_this:REPLACE_THIS"]');
    $data.each( function() {
        $data.fadeOut(2000, function() {
            var newquery = $.query.set('ajaxize_this', $data.attr('id')).set('_wpnonce', ajaxizeParams._wpnonce);
            $data.load(location.pathname + newquery, function() {
                $data.fadeIn(2000);
            });
        });
    });
 
    return false;
}, 10000); 
&lt;/script&gt;
</code></pre> 
 
<h4>I'm getting an error after generating a DIV "ajaxize: Error executing . Is this function correct?" What's wrong?</h4> 
 
<p>Check that the function name is correct, do not include brackets. Use: <em>some_function</em> instead of <em>some_function()</em>.
Make sure the function does not require any parameters either.</p> 
 
<h4>I generate a DIV but the Function output is empty. Why?</h4> 
 
<p>Some plugins/functions produce iframes and other content that might only get displayed in the right context.
In general, if there's an error, you would get an error message on the settings page. Try to copy the div into a post instead. It might still work.</p> <hr /> 
EOD;
    }
    return $contextual_help;
}
/* END PLUGIN SETTINGS */
?>
