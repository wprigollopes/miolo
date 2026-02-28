<?php

class MDropdownMenu extends MDiv
{
    private $itensMenu;

    public function __construct( $name = NULL, $label = ' ', $itensMenu = array(), $image = NULL )
    {
        parent::__construct( $name );

        $this->addStyleFile('m_boxes.css');
        $this->setBoxId($this->GetId());
        $this->setItensMenu($itensMenu);

        $buttonInner = new MButton('btnDropwon' . $name, $label, 'NONE', $image);
        $buttonInner->setClass('dropdown-toggle');
        $buttonInner->addAttribute('data-toggle', 'dropdown');
        $fieldsInner[] = $buttonInner;

        $this->setInner($fieldsInner);
    }

    public function generate()
    {
        $inner = $this->getInnerToString();

        $inner .= '<ul class="dropdown-menu">';

        foreach ($this->itensMenu as $itemMenu)
        {
            $inner .= '<li>' . $itemMenu->generate() . '</li>';
        }

        $inner .= '</ul>';

        $class = $this->getClass() . ' dropdown';
        return '<div class="'.$class.'">' .$inner.'</div>';
    }

    public function getItensMenu()
    {
        return $this->itensMenu;
    }

    public function setItensMenu($itensMenu)
    {
        $this->itensMenu = $itensMenu;
    }
}
