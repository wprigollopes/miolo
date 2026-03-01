mexpanddiv = {
    context: null,
    constructor: function() { },

    expand: function( id )
    {
        var box = document.getElementById(id);
        var boxButton = document.getElementById(id + "Button");

        if (box.offsetHeight < box.scrollHeight)
        {
            boxButton.className = "m-expand-div-button m-expand-div-button-expanded"
            box.className = 'm-expand-div-text-expanded';
        }
        else
        {
            boxButton.className = "m-expand-div-button m-expand-div-button-collapsed";
            box.className = 'm-expand-div-text-collapsed';
        }
    }
};
