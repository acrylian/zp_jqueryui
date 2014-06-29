zp_jqueryui
===========

An experimental [Zenphoto](http://www.zenphoto.org) plugin for jQuery UI

The plugin does two things:
- Load jQuery on the front end (This does not load the Zenphoto core one but its own newer 1.10.x version!)
- Experimental: Provides content macros to insert an accordion within the normal description or content fields (planned to be extended to tabs)

##Usage
- Install the file and folder within your `/plugins` folder
- Activate the plugin
- Choose the theme page the scripts should be loaded on
- Choose the jQuery UI theme to be used
- Either use the macros below and/or use jquery ui features directly on your theme.

###Macros

These are some macros you can enter in all main content text fields.

*NOTE: Zenphoto's default editor TinyMCE automatically wraps everything in paragraphs. This results in invalid nested html as the macros are applied after TinyMCE does this. To have these working correctly with valid HTML your server needs to support the [PHP Tidy extension](http://www.php.net/manual/en/book.tidy.php).*

*Because of TinyMCE the macros work best if entered inline and/or via TinyMCE's code view window. Currently you also can only use simple text content (no headlines, no lists etc) within each Accordion element. If you need parapgraphs you mimic them with line breaks.*

####a) Accordion
Example: http://jqueryui.com/accordion/

```
[UIACC]
    [UIACC-HL]Headline 1[UIACC-HL-END]
    [UIACC-EL]Some Content 1 [UIACC-EL-END]
    [UIACC-HL]Headline 2[UIACC-HL-END]
    [UIACC-EL]Some Content 2[UIACC-EL-END]
[UIACC-END]
```

This generats the following HTML:

``` html 
<div class="ui-accordion">
  <h3>Headline 1</h3>
  <div><!-- content wrapper -->
     Some content
  </div>
  <h3>Headline 2</h3>
  <div><!-- content wrapper -->
    Some content
  </div>
</div>
```

####b) Tabs
Example: http://jqueryui.com/tabs/
The macros to generate those:
```
[UITABS mytabs a=Tab1 b=Tab2 c=Tag3] // the id of the tabs and then the titles of the tabs. this also sets the number of tabs
  [UITAB 1] // Each tab must get a number and the total number of [UITAB] must match those in the first macro
   Tab content 1
  [UITAB-END]
  [UITAB 2]
   Tab content 2
  [UITAB-END]
  [UITAB 3]
   Tab content 3
  [UITAB-END]
[UITABS-END]
```

That generates the following HTML:

```html
<div class="ui-tabs" id="mytabs">
  <ul>
   <li><a href="#mytabs-1">Tab1</li>
   <li><a href="#mytabs-2">Tab2</li>
   <li><a href="#mytabs-3">Tab2</li>
  </ul>
  <div id="mytabs-1">
    Tab content 1
  </div>
  <div id="mytabs-2">
   Tab content 3
  </div>
  <div id="mytabs-3">
   Tab content 3
  </div>
/div>
```
