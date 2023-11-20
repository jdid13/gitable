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
    var_dump($repos[0]);

    // Output GitHub repositories as cards
    $output = '<div class="github-repo-cards">';    
    foreach ($repos as $repo) {
        $output .= "<div class='card'>";
            $output .= "<div class='header_card'>";
                $output .= "<img src=" . "" . " alt='card title'>";
            $output .= "</div>";
            $output .= "<div class='body_card'>";
                $output .= "<div class='card_content'>";
                    $output .= '<h1>' . $repo['name'] . '</h1>';
                    $output .= '<p>' . $repo['description'] . '</p>';
                        $output .= "<div class='container_infos'>";
                            $output .= "<div class='postedBy'>";
                            $output .= "<span><u>Author:</u></span> ";
                            $output .= "<i>" . $username . "</i>";
                            $output .= "</div>";
                            $output .= "<div class='container_tags'>";
                                $output .= "<span><u>Status:</u></span> ";
                                $output .= ($repo['private'] ? 'Private' : 'Public');
                            $output .= "</div>";
                        $output .= "</div>";
                    $output .= "</div>";
                $output .= "</div>";
            $output .= "</div>";
            //$output .= '<a href="' . $repo['html_url'] . '" target="_blank">View on GitHub</a>';
        $output .= '</div>';
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