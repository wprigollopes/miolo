dojo.declare ("MIntegerField", null,
{
    validate: function(input)
    {
        // Obtém apenas números que podem ou não começar com "-" ou "+"
        var numbers = input.value.match(/(^[-\+0-9]?)|[0-9]/g);
        input.value = numbers.join('');
    }
});

miolo.integerfield = new MIntegerField;
