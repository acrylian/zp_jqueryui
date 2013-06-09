zp_jqueryui
===========

A Zenphoto plugin for jQuery UI

**Experimental plugin, requires Zenphoto 1.4.5!** 

Install the file and folder within your `/plugins` folder.

The plugin does two things:
- Load jQuery on the front end (This does not load the Zenphoto core one but its own newer 1.10.x version!)
- Provides macros

Usage
-----
- Activate the plugin
- Choose the theme page the scripts should be loaded on
- Choose the jQuery UI theme to be used

Macros
------
Due to the experimental nature it currently has only one macro currently: An accordion.
The structure of an accordion is:
```   
  <div class="accordion">
    <h3>Headline 1</h3>
    <div><!-- content wrapper -->
      <p>Some content</p>
    </div>
    <h3>Headline 2</h3>
    <div><!-- content wrapper -->
      <p>Some content</p>
    </div>
  </div>
```

Since such a structure is hard to get using TinyMCE there are these macros available that generate the above structure:
```
[UIACC]
    [UIACC-HL]Headline 1[UIACC-HL-END]
    [UIACC-EL]Some Content[UIACC-EL-END]
    [UIACC-HL]Headline 2[UIACC-HL-END]
    [UIACC-EL]Some Content[UIACC-EL-END]
[UIACC-END]
```

Due to limits of TinyMCE that always likes to add  line breaks or paragraphs automatically this currently does only 
work if written inconveniently inline:
`[UIACC][UIACC-HL]Headline 1[UIACC-HL-END][UIACC-EL]Some Content[UIACC-EL-END][UIACC-HL]Headline 2[UIACC-HL-END][UIACC-EL]Some Content[UIACC-EL-END][UIACC-END]`
Because of this you can only use simple content within each Accordion element.
