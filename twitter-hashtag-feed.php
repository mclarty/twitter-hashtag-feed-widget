<?php
/*
Plugin Name: Twitter Hashtag Feed Widget
Version: 1.0.0
Description: Allows you to add a widget with a Twitter feed using a hashtag search.
Author: Nick McLarty
Author URI: http://www.inick.net
*/

add_action( 'widgets_init', 'register_twitter_hashtag_feed_widget' );

function register_twitter_hashtag_feed_widget() {
	register_widget( 'Twitter_Hashtag_Feed_Widget' );
}

class Twitter_Hashtag_Feed_Widget extends WP_Widget {
	function Twitter_Hashtag_Feed_Widget() {
		$opts = array(
			'classname' => 'Twitter_Hashtag_Feed_Widget',
			'description' => 'Creates a Twitter feed widget using a hashtag search.',
			);

	$this->WP_Widget(
		'Twitter_Hashtag_Feed_Widget',
		'Twitter Hashtag Feed Widget'
		);
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
		$hashtag = esc_attr( $instance['hashtag'] );
		$consumer_key = esc_attr( $instance['consumer_key'] );
		$consumer_secret = esc_attr( $instance['consumer_secret'] );
		$oauth_token = esc_attr( $instance['oauth_token'] );
		$oauth_token_secret = esc_attr( $instance['oauth_token_secret'] );
		$results = esc_attr( $instance['results'] );

	?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php _e( 'Title:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" 
			name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label>
	</p>
	<p><label for="<?php echo $this->get_field_id( 'hashtag' ); ?>">
		<?php _e( 'Hashtag:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'hashtag' ); ?>" 
			name="<?php echo $this->get_field_name( 'hashtag' ); ?>" type="text" value="<?php echo $hashtag; ?>" /></label>
	</p>
	<p><label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>">
		<?php _e( 'Twitter Consumer Key:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" 
			name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" type="text" value="<?php echo $consumer_key; ?>" /></label>
	</p>
	<p><label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>">
		<?php _e( 'Twitter Consumer Secret:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" 
			name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" type="text" value="<?php echo $consumer_secret; ?>" /></label>
	</p>
	<p><label for="<?php echo $this->get_field_id( 'oauth_token' ); ?>">
		<?php _e( 'Twitter OAuth Token:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'oauth_token' ); ?>" 
			name="<?php echo $this->get_field_name( 'oauth_token' ); ?>" type="text" value="<?php echo $oauth_token; ?>" /></label>
	</p>
	<p><label for="<?php echo $this->get_field_id( 'oauth_token_secret' ); ?>">
		<?php _e( 'Twitter OAuth Token Secret:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'oauth_token_secret' ); ?>" 
			name="<?php echo $this->get_field_name( 'oauth_token_secret' ); ?>" type="text" value="<?php echo $oauth_token_secret; ?>" /></label>
	</p>
	<p><label for="<?php echo $this->get_field_id( 'results' ); ?>">
		<?php _e( 'Number of Results:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'results' ); ?>" 
			name="<?php echo $this->get_field_name( 'results' ); ?>" type="text" value="<?php echo $results; ?>" /></label>
	</p>

	<?php 
	}

	function widget( $args, $instance ) {
		require_once 'twitteroauth/twitteroauth.php';

		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$query = array(	'q' => $instance['hashtag'],
						'result_type' => 'recent',
						'count' => $instance['results'],
					);

		$conn = new TwitterOAuth( $instance['consumer_key'], $instance['consumer_secret'], 
			$instance['oauth_token'], $instance['oauth_token_secret'] );
		$content = $conn->get( $url, $query );

		echo '<h4 class="widgettitle">' . $instance['title'] . '</h4>';

		if ( $content->search_metadata->count > 0 ) {
			foreach( $content->statuses as $status ) {
				echo '<p>';
				echo '<a href="https://twitter.com/account/redirect_by_id?id=' . $status->user->id_str . '" target="_blank">';
				echo $status->user->name . '</a>: ' . $status->text;
				echo '</p>';
			}
		}
	}
}
