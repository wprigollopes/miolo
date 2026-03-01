<?php

class frmScrollableDiv extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Scrollable Divs', MIOLO::getCurrentModule()));
        $this->eventHandler();
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        for ( $i = 0; $i < 15; $i++ )
        {
            $aOptionsControl[] = new MCheckBox("chkBox$i", "value$i", _M('Label @1', $module, $i), false, "chk$i");
        }
        $aText[] = new MTextHeader('', '3', _M('Create an inspiring opening', $module));
        $aText[] = new MTextHeader('', '4', _M('- Mention a team work example.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Use quotes.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Give a personal evidence.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Read a testimonial.', $module));
        $aText[] = new MTextHeader('', '3', _M('Appreciate reports about achievements', $module));
        $aText[] = new MTextHeader('', '4', _M('- Greet those who win awards.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Comment about knowledge areas.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Ask for spontaneous successful testimonials.', $module));
        $aText[] = new MTextHeader('', '3', _M('Address the challenge of sales', $module));
        $aText[] = new MTextHeader('', '4', _M('- Identify the need or the opportunity.', $module));
        $aText[] = new MTextHeader('', '3', _M('Lead a smart and creative session', $module));
        $aText[] = new MTextHeader('', '4', _M('- Ask:  How can we?', $module));
        $aText[] = new MTextHeader('', '4', _M('-- Evaluate the situation. Go to facts.', $module));
        $aText[] = new MTextHeader('', '4', _M('-- Get creative solutions, keeping the mind opened and without prejudices.', $module));
        $aText[] = new MTextHeader('', '4', _M('-- Choose the best solution.', $module));
        $aText[] = new MTextHeader('', '3', _M('Define SMART goals', $module));
        $aText[] = new MTextHeader('', '4', _M('- Lead a training of SMART goals.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Define team goals', $module));
        $aText[] = new MTextHeader('', '4', _M('- Define individual goals', $module));
        $aText[] = new MTextHeader('', '3', _M('Identify the required skills to reach the goals', $module));
        $aText[] = new MTextHeader('', '4', _M('- Get personal relationship.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Phone skills.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Prospect potential clients.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Ask questions.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Use evidences.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Deal with objections.', $module));
        $aText[] = new MTextHeader('', '3', _M('Get commitment', $module));
        $aText[] = new MTextHeader('', '4', _M('- Team commitments.', $module));
        $aText[] = new MTextHeader('', '4', _M('- Individual commitments.', $module));
        $aText[] = new MTextHeader('', '3', _M('Closing', $module));
        $aText[] = new MTextHeader('', '4', _M('- Make a creative closing.', $module));

        $fields = array(
            new MText('txt1', _M('Scrollable divs can be used with group controls to optimize the space on the form', $module)),
            new MCheckBoxGroup('chkGroup', _M('Field Label', $module), $aOptionsControl, '', 'vertical'),
            new MText('txt2', _M('Presentation with text blocks', $module)),
            new MBaseGroup('chkGroupText', _M('Motivate a team', $module), $aText, 'vertical')
        );
        $this->setFields($fields);
        $this->txt1->setBold();
        $this->txt2->setBold();
        $this->chkGroup->setScrollHeight('100px');
        $this->chkGroupText->setScrollHeight('120px');
        $buttons = array(
            new MBackButton(),
            new MButton('btnPost', _M('Submit checked checkboxes', $module)),
        );
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();

        for ( $i = 0; $i < 15; $i++ )
        {
            $field = $this->getField("chkBox$i");
            if ( $field->checked )
            {
                $value = $this->getFieldValue("chkBox$i");
                $this->addField(new MText('', _M('Option @1 checked - value = @2', $module, $i, $value)));
            }
        }
    }
}
