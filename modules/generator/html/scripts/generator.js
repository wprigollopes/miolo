dojo.declare ("MIOLOGenerator", null,
{
    selectCode: function(tab)
    {
        var node = dojo.query(tab + ' code')[0];
        window.getSelection().removeAllRanges();

        var range = document.createRange();
        range.selectNode(node);
        window.getSelection().addRange(range);
    }
});

var generator = new MIOLOGenerator;
