# RocketGeek jQuery UI tabs for WordPress

This is a code library for WordPress plugins. You can include this library according to the instructions and implement a set of jQuery tabs for your content.

Currently, it is designed for use in admin panels, but eventually, it may add a frontend tool/shortcode/gutenberg block.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

It is assumed that you are already running WordPress.  If so, you just need to include this library in your plugin (or theme).

The library relies on jQuery UI. It enqueues jQuery and jQuery UI Core using `wp_enqueue_script`.  This means that if you properly load these two libraries already in other plugins or your theme, then you're fine.  But if you use plugins or themes that improperly load these two libraries (i.e. they do not enqueue but rather load directly), you may run into conflicts.

### Using the library

Copy the entire rocketgeek-jquery-tabs folder to your project. I like to do this in an "includes" subdirectory. For example:

```
include_once YOUR_PLUGIN_PATH . 'includes/libraries/rocketgeek-jquery-tabs/rocketgeek-jquery-tabs.php';
```

Once the library is included, you can call it in your project. Define the tabs as an array, and pass that array with the call.

```
$tabs = array(
	'my_first_tab' => array(
		'tab' => 'First Tab Title',
		'content' => 'The tab content (can be a variable if you build more complex HTML).',
	),
	'my_second_tab' => array(
		'tab' => __( 'Second Tab Title (localized)', 'your-text-domain' ),
		'content' => 'The tab content (can be a variable if you build more complex HTML).',
	),
);
RocketGeek_jQuery_Tabs::tabs( $tabs );
```

The primary array keys ('my_first_tab', 'my_second_tab') can be whatever you need them to be.  The sub-array keys ('tab' and 'content') MUST be 'tab' and 'content'.

The library accepts a couple of additional arguments beyond the `$tabs` definition.  You can give your tabs a unique ID (useful if implementing more than one set of tabs on a page), and you can toggle whether the generated HTML is echoed immediately (default) or returned as a variable.  For example:

```
$my_tabs = RocketGeek_jQuery_Tabs::tabs( $tabs, 'my_tabs', false );
```

In the example above, the tabs would be wrapped with div ID "my_tabs" and the generated HTML would be returned to the variable `$my_tabs` instead of echoed to the screen.


## Built With

* [jQuery](https://jquery.com/)
* [jQuery UI](https://jqueryui.com/)
* [WordPress](https://make.wordpress.org/)

## Contributing

I do accept pull requests. However, make sure your pull request is properly formatted. Also, make sure your request is generic in nature. In other words, don't submit things that are case specific - that's what forks are for. The library also has hooks that follow WP standards - use 'em.

## Versioning

I use [SemVer](https://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/rocketgeek/jquery_tabs/tags). 

## Authors

* **Chad Butler** - [ButlerBlog](https://github.com/butlerblog)
* **RocketGeek** - [RocketGeek](https://github.com/rocketgeek)

## License

This project is licensed under the Apache-2.0 License - see the [LICENSE](LICENSE) file for details.

I hope you find this project useful. If you use it your project, attribution is appreciated.
