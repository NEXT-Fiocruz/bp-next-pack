<?php
/*
Plugin Name: NEXT BuddyPress Pack
Plugin URI: {URI where you plan to host your plugin file}
Description: Plugin to add some widgets used in NEXT social networks
Version: 1
Author: Alberto Souza 
Author URI: albertosouza.net
*/

class BpNextProfileWidget extends WP_Widget {
  function BpNextProfileWidget() {
    parent::WP_Widget( false, $name = 'NEXT Logged ind user Profile' );
  }

  function widget( $args, $instance ) {
    extract( $args );

    echo $before_widget;

    if ( is_user_logged_in() ) {
      $title = apply_filters('widget_title', $instance['title']);
      if( $title ) echo $before_title . $title . $after_title;
      $this->logedUserBlock( $args, $instance ); 

    }else {
      $this->disconectedUser( $args, $instance );
    }
    echo $after_widget;
  }

  //////////////////////////////////////////////////////
  //Update the widget settings
  /**
   * Update the login widget options
   *
   * @param array $new_instance The new instance options
   * @param array $old_instance The old instance options
   */
  function update( $new_instance, $old_instance ) {
    $instance             = $old_instance;
    $instance['title']    = strip_tags( $new_instance['title'] );
    $instance['register'] = esc_url( $new_instance['register'] );
    $instance['lostpass'] = esc_url( $new_instance['lostpass'] );

    return $instance;
  }
  
  ////////////////////////////////////////////////////
  //Display the widget settings on the widgets admin panel
  function form( $instance ) {

    // Form values
    $title    = !empty( $instance['title'] )    ? esc_attr( $instance['title'] )    : '';
    $register = !empty( $instance['register'] ) ? esc_attr( $instance['register'] ) : '';
    $lostpass = !empty( $instance['lostpass'] ) ? esc_attr( $instance['lostpass'] ) : '';

    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'register' ); ?>"><?php _e( 'Register URI:', 'bbpress' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'register' ); ?>" name="<?php echo $this->get_field_name( 'register' ); ?>" type="text" value="<?php echo $register; ?>" /></label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'lostpass' ); ?>"><?php _e( 'Lost Password URI:', 'bbpress' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'lostpass' ); ?>" name="<?php echo $this->get_field_name( 'lostpass' ); ?>" type="text" value="<?php echo $lostpass; ?>" /></label>
    </p>

    <?php
  }

  function disconectedUser( $args, $instance ) {
    ?><div class="login-box"><?php
    global $opt_jfb_hide_button;
    if( !get_option($opt_jfb_hide_button) ){
        jfb_output_facebook_btn();
        ?> <span class="login_or"><?php _e('or'); ?></span> <?php 
    }

    ?>
      <form name="loginform" method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>" class="bbp-login-form">
        <fieldset>
          <legend><?php _e( 'Log In', 'bbpress' ); ?></legend>
          <div class="bbp-username">
            <label for="user_login"><?php _e( 'Username', 'bbpress' ); ?>: </label>
            <input type="text" name="log" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
          </div>
          <div class="bbp-password">
            <label for="user_pass"><?php _e( 'Password', 'bbpress' ); ?>: </label>
            <input type="password" name="pwd" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="user_pass" tabindex="<?php bbp_tab_index(); ?>" />
          </div>
          <div class="bbp-remember-me">
            <input type="checkbox" name="rememberme" value="forever" <?php checked( bbp_get_sanitize_val( 'rememberme', 'checkbox' ), true, true ); ?> id="rememberme" tabindex="<?php bbp_tab_index(); ?>" />
            <label for="rememberme"><?php _e( 'Remember Me', 'bbpress' ); ?></label>
          </div>
          <div class="bbp-submit-wrapper">
            <?php do_action( 'login_form' ); ?>
            <button type="submit" name="user-submit" id="user-submit" tabindex="<?php bbp_tab_index(); ?>" class="button submit user-submit"><?php _e( 'Log In', 'bbpress' ); ?></button>
            <?php bbp_user_login_fields(); ?>
          </div>
          <?php if ( !empty( $register ) || !empty( $lostpass ) ) : ?>
            <div class="bbp-login-links">
              <?php if ( !empty( $register ) ) : ?>
                <a href="<?php echo esc_url( $register ); ?>" title="<?php _e( 'Register', 'bbpress' ); ?>" class="bbp-register-link"><?php _e( 'Register', 'bbpress' ); ?></a>
              <?php endif; ?>
              <?php if ( !empty( $lostpass ) ) : ?>
                <a href="<?php echo esc_url( $lostpass ); ?>" title="<?php _e( 'Lost Password', 'bbpress' ); ?>" class="bbp-lostpass-link"><?php _e( 'Lost Password', 'bbpress' ); ?></a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </fieldset>
      </form>
       <?php do_action( 'login_footer' ); ?>
    </div>
    <?php
  }

  function logedUserBlock( $args, $instance ){
    $userdata = wp_get_current_user();
    $user_image_args = array(
      'type'   => 'full' 
    );

    ?>
    <a class="next-user-avatar" href="<?php bp_loggedin_user_link() ?>">
      <?php bp_loggedin_user_avatar( ); ?> <?php print $userdata->display_name ?>
    </a> 
    <span class="activity"> 
      <a href="<?php echo bp_loggedin_user_domain() ?>profile/edit">Edit My Profile</a>    
    </span>
    <div id="user-menu">       

          <?php bp_next_pack_adminbar_account_menu(); ?>

          <?php /* if ( has_nav_menu( 'profile-menu' ) ) : ?>
              <?php wp_nav_menu( array( 'container' => false, 'menu_id' => 'nav', 'theme_location' => 'profile-menu', 'items_wrap' => '%3$s' ) ); ?>
          <?php endif; */?>

          <?php do_action( 'bp_member_options_nav' ) ?>


      <ul class="user-loggedin-group-menu">
        <?php if( ! class_exists('BP_Groups_Group') ) {
          _e( 'You must enable Groups component to use this widget.', 'bp-group-hierarchy' );
          return; 
        } ?>
      </ul>
      
      <ul class="user-loggedin-friends-menu">
        
      </ul>
      
    </div><!-- #item-nav -->
    <?php
  }
}


class BpNextAddWidget extends WP_Widget {
  function BpNextAddWidget() {
    parent::WP_Widget( false, $name = 'NEXT add Menu' );
  }

  function widget( $args, $instance ) {
    extract( $args );

    echo $before_widget;

    $title = apply_filters('widget_title', $instance['title']);
    if( $title ) echo $before_title . $title . $after_title;
    $this->logedUserBlock( $args, $instance ); 

    echo $after_widget;
  }

  //////////////////////////////////////////////////////
  //Update the widget settings
  /**
   * Update the login widget options
   *
   * @param array $new_instance The new instance options
   * @param array $old_instance The old instance options
   */
  function update( $new_instance, $old_instance ) {
    $instance             = $old_instance;
    $instance['title']    = strip_tags( $new_instance['title'] );

    return $instance;
  }
  
  ////////////////////////////////////////////////////
  //Display the widget settings on the widgets admin panel
  function form( $instance ) {

    // Form values
    $title    = !empty( $instance['title'] )    ? esc_attr( $instance['title'] )    : '';

    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label>
    </p>


    <?php
  }

  
  function logedUserBlock( $args, $instance ){
    $userdata = wp_get_current_user();
    
    ?>
    <div class="bp-next-pack-user-menu" >
    <ul class="superfish-menu sf-vertical sf-js-enabled sf-shadow">
      <?php
      // add message
      ?>
      <li><a class="sf-with-ul add-group" 
        href="<?php echo trailingslashit( bp_get_root_domain() . '/members/alberto/messages/compose/' ); ?>">
      <?php _e( 'Messages', 'buddypress' ); ?> </a></li> <?php
      
      // add group link
      if ( is_user_logged_in() && bp_user_can_create_groups() ){
        ?><li><a class="sf-with-ul add-group" 
        href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ); ?>">
        <?php _e( 'Group', 'buddypress' ); ?> </a></li><?php
      }
   
       // add site
      ?>
      <li><a class="sf-with-ul add-group" 
        href="<?php echo trailingslashit( bp_get_root_domain() . '/sites/create/' ); ?>">
      <?php _e( 'Messages', 'buddypress' ); ?> </a></li><?php
    
    ?> </div></ul> <?php
  }
}



class BpNextUserGroupsWidget extends WP_Widget {
  function BpNextUserGroupsWidget() {
    parent::WP_Widget( false, $name = 'NEXT Logged in user groups' );
  }

  function widget( $args, $instance ) {
    
    if ( is_user_logged_in() ) {
      extract( $args );
      echo $before_widget;
      $title = apply_filters('widget_title', $instance['title']);
      if( $title ) echo $before_title . $title . $after_title;
      $this->logedUserBlock( $args, $instance ); 
      echo $after_widget;
    }
    
  }


  //////////////////////////////////////////////////////
  //Update the widget settings
  function update( $new_instance, $old_instance )
  {
      $instance = $old_instance;
      $instance['title'] = $new_instance['title'];
      return $instance;
  }
  
  ////////////////////////////////////////////////////
  //Display the widget settings on the widgets admin panel
  function form( $instance )
  {
      ?>
      <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
      </p>
      <?php
  }
  
  
  /**
   * show something here if the user is disconected
   * 
   */
  function disconectedUser( $args, $instance ) {
    

  }
  
  /**
   * Show logged in use groups block
   * 
   */
  function logedUserBlock( $args, $instance ){
    global $bp;
      
    $user_id = get_current_user_id( );
    $quantidade = 4;
    $query_args = array(
      'type' => 'active',
      'page' => 1,
      'per_page' => $quantidade,
      'max' => NULL,
      'show_hidden' => NULL,
      'user_id' => $user_id,
      'slug' => NULL,
      'search_terms' => NULL,
      'include' => NULL,
      'exclude' => NULL,
      'populate_extras' => 1
    );

    ?>
    <?php if ( bp_has_groups( $query_args ) ) : ?>
    
      <?php do_action( 'bp_before_directory_groups_list' ); ?>
    
      <ul id="groups-list" class="item-list" role="main">
    
      <?php while ( bp_groups() ) : bp_the_group(); ?>

      <li>
        <div class="item-avatar">
          <a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
        </div>
  
        <div class="item">
          <div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
          <div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div>
          <?php do_action( 'bp_directory_groups_item' ); ?>
  
        </div>

        <div class="clear"></div>
      </li>
  
    <?php endwhile; ?>
    
    </ul>
    <div class="link-more">
    <a href="<?php print bp_loggedin_user_link() . 'groups/' ; ?>" title="<?php _e("Click here to show the complete group list") ?>"><?php _e("More groups") ?></a>
    </div>
    
    <?php else: ?>
    
      <div id="message" class="info">
        <p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
      </div>
    
    <?php endif;
  }
}

add_action( 'widgets_init', 'BpNextProfileWidgetInit' );
function BpNextProfileWidgetInit() {
  register_widget( 'BpNextProfileWidget' );
  register_widget( 'BpNextUserGroupsWidget' );
  register_widget( 'BpNextAddWidget' );
  
}

// **** "logged in  Account" Menu ******
function bp_next_pack_adminbar_account_menu() {
  global $bp;

  if ( !$bp->bp_nav || !is_user_logged_in() )
    return false;
  ?>
  <script>
    jQuery(document).ready(function() { 
        jQuery('ul.superfish-menu').superfish({ 
            delay:       300,                            // one second delay on mouseout 
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
            speed:       'fast',                          // faster animation speed 
            autoArrows:  true,                           // disable generation of arrow mark-up 
            dropShadows: true                            // disable drop shadows 
        }); 
    }); 
  </script>
  <div class="bp-next-pack-user-menu" >
    <ul class="superfish-menu sf-vertical sf-js-enabled sf-shadow">
  <?php

  // Loop through each navigation item
  $counter = 0;
  foreach( (array)$bp->bp_nav as $nav_item ) {
    $alt = ( 0 == $counter % 2 ) ? ' class="alt"' : '';

    if ( -1 == $nav_item['position'] )
      continue;

    echo '<li' . $alt . '>';
    echo '<a id="bp-admin-' . $nav_item['css_id'] . '" href="' . $nav_item['link'] . '">' . $nav_item['name'] . '</a>';

    if ( isset( $bp->bp_options_nav[$nav_item['slug']] ) && is_array( $bp->bp_options_nav[$nav_item['slug']] ) ) {
      echo '<ul>';
      $sub_counter = 0;

      foreach( (array)$bp->bp_options_nav[$nav_item['slug']] as $subnav_item ) {
        $link = $subnav_item['link'];
        $name = $subnav_item['name'];

        if ( isset( $bp->displayed_user->domain ) )
          $link = str_replace( $bp->displayed_user->domain, $bp->loggedin_user->domain, $subnav_item['link'] );

        if ( isset( $bp->displayed_user->userdata->user_login ) )
          $name = str_replace( $bp->displayed_user->userdata->user_login, $bp->loggedin_user->userdata->user_login, $subnav_item['name'] );

        $alt = ( 0 == $sub_counter % 2 ) ? ' class="alt"' : '';
        echo '<li' . $alt . '><a id="bp-admin-' . $subnav_item['css_id'] . '" href="' . $link . '">' . $name . '</a></li>';
        $sub_counter++;
      }
      echo '</ul>';
    }

    echo '</li>';

    $counter++;
  }

  $alt = ( 0 == $counter % 2 ) ? ' class="alt"' : '';

  echo '<li' . $alt . '><a id="bp-admin-logout" class="logout" href="' . wp_logout_url( home_url() ) . '">' . __( 'Log Out', 'buddypress' ) . '</a></li>';
  echo '</ul>';
  echo '</div>';  
}

// _----------------------____------------________-------_______------_______-------_____--
/**  Alteração no menu de login para melhorar logar também OCS os 2 usando LDAP  **/

/**
 * Add a "Login with Shibboleth" link to the WordPress login form.  This link 
 * will be wrapped in a <p> with an id value of "shibboleth_login" so that 
 * deployers can style this however they choose.
 */
function bp_next_pack_login_form() {
  //$login_url = add_query_arg('action', 'shibboleth');
  
  bp_next_pack_ocs_login_sniplet_and_form();
  
	// echo '<p id="shibboleth_login"><a href="' . $login_url . '">' . __('Login with Shibboleth', 'shibboleth') . '</a></p>';
}
add_action('login_footer', 'bp_next_pack_login_form');

function bp_next_pack_ocs_login_sniplet_and_form() {
  ?>
  <div class="container-ocs-form" style="display:none" >
    <form id="signinForm" name="ocslogin" method="post" target="ocs_iframe" action="http://localhost/confs/index.php/testando/teste/login/signIn">

    <input type="hidden" name="source" value="">

      <table id="signinTable" class="data">
      <tbody><tr>
        <td class="label"><label for="loginUsername">Login</label></td>
        <td class="value"><input type="text" id="loginUsername" name="username" value="next" size="20" maxlength="32" class="textField"></td>
      </tr>
      <tr>
        <td class="label"><label for="loginPassword">Senha</label></td>
        <td class="value"><input type="password" id="loginPassword" name="password" value="" size="20" maxlength="32" class="textField"></td>
      </tr>
        <tr valign="middle">
        <td></td>
        <td class="value"><input type="checkbox" id="loginRemember" name="remember" value="1" checked='checked'> <label for="loginRemember">Lembrete com login e senha</label></td>
      </tr>
        <tr>
        <td></td>
        <td><input id="ocsEnviar" type="submit" value="Acesso" class="button"></td>
      </tr>
      </tbody></table>
    <script type="text/javascript">
    <!--
      document.login.loginPassword.focus();
    // -->
    </script>
    </form>
    
    <script type="text/javascript">
    <!--
      jQuery('form[name=loginform]').submit(function(){
        var login = jQuery('form[name=loginform]').find('#user_login').val();
        var senha = jQuery('form[name=loginform]').find('#user_pass').val();

        jQuery('form[name=ocslogin]').find('#loginUsername').val(login);
        jQuery('form[name=ocslogin]').find('#loginPassword').val(senha);
        jQuery('form[name=ocslogin]').submit();

        var myIframe = document.getElementById('ocs_iframe');
        
        if(jQuery('form[name=ocslogin]').find('#ocsEnviar').val() == 'Acesso'){
                
          jQuery('form[name=ocslogin]').find('#ocsEnviar').val('enviado');
          myIframe.onload = function() {
              jQuery('form[name=loginform]').submit();
          };

          return false;
        }else{
          return true;
        }
      });
       
    // -->
    </script>  
    
    <!-- when the form is submitted, the server response will appear in this iframe -->
    <iframe id="ocs_iframe" name="ocs_iframe" src=""></iframe>
  </div>
  
  <?php
}
/***** fim da alteração para login no ocs ***/
