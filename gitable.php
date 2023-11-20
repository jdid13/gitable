<?php 
/*
 * Plugin Name: GITable
 * Description: Plugin to show your repositories in your WordPress in an elegant manner
 * Author: Naben0
 */

// Shortcode function to display GitHub repositories
function github_repo_cards_shortcode($atts) {
    // Get the GitHub username from the shortcode attribute
    $username = isset($atts['username']) ? $atts['username'] : '';

    if (empty($username)) {
        return 'Please provide a GitHub username.';
    }

    // Fetch GitHub repositories
    $api_url = "https://api.github.com/users/{$username}/repos";
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return 'Error fetching GitHub repositories.';
    }

    $repos = json_decode(wp_remote_retrieve_body($response), true);

    // Output GitHub repositories as cards
    $output = '<div id="github-repo-cards">';    
    foreach ($repos as $repo) {
        $output .= "<div id='grid__item'>";
            $output .= "<div id='card'>";
                $output .= "<img id='card__img' src='https://images.unsplash.com/photo-1519681393784-d120267933ba?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2250&q=80' alt='Snowy Mountains'>";
                $output .= "<div id='card__content'>";
                    $output .= "<h1 id='card__header'>" .  $repo['name'] . "</h1>";
                    $output .= "<p id='card__text'>" . $repo['description'] . "</p>";
                    $output .= "<a href='" . $repo['html_url'] . "' id='card__btn'>See more <span>&rarr;</span></a>";
                $output .= "</div>";
            $output .= "</div>";
        $output .= "</div>";
    }


    $output .= '</div>';

    return $output;
}

// Register the shortcode
add_shortcode('github_repo_cards', 'github_repo_cards_shortcode');

// Enqueue the stylesheet
function github_repo_cards_enqueue_styles() {
    wp_enqueue_style('github-repo-cards-style', plugins_url('github-repo-cards-style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'github_repo_cards_enqueue_styles');