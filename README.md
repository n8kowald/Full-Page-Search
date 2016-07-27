***Important:*** This plugin is a work in progress. We would still like to make some enhancements to it, such as full localization support, grunt.js support and a few more things. As we continue to improve on this plugin, things will only get better - so stay tuned!

## About...
This plugin is a fork of the in house plugin we use for full width page templates. This has been altered from it's original form, which is originally intended to work with the YIKES, in house, starter theme.

**Note:** This plugin may not work properly if your theme, or another plugin, already defines custom menu fields in the dashboard.

## Basic Usage
The <strong>Full Width Search Plugin</strong> generates a full width search template, that is overlaid on the website when the appropriate link is clicked.

Out of the box, on each menu item in the dashboard, you'll see a new checkbox. Checking off the option will set the current menu item to be the link toggle - meaning when it is clicked, the full width search template will display.

![Full Width Search Menu Item Toggle](https://cldup.com/UguHFlNCqV.png)

## Alternative Usage
Maybe you want to create a custom link, outside your site navigation. You can do so by creating an anchor tag with the class `.yikes-full-page-search-toggle`. This is cool so you can display your custom link in a sidebar, or in the content of your site.

An example of a custom link, to toggle the full width search visibility is:
```html
<a href="#" class="yikes-full-page-search-toggle">Open Search Container</a>
```

## Front End Example:
![Full Width Search Container Example](https://cldup.com/q5eY5AsdNR.gif)

## Troubleshooting
We'd like to make this plugin as awesome as possible. If you run into any issues, or have an idea on how to make it better, we would love to hear from you! [Open up an issue](https://github.com/yikesinc/Full-Page-Search/issues) here in the Github repository, and we'll get back to you as soon as possible.
