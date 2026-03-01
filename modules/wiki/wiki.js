//
//
//
function WIKI_showTip(tip)
{
    var w = 400;
    var h = 400;
    var x = screen.width - w - 50;
    var y = screen.height - h - 50;
    var wnd = window.open('#','tip','menubar=no,locationbar=no,toolbar=no,statusbar=yes,scrollbars=yes,' +
                              'outerWidth=' + w + ',outerHeight=' + h + ',left=' + x + ',top=' + y + ',resizeable=yes');
    var doc = wnd.document;
    doc.open();
    doc.writeln('<div align="center"><b>Tip</b></div>');
    doc.write('<p><small>');
    doc.write(tip);
    doc.writeln('</small></p>');
    doc.writeln('<form><center><input type="button" value="Close Tip" onClick="window.close()"></center></form>');
    doc.close();
}
