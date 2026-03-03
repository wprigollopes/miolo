<?php

class frmExpandDiv extends MForm
{
    public function __construct()
    {   
        parent::__construct(_M('@1 Sample', MIOLO::getCurrentModule(), 'MExpandDiv'));
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = new MLabel(_M('Expand Div', $module), NULL, true);
        $fields[] = new MExpandDiv('div', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum ligula vitae urna. Duis semper, nibh non vestibulum pulvinar, ipsum quam commodo magna, et gravida neque libero quis leo. Fusce egestas semper erat. Nunc tincidunt, sapien nec tincidunt fermentum, elit metus blandit nisi, vitae imperdiet ligula nunc ut eros. Sed a felis dictum ligula iaculis placerat. Quisque hendrerit nunc quis felis. Aenean viverra gravida augue. Sed a diam sit amet enim tincidunt facilisis. Nunc dictum eros at nisi. Fusce convallis risus. Curabitur id dui sodales tellus tincidunt facilisis. Maecenas iaculis ante dapibus mi consectetur tristique. Integer accumsan ante nec neque.');

        $fields[] = new MSpacer();

        // You can use \n and <br/> to make new lines
        $fields[] = new MLabel(_M('Expand Div with Line Breaks', $module), NULL, true);
        $fields[] = $e = new MExpandDiv('divWithBR', "Lorem\n ipsum dolor sit amet, <br/>consectetur adipiscing elit. Mauris condimentum\n ligula vitae urna. Duis semper, nibh non vestibulum pulvinar, ipsum quam commodo magna, et gravida neque libero quis leo. Fusce egestas semper erat. Nunc tincidunt, sapien nec tincidunt fermentum, elit metus blandit nisi, vitae imperdiet ligula nunc ut eros. Sed a felis dictum ligula iaculis placerat. Quisque hendrerit nunc quis felis. Aenean viverra gravida augue. Sed a diam sit amet enim tincidunt facilisis. Nunc dictum eros at nisi. Fusce convallis risus. Curabitur id dui sodales tellus tincidunt facilisis. Maecenas iaculis ante dapibus mi consectetur tristique. Integer accumsan ante nec neque.");
        $e->setExpandedOnLoad(true);

        $fields[] = new MSpacer();

        $content[] = new MExpandDiv('bsgDiv', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum ligula vitae urna. Duis semper, nibh non vestibulum pulvinar, ipsum quam commodo magna, et gravida neque libero quis leo. Fusce egestas semper erat. Nunc tincidunt, sapien nec tincidunt fermentum, elit metus blandit nisi, vitae imperdiet ligula nunc ut eros. Sed a felis dictum ligula iaculis placerat. Quisque hendrerit nunc quis felis. Aenean viverra gravida augue. Sed a diam sit amet enim tincidunt facilisis. Nunc dictum eros at nisi. Fusce convallis risus. Curabitur id dui sodales tellus tincidunt facilisis. Maecenas iaculis ante dapibus mi consectetur tristique. Integer accumsan ante nec neque.');
        $fields[] = new MBaseGroup('bsg', _M('Expand Div in a BaseGroup', $module), $content);

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
