<?php

class MImageButton extends MImageLink
{
    private $onClick;


    public function generateLink()
    {
        parent::generateLink();

        $this->target = '';
        $action = $this->href;
        if ( $action == '' )
        {
            $action = 'submit';
        }

        if ( empty($this->onClick) )
        {
            if (preg_match('/^https?:\/\//i', $action))
            {
                $this->onClick = $this->getOnClick($action, '', 'href');
            }
            else
            {
                $this->onClick = $this->getOnClick('', $action, '');
            }
        }

        if ($this->onClick != '')
        {
            $this->addAttribute('onclick', $this->onClick);
        }
    }

    /**
     * @param mixed $onClick
     */
    public function setOnClick($onClick)
    {
        $this->onClick = $onClick;
    }
}

?>