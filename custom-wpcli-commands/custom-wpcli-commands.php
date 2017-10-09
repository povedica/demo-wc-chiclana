<?php
/**
 * Plugin Name:     Custom Wpcli Commands
 * Plugin URI:      http://wpmadrid1.demo
 * Description:     WP_CLI custom commands
 * Author:          Pablo Poveda Ortega
 * Author URI:      http://wpmadrid1.demo
 * Text Domain:     custom-wpcli-commands
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Custom_Wpcli_Commands
 */
if (class_exists('WP_CLI_Command')) {
    /**
     * Implements example command.
     */
    class WCChiclana_WPCli extends WP_CLI_Command
    {

        /**
         * Prints a greeting.
         *
         * ## OPTIONS
         *
         *
         *
         * [--format=<type>]
         * : Output format: json
         * ---
         * default: print
         * options:
         *   - print
         *   - json
         * ---
         *
         * ## EXAMPLES
         *
         *     wp wpmadrid get_domain
         * @subcommand get_domain
         * @when before_wp_load
         */
        function get_domain($args, $assoc_args)
        {
            try {
                $domain = explode('://', get_option('siteurl', ''))[1];
                echo ((isset($assoc_args['format']) && $assoc_args['format'] === 'json' ) ? json_encode(array("domain"=>$domain)) : $domain) . PHP_EOL;
            } catch (Exception $e) {
                WP_CLI::error("Error occurred.");
            }
        }
    }

    WP_CLI::add_command('wc-chiclana', 'WCChiclana_WPCli');
}
