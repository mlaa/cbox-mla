# About this Document

This style guide is highly opinionated. Feel free to edit this document. 

This document is written in Markdown and exported to HTML. When making changes, please edit the source document. 

The markdown variety used for this document is [Pandoc markdown](http://johnmacfarlane.net/pandoc/demo/example9/pandocs-markdown.html). Pandoc was chosen for its extra features, such as footnotes and tables, as well as the export capabilities of the command-line application. 

# HTML Markup 
## Inline Styles

These should be avoided whenever possible. 

## HTML4 vs. HTML5 Syntax

Since HTML5 syntax is now well-understood by modern browsers, and since Javascript plugins like Modernizr now handle most of the work of backporting HTML5 tags for those browsers that don't understand them, HTML5 syntax should be used whenever possible. In particular, avoid the use of `<b>` and `<i>` tags for connoting bold and italic typeface variants. Instead, use semantic markup--don't denote the desired style, but denote the type of the text. For instance, for a work that is to be italicized, use the `<cite>` tag: 

```html
The study appears in <cite>New Perspectives on "The Eve of St. Agnes."</cite>

```

For more on these tags, see [this w3c article](http://www.w3.org/International/questions/qa-b-and-i-tags). 

# Styles

## Principles

Flatness and simplicity are key. Gradients, shadows, and other 3D elements are generally avoided. 

### Dimensionality
To achieve a minimum level of dimensionality, single-pixel, subtle borders are used. In this way, cards and buttons will be foregrounded slightly, and text boxes will be backgrounded. Items to be foregrounded should generally be of a lighter color of gray than the background.  

## Patterns

### Colors

Grays: 

<div class="colorBlock fafafa">Light Gray (#fafafa)</div>  
<div class="colorBlock eaeaea">Gray (#eaeaea)</div>  
<div class="colorBlock 777">Meta-Gray (#777)</div>  
<div class="colorBlock 555">Gray 5 (#555)</div>  
<div class="colorBlock 444">Gray 4 (#444)</div>  
<div class="colorBlock 333">Gray 3 (#333)</div>  

Links: 

<div class="colorBlock 07d">MLA Blue</div> 

### Typography

Font stack: 
 * Adobe TypeKit: Proxima Nova
 * Helvetica
 * Sans-serif 

## UI Elements

### Cards

### Buttons

Buttons vs. Links

Pagination

### Tabs

### Text Boxes

Slightly recessed. 

Search boxes. 

### Tables

### Block Quotes

Also, comment replies. 

### Alerts


## Works Cited

Indentation? 

URLs/Links? 

