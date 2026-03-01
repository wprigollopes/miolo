<?

$text = 
"= This is a Wiki Page =
A WikiPage usually contains WikiText along with some information about version and date of the information.
To display a WikiPage, you follow these steps:
  # instantiate a '''WikiPage''' object
  # call the '''SetContent()''' method and pass the '''raw''' wiki text, _
    which you usually will obtain from a database; internally, the WikiPage _
    will instantiate a WikiText from this text, which is used to render the _
    ''HTML'' output.
  # call the '''SetVersion()''' and '''SetDate()''' methods accordingly, _
    to set the respective page information
  # set the page object as content of the current theme
  # that's all
The Wiki text can be formatted in a couple of ways. By now the WikiText class renders
the following different kinds of text styles:
  * '''Block Formats'''
  ** Headings
  ** Paragraphs
  ** Ordered Lists (with numbers, up to three levels deep)
  ** Unordered Lists (with bullets, up to three levels deep)
  ** Definition Lists (with terms and definitions, up to three levels deep)
  * '''Character Formats'''
  ** Italic Words
  ** Bold Words
=== Headings ===
There are three kinds of heading levels. Heading 1 to 3, where the first is the largest one,
usually adopted for page titles, and the remaining for chapters and sub-chapters. The Wiki
Renderer supports the definition of heading tags the following ways:
  * For '''H1''' headings: ''= Heading Text'' or ''! Heading Text''
  * For '''H2''' headings: ''== Heading Text'' or ''!! Heading Text''
  * For '''H3''' headings: ''=== Heading Text'' or ''!!! Heading Text''
The heading may be specified with or without whitespace between the marker and heading text.
Optionally the same marker may appear at the end of the heading text (''= Heading 1 ='').
=== Paragraphs ===
A paragraph contains an ordinary block of text. Only character formatting markers may appear
in a paragraph. Other ''block'' formatting markers will always close a paragraph block. You can
start a new paragraph by entering a blank line between the text blocks.
=== Block Quotes ===
A block quote may be emulated using the syntax for the defintion list with an empty definition term.

Example:
  - : This should appear as quoted (indented) text
  - : ''The same trick but with italic characters''
This is a link to [[another page]].
  
Comments and Feedback to: [[mailto:ts@interact2000.com.br]]
";

    $MIOLO->GetUI();
    
    $MIOLO->Uses('forms/WikiPage.class',$module);
    
    $page = new WikiPage();
    $page->SetDate("7/2/2003 23:34");
    $page->SetVersion("1.0");
    $page->SetContent($text);
    $page->AddComment("==Comment 1==");
    $page->AddComment("==Comment 2==");
    $page->AddComment("==Comment 3==");

    $theme->SetContent($page);
    
    $theme->Generate();
    
    return;
?>
